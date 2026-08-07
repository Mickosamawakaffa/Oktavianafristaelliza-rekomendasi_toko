@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-violet-600 to-purple-500 text-white flex items-center justify-center shadow-md">
                <i class="fa-solid fa-gauge-high text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Dashboard Admin</h1>
                <p class="text-sm text-slate-500">Selamat datang, <span class="font-semibold text-violet-600">{{ Auth::user()->name }}</span></p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">
            <i class="fa-solid fa-shield-halved text-xs"></i> Administrator
        </span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
        {{-- Total Toko --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fa-solid fa-store"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_toko'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500">Total Toko</p>
        </div>

        {{-- Total Kriteria --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_kriteria'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500">Total Kriteria</p>
        </div>

        {{-- Total User --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_user'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500">Pengguna Terdaftar</p>
        </div>

        {{-- Total Pemilik --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <span class="text-3xl font-extrabold text-slate-900">{{ $stats['total_pemilik'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500">Pemilik Toko</p>
        </div>
    </div>

    {{-- Toko Perlu Dinilai --}}
    @if($tokoPerluDinilai->isNotEmpty())
    <div class="mb-8 bg-white rounded-2xl border border-amber-200 shadow-xs overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 bg-amber-50 border-b border-amber-200">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-amber-900">Toko Belum Dinilai</h2>
                    <p class="text-[11px] text-amber-600">{{ $tokoPerluDinilai->count() }} toko perlu segera diisi nilai kriterianya</p>
                </div>
            </div>
            <a href="{{ route('admin.nilai-alternatif.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900 flex items-center gap-1">
                Lihat semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($tokoPerluDinilai->take(8) as $toko)
            <div class="flex items-center justify-between px-6 py-3 hover:bg-amber-50/40 transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-shirt text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ $toko->nama }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ $toko->alamat }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-4">
                    @if($toko->nilai_alternatif_count === 0)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200">Belum dinilai</span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">Sebagian ({{ $toko->nilai_alternatif_count }})</span>
                    @endif
                    <a href="{{ route('admin.nilai-alternatif.edit', $toko->id) }}"
                       class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold rounded-lg transition flex items-center gap-1">
                        <i class="fa-solid fa-pen-to-square text-[10px]"></i> Nilai Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @if($tokoPerluDinilai->count() > 8)
        <div class="px-6 py-3 bg-amber-50/60 border-t border-amber-100 text-center">
            <a href="{{ route('admin.nilai-alternatif.index') }}" class="text-xs font-semibold text-amber-700 hover:underline">
                + {{ $tokoPerluDinilai->count() - 8 }} toko lainnya &mdash; Lihat semua
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- Recent Data --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Toko Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-base font-bold text-slate-900">Toko Terbaru</h2>
                <a href="{{ route('toko.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline flex items-center gap-1">Lihat semua <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
            </div>
            <div class="space-y-3">
                @foreach($tokoTerbaru as $toko)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $toko->nama }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ $toko->alamat }}</p>
                        </div>
                        <a href="{{ route('toko.show', $toko->id) }}" class="shrink-0 text-[10px] text-slate-400 hover:text-emerald-600 font-medium border border-slate-200 rounded-lg px-2 py-1 hover:border-emerald-300 transition-colors">Detail</a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- User Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-base font-bold text-slate-900">User Terbaru</h2>
            </div>
            <div class="space-y-3">
                @foreach($userTerbaru as $u)
                    @php
                        $roleLabels = ['admin' => 'Admin', 'user' => 'User', 'pemilik_toko' => 'Pemilik'];
                        $roleColors = ['admin' => 'bg-violet-100 text-violet-700', 'user' => 'bg-emerald-100 text-emerald-700', 'pemilik_toko' => 'bg-amber-100 text-amber-700'];
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                        <div class="w-9 h-9 rounded-full bg-slate-700 text-white flex items-center justify-center text-sm font-bold shrink-0 uppercase">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $u->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-1 rounded-md {{ $roleColors[$u->role] ?? 'bg-slate-100 text-slate-600' }} shrink-0">
                            {{ $roleLabels[$u->role] ?? $u->role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
