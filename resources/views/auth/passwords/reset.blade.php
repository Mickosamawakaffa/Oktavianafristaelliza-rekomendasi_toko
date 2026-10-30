@extends('layouts.auth')

@section('title', 'Reset Password')

@section('auth-content')
<h1 class="text-2xl font-extrabold text-[#0F172A] text-center mb-1">Reset Password</h1>
<p class="text-[#526071] text-sm text-center mb-7">Masukkan password baru untuk akun Anda.</p>

{{-- Validation Errors --}}
@if($errors->any())
    <div class="mb-5 flex items-center gap-3 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-sm">
        <svg class="w-5 h-5 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<form action="{{ route('password.update') }}" method="POST" class="space-y-4">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

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
                value="{{ old('email', $email) }}"
                required
                autocomplete="email"
                placeholder="nama@email.com"
                class="w-full pl-10 pr-4 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
        </div>
    </div>

    {{-- Password Baru --}}
    <div>
        <label for="password" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Password Baru</label>
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
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="w-full pl-10 pr-12 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
            <button
                type="button"
                onclick="togglePw('password')"
                aria-label="Tampilkan atau sembunyikan password"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#526071] hover:text-[#0F172A] p-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Konfirmasi Password Baru --}}
    <div>
        <label for="password_confirmation" class="block text-xs font-semibold text-[#0F172A] mb-1.5">Konfirmasi Password Baru</label>
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
                autocomplete="new-password"
                placeholder="Ulangi password baru"
                class="w-full pl-10 pr-12 py-2.5 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] placeholder-[#94A3B8] text-sm focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] transition-all min-h-[44px]"
            />
            <button
                type="button"
                onclick="togglePw('password_confirmation')"
                aria-label="Tampilkan atau sembunyikan password"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#526071] hover:text-[#0F172A] p-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>

    <button
        type="submit"
        class="w-full py-3.5 px-4 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-sm flex items-center justify-center gap-2 min-h-[44px] mt-2"
    >
        <span>Reset Password</span>
    </button>
</form>

<script>
function togglePw(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
