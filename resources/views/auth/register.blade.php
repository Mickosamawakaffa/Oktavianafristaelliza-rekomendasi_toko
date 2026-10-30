@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('auth-content')
<h1 class="text-2xl font-extrabold text-[#0F172A] text-center mb-1">Buat Akun Baru</h1>
<p class="text-[#526071] text-sm text-center mb-7">Bergabung dan temukan toko thrift fashion terbaik di Jember.</p>

{{-- Pesan Validasi Error --}}
@if($errors->any())
    <div class="mb-5 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs space-y-1">
        <p class="font-bold flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Terdapat beberapa kesalahan pengisian:</span>
        </p>
        <ul class="list-disc pl-5 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register.post') }}" method="POST" class="space-y-4">
    @csrf

    {{-- Nama Lengkap --}}
    <div>
        <label for="name" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Nama Lengkap</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                placeholder="Nama lengkap Anda"
                class="w-full pl-10 pr-4 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Alamat Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="nama@email.com"
                class="w-full pl-10 pr-4 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input
                type="password"
                id="password"
                name="password"
                required
                placeholder="Minimal 8 karakter"
                class="w-full pl-10 pr-4 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div>
        <label for="password_confirmation" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Konfirmasi Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                placeholder="Ulangi password"
                class="w-full pl-10 pr-4 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
    </div>

    {{-- Pilihan Role Akun --}}
    <div>
        <label class="block text-xs font-semibold text-[#0F172A] mb-2">Daftar Sebagai</label>
        <div class="grid grid-cols-2 gap-3">
            {{-- User / Pengguna --}}
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} class="peer sr-only">
                <div class="flex flex-col items-center gap-1.5 p-3 rounded-[12px] border-2 border-[#E2E8F0] bg-white peer-checked:border-[#0F766E] peer-checked:bg-[#E6F4F1] transition-all text-[#526071] peer-checked:text-[#0F766E] text-center">
                    <svg class="w-5 h-5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-xs font-bold text-[#0F172A]">Pengguna</span>
                    <span class="text-[10px] text-[#526071]">Cari toko & preferensi</span>
                </div>
            </label>

            {{-- Pemilik Toko --}}
            <label class="relative cursor-pointer">
                <input type="radio" name="role" value="pemilik_toko" {{ old('role') === 'pemilik_toko' ? 'checked' : '' }} class="peer sr-only">
                <div class="flex flex-col items-center gap-1.5 p-3 rounded-[12px] border-2 border-[#E2E8F0] bg-white peer-checked:border-[#0F766E] peer-checked:bg-[#E6F4F1] transition-all text-[#526071] peer-checked:text-[#0F766E] text-center">
                    <svg class="w-5 h-5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-xs font-bold text-[#0F172A]">Pemilik Toko</span>
                    <span class="text-[10px] text-[#526071]">Kelola profil toko</span>
                </div>
            </label>
        </div>
        @error('role')
            <p class="text-xs text-[#991B1B] mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tombol Submit Teal Solid --}}
    <button
        type="submit"
        class="w-full py-3 px-4 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-sm flex items-center justify-center gap-2 min-h-[44px] mt-2"
    >
        <span>Buat Akun Sekarang</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
        </svg>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-[#E2E8F0] text-center">
    <p class="text-xs text-[#526071]">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-[#0F766E] hover:text-[#0B5C56] font-bold ml-1 transition-colors">
            Masuk di sini
        </a>
    </p>
</div>
@endsection
