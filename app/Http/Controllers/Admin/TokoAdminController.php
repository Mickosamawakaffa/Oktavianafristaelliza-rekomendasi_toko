<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TokoAdminController extends Controller
{
    /**
     * Tampilkan daftar semua toko.
     */
    public function index(Request $request)
    {
        // Hitung statistik status toko untuk tab navigasi
        $countAll = Toko::count();
        $countPending = Toko::where('status', 'pending')->count();
        $countApproved = Toko::where('status', 'approved')->count();
        $countRejected = Toko::where('status', 'rejected')->count();

        $query = Toko::with(['pemilik', 'nilaiAlternatif']);

        // Filter status
        if ($request->filled('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }

        // Search nama atau alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $tokos = $query->latest()->paginate(10)->withQueryString();

        return view('admin.toko.index', compact(
            'tokos',
            'countAll',
            'countPending',
            'countApproved',
            'countRejected'
        ));
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
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'pemilik_id'      => ['nullable', 'exists:users,id'],
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

        // Toko yang dibuat langsung oleh Admin otomatis diset 'approved'
        $validated['status'] = 'approved';

        Toko::create($validated);

        return redirect()->route('admin.toko.index')
            ->with('success', 'Data toko berhasil ditambahkan dan disetujui!');
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
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'pemilik_id'      => ['nullable', 'exists:users,id'],
            'status'          => ['nullable', 'in:pending,approved,rejected'],
            'catatan_verifikasi' => ['nullable', 'string'],
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

        $toko->update($validated);

        return redirect()->route('admin.toko.index')
            ->with('success', 'Data toko berhasil diperbarui!');
    }

    /**
     * Setujui permohonan toko (Approve).
     */
    public function approve(Toko $toko)
    {
        $toko->update([
            'status' => 'approved',
            'catatan_verifikasi' => null,
        ]);

        return redirect()->back()
            ->with('success', "Toko \"{$toko->nama}\" berhasil disetujui!");
    }

    /**
     * Tolak permohonan toko dengan catatan/alasan (Reject).
     */
    public function reject(Request $request, Toko $toko)
    {
        $request->validate([
            'catatan_verifikasi' => ['required', 'string', 'max:500'],
        ], [
            'catatan_verifikasi.required' => 'Alasan penolakan wajib diisi agar pemilik toko dapat memperbaiki datanya.',
        ]);

        $toko->update([
            'status' => 'rejected',
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        return redirect()->back()
            ->with('success', "Toko \"{$toko->nama}\" ditolak dengan catatan.");
    }

    /**
     * Hapus data toko.
     */
    public function destroy(Toko $toko)
    {
        $namaToko = $toko->nama;

        if ($toko->gambar && Storage::disk('public')->exists($toko->gambar)) {
            Storage::disk('public')->delete($toko->gambar);
        }

        $toko->delete();

        return redirect()->route('admin.toko.index')
            ->with('success', "Toko \"{$namaToko}\" berhasil dihapus.");
    }
}
