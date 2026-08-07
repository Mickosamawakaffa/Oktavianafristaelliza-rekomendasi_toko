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

        return view('admin.nilai-alternatif.edit', compact('toko', 'kriterias', 'nilaiMap'));
    }

    /**
     * Update nilai kriteria toko dengan validasi angka (skala 1-100).
     * Hanya admin yang diizinkan. Pemilik toko akan mendapat 403.
     */
    public function update(Request $request, $tokoId)
    {
        $this->denyPemilikToko();

        $toko = Toko::findOrFail($tokoId);

        $request->validate([
            'nilai'              => ['required', 'array'],
            'nilai.*'            => ['required', 'numeric', 'min:0', 'max:100'],
        ], [
            'nilai.*.required' => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'      => 'Nilai minimum adalah 0.',
            'nilai.*.max'      => 'Nilai maksimum adalah 100.',
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
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
