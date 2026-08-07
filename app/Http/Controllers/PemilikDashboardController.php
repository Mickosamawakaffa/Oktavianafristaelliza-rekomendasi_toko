<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikDashboardController extends Controller
{
    /**
     * Halaman dashboard pemilik toko (daftar toko milik sendiri).
     */
    public function index()
    {
        $user = Auth::user();
        // Hanya load count nilai, BUKAN detail skor — pemilik toko tidak perlu tahu angkanya
        $tokoSaya = Toko::where('pemilik_id', $user->id)
            ->withCount('nilaiAlternatif')
            ->get();

        $totalKriteria = Kriteria::count();

        return view('pemilik.dashboard', compact('tokoSaya', 'totalKriteria'));
    }

    /**
     * Form pendaftaran toko baru bagi pemilik toko.
     */
    public function create()
    {
        return view('pemilik.toko.create');
    }

    /**
     * Simpan toko baru (otomatis terikat ke pemilik_id = Auth::id()).
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
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        // Set pemilik_id ke user yang sedang login
        $validated['pemilik_id'] = Auth::id();

        Toko::create($validated);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Toko Anda berhasil terdaftar!');
    }

    /**
     * Form edit toko milik sendiri.
     */
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);

        // Proteksi Hak Akses Kepemilikan
        $this->authorizeOwnership($toko);

        return view('pemilik.toko.edit', compact('toko'));
    }

    /**
     * Update data toko milik sendiri.
     */
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);

        // Proteksi Hak Akses Kepemilikan
        $this->authorizeOwnership($toko);

        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string'],
            'deskripsi'       => ['nullable', 'string'],
            'media_sosial'    => ['nullable', 'string', 'max:255'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        $toko->update($validated);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Data toko Anda berhasil diperbarui!');
    }

    /**
     * Hapus toko milik sendiri.
     */
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);

        // Proteksi Hak Akses Kepemilikan
        $this->authorizeOwnership($toko);

        $nama = $toko->nama;
        $toko->delete();

        return redirect()->route('pemilik.dashboard')
            ->with('success', "Toko \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Verifikasi bahwa toko memang milik user yang sedang login.
     */
    protected function authorizeOwnership(Toko $toko): void
    {
        if ((int) $toko->pemilik_id !== (int) Auth::id()) {
            abort(403, 'Akses Ditolak. Anda hanya dapat mengelola toko milik Anda sendiri.');
        }
    }
}
