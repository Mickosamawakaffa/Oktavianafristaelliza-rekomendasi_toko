@extends('layouts.auth')

@section('title', 'Masuk ke Akun')

@section('auth-content')
<h1 class="text-2xl font-extrabold text-white text-center mb-2">Selamat Datang</h1>
<p class="text-slate-400 text-sm text-center mb-8">Masuk untuk mengakses rekomendasi toko terbaik.</p>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-5 p-4 rounded-xl bg-rose-900/40 border border-rose-500/30 text-rose-300 text-xs space-y-1">
        <p class="font-semibold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan:</p>
        @foreach($errors->all() as $error)
            <p>- {{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('login.post') }}" method="POST" class="space-y-5">
    @csrf

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
                autofocus
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
                placeholder="••••••••"
                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
            />
        </div>
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center">
        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-slate-600 bg-white/5 text-emerald-500 focus:ring-emerald-500">
        <label for="remember" class="ml-2 text-xs font-medium text-slate-400">Ingat saya di perangkat ini</label>
    </div>

    {{-- Submit --}}
    <button
        type="submit"
        class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-slate-900 font-bold text-sm rounded-xl transition-all shadow-lg hover:shadow-emerald-500/25 flex items-center justify-center gap-2"
    >
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        Masuk Sekarang
    </button>
</form>

<div class="mt-6 pt-6 border-t border-white/10 text-center">
    <p class="text-xs text-slate-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold ml-1">Daftar di sini</a>
    </p>
</div>

{{-- Demo Credentials --}}
<div class="mt-4 p-3.5 rounded-xl bg-emerald-950/50 border border-emerald-500/20">
    <p class="text-[11px] font-semibold text-emerald-400 mb-2"><i class="fa-solid fa-circle-info mr-1"></i>Akun Demo (Testing):</p>
    <div class="space-y-1 text-[11px] text-slate-400 font-mono">
        <p>Admin: <span class="text-slate-300">admin@gmail.com</span> / password</p>
        <p>User: <span class="text-slate-300">user@gmail.com</span> / password</p>
        <p>Pemilik: <span class="text-slate-300">pemilik@toko.com</span> / password</p>
    </div>
</div>
@endsection
