<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class TokoAdminController extends Controller
{
    /**
     * Tampilkan daftar semua toko.
     */
    public function index(Request $request)
    {
        // Admin dapat melihat semua toko (baik lengkap maupun belum)
        $query = Toko::with(['pemilik', 'nilaiAlternatif']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $tokos = $query->latest()->paginate(10);

        return view('admin.toko.index', compact('tokos'));
    }

    /**
     * Form tambah toko baru.
     */
    public function create()
    {
        $pemiliks = User::where('role', 'pemilik_toko')->get();

        return view('admin.toko.create', compact('pemiliks'));
    }

    /**
     * Simpan data toko baru ke database dengan validasi input.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string'],
            'deskripsi'       => ['nullable', 'string'],
            'media_sosial'    => ['nullable', 'string', 'max:255'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'pemilik_id'      => ['nullable', 'exists:users,id'],
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        Toko::create($validated);

        return redirect()->route('admin.toko.index')
            ->with('success', 'Data toko berhasil ditambahkan!');
    }

    /**
     * Form edit toko.
     */
    public function edit(Toko $toko)
    {
        $pemiliks = User::where('role', 'pemilik_toko')->get();

        return view('admin.toko.edit', compact('toko', 'pemiliks'));
    }

    /**
     * Update data toko dengan validasi input.
     */
    public function update(Request $request, Toko $toko)
    {
        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string'],
            'deskripsi'       => ['nullable', 'string'],
            'media_sosial'    => ['nullable', 'string', 'max:255'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'pemilik_id'      => ['nullable', 'exists:users,id'],
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        $toko->update($validated);

        return redirect()->route('admin.toko.index')
            ->with('success', 'Data toko berhasil diperbarui!');
    }

    /**
     * Hapus data toko.
     */
    public function destroy(Toko $toko)
    {
        $namaToko = $toko->nama;
        $toko->delete();

        return redirect()->route('admin.toko.index')
            ->with('success', "Toko \"{$namaToko}\" berhasil dihapus.");
    }
}
