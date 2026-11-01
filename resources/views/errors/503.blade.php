@extends('errors.layout')

@section('title', 'Sedang Dalam Pemeliharaan')
@section('code', '503')
@section('icon', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z')
@section('heading', 'Sedang Dalam Pemeliharaan')
@section('message', 'Kami sedang melakukan pembaruan sistem. Layanan akan segera kembali normal. Terima kasih atas kesabaran Anda.')

@section('actions')
    <a href="{{ url()->current() }}" class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn shadow-sm transition-colors min-h-[44px] flex items-center justify-center">
        Coba Lagi
    </a>
@endsection
