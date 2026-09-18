<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    /**
     * Tampilkan daftar toko fashion secondhand yang nilai kriterianya sudah lengkap.
     */
    public function index(Request $request)
    {
        // Hanya toko dengan nilai kriteria lengkap yang tampil di halaman publik
        $query = Toko::lengkap()->with(['nilaiAlternatif.kriteria']);

        // Filter pencarian berdasarkan nama atau alamat
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $tokos = $query->latest()->get();

        return view('toko.index', compact('tokos'));
    }

    /**
     * Tampilkan detail toko spesifik.
     * Untuk admin: sertakan semua kriteria dan nilaiMap agar form bisa pra-isi.
     */
    public function show($id)
    {
        $toko      = Toko::with(['nilaiAlternatif.kriteria', 'pemilik'])->findOrFail($id);
        $kriterias = Kriteria::orderBy('id')->get();
        $nilaiMap  = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();

        return view('toko.show', compact('toko', 'kriterias', 'nilaiMap'));
    }

    /**
     * Simpan/update nilai kriteria toko (hanya admin).
     * Validasi backend: non-admin langsung 403.
     */
    public function updateNilaiKriteria(Request $request, $id)
    {
        // Guard backend — tolak semua selain admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak: Hanya admin yang dapat mengubah nilai kriteria toko.');
        }

        $toko = Toko::findOrFail($id);

        // Cari ID kriteria "Ulasan" untuk sinkronisasi rating
        $kriteriaUlasan = Kriteria::where('nama_kriteria', 'Ulasan')->first();
        $kriteriaUlasanId = $kriteriaUlasan?->id;

        $request->validate([
            'nilai'         => ['required', 'array'],
            'nilai.*'       => ['required', 'numeric', 'min:0', 'max:100'],
            'rating_ulasan' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'jumlah_ulasan' => ['nullable', 'integer', 'min:0'],
        ], [
            'nilai.*.required' => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'      => 'Nilai minimum adalah 0.',
            'nilai.*.max'      => 'Nilai maksimum adalah 100.',
            'rating_ulasan.min' => 'Rating ulasan minimum adalah 1.',
            'rating_ulasan.max' => 'Rating ulasan maksimum adalah 5.',
        ]);

        // Simpan rating_ulasan & jumlah_ulasan ke tabel toko jika disediakan
        if ($request->filled('rating_ulasan')) {
            $toko->update([
                'rating_ulasan' => (float) $request->rating_ulasan,
                'jumlah_ulasan' => $request->jumlah_ulasan ? (int) $request->jumlah_ulasan : null,
            ]);
        }

        foreach ($request->nilai as $kriteriaId => $val) {
            // Untuk kriteria Ulasan: gunakan skor konversi dari rating, bukan input manual
            // Ini memastikan nilai di nilai_alternatif selalu konsisten dengan rating_ulasan di tabel toko
            if ($kriteriaUlasanId && (int) $kriteriaId === $kriteriaUlasanId && $request->filled('rating_ulasan')) {
                $val = round(((float) $request->rating_ulasan / 5) * 100, 1);
            }

            NilaiAlternatif::updateOrCreate(
                ['toko_id' => $toko->id, 'kriteria_id' => $kriteriaId],
                ['nilai'   => (float) $val]
            );
        }

        return redirect()
            ->route('toko.show', $toko->id)
            ->with('success', 'Nilai kriteria toko "' . $toko->nama . '" berhasil disimpan!');
    }
}
