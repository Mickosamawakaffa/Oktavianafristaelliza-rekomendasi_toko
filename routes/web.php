<?php

use App\Http\Controllers\Admin\KriteriaAdminController;
use App\Http\Controllers\Admin\NilaiAlternatifAdminController;
use App\Http\Controllers\Admin\TokoAdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PemilikDashboardController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;

// ─── Halaman Publik (Tanpa Login) ───────────────────────────────────────────
Route::get('/', [TokoController::class, 'index'])->name('toko.index');
Route::get('/toko/{id}', [TokoController::class, 'show'])->name('toko.show');
// Hanya admin yang bisa POST ini (guard ada di controller, bukan middleware)
Route::post('/toko/{id}/nilai-kriteria', [TokoController::class, 'updateNilaiKriteria'])->name('toko.nilai-kriteria.update')->middleware('auth');

// ─── Autentikasi (Guest Only) ────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

// ─── Logout (Auth Only) ──────────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── User Dashboard ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/preferensi', [UserDashboardController::class, 'preferensi'])->name('preferensi');
    Route::post('/preferensi', [UserDashboardController::class, 'simpanPreferensi'])->name('preferensi.simpan');
    Route::get('/hasil', [UserDashboardController::class, 'hasil'])->name('hasil');
});

// ─── Admin Dashboard & CRUD ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Toko & Verifikasi
    Route::post('/toko/{toko}/approve', [TokoAdminController::class, 'approve'])->name('toko.approve');
    Route::post('/toko/{toko}/reject', [TokoAdminController::class, 'reject'])->name('toko.reject');
    Route::resource('toko', TokoAdminController::class);

    // CRUD Kriteria
    Route::resource('kriteria', KriteriaAdminController::class);

    // CRUD Nilai Alternatif
    Route::get('/nilai-alternatif', [NilaiAlternatifAdminController::class, 'index'])->name('nilai-alternatif.index');
    Route::get('/nilai-alternatif/{tokoId}/edit', [NilaiAlternatifAdminController::class, 'edit'])->name('nilai-alternatif.edit');
    Route::put('/nilai-alternatif/{tokoId}', [NilaiAlternatifAdminController::class, 'update'])->name('nilai-alternatif.update');
});

// ─── Pemilik Toko Dashboard ───────────────────────────────────────────────────
Route::middleware(['auth', 'role:pemilik_toko'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard');
    Route::get('/toko/create', [PemilikDashboardController::class, 'create'])->name('toko.create');
    Route::post('/toko', [PemilikDashboardController::class, 'store'])->name('toko.store');
    Route::get('/toko/{id}/edit', [PemilikDashboardController::class, 'edit'])->name('toko.edit');
    Route::put('/toko/{id}', [PemilikDashboardController::class, 'update'])->name('toko.update');
    Route::delete('/toko/{id}', [PemilikDashboardController::class, 'destroy'])->name('toko.destroy');
});
