@extends('errors.layout')

@section('title', 'Terjadi Kesalahan')
@section('code', '500')
@section('icon', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z')
@section('heading', 'Terjadi Kesalahan pada Server')
@section('message', 'Maaf, ada masalah di sisi kami. Kami sedang berupaya memperbaikinya. Silakan coba beberapa saat lagi.')

@section('actions')
    <a href="{{ url()->current() }}" class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn shadow-sm transition-colors min-h-[44px] flex items-center justify-center">
        Muat Ulang Halaman
    </a>
    <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-page text-ink border border-border-strong font-bold text-sm rounded-btn transition-colors min-h-[44px] flex items-center justify-center">
        Ke Beranda
    </a>
@endsection
