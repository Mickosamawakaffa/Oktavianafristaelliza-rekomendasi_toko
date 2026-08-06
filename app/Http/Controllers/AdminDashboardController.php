<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
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
        $stats = [
            'total_toko'     => Toko::count(),
            'total_kriteria' => Kriteria::count(),
            'total_user'     => User::where('role', 'user')->count(),
            'total_pemilik'  => User::where('role', 'pemilik_toko')->count(),
        ];

        $tokoTerbaru = Toko::latest()->take(5)->get();
        $userTerbaru = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'tokoTerbaru', 'userTerbaru'));
    }
}
