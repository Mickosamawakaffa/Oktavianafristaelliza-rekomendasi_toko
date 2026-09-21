<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        // Anti-spam: Cegah pemilik toko membuat banyak pengajuan toko saat masih ada yang pending
        $hasPending = Toko::where('pemilik_id', Auth::id())->where('status', 'pending')->exists();
        if ($hasPending) {
            return redirect()->route('pemilik.dashboard')
                ->with('error', 'Anda masih memiliki pengajuan toko yang sedang menunggu verifikasi Admin. Harap tunggu hingga verifikasi selesai sebelum mendaftarkan toko lain.');
        }

        $validated = $request->validate([
            'nama'            => ['required', 'string', 'max:255'],
            'alamat'          => ['required', 'string'],
            'deskripsi'       => ['nullable', 'string'],
            'media_sosial'    => ['nullable', 'string', 'max:255'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'gambar.image'    => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('toko', 'public');
        }

        // Set pemilik_id ke user yang sedang login & set status ke 'pending'
        $validated['pemilik_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['catatan_verifikasi'] = null;

        Toko::create($validated);

        return redirect()->route('pemilik.dashboard')
            ->with('success', 'Pengajuan pendaftaran toko berhasil dikirim! Admin akan memverifikasi toko Anda terlebih dahulu.');
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
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
        ], [
            'nama.required'   => 'Nama toko wajib diisi.',
            'alamat.required' => 'Alamat toko wajib diisi.',
            'gambar.image'    => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'gambar.max'      => 'Ukuran gambar maksimal 2MB.',
            'latitude.numeric'  => 'Format latitude harus berupa angka koordinat.',
            'longitude.numeric' => 'Format longitude harus berupa angka koordinat.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($toko->gambar && Storage::disk('public')->exists($toko->gambar)) {
                Storage::disk('public')->delete($toko->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('toko', 'public');
        }

        // Jika toko sebelumnya ditolak, reset status menjadi 'pending' untuk diverifikasi ulang oleh Admin
        if ($toko->status === 'rejected') {
            $validated['status'] = 'pending';
            $validated['catatan_verifikasi'] = null;
            $pesan = 'Data toko berhasil diperbarui dan diajukan ulang untuk verifikasi Admin.';
        } else {
            $pesan = 'Data toko Anda berhasil diperbarui!';
        }

        $toko->update($validated);

        return redirect()->route('pemilik.dashboard')
            ->with('success', $pesan);
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

        if ($toko->gambar && Storage::disk('public')->exists($toko->gambar)) {
            Storage::disk('public')->delete($toko->gambar);
        }

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
