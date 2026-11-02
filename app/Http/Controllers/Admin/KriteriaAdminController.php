<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaAdminController extends Controller
{
    /**
     * Kriteria inti yang dirujuk langsung oleh logika perhitungan (berdasarkan nama)
     * beserta jenis yang wajib: Lokasi dihitung dari jarak GPS (cost) dan Ulasan dari
     * Bayesian Average (benefit). Nama, jenis, dan keberadaannya tidak boleh diubah.
     */
    public const KRITERIA_INTI = [
        'Lokasi' => 'cost',
        'Ulasan' => 'benefit',
    ];
    /**
     * Tampilkan daftar kriteria.
     */
    public function index()
    {
        $kriterias = Kriteria::withCount('nilaiAlternatif')->get();

        return view('admin.kriteria.index', compact('kriterias'));
    }

    /**
     * Form tambah kriteria baru.
     */
    public function create()
    {
        return view('admin.kriteria.create');
    }

    /**
     * Simpan kriteria baru ke database dengan validasi input.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => ['required', 'string', 'max:255', 'unique:kriteria,nama_kriteria', 'not_in:' . implode(',', array_keys(self::KRITERIA_INTI))],
            'jenis'         => ['required', 'in:benefit,cost'],
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.unique'   => 'Nama kriteria ini sudah ada di database.',
            'jenis.required'         => 'Jenis kriteria wajib dipilih.',
            'jenis.in'               => 'Jenis kriteria harus benefit atau cost.',
        ]);

        Kriteria::create($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria baru berhasil ditambahkan!');
    }

    /**
     * Form edit kriteria.
     */
    public function edit(Kriteria $kriterium)
    {
        return view('admin.kriteria.edit', ['kriteria' => $kriterium]);
    }

    /**
     * Update data kriteria dengan validasi input.
     */
    public function update(Request $request, Kriteria $kriterium)
    {
        if (array_key_exists($kriterium->nama_kriteria, self::KRITERIA_INTI)) {
            return redirect()->route('admin.kriteria.index')
                ->with('error', "Kriteria \"{$kriterium->nama_kriteria}\" adalah kriteria inti sistem (dihitung otomatis dari GPS/ulasan) sehingga nama dan jenisnya tidak dapat diubah.");
        }

        $validated = $request->validate([
            'nama_kriteria' => ['required', 'string', 'max:255', 'unique:kriteria,nama_kriteria,' . $kriterium->id],
            'jenis'         => ['required', 'in:benefit,cost'],
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.unique'   => 'Nama kriteria ini sudah digunakan kriteria lain.',
            'jenis.required'         => 'Jenis kriteria wajib dipilih.',
            'jenis.in'               => 'Jenis kriteria harus benefit atau cost.',
        ]);

        $kriterium->update($validated);

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Data kriteria berhasil diperbarui!');
    }

    /**
     * Hapus kriteria.
     */
    public function destroy(Kriteria $kriterium)
    {
        if (array_key_exists($kriterium->nama_kriteria, self::KRITERIA_INTI)) {
            return redirect()->route('admin.kriteria.index')
                ->with('error', "Kriteria \"{$kriterium->nama_kriteria}\" adalah kriteria inti sistem dan tidak dapat dihapus.");
        }

        $nama = $kriterium->nama_kriteria;
        $kriterium->delete();

        return redirect()->route('admin.kriteria.index')
            ->with('success', "Kriteria \"{$nama}\" berhasil dihapus.");
    }
}
