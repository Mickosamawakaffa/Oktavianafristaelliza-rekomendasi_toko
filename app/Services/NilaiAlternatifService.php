<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiAlternatifService
{
    /**
     * Validasi dan simpan nilai kriteria sebuah toko beserta rating/jumlah ulasannya.
     *
     * Untuk kriteria "Ulasan", nilai dihitung otomatis dari rating_ulasan (skala 1-5 -> 0-100)
     * agar nilai_alternatif selalu konsisten dengan rating_ulasan di tabel toko.
     */
    public function simpanDariRequest(Request $request, Toko $toko): void
    {
        $kriteriaIds = Kriteria::pluck('id')->all();

        $request->validate([
            'nilai'         => ['required', 'array'],
            'nilai.*'       => ['required', 'numeric', 'min:0', 'max:100'],
            'rating_ulasan' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'jumlah_ulasan' => ['nullable', 'integer', 'min:0'],
        ], [
            'nilai.*.required'      => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'       => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'           => 'Nilai minimum adalah 0.',
            'nilai.*.max'           => 'Nilai maksimum adalah 100.',
            'rating_ulasan.min'     => 'Rating ulasan minimum adalah 1.',
            'rating_ulasan.max'     => 'Rating ulasan maksimum adalah 5.',
            'jumlah_ulasan.integer' => 'Jumlah ulasan harus berupa bilangan bulat.',
        ]);

        $request->validate([
            'nilai' => [function ($attribute, $value, $fail) use ($kriteriaIds) {
                foreach (array_keys($value) as $id) {
                    if (!in_array((int) $id, $kriteriaIds, true)) {
                        $fail('Kriteria yang dikirim tidak valid.');
                        return;
                    }
                }
            }],
        ]);

        $kriteriaUlasanId = Kriteria::where('nama_kriteria', 'Ulasan')->value('id');

        if ($request->filled('rating_ulasan')) {
            $toko->update([
                'rating_ulasan' => (float) $request->rating_ulasan,
                // Jumlah ulasan 0 tetap disimpan sebagai 0, bukan null.
                'jumlah_ulasan' => $request->filled('jumlah_ulasan') ? (int) $request->jumlah_ulasan : null,
            ]);
        }

        foreach ($request->nilai as $kriteriaId => $val) {
            if ($kriteriaUlasanId && (int) $kriteriaId === (int) $kriteriaUlasanId && $request->filled('rating_ulasan')) {
                $val = round(((float) $request->rating_ulasan / 5) * 100, 1);
            }

            NilaiAlternatif::updateOrCreate(
                ['toko_id' => $toko->id, 'kriteria_id' => (int) $kriteriaId],
                ['nilai' => (float) $val]
            );
        }
    }
}
