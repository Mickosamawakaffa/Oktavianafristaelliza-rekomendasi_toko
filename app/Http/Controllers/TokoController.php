<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\PreferensiUser;
use App\Models\Toko;
use App\Services\RocSmartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    protected RocSmartService $rocSmartService;

    public function __construct(RocSmartService $rocSmartService)
    {
        $this->rocSmartService = $rocSmartService;
    }

    /**
     * Tampilkan daftar toko fashion secondhand yang nilai kriterianya sudah lengkap
     * dengan skor dan peringkat rekomendasi metode ROC-SMART.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kriteria yang ada di sistem
        $allKriterias = Kriteria::orderBy('id')->get();
        $allKriteriaIds = $allKriterias->pluck('id')->toArray();

        // 2. Tentukan urutan prioritas kriteria (untuk perhitungan ROC-SMART)
        $orderedKriteriaIds = [];
        if ($request->filled('kriteria_order')) {
            $rawOrder = $request->input('kriteria_order');
            if (is_string($rawOrder)) {
                $orderedKriteriaIds = array_map('intval', explode(',', $rawOrder));
            } elseif (is_array($rawOrder)) {
                $orderedKriteriaIds = array_map('intval', $rawOrder);
            }
        } elseif (Auth::check() && Auth::user()->role === 'user') {
            // Gunakan preferensi yang tersimpan jika user sudah login
            $userPrefs = PreferensiUser::where('user_id', Auth::id())
                ->orderBy('urutan')
                ->pluck('kriteria_id')
                ->toArray();
            if (!empty($userPrefs)) {
                $orderedKriteriaIds = $userPrefs;
            }
        }

        // Filter hanya ID kriteria yang valid
        $orderedKriteriaIds = array_values(array_intersect($orderedKriteriaIds, $allKriteriaIds));

        // Tambahkan kriteria yang belum masuk ke urutan (fallback kelengkapan)
        foreach ($allKriteriaIds as $kid) {
            if (!in_array($kid, $orderedKriteriaIds)) {
                $orderedKriteriaIds[] = $kid;
            }
        }

        // Map objek kriteria sesuai urutan prioritas untuk modal & preview
        $kriteriasMap = $allKriterias->keyBy('id');
        $orderedKriterias = collect($orderedKriteriaIds)->map(function ($id) use ($kriteriasMap) {
            return $kriteriasMap->get($id);
        })->filter()->values();

        // 3. Ambil toko lengkap yang sudah disetujui (approved)
        $query = Toko::lengkap()->with(['nilaiAlternatif.kriteria']);

        // Filter pencarian berdasarkan nama atau alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $tokos = $query->get();

        // 4. Hitung Skor ROC-SMART untuk seluruh toko
        $kriteriaTypes = $allKriterias->pluck('jenis', 'id')->toArray();
        $kriteriaUlasan = $allKriterias->firstWhere('nama_kriteria', 'Ulasan');

        $alternativesValues = [];
        foreach ($tokos as $toko) {
            $alternativesValues[$toko->id] = [];
            foreach ($toko->nilaiAlternatif as $nilai) {
                if (in_array($nilai->kriteria_id, $orderedKriteriaIds)) {
                    $val = (float) $nilai->nilai;
                    // Sinkronisasi dengan skor ulasan bayesian jika tersedia
                    if ($kriteriaUlasan && $nilai->kriteria_id === $kriteriaUlasan->id && $toko->skor_ulasan !== null) {
                        $val = (float) $toko->skor_ulasan;
                    }
                    $alternativesValues[$toko->id][$nilai->kriteria_id] = $val;
                }
            }
        }

        $rocResult = $this->rocSmartService->processRocSmart(
            $orderedKriteriaIds,
            $alternativesValues,
            $kriteriaTypes
        );

        // Petakan skor akhir & peringkat ke toko
        $rankingMap = [];
        if (!empty($rocResult['rankings'])) {
            foreach ($rocResult['rankings'] as $index => $rItem) {
                $rankingMap[$rItem['toko_id']] = [
                    'score'   => (float) $rItem['score'],
                    'percent' => round($rItem['score'] * 100, 1),
                    'rank'    => $index + 1,
                ];
            }
        }

        foreach ($tokos as $toko) {
            $toko->roc_score   = $rankingMap[$toko->id]['score'] ?? 0.0;
            $toko->roc_percent = $rankingMap[$toko->id]['percent'] ?? 0.0;
            $toko->roc_rank    = $rankingMap[$toko->id]['rank'] ?? 999;
        }

        // 5. Filter toko yang sedang buka saja jika diminta
        if ($request->boolean('buka_saja')) {
            $tokos = $tokos->filter(function ($toko) {
                return $toko->is_buka === true;
            });
        }

        // 6. Pengurutan (Sorting)
        $sort = $request->input('sort', 'rekomendasi');

        if ($sort === 'rating_tertinggi') {
            $tokos = $tokos->sortByDesc(function ($toko) {
                return [$toko->rating_ulasan ?? 0, $toko->jumlah_ulasan ?? 0];
            })->values();
        } elseif ($sort === 'nama') {
            $tokos = $tokos->sortBy('nama', SORT_NATURAL | SORT_FLAG_CASE)->values();
        } elseif ($sort === 'terbaru') {
            $tokos = $tokos->sortByDesc('created_at')->values();
        } else {
            // Default: Urutkan berdasarkan Skor Rekomendasi ROC-SMART tertinggi
            $sort = 'rekomendasi';
            $tokos = $tokos->sortByDesc('roc_score')->values();
        }

        return view('toko.index', [
            'tokos'              => $tokos,
            'sort'               => $sort,
            'orderedKriterias'   => $orderedKriterias,
            'orderedKriteriaIds' => $orderedKriteriaIds,
            'weights'            => $rocResult['weights'] ?? [],
        ]);
    }

    /**
     * Tampilkan detail toko spesifik.
     * Untuk admin: sertakan semua kriteria dan nilaiMap agar form bisa pra-isi.
     */
    public function show($id)
    {
        $toko      = Toko::with(['nilaiAlternatif.kriteria', 'pemilik'])->findOrFail($id);
        $kriterias = Kriteria::orderBy('id')->get();
        $nilaiMap  = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();

        return view('toko.show', compact('toko', 'kriterias', 'nilaiMap'));
    }

    /**
     * Simpan/update nilai kriteria toko (hanya admin).
     * Validasi backend: non-admin langsung 403.
     */
    public function updateNilaiKriteria(Request $request, $id)
    {
        // Guard backend — tolak semua selain admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya admin yang dapat mengubah nilai kriteria toko.');
        }

        $toko = Toko::findOrFail($id);

        // Cari ID kriteria "Ulasan" untuk sinkronisasi rating
        $kriteriaUlasan = Kriteria::where('nama_kriteria', 'Ulasan')->first();
        $kriteriaUlasanId = $kriteriaUlasan?->id;

        $request->validate([
            'nilai'         => ['required', 'array'],
            'nilai.*'       => ['required', 'numeric', 'min:0', 'max:100'],
            'rating_ulasan' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'jumlah_ulasan' => ['nullable', 'integer', 'min:0'],
        ], [
            'nilai.*.required' => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'      => 'Nilai minimum adalah 0.',
            'nilai.*.max'      => 'Nilai maksimum adalah 100.',
            'rating_ulasan.min' => 'Rating ulasan minimum adalah 1.',
            'rating_ulasan.max' => 'Rating ulasan maksimum adalah 5.',
        ]);

        // Simpan rating_ulasan & jumlah_ulasan ke tabel toko jika disediakan
        if ($request->filled('rating_ulasan')) {
            $toko->update([
                'rating_ulasan' => (float) $request->rating_ulasan,
                'jumlah_ulasan' => $request->jumlah_ulasan ? (int) $request->jumlah_ulasan : null,
            ]);
        }

        foreach ($request->nilai as $kriteriaId => $val) {
            // Untuk kriteria Ulasan: gunakan skor konversi dari rating, bukan input manual
            // Ini memastikan nilai di nilai_alternatif selalu konsisten dengan rating_ulasan di tabel toko
            if ($kriteriaUlasanId && (int) $kriteriaId === $kriteriaUlasanId && $request->filled('rating_ulasan')) {
                $val = round(((float) $request->rating_ulasan / 5) * 100, 1);
            }

            NilaiAlternatif::updateOrCreate(
                ['toko_id' => $toko->id, 'kriteria_id' => $kriteriaId],
                ['nilai'   => (float) $val]
            );
        }

        return redirect()
            ->route('toko.show', $toko->id)
            ->with('success', 'Nilai kriteria toko "' . $toko->nama . '" berhasil disimpan!');
    }
}
