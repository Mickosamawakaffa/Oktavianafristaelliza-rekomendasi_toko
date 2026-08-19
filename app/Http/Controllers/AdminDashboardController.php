<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Halaman dashboard admin.
     */
    public function index()
    {
        $totalKriteria = Kriteria::count();

        $stats = [
            'total_toko'     => Toko::count(),
            'total_kriteria' => $totalKriteria,
            'total_user'     => User::where('role', 'user')->count(),
            'total_pemilik'  => User::where('role', 'pemilik_toko')->count(),
        ];

        $tokoTerbaru = Toko::with('nilaiAlternatif')->latest()->take(5)->get();
        $userTerbaru = User::latest()->take(5)->get();

        // Toko yang belum dinilai admin sama sekali (jumlah nilai_alternatif < jumlah kriteria)
        // atau belum memiliki nilai sama sekali.
        $tokoPerluDinilai = Toko::withCount('nilaiAlternatif')
            ->having('nilai_alternatif_count', '<', $totalKriteria)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('stats', 'tokoTerbaru', 'userTerbaru', 'tokoPerluDinilai'));
    }
}
