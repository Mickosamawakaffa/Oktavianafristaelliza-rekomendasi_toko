<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use Illuminate\Http\Request;

class NilaiAlternatifAdminController extends Controller
{
    /**
     * Tolak secara eksplisit semua akses dari pemilik_toko ke controller ini.
     * Defense-in-depth: sudah ada middleware role:admin di route, ini lapisan ke-2.
     */
    private function denyPemilikToko(): void
    {
        if (auth()->check() && auth()->user()->role === 'pemilik_toko') {
            abort(403, 'Akses Ditolak: Pemilik toko tidak diizinkan mengakses atau mengubah nilai kriteria toko.');
        }
    }

    /**
     * Tampilkan matriks nilai alternatif toko per kriteria.
     */
    public function index()
    {
        $this->denyPemilikToko();

        $kriterias = Kriteria::all();
        $tokos = Toko::with(['nilaiAlternatif'])->paginate(10);

        return view('admin.nilai-alternatif.index', compact('kriterias', 'tokos'));
    }

    /**
     * Form edit nilai kriteria untuk toko tertentu.
     */
    public function edit($tokoId)
    {
        $this->denyPemilikToko();

        $toko = Toko::with('nilaiAlternatif')->findOrFail($tokoId);
        $kriterias = Kriteria::all();

        // Peta nilai saat ini [kriteria_id => nilai]
        $nilaiMap = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();

        // Cari ID kriteria "Ulasan" untuk rendering input khusus di view
        $kriteriaUlasan = Kriteria::where('nama_kriteria', 'Ulasan')->first();
        $kriteriaUlasanId = $kriteriaUlasan?->id;

        return view('admin.nilai-alternatif.edit', compact('toko', 'kriterias', 'nilaiMap', 'kriteriaUlasanId'));
    }

    /**
     * Update nilai kriteria toko dengan validasi angka (skala 1-100).
     * Untuk kriteria Ulasan, nilai dihitung otomatis dari rating_ulasan (skala 1-5).
     * Hanya admin yang diizinkan. Pemilik toko akan mendapat 403.
     */
    public function update(Request $request, $tokoId)
    {
        $this->denyPemilikToko();

        $toko = Toko::findOrFail($tokoId);

        // Cari ID kriteria "Ulasan"
        $kriteriaUlasan = Kriteria::where('nama_kriteria', 'Ulasan')->first();
        $kriteriaUlasanId = $kriteriaUlasan?->id;

        // Bangun aturan validasi — kriteria Ulasan tidak wajib di array nilai
        // karena nilainya dihitung dari rating_ulasan
        $nilaiRules = ['required', 'array'];
        $nilaiItemRules = ['required', 'numeric', 'min:0', 'max:100'];

        $request->validate([
            'nilai'              => $nilaiRules,
            'nilai.*'            => $nilaiItemRules,
            'rating_ulasan'      => ['nullable', 'numeric', 'min:1', 'max:5'],
            'jumlah_ulasan'      => ['nullable', 'integer', 'min:0'],
        ], [
            'nilai.*.required'       => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'        => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'            => 'Nilai minimum adalah 0.',
            'nilai.*.max'            => 'Nilai maksimum adalah 100.',
            'rating_ulasan.min'      => 'Rating ulasan minimum adalah 1.',
            'rating_ulasan.max'      => 'Rating ulasan maksimum adalah 5.',
            'jumlah_ulasan.integer'  => 'Jumlah ulasan harus berupa bilangan bulat.',
        ]);

        // Simpan rating_ulasan & jumlah_ulasan ke tabel toko
        if ($request->filled('rating_ulasan')) {
            $toko->update([
                'rating_ulasan'  => (float) $request->rating_ulasan,
                'jumlah_ulasan'  => $request->jumlah_ulasan ? (int) $request->jumlah_ulasan : null,
            ]);
        }

        foreach ($request->nilai as $kriteriaId => $val) {
            // Untuk kriteria Ulasan: gunakan skor konversi dari rating, bukan input manual
            if ($kriteriaUlasanId && (int) $kriteriaId === $kriteriaUlasanId && $request->filled('rating_ulasan')) {
                $val = round(((float) $request->rating_ulasan / 5) * 100, 1);
            }

            NilaiAlternatif::updateOrCreate(
                [
                    'toko_id'     => $toko->id,
                    'kriteria_id' => $kriteriaId,
                ],
                [
                    'nilai'       => (float) $val,
                ]
            );
        }

        return redirect()->route('admin.nilai-alternatif.index')
            ->with('success', "Nilai kriteria untuk toko \"{$toko->nama}\" berhasil diperbarui!");
    }
}

