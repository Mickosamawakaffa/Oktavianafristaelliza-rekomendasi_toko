@extends('layouts.app')

@section('title', 'Daftar Toko Thrift & Rekomendasi ROC-SMART - Jember')

@section('content')
<!-- Hero Section (Latar --color-hero: #0E2A3B) -->
<section class="bg-[#0E2A3B] text-white pt-14 pb-20 lg:pt-16 lg:pb-24 px-4 sm:px-6 lg:px-8 border-b border-[#1a3a4e]">
    <div class="max-w-4xl mx-auto text-center">
        <!-- Badge Pil: Sistem Pendukung Keputusan Jember -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-[#5EEAD4]/10 text-[#5EEAD4] border border-[#5EEAD4]/25 mb-6">
            <svg class="w-4 h-4 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Sistem Pendukung Keputusan Jember</span>
        </div>

        <!-- H1 Judul Hero (clamp 32px s/d 52px, bobot 800, kata "Terbaik" berwarna #5EEAD4) -->
        <h1 class="text-[clamp(32px,5vw,52px)] font-extrabold text-white tracking-tight mb-4 leading-tight">
            Temukan Toko Thrift <span class="text-[#5EEAD4]">Terbaik</span> di Jember
        </h1>

        <!-- Paragraf Warna #CBD5E1 -->
        <p class="text-[#CBD5E1] text-sm sm:text-base max-w-2xl mx-auto mb-8 leading-relaxed font-normal">
            Sistem rekomendasi objektif berbasis multi-kriteria metode ROC-SMART untuk memudahkan Anda memilih toko thrift fashion pilihan dengan nilai tertinggi.
        </p>

        <!-- Kolom Pencarian Putih dengan Bayangan Lembut -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('toko.index') }}" method="GET" class="relative">
                @if(request('buka_saja'))
                    <input type="hidden" name="buka_saja" value="1">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('kriteria_order'))
                    <input type="hidden" name="kriteria_order" value="{{ is_array(request('kriteria_order')) ? implode(',', request('kriteria_order')) : request('kriteria_order') }}">
                @endif

                <div class="flex items-center bg-white rounded-[12px] p-1.5 shadow-[0_8px_24px_rgba(15,23,42,0.12)] border border-[#E2E8F0]">
                    <div class="pl-3.5 pr-2 text-[#526071]">
                        <svg class="w-5 h-5 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari toko thrift di Jember..." 
                        class="w-full py-3 px-2 bg-transparent text-[#0F172A] placeholder-[#526071] text-sm sm:text-base focus:outline-none"
                    />
                    <button 
                        type="submit" 
                        class="px-5 sm:px-7 py-3 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-sm font-bold rounded-[10px] transition-colors shrink-0 shadow-sm flex items-center gap-2 min-h-[44px]"
                    >
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Kontainer Utama -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

    <!-- Banner Ajakan Login untuk Tamu (Kartu Putih Menumpuk -mt-[52px] ke Hero) -->
    @guest
    <div class="-mt-[52px] mb-8 relative z-10">
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 sm:p-7 shadow-[0_8px_24px_rgba(15,23,42,0.06)] flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0">
                    <!-- Ikon Perisai SVG Stroke -->
                    <svg class="w-6 h-6 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#0F172A]">
                        Ingin Rekomendasi yang Disesuaikan untuk Anda?
                    </h2>
                    <p class="text-sm text-[#526071] mt-1 leading-relaxed">
                        Masuk atau daftar untuk mengatur prioritas kriteria sesuai preferensi Anda dan memperoleh rekomendasi personal.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('login') }}" 
                   class="flex-1 md:flex-none text-center px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-sm min-h-[44px] flex items-center justify-center">
                    Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" 
                   class="flex-1 md:flex-none text-center px-5 py-2.5 bg-white hover:bg-[#E6F4F1] text-[#0F766E] font-bold text-sm rounded-[10px] border border-[#0F766E] transition-colors min-h-[44px] flex items-center justify-center">
                    Daftar Akun
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="pt-6"></div>
    @endguest

    <!-- Notifikasi Info Prioritas Kriteria Kustom Aktif -->
    @if(request('kriteria_order'))
        <div class="mb-6 p-4 rounded-[12px] bg-[#E6F4F1] border border-[#0F766E]/30 flex flex-wrap items-center justify-between gap-3 text-sm">
            <div class="flex items-center gap-2 text-[#0B5C56] font-medium">
                <svg class="w-5 h-5 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Prioritas kriteria kustom aktif: skor dihitung ulang dengan metode ROC-SMART.</span>
            </div>
            <button type="button" onclick="openPrioritasModal()" class="text-xs font-bold text-[#0F766E] hover:text-[#0B5C56] hover:underline flex items-center gap-1 min-h-[44px]">
                <span>Ubah Prioritas Lagi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Hasil Pencarian Info Banner -->
    @if(request('search'))
        <div class="mb-6 p-4 rounded-[12px] bg-white border border-[#E2E8F0] shadow-soft flex items-center justify-between gap-3">
            <p class="text-sm text-[#334155]">
                Hasil pencarian untuk "<span class="font-bold text-[#0F172A]">{{ request('search') }}</span>"
                — Ditemukan <span class="font-extrabold text-[#0F766E]">{{ $tokos->count() }}</span> toko
            </p>
            <a href="{{ route('toko.index') }}" class="text-xs font-bold text-[#991B1B] hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Hapus pencarian</span>
            </a>
        </div>
    @endif

    <!-- Bar Kontrol (Filter Kiri + Pengurutan Kanan) -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        {{-- Kelompok Filter Kiri --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Tombol "Buka Sekarang" (Outline + Titik Hijau) --}}
            @php
                $bukaQuery = request()->all();
                if (request('buka_saja')) {
                    unset($bukaQuery['buka_saja']);
                } else {
                    $bukaQuery['buka_saja'] = 1;
                }
            @endphp
            <a href="{{ route('toko.index', $bukaQuery) }}" 
               class="px-4 py-2.5 rounded-[10px] text-sm font-semibold border transition-all flex items-center gap-2 min-h-[44px]
               {{ request('buka_saja') 
                   ? 'bg-[#DCFCE7] text-[#166534] border-[#16A34A] shadow-soft' 
                   : 'bg-white text-[#334155] border-[#CBD5E1] hover:border-[#0F766E] hover:text-[#0F766E]' }}">
                <span class="w-2.5 h-2.5 rounded-full {{ request('buka_saja') ? 'bg-[#16A34A]' : 'bg-[#16A34A]' }}"></span>
                <span>Buka Sekarang</span>
            </a>

            {{-- Tombol "Atur Prioritas Kriteria" (Tint Teal + Ikon Sliders) --}}
            <button 
                type="button" 
                onclick="openPrioritasModal()" 
                class="px-4 py-2.5 rounded-[10px] text-sm font-semibold bg-[#E6F4F1] text-[#0B5C56] border border-[#0F766E]/30 hover:bg-[#d5eee8] transition-all flex items-center gap-2 min-h-[44px]"
            >
                <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Atur Prioritas Kriteria</span>
                @if(request('kriteria_order'))
                    <span class="w-2 h-2 rounded-full bg-[#0F766E]"></span>
                @endif
            </button>

            {{-- Reset Filter Jika Sedang Terfilter --}}
            @if(request('search') || request('buka_saja') || request('sort') || request('kriteria_order'))
                <a href="{{ route('toko.index') }}" 
                   class="px-3.5 py-2.5 rounded-[10px] text-sm font-semibold text-[#991B1B] hover:text-[#7f1d1d] bg-[#FEF2F2] border border-[#FECACA] transition flex items-center gap-1.5 min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset</span>
                </a>
            @endif
        </div>

        {{-- Kelompok Pengurutan --}}
        <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-end">
            <label for="sortDropdown" class="text-sm font-semibold text-[#526071] shrink-0">
                Urutkan:
            </label>
            <div class="relative">
                <select 
                    id="sortDropdown" 
                    onchange="handleSortChange(this.value)"
                    class="px-3.5 py-2.5 pr-8 rounded-[10px] bg-white border border-[#CBD5E1] text-[#0F172A] text-sm font-medium focus:outline-none focus:border-[#0F766E] cursor-pointer shadow-soft appearance-none min-h-[44px] min-w-[240px]"
                >
                    <option value="rekomendasi" {{ ($sort ?? 'rekomendasi') === 'rekomendasi' ? 'selected' : '' }}>
                        Skor Rekomendasi ROC-SMART
                    </option>
                    <option value="rating_tertinggi" {{ ($sort ?? '') === 'rating_tertinggi' ? 'selected' : '' }}>
                        Rating Tertinggi
                    </option>
                    <option value="nama" {{ ($sort ?? '') === 'nama' ? 'selected' : '' }}>
                        Nama A-Z
                    </option>
                    <option value="terbaru" {{ ($sort ?? '') === 'terbaru' ? 'selected' : '' }}>
                        Toko Terbaru
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-[#526071]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Kartu Toko -->
    @if($tokos->isEmpty())
        <div class="bg-white rounded-[16px] p-10 sm:p-12 text-center border border-[#E2E8F0] shadow-soft max-w-md mx-auto my-12">
            <div class="w-16 h-16 bg-[#F3F5F8] rounded-full flex items-center justify-center mx-auto text-[#526071] mb-4">
                <svg class="w-8 h-8 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="font-bold text-[#0F172A] text-lg mb-1">Toko Tidak Ditemukan</h3>
            <p class="text-[#526071] text-sm mb-6 leading-relaxed">
                Tidak ada toko yang sesuai dengan filter atau kata kunci "{{ request('search') }}".
            </p>
            <a href="{{ route('toko.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0F766E] text-white text-sm font-bold rounded-[10px] hover:bg-[#0B5C56] transition-colors shadow-soft min-h-[44px]">
                <span>Tampilkan Semua Toko</span>
            </a>
        </div>
    @else
        <div class="store-grid">
            @foreach($tokos as $toko)
                @php
                    $isTopRank = ($toko->roc_rank === 1);
                @endphp
                <div class="bg-white rounded-[16px] p-6 flex flex-col justify-between transition-all duration-200
                     {{ $isTopRank 
                         ? 'border-2 border-[#0F766E] shadow-[0_8px_24px_rgba(15,118,110,0.12)]' 
                         : 'border border-[#E2E8F0] shadow-soft hover:shadow-[0_8px_24px_rgba(15,23,42,0.06)]' }}">
                    
                    <div class="space-y-4">
                        <!-- 1. Baris Atas: Badge Peringkat (#1 Emas, #2 Perak, #3 Perunggu) & Rating Bintang + Jumlah Ulasan -->
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                @if($toko->roc_rank === 1)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A]">
                                        <svg class="w-3.5 h-3.5 text-[#92400E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                        </svg>
                                        <span>#1 Rekomendasi</span>
                                    </span>
                                @elseif($toko->roc_rank === 2)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#E2E8F0] text-[#334155] border border-[#CBD5E1]">
                                        <span>#2 Rekomendasi</span>
                                    </span>
                                @elseif($toko->roc_rank === 3)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FFEDD5] text-[#9A3412] border border-[#FED7AA]">
                                        <span>#3 Rekomendasi</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F3F5F8] text-[#526071] border border-[#E2E8F0]">
                                        <span>#{{ $toko->roc_rank }}</span>
                                    </span>
                                @endif
                            </div>

                            <!-- Rating Bintang + Jumlah Ulasan -->
                            <div class="flex items-center gap-1 text-sm font-semibold text-[#0F172A]">
                                <svg class="w-4 h-4 text-[#F59E0B]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span>{{ number_format($toko->rating_ulasan ?? 4.5, 1, ',', '.') }}</span>
                                <span class="text-xs text-[#526071] font-normal">({{ $toko->jumlah_ulasan ?? 0 }} ulasan)</span>
                            </div>
                        </div>

                        <!-- 2. Ikon Toko dalam Kotak Tint 48px, Nama Toko (18px), Lokasi dengan Ikon Pin -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-[18px] font-bold text-[#0F172A] leading-snug truncate">
                                    {{ $toko->nama }}
                                </h3>
                                <p class="text-xs text-[#526071] flex items-center gap-1 mt-0.5 truncate">
                                    <svg class="w-3.5 h-3.5 text-[#526071] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Jember, Jawa Timur</span>
                                </p>
                            </div>
                        </div>

                        <!-- 3. Blok "Skor ROC-SMART": Angka Persen Besar 28px Bobot 800 Warna Teal + Progress Bar 8px -->
                        <div class="bg-[#F8FAFC] rounded-[12px] p-3.5 border border-[#E2E8F0]">
                            <div class="flex items-baseline justify-between mb-2">
                                <span class="text-xs font-semibold text-[#526071]">Skor ROC-SMART</span>
                                <span class="text-[28px] font-extrabold text-[#0F766E] leading-none">
                                    {{ number_format($toko->roc_percent, 1, ',', '.') }}%
                                </span>
                            </div>
                            <div class="w-full bg-[#E2E8F0] rounded-full h-2 overflow-hidden">
                                <div class="bg-[#0F766E] h-2 rounded-full transition-all duration-500"
                                     style="width: {{ min(100, max(0, $toko->roc_percent)) }}%">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Deskripsi, lalu Alamat dalam Kotak Abu Muda dengan Ikon Pin Teal -->
                        <p class="text-sm text-[#334155] line-clamp-2 leading-relaxed">
                            {{ $toko->deskripsi ?? 'Toko thrift fashion secondhand berkualitas di Jember dengan koleksi pakaian pilihan.' }}
                        </p>

                        <div class="flex items-start gap-2 bg-[#F8FAFC] p-3 rounded-[10px] border border-[#E2E8F0] text-xs text-[#526071]">
                            <svg class="w-4 h-4 text-[#0F766E] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="line-clamp-2 leading-relaxed">{{ $toko->alamat }}</span>
                        </div>

                        <!-- 5. "NILAI KRITERIA": Satu Baris Per Kriteria (Label, Progress Bar 6px Teal, Angka Tebal di Kanan) -->
                        @if($toko->nilaiAlternatif && $toko->nilaiAlternatif->count() > 0)
                            <div class="pt-2 border-t border-[#E2E8F0] space-y-2.5">
                                <span class="text-xs font-bold text-[#526071] tracking-wider uppercase block">
                                    Nilai Kriteria
                                </span>
                                <div class="space-y-2">
                                    @foreach($toko->nilaiAlternatif->take(3) as $nilai)
                                        <div>
                                            <div class="flex justify-between items-center text-xs mb-1">
                                                <span class="font-medium text-[#334155] truncate">{{ $nilai->kriteria->nama_kriteria }}</span>
                                                <span class="font-bold text-[#0F172A]">{{ number_format($nilai->nilai, 1, ',', '.') }}</span>
                                            </div>
                                            <div class="w-full bg-[#E2E8F0] rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#0F766E] h-1.5 rounded-full"
                                                     style="width: {{ min(100, max(0, $nilai->nilai)) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- 6. Pil Status "Buka Sekarang" + Jam Buka dengan Ikon Jam -->
                        <div class="flex items-center justify-between text-xs pt-2">
                            <div>
                                @if($toko->is_buka !== null)
                                    @if($toko->is_buka)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                            <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                                            <span>Buka Sekarang</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEF2F2] text-[#991B1B]">
                                            <span class="w-2 h-2 rounded-full bg-[#DC2626]"></span>
                                            <span>Tutup</span>
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5 text-[#526071] font-medium">
                                <svg class="w-4 h-4 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $toko->jam_operasional ?? '10:00 - 21:00' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Dua Tombol: "Lihat Detail" (Teal Solid) dan "Lihat di Peta" (Outline) -->
                    <div class="pt-5 mt-4 border-t border-[#E2E8F0] grid grid-cols-2 gap-3">
                        <a href="{{ route('toko.show', $toko->id) }}" 
                           class="py-2.5 px-3 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs sm:text-sm font-bold rounded-[10px] transition-colors text-center flex items-center justify-center gap-1.5 shadow-sm min-h-[44px]">
                            <span>Lihat Detail</span>
                        </a>

                        @php
                            $mapsUrl = "https://www.google.com/maps/search/?api=1&query={$toko->latitude},{$toko->longitude}";
                        @endphp
                        <a href="{{ $mapsUrl }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="py-2.5 px-3 bg-white hover:bg-[#E6F4F1] text-[#0F766E] border border-[#0F766E] text-xs sm:text-sm font-bold rounded-[10px] transition-colors text-center flex items-center justify-center gap-1.5 min-h-[44px]">
                            <span>Lihat di Peta</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Modal Atur Prioritas Kriteria ROC-SMART --}}
<div id="modalPrioritas" 
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="prioritasTitle">

    <!-- Overlay Latar Belakang -->
    <div class="fixed inset-0 bg-[#0F172A]/50 backdrop-blur-xs transition-opacity" onclick="closePrioritasModal()"></div>

    <!-- Panel Box Modal -->
    <div class="relative z-10 w-full max-w-lg bg-white border border-[#E2E8F0] rounded-[16px] shadow-[0_8px_24px_rgba(15,23,42,0.12)] overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Header Modal -->
        <div class="flex items-center justify-between px-6 py-4 bg-[#F8FAFC] border-b border-[#E2E8F0]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <h3 id="prioritasTitle" class="text-base font-extrabold text-[#0F172A]">Atur Prioritas Kriteria (ROC)</h3>
                    <p class="text-xs text-[#526071]">Urutkan kriteria dari yang paling Anda utamakan</p>
                </div>
            </div>
            <button type="button" onclick="closePrioritasModal()" 
                    class="w-9 h-9 rounded-[10px] bg-white border border-[#E2E8F0] text-[#526071] hover:text-[#0F172A] hover:bg-[#F3F5F8] flex items-center justify-center transition min-h-[36px]"
                    aria-label="Tutup modal prioritas">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Info Petunjuk -->
        <div class="px-6 py-3 bg-[#E6F4F1] border-b border-[#E2E8F0] text-xs text-[#0B5C56] flex items-start gap-2.5">
            <svg class="w-4 h-4 text-[#0F766E] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Gunakan tombol panah Naik dan Turun untuk mengubah urutan prioritas. Posisi #1 memperoleh bobot ROC tertinggi.</span>
        </div>

        <!-- Form Urutan Kriteria -->
        <form id="prioritasForm" action="{{ route('toko.index') }}" method="GET" class="flex flex-col flex-1 overflow-hidden">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('buka_saja'))
                <input type="hidden" name="buka_saja" value="1">
            @endif
            <input type="hidden" name="sort" value="{{ request('sort', 'rekomendasi') }}">

            <!-- Daftar Kriteria Terurut -->
            <div class="p-6 space-y-2.5 overflow-y-auto flex-1 bg-white" id="kriteriaListContainer">
                @foreach($orderedKriterias as $index => $kriteria)
                    <div class="kriteria-item flex items-center justify-between p-3.5 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0] hover:border-[#0F766E]/50 transition-all gap-3"
                         data-id="{{ $kriteria->id }}">
                        
                        <input type="hidden" name="kriteria_order[]" value="{{ $kriteria->id }}" class="kriteria-input-id">

                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Nomor Urut / Rank Badge -->
                            <span class="rank-badge w-8 h-8 rounded-[8px] bg-[#E6F4F1] text-[#0F766E] font-bold text-xs flex items-center justify-center shrink-0">
                                #{{ $index + 1 }}
                            </span>

                            <div class="min-w-0">
                                <span class="text-sm font-bold text-[#0F172A] block truncate">{{ $kriteria->nama_kriteria }}</span>
                                <span class="text-xs font-medium text-[#526071]">
                                    Jenis: <span class="capitalize">{{ $kriteria->jenis }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Aksi Panah Naik / Turun -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button 
                                type="button" 
                                onclick="moveKriteria(this, -1)" 
                                class="btn-move-up w-9 h-9 rounded-[8px] bg-white hover:bg-[#E6F4F1] text-[#334155] hover:text-[#0F766E] flex items-center justify-center transition border border-[#CBD5E1] text-xs"
                                aria-label="Pindahkan kriteria ke atas"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <button 
                                type="button" 
                                onclick="moveKriteria(this, 1)" 
                                class="btn-move-down w-9 h-9 rounded-[8px] bg-white hover:bg-[#E6F4F1] text-[#334155] hover:text-[#0F766E] flex items-center justify-center transition border border-[#CBD5E1] text-xs"
                                aria-label="Pindahkan kriteria ke bawah"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 bg-[#F8FAFC] border-t border-[#E2E8F0] flex flex-wrap items-center justify-between gap-3">
                <button 
                    type="button" 
                    onclick="resetKriteriaOrder()"
                    class="px-3.5 py-2 text-xs font-bold text-[#526071] hover:text-[#0F172A] transition flex items-center gap-1.5 min-h-[44px]"
                >
                    <svg class="w-4 h-4 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset Default</span>
                </button>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        onclick="closePrioritasModal()" 
                        class="px-4 py-2.5 rounded-[10px] text-sm font-semibold text-[#334155] hover:bg-white transition border border-[#CBD5E1] min-h-[44px]"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-sm min-h-[44px] flex items-center gap-2"
                    >
                        <span>Terapkan & Hitung Ulang</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function handleSortChange(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    }

    function openPrioritasModal() {
        const modal = document.getElementById('modalPrioritas');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closePrioritasModal() {
        const modal = document.getElementById('modalPrioritas');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePrioritasModal();
    });

    function moveKriteria(btn, direction) {
        const item = btn.closest('.kriteria-item');
        const container = document.getElementById('kriteriaListContainer');
        const items = Array.from(container.querySelectorAll('.kriteria-item'));
        const index = items.indexOf(item);

        const newIndex = index + direction;
        if (newIndex < 0 || newIndex >= items.length) return;

        if (direction === -1) {
            container.insertBefore(item, items[newIndex]);
        } else {
            container.insertBefore(item, items[newIndex].nextSibling);
        }

        updateRankBadges();
    }

    function updateRankBadges() {
        const container = document.getElementById('kriteriaListContainer');
        const items = container.querySelectorAll('.kriteria-item');
        items.forEach((item, idx) => {
            const badge = item.querySelector('.rank-badge');
            if (badge) {
                badge.textContent = '#' + (idx + 1);
            }
        });
    }

    function resetKriteriaOrder() {
        const container = document.getElementById('kriteriaListContainer');
        const items = Array.from(container.querySelectorAll('.kriteria-item'));
        
        items.sort((a, b) => {
            return parseInt(a.dataset.id) - parseInt(b.dataset.id);
        });

        items.forEach(item => container.appendChild(item));
        updateRankBadges();
    }
</script>
@endsection
