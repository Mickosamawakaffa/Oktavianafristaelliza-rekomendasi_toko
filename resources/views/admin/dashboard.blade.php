@extends('layouts.app')

@section('title', 'Dashboard Admin - ThriftJember')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shadow-soft">
                <svg class="w-6 h-6 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-[#0F172A]">Dashboard Admin</h1>
                <p class="text-xs sm:text-sm text-[#526071]">Selamat datang, <span class="font-bold text-[#0F766E]">{{ Auth::user()->name }}</span></p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#E6F4F1] text-[#0B5C56] border border-[#0F766E]/30">
            <svg class="w-3.5 h-3.5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Administrator</span>
        </span>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-[12px] bg-[#DCFCE7] border border-[#BBF7D0] text-[#166534] text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats Grid: Kartu Putih, Border #E2E8F0, Radius 16px --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Toko --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $stats['total_toko'] }}</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-[#526071] mb-2">Total Toko</p>
                <div class="flex items-center gap-1.5 text-[10px] font-bold">
                    <span class="px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534]">{{ $stats['total_approved'] }} Disetujui</span>
                    @if($stats['total_pending'] > 0)
                        <span class="px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]">{{ $stats['total_pending'] }} Menunggu</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Kriteria --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $stats['total_kriteria'] }}</span>
            </div>
            <p class="text-xs font-semibold text-[#526071]">Total Kriteria</p>
        </div>

        {{-- Total Pengguna --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-[10px] bg-[#F3F5F8] text-[#334155] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $stats['total_user'] }}</span>
            </div>
            <p class="text-xs font-semibold text-[#526071]">Pengguna Terdaftar</p>
        </div>

        {{-- Total Pemilik Toko --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-[10px] bg-[#F3F5F8] text-[#334155] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <span class="text-3xl font-extrabold text-[#0F172A]">{{ $stats['total_pemilik'] }}</span>
            </div>
            <p class="text-xs font-semibold text-[#526071]">Pemilik Toko</p>
        </div>
    </div>

    {{-- Toko Perlu Dinilai --}}
    @if($tokoPerluDinilai->isNotEmpty())
    <div class="mb-8 bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 bg-[#F8FAFC] border-b border-[#E2E8F0]">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-[8px] bg-[#FEF3C7] text-[#92400E] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#0F172A]">Toko Belum Dinilai</h2>
                    <p class="text-[11px] text-[#526071]">{{ $tokoPerluDinilai->count() }} toko perlu diisi nilai kriterianya untuk kalkulasi ROC-SMART</p>
                </div>
            </div>
            <a href="{{ route('admin.nilai-alternatif.index') }}" class="text-xs font-bold text-[#0F766E] hover:text-[#0B5C56] flex items-center gap-1">
                <span>Lihat semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="divide-y divide-[#E2E8F0]">
            @foreach($tokoPerluDinilai->take(6) as $toko)
            <div class="flex items-center justify-between px-6 py-3.5 hover:bg-[#F8FAFC] transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-[8px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-[#0F172A] truncate">{{ $toko->nama }}</p>
                        <p class="text-[11px] text-[#526071] truncate">{{ $toko->alamat }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0 ml-4">
                    @if($toko->nilai_alternatif_count === 0)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#FEF2F2] text-[#991B1B]">Belum dinilai</span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E]">Sebagian ({{ $toko->nilai_alternatif_count }})</span>
                    @endif
                    <a href="{{ route('admin.nilai-alternatif.edit', $toko->id) }}"
                       class="px-3 py-1.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-[11px] font-bold rounded-[8px] transition-colors shadow-sm flex items-center gap-1 min-h-[32px]">
                        <span>Nilai Sekarang</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Grid 2 Kolom: Toko Terbaru & Pengguna Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Toko Terbaru --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-base font-bold text-[#0F172A]">Toko Terbaru</h2>
                <a href="{{ route('admin.toko.index') }}" class="text-xs font-semibold text-[#0F766E] hover:underline flex items-center gap-1">
                    <span>Lihat semua</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="space-y-3">
                @foreach($tokoTerbaru as $toko)
                    <div class="flex items-center gap-3 p-3 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0]">
                        <div class="w-9 h-9 rounded-[8px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-[#0F172A] truncate">{{ $toko->nama }}</p>
                                @if($toko->isKriteriaLengkap())
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534] shrink-0">Lengkap</span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E] shrink-0">Belum Lengkap</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-[#526071] truncate">{{ $toko->alamat }}</p>
                        </div>
                        <a href="{{ route('toko.show', $toko->id) }}" class="shrink-0 text-xs text-[#0F766E] hover:underline font-semibold px-2 py-1">
                            Detail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pengguna Terbaru --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-base font-bold text-[#0F172A]">Pengguna Terbaru</h2>
            </div>
            <div class="space-y-3">
                @foreach($userTerbaru as $u)
                    @php
                        $roleLabels = ['admin' => 'Admin', 'user' => 'Pengguna', 'pemilik_toko' => 'Pemilik'];
                        $roleColors = ['admin' => 'bg-[#E6F4F1] text-[#0B5C56]', 'user' => 'bg-[#DCFCE7] text-[#166534]', 'pemilik_toko' => 'bg-[#FEF3C7] text-[#92400E]'];
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0]">
                        <div class="w-9 h-9 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-xs font-bold shrink-0 uppercase">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-[#0F172A] truncate">{{ $u->name }}</p>
                            <p class="text-[11px] text-[#526071] truncate">{{ $u->email }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $roleColors[$u->role] ?? 'bg-[#F3F5F8] text-[#526071]' }} shrink-0">
                            {{ $roleLabels[$u->role] ?? $u->role }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
