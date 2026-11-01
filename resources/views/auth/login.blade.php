@extends('layouts.auth')

@section('title', 'Masuk ke Akun')

@section('auth-content')
<h1 class="text-2xl font-extrabold text-[#0F172A] text-center mb-1">Selamat Datang</h1>
<p class="text-[#526071] text-sm text-center mb-7">Masuk untuk mengakses rekomendasi toko thrift terbaik.</p>

{{-- Pesan Validasi Error --}}
@if($errors->has('email') || $errors->has('throttle'))
    <div class="mb-5 flex items-center gap-3 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-sm">
        <svg class="w-5 h-5 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>{{ $errors->first('email') ?: $errors->first('throttle') }}</span>
    </div>
@endif

<form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="loginForm" novalidate>
    @csrf

    {{-- Field Email --}}
    <div>
        <label for="email" class="block text-sm font-semibold text-[#0F172A] mb-1.5">Alamat Email</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="nama@email.com"
                class="w-full pl-11 pr-4 py-3 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
        <p id="emailError" class="hidden text-xs text-[#991B1B] mt-1.5 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Format email tidak valid.</span>
        </p>
    </div>

    {{-- Field Password --}}
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-sm font-semibold text-[#0F172A]">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-[#0F766E] hover:text-[#0B5C56] font-semibold transition-colors">
                Lupa password?
            </a>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#526071]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password Anda"
                class="w-full pl-11 pr-12 py-3 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
            <button
                type="button"
                id="togglePassword"
                aria-label="Tampilkan atau sembunyikan password"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#526071] hover:text-[#0F172A] transition-colors p-1"
            >
                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center">
        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-[#CBD5E1] text-[#0F766E] focus:ring-[#0F766E] cursor-pointer">
        <label for="remember" class="ml-2 text-sm text-[#526071] cursor-pointer">Ingat saya di perangkat ini</label>
    </div>

    {{-- Tombol Submit Teal Solid Lebar Penuh --}}
    <button
        type="submit"
        id="loginButton"
        class="w-full py-3.5 px-4 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-sm flex items-center justify-center gap-2 min-h-[44px]"
    >
        <span id="btnTextNormal" class="flex items-center gap-2">
            <span>Masuk Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </span>
        <span id="btnTextLoading" class="hidden flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span>Memproses...</span>
        </span>
    </button>
</form>

<div class="mt-6 pt-6 border-t border-[#E2E8F0] text-center">
    <p class="text-sm text-[#526071]">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#0F766E] hover:text-[#0B5C56] font-bold ml-1 transition-colors">
            Daftar di sini
        </a>
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.innerHTML = isPassword 
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />'
            : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    });

    const form = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const loginButton = document.getElementById('loginButton');
    const btnTextNormal = document.getElementById('btnTextNormal');
    const btnTextLoading = document.getElementById('btnTextLoading');

    emailInput.addEventListener('input', function () {
        if (!emailError.classList.contains('hidden') && isValidEmail(emailInput.value)) {
            emailError.classList.add('hidden');
        }
    });

    form.addEventListener('submit', function (e) {
        if (!isValidEmail(emailInput.value.trim())) {
            e.preventDefault();
            emailError.classList.remove('hidden');
            emailInput.focus();
            return;
        }

        emailError.classList.add('hidden');
        loginButton.disabled = true;
        btnTextNormal.classList.add('hidden');
        btnTextLoading.classList.remove('hidden');
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});
</script>
@endsection
