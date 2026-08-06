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
     * Tampilkan matriks nilai alternatif toko per kriteria.
     */
    public function index()
    {
        $kriterias = Kriteria::all();
        $tokos = Toko::with(['nilaiAlternatif'])->paginate(10);

        return view('admin.nilai-alternatif.index', compact('kriterias', 'tokos'));
    }

    /**
     * Form edit nilai kriteria untuk toko tertentu.
     */
    public function edit($tokoId)
    {
        $toko = Toko::with('nilaiAlternatif')->findOrFail($tokoId);
        $kriterias = Kriteria::all();

        // Peta nilai saat ini [kriteria_id => nilai]
        $nilaiMap = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();

        return view('admin.nilai-alternatif.edit', compact('toko', 'kriterias', 'nilaiMap'));
    }

    /**
     * Update nilai kriteria toko dengan validasi angka (skala 1-100).
     */
    public function update(Request $request, $tokoId)
    {
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
