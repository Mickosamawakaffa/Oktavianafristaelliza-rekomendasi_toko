<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\PreferensiUser;
use App\Models\Toko;
use App\Services\RocSmartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    protected RocSmartService $rocSmartService;

    public function __construct(RocSmartService $rocSmartService)
    {
        $this->rocSmartService = $rocSmartService;
    }

    /**
     * Halaman preferensi + drag-and-drop ranking kriteria.
     */
    public function preferensi()
    {
        $user = Auth::user();
        $kriteria = Kriteria::all();

        // Ambil preferensi yang sudah tersimpan (jika ada)
        $preferensiSaya = PreferensiUser::where('user_id', $user->id)
            ->with('kriteria')
            ->orderBy('urutan')
            ->get();

        return view('user.preferensi', compact('kriteria', 'preferensiSaya'));
    }

    /**
     * Simpan urutan preferensi kriteria user + koordinat GPS lokasi user.
     */
    public function simpanPreferensi(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'urutan_kriteria'   => ['required', 'array', 'min:1'],
            'urutan_kriteria.*' => ['required', 'integer', 'distinct', 'exists:kriteria,id'],
            'user_lat'          => ['nullable', 'numeric', 'between:-90,90'],
            'user_lng'          => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $orderedIds = $request->urutan_kriteria;

        // Hitung bobot ROC berdasarkan urutan
        $weights = $this->rocSmartService->calculateRocWeights($orderedIds);

        // Simpan / update preferensi user di database
        DB::transaction(function () use ($user, $orderedIds, $weights) {
            PreferensiUser::where('user_id', $user->id)->delete();

            foreach ($orderedIds as $rank => $kriteriaId) {
                PreferensiUser::create([
                    'user_id'     => $user->id,
                    'kriteria_id' => $kriteriaId,
                    'urutan'      => $rank + 1,
                    'bobot_roc'   => $weights[$kriteriaId] ?? null,
                ]);
            }
        });

        // Simpan lokasi GPS user di session jika disediakan
        if ($request->filled('user_lat') && $request->filled('user_lng')) {
            session([
                'user_lat' => (float) $request->user_lat,
                'user_lng' => (float) $request->user_lng,
            ]);
        }

        return redirect()->route('user.hasil');
    }

    /**
     * Hitung dan tampilkan hasil ranking toko berdasarkan preferensi user & jarak GPS (Haversine).
     */
    public function hasil(Request $request)
    {
        $user = Auth::user();

        // Ambil preferensi user (sudah terurut)
        $preferensiUser = PreferensiUser::where('user_id', $user->id)
            ->with('kriteria')
            ->orderBy('urutan')
            ->get();

        if ($preferensiUser->isEmpty()) {
            return redirect()->route('user.preferensi')
                ->with('info', 'Silakan atur preferensi kriteria kamu terlebih dahulu.');
        }

        // Tentukan posisi GPS User (Request param > Session > Default Alun-alun Jember)
        $gpsDefault = !$request->filled('user_lat') && !session()->has('user_lat');
        $userLat = $request->input('user_lat', session('user_lat', -8.1724));
        $userLng = $request->input('user_lng', session('user_lng', 113.7000));

        // Ambil toko yang data kriterianya sudah lengkap
        $tokos = Toko::lengkap()->with(['nilaiAlternatif.kriteria'])->get();

        // Guard: Jika 0 toko yang data kriterianya lengkap
        if ($tokos->isEmpty()) {
            return redirect()->route('user.preferensi')
                ->with('info', 'Belum ada toko dengan data lengkap yang bisa direkomendasikan saat ini.');
        }

        $kriteriaIds = $preferensiUser->pluck('kriteria_id')->toArray();

        // Cari ID Kriteria "Lokasi"
        $kriteriaLokasi = Kriteria::where('nama_kriteria', 'Lokasi')->first();

        // Cari ID Kriteria "Ulasan"
        $kriteriaUlasan = Kriteria::where('nama_kriteria', 'Ulasan')->first();

        // Hitung jarak (km) untuk setiap toko dan bangun matriks nilai
        $storeDistances = [];
        $alternativesValues = [];

        foreach ($tokos as $toko) {
            $tokoLat = $toko->latitude ?? -8.1695;
            $tokoLng = $toko->longitude ?? 113.7152;

            // Perhitungan Haversine Formula (KM)
            $distanceKm = $this->rocSmartService->calculateHaversineDistance(
                $userLat,
                $userLng,
                $tokoLat,
                $tokoLng
            );

            $storeDistances[$toko->id] = $distanceKm;

            $alternativesValues[$toko->id] = [];
            foreach ($toko->nilaiAlternatif as $nilai) {
                if (in_array($nilai->kriteria_id, $kriteriaIds)) {
                    $alternativesValues[$toko->id][$nilai->kriteria_id] = (float) $nilai->nilai;
                }
            }

            // Jika kriteria Lokasi ada di preferensi user, override nilainya dengan jarak fisik asli (km).
            // Kriteria Lokasi berjenis 'cost': semakin kecil jarak -> utility SMART semakin tinggi.
            // Jarak km dimasukkan langsung agar SMART dapat membedakan semua rentang jarak secara akurat.
            if ($kriteriaLokasi && in_array($kriteriaLokasi->id, $kriteriaIds)) {
                $alternativesValues[$toko->id][$kriteriaLokasi->id] = $distanceKm;
            }

            // Jika kriteria Ulasan ada di preferensi user, override nilainya dengan skor konversi
            // dari rating_ulasan (skala 1-5 → skor 1-100), bukan nilai manual dari nilai_alternatif.
            // Safety net: memastikan skor SMART selalu konsisten dengan rating_ulasan terbaru.
            if ($kriteriaUlasan && in_array($kriteriaUlasan->id, $kriteriaIds) && $toko->skor_ulasan !== null) {
                $alternativesValues[$toko->id][$kriteriaUlasan->id] = $toko->skor_ulasan;
            }
        }

        // Peta jenis kriteria [kriteria_id => 'benefit'|'cost']
        $kriteriaTypes = $preferensiUser->pluck('kriteria.jenis', 'kriteria_id')->toArray();
        $orderedKriteriaIds = $preferensiUser->pluck('kriteria_id')->toArray();

        // Jalankan proses ROC + SMART
        $result = $this->rocSmartService->processRocSmart(
            $orderedKriteriaIds,
            $alternativesValues,
            $kriteriaTypes
        );

        // Gabungkan data toko & jarak ke hasil ranking
        $tokoMap = $tokos->keyBy('id');
        $rankings = collect($result['rankings'])->map(function ($item) use ($tokoMap, $storeDistances, $alternativesValues) {
            $toko = $tokoMap->get($item['toko_id']);
            $distance = $storeDistances[$item['toko_id']] ?? null;

            return array_merge($item, [
                'toko'       => $toko,
                'jarak_km'   => $distance,
                'raw_values' => $alternativesValues[$item['toko_id']] ?? [],
            ]);
        });

        return view('user.hasil', [
            'rankings'           => $rankings,
            'preferensiUser'     => $preferensiUser,
            'weights'            => $result['weights'],
            'userLat'            => $userLat,
            'userLng'            => $userLng,
            'gpsDefault'         => $gpsDefault,
            'alternativesValues' => $alternativesValues,
        ]);
    }
}
