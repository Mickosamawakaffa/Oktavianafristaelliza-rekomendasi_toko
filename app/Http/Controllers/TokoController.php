<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;

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
     */
    public function show($id)
    {
        $toko = Toko::with(['nilaiAlternatif.kriteria', 'pemilik'])->findOrFail($id);

        return view('toko.show', compact('toko'));
    }
}
