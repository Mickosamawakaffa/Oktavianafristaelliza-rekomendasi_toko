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
            'total_approved' => Toko::where('status', 'approved')->count(),
            'total_pending'  => Toko::where('status', 'pending')->count(),
            'total_rejected' => Toko::where('status', 'rejected')->count(),
            'total_kriteria' => $totalKriteria,
            'total_user'     => User::where('role', 'user')->count(),
            'total_pemilik'  => User::where('role', 'pemilik_toko')->count(),
        ];

        $tokoTerbaru = Toko::with('nilaiAlternatif')->latest()->take(5)->get();
        $userTerbaru = User::latest()->take(5)->get();

        // Toko yang belum dinilai admin sama sekali (jumlah nilai_alternatif < jumlah kriteria)
        // atau belum memiliki nilai sama sekali.
        $tokoPerluDinilai = Toko::withCount(['nilaiAlternatif' => function ($q) {
                $q->whereNotNull('nilai');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn ($toko) => $toko->nilai_alternatif_count < $totalKriteria)
            ->values();

        return view('admin.dashboard', compact('stats', 'tokoTerbaru', 'userTerbaru', 'tokoPerluDinilai'));
    }
}
