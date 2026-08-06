@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('auth-content')
<h1 class="text-2xl font-extrabold text-white text-center mb-2">Buat Akun Baru</h1>
<p class="text-slate-400 text-sm text-center mb-8">Bergabung dan temukan toko thrift terbaik di Jember.</p>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-5 p-4 rounded-xl bg-rose-900/40 border border-rose-500/30 text-rose-300 text-xs space-y-1">
        <p class="font-semibold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan:</p>
        @foreach($errors->all() as $error)
            <p>- {{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-5">
    @csrf

    {{-- Nama Lengkap --}}
    <div>
        <label for="name" class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap</label>
        <div class="relative">
            <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                placeholder="Nama Lengkap Kamu"
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('name') border-rose-500/50 @enderror"
            />
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email</label>
        <div class="relative">
            <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="email@kamu.com"
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-rose-500/50 @enderror"
            />
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">Password</label>
        <div class="relative">
            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Min. 8 karakter"
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 mb-1.5">Konfirmasi Password</label>
        <div class="relative">
            <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                placeholder="Ulangi password kamu"
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
        </div>
    </div>

    {{-- Pilih Role --}}
    <div>
        <label class="block text-xs font-semibold text-slate-300 mb-2">Daftar Sebagai</label>
        <div class="grid grid-cols-2 gap-3">
            {{-- User Biasa --}}
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} class="peer sr-only">
                <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-white/10 bg-white/5 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 transition-all text-slate-400 peer-checked:text-emerald-400 text-center">
                    <i class="fa-solid fa-user-check text-xl"></i>
                    <span class="text-xs font-bold">Pengguna</span>
                    <span class="text-[10px] text-slate-500 peer-checked:text-emerald-500/80">Cari & rekomendasikan toko</span>
                </div>
                <div class="absolute top-2 right-2 w-4 h-4 rounded-full border-2 border-white/20 peer-checked:border-emerald-400 peer-checked:bg-emerald-500 hidden peer-checked:flex items-center justify-center">
                </div>
            </label>

            {{-- Pemilik Toko --}}
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="pemilik_toko" {{ old('role') === 'pemilik_toko' ? 'checked' : '' }} class="peer sr-only">
                <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-white/10 bg-white/5 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 transition-all text-slate-400 peer-checked:text-amber-400 text-center">
                    <i class="fa-solid fa-store text-xl"></i>
                    <span class="text-xs font-bold">Pemilik Toko</span>
                    <span class="text-[10px] text-slate-500 peer-checked:text-amber-500/80">Kelola profil toko kamu</span>
                </div>
            </label>
        </div>
        @error('role')
            <p class="text-xs text-rose-400 mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <button
        type="submit"
        class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-bold text-sm rounded-xl transition-all shadow-lg hover:shadow-emerald-500/25 flex items-center justify-center gap-2"
    >
        <i class="fa-solid fa-user-plus"></i>
        Buat Akun Sekarang
    </button>
</form>

<div class="mt-6 pt-6 border-t border-white/10 text-center">
    <p class="text-xs text-slate-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold ml-1">Masuk di sini</a>
    </p>
</div>
@endsection
