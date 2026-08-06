<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaAdminController extends Controller
{
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
            'nama_kriteria' => ['required', 'string', 'max:255', 'unique:kriteria,nama_kriteria'],
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
        $nama = $kriterium->nama_kriteria;
        $kriterium->delete();

        return redirect()->route('admin.kriteria.index')
            ->with('success', "Kriteria \"{$nama}\" berhasil dihapus.");
    }
}
