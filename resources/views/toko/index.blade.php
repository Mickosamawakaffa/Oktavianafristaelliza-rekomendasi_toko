@extends('layouts.app')

@section('title', 'Daftar Toko Fashion Secondhand - Jember')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 text-white py-12 px-4 sm:px-6 lg:px-8 shadow-inner">
    <div class="max-w-7xl mx-auto text-center">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-4">
            <i class="fa-solid fa-sparkles text-xs"></i> Sistem Pendukung Keputusan Jember
        </span>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
            Cari Toko Fashion Secondhand <span class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent">Terbaik</span>
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto mb-8 leading-relaxed">
            Temukan rekomendasi tempat thrift fashion terbaik di wilayah Jember berdasarkan kriteria harga, kualitas, variasi, dan ulasan terpercaya.
        </p>

        <!-- Search Bar -->
        <form action="{{ route('toko.index') }}" method="GET" class="max-w-xl mx-auto relative">
            <div class="relative flex items-center">
                <i class="fa-solid fa-magnifying-glass absolute left-4 text-slate-400"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama toko atau lokasi (misal: Sumbersari, Kalimantan)..." 
                    class="w-full pl-11 pr-28 py-3.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-slate-900/80 transition-all text-sm"
                />
                <button 
                    type="submit" 
                    class="absolute right-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm flex items-center gap-1.5"
                >
                    <span>Cari</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Content / Store Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Daftar Toko Fashion</h2>
            <p class="text-xs sm:text-sm text-slate-500">Menampilkan {{ $tokos->count() }} toko fashion secondhand di Jember</p>
        </div>
        @if(request('search'))
            <a href="{{ route('toko.index') }}" class="text-xs text-rose-600 hover:text-rose-700 font-medium flex items-center gap-1">
                <i class="fa-solid fa-xmark"></i> Reset Pencarian
            </a>
        @endif
    </div>

    @if($tokos->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 max-w-md mx-auto my-8">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-4 text-2xl">
                <i class="fa-solid fa-store-slash"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-lg mb-1">Toko Tidak Ditemukan</h3>
            <p class="text-slate-500 text-xs mb-4">Tidak ada toko yang cocok dengan kata kunci "{{ request('search') }}"</p>
            <a href="{{ route('toko.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition">
                Tampilkan Semua Toko
            </a>
        </div>
    @else
        <!-- Grid 3 Kolom -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tokos as $toko)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden group hover:-translate-y-1">
                    <div>
                        <!-- Header Card dengan Gradient Thumbnail Placeholder -->
                        <div class="h-36 bg-gradient-to-r from-emerald-600 to-teal-700 p-5 relative flex flex-col justify-between text-white">
                            <div class="flex justify-between items-start">
                                <span class="px-2.5 py-1 rounded-full bg-black/30 backdrop-blur-md text-[11px] font-medium text-emerald-200 border border-white/10 flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    {{ $toko->jam_operasional ?? '10:00 - 21:00' }}
                                </span>
                                @if($toko->media_sosial)
                                    <span class="text-xs bg-white/20 backdrop-blur-md px-2 py-0.5 rounded text-white font-mono">
                                        {{ $toko->media_sosial }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-white text-emerald-700 flex items-center justify-center font-bold text-xl shadow-md">
                                    <i class="fa-solid fa-shirt"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-white group-hover:text-emerald-200 transition-colors line-clamp-1">
                                        {{ $toko->nama }}
                                    </h3>
                                    <p class="text-xs text-emerald-100 flex items-center gap-1">
                                        <i class="fa-solid fa-location-dot text-[10px]"></i>
                                        Jember, Jawa Timur
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="p-5">
                            <p class="text-xs text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                                {{ $toko->deskripsi ?? 'Toko thrift fashion secondhand berkualitas di Jember.' }}
                            </p>

                            <!-- Alamat Detail -->
                            <div class="flex items-start gap-2 text-xs text-slate-500 mb-4 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                <i class="fa-solid fa-map-pin text-emerald-600 mt-0.5"></i>
                                <span class="line-clamp-2">{{ $toko->alamat }}</span>
                            </div>

                            <!-- Nilai Kriteria Badges -->
                            @if($toko->nilaiAlternatif->count() > 0)
                                <div class="border-t border-slate-100 pt-3">
                                    <span class="text-[11px] font-semibold text-slate-400 block mb-2 uppercase tracking-wider">Nilai Kriteria Preview</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($toko->nilaiAlternatif->take(3) as $nilai)
                                            <span class="inline-flex items-center gap-1 text-[11px] px-2 py-1 rounded bg-emerald-50 text-emerald-800 font-medium border border-emerald-200/60">
                                                <span class="text-slate-500">{{ $nilai->kriteria->nama_kriteria }}:</span>
                                                <span class="font-bold">{{ $nilai->nilai }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Card / Action -->
                    <div class="px-5 pb-5 pt-0">
                        <a href="{{ route('toko.show', $toko->id) }}" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl transition-all duration-200 flex items-center justify-center gap-2 group-hover:shadow-md">
                            <span>Lihat Detail & Lokasi</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
