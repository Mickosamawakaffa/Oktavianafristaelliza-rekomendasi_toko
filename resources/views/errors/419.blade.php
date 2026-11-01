@extends('errors.layout')

@section('title', 'Sesi Berakhir')
@section('code', '419')
@section('icon', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z')
@section('heading', 'Sesi Anda Telah Berakhir')
@section('message', 'Demi keamanan, formulir yang Anda kirim sudah kedaluwarsa karena halaman terlalu lama dibiarkan terbuka. Silakan muat ulang halaman lalu coba lagi.')

@section('actions')
    <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn shadow-sm transition-colors min-h-[44px] flex items-center justify-center">
        Kembali &amp; Coba Lagi
    </a>
    <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-page text-ink border border-border-strong font-bold text-sm rounded-btn transition-colors min-h-[44px] flex items-center justify-center">
        Ke Beranda
    </a>
@endsection
