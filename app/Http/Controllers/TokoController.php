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
     * Tampilkan daftar semua toko fashion secondhand.
     */
    public function index(Request $request)
    {
        $query = Toko::with(['nilaiAlternatif.kriteria']);

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

        $request->validate([
            'nilai'    => ['required', 'array'],
            'nilai.*'  => ['required', 'numeric', 'min:0', 'max:100'],
        ], [
            'nilai.*.required' => 'Semua nilai kriteria wajib diisi.',
            'nilai.*.numeric'  => 'Nilai kriteria harus berupa angka.',
            'nilai.*.min'      => 'Nilai minimum adalah 0.',
            'nilai.*.max'      => 'Nilai maksimum adalah 100.',
        ]);

        foreach ($request->nilai as $kriteriaId => $val) {
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
