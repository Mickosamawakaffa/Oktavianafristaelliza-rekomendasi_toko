@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Toko Terbaik')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0 border border-[#0F766E]/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-[#0F172A] leading-tight">Hasil Rekomendasi Toko</h1>
                <p class="text-sm text-[#526071] mt-0.5">Rekomendasi disesuaikan secara otomatis berdasarkan <strong class="text-[#0F766E]">urutan prioritas dan titik lokasi Anda</strong></p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('user.hasil.cetak') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] transition shadow-soft min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Laporan SPK (PDF)</span>
            </a>
            <a href="{{ route('user.preferensi') }}" class="flex items-center gap-2 px-4 py-2.5 border border-[#CBD5E1] hover:border-[#0F766E] text-[#0F172A] hover:text-[#0F766E] text-xs font-bold rounded-[10px] transition bg-white hover:bg-[#E6F4F1]/30 min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Ubah Preferensi & GPS</span>
            </a>
        </div>
    </div>

    {{-- Pilihan Terbaik (ringkasan cepat untuk pengguna) --}}
    @php
        $topItem = $rankings->first();
        $topToko = $topItem['toko'] ?? null;
    @endphp
    @if($topToko)
        <section aria-labelledby="judul-pilihan-terbaik" class="mb-8 rounded-card bg-hero text-white overflow-hidden shadow-teal border border-[#1a384c] relative">
            <div class="absolute -top-24 -right-16 w-80 h-80 rounded-full bg-hero-accent/10 blur-3xl" aria-hidden="true"></div>
            <div class="relative p-6 sm:p-8 flex flex-col md:flex-row md:items-center gap-6">
                @if($topToko->gambar)
                    <img src="{{ asset('storage/' . $topToko->gambar) }}" alt="Foto {{ $topToko->nama }}" class="w-full md:w-48 h-40 md:h-36 rounded-[14px] object-cover border border-white/15 shadow-soft shrink-0" />
                @endif
                <div class="flex-1 min-w-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gold-bg text-gold-text mb-3">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Pilihan terbaik untuk Anda
                    </span>
                    <h2 id="judul-pilihan-terbaik" class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-1">{{ $topToko->nama }}</h2>
                    <p class="text-sm text-[#CBD5E1] mb-4">{{ $topToko->alamat }}</p>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
                        <div>
                            <p class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider">Skor</p>
                            <p class="text-2xl font-extrabold text-hero-accent leading-tight">{{ number_format($topItem['score'] * 100, 1, ',', '.') }}%</p>
                        </div>
                        @if($topItem['jarak_km'] !== null)
                            <div>
                                <p class="text-xs font-semibold text-[#94A3B8] uppercase tracking-wider">Jarak (garis lurus)</p>
                                <p class="text-2xl font-extrabold leading-tight">{{ number_format($topItem['jarak_km'], 1, ',', '.') }} <span class="text-base font-bold text-[#CBD5E1]">km</span></p>
                            </div>
                        @endif
                        @if($topToko->is_buka !== null)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $topToko->is_buka ? 'bg-success-bg text-success-text' : 'bg-[#FEF2F2] text-[#991B1B]' }}">
                                <span class="w-2 h-2 rounded-full {{ $topToko->is_buka ? 'bg-success-dot' : 'bg-[#DC2626]' }}"></span>
                                {{ $topToko->is_buka ? 'Buka sekarang' : 'Sedang tutup' }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-row md:flex-col gap-3 shrink-0">
                    <a href="{{ route('toko.show', $topToko->id) }}" class="flex-1 px-5 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-btn transition-colors text-center min-h-[44px] flex items-center justify-center shadow-sm">Lihat Detail</a>
                    <a href="https://www.google.com/maps/dir/?api=1&origin={{ $userLat }},{{ $userLng }}&destination={{ $topToko->latitude }},{{ $topToko->longitude }}" target="_blank" rel="noopener noreferrer" class="flex-1 px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-btn transition-colors border border-white/25 text-center min-h-[44px] flex items-center justify-center">Buka Rute</a>
                </div>
            </div>
        </section>
    @endif

    {{-- User GPS Banner --}}
    <div class="mb-6 p-4 rounded-[16px] bg-white border border-[#E2E8F0] shadow-soft flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center font-bold text-base shrink-0 border border-[#0F766E]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-[#526071] uppercase tracking-wider block">Titik Acuan GPS Lokasi Kamu</span>
                <span class="text-xs font-bold text-[#0F172A] font-mono">
                    Latitude: {{ $userLat }}, Longitude: {{ $userLng }}
                </span>
                @if($gpsDefault)
                    <span class="block text-[11px] font-semibold text-[#92400E] mt-1">Menggunakan lokasi default (pusat Kota Jember) karena GPS belum diatur. Klik "Ubah Preferensi & GPS" agar jarak sesuai posisi Anda.</span>
                @else
                    <span class="block text-[11px] text-[#526071] mt-1">Lokasi sesuai yang Anda atur di halaman preferensi.</span>
                @endif
            </div>
        </div>
        <span class="text-xs px-3 py-1.5 bg-[#DCFCE7] text-[#166534] font-semibold rounded-full border border-[#BBF7D0] flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            <span>Jarak dari lokasi Anda</span>
        </span>
    </div>

    {{-- Bobot & Preferensi Summary --}}
    <div class="bg-white rounded-[16px] p-5 mb-8 border border-[#E2E8F0] shadow-soft">
        <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2 mb-4">
            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
            </svg>
            <span>Tingkat Prioritas Kriteria Pilihan Anda</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($preferensiUser as $index => $pref)
                @php
                    $rawPct = isset($weights[$pref->kriteria_id]) ? $weights[$pref->kriteria_id] * 100 : 0;
                    $pct = number_format($rawPct, 1, ',', '.');
                    $barWidth = min(100, $rawPct);
                @endphp
                <div class="bg-[#F8FAFC] border border-[#E2E8F0] rounded-[12px] p-3 text-center">
                    <div class="text-xs font-bold text-[#0F766E] mb-0.5">Rank {{ $pref->urutan }}</div>
                    <div class="text-xs font-semibold text-[#0F172A] mb-2 truncate" title="{{ $pref->kriteria->nama_kriteria }}">{{ $pref->kriteria->nama_kriteria }}</div>
                    <div class="text-xl font-extrabold text-[#0F172A] font-mono">{{ $pct }}%</div>
                    <div class="mt-2 h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden">
                        <div class="bg-[#0F766E] h-1.5 rounded-full" style="width: {{ $barWidth }}%"></div>
                    </div>
                    <div class="mt-2 text-xs px-2 py-0.5 rounded-full {{ $pref->kriteria->jenis === 'benefit' ? 'bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0]' : 'bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA]' }} font-semibold inline-block">
                        {{ ucfirst($pref->kriteria->jenis) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Peta Persebaran Toko (OpenStreetMap + Leaflet) --}}
    <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
            <div>
                <h2 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Peta Persebaran Toko Rekomendasi</span>
                </h2>
                <p class="text-xs text-[#526071]">Marker melambangkan posisi Anda dan toko rekomendasi dengan nomor peringkat.</p>
            </div>
            <span class="text-[10px] font-bold px-2.5 py-1 rounded-[6px] bg-[#E6F4F1] text-[#0F766E] border border-[#0F766E]/20 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <span>OpenStreetMap</span>
            </span>
        </div>

        {{-- Map container --}}
        <div id="osm-map-hasil" class="w-full h-80 sm:h-96 rounded-[12px] border border-[#CBD5E1] shadow-inner overflow-hidden"></div>
    </div>

    {{-- Rankings List --}}
    <div class="mb-5 flex items-center justify-between">
        <h2 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
            <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span>Ranking Toko ({{ $rankings->count() }} Toko Ditemukan)</span>
        </h2>
    </div>

    <div class="space-y-4">
        @foreach($rankings as $idx => $item)
            @php
                $toko = $item['toko'];
                $score = $item['score'];
                $utilities = $item['utilities'];
                $distanceKm = $item['jarak_km'];
                $rank = $idx + 1;

                // Design tokens for rank badges
                $rankBadgeClass = match($rank) {
                    1 => 'bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A]',
                    2 => 'bg-[#E2E8F0] text-[#334155] border border-[#CBD5E1]',
                    3 => 'bg-[#FFEDD5] text-[#9A3412] border border-[#FED7AA]',
                    default => 'bg-[#F1F5F9] text-[#526071] border border-[#E2E8F0]',
                };

                $rankLabel = match($rank) {
                    1 => 'Peringkat #1 Terbaik',
                    2 => 'Peringkat #2 Runner-up',
                    3 => 'Peringkat #3',
                    default => "Peringkat #{$rank}",
                };

                $isTop1 = ($rank === 1);
            @endphp

            <div id="toko-card-{{ $rank }}" class="bg-white rounded-[16px] {{ $isTop1 ? 'border-2 border-[#0F766E] shadow-[0_8px_24px_rgba(15,118,110,0.12)]' : 'border border-[#E2E8F0] shadow-soft' }} overflow-hidden transition-all hover:-translate-y-0.5 duration-200">
                <div class="flex flex-col sm:flex-row gap-0">

                    {{-- Rank Badge Sidebar --}}
                    <div class="{{ $isTop1 ? 'bg-[#0E2A3B] text-white' : 'bg-[#F8FAFC] text-[#0F172A] border-b sm:border-b-0 sm:border-r border-[#E2E8F0]' }} px-6 py-5 flex flex-row sm:flex-col items-center justify-between sm:justify-center gap-4 sm:gap-2 sm:min-w-[100px] sm:text-center">
                        <div class="flex flex-col sm:block items-center gap-1">
                            <span class="text-3xl font-extrabold leading-none {{ $isTop1 ? 'text-[#5EEAD4]' : 'text-[#0F172A]' }}">#{{ $rank }}</span>
                            <span class="text-xs font-semibold opacity-70 hidden sm:block">dari {{ $rankings->count() }}</span>
                        </div>
                        <span class="text-xs font-bold sm:hidden px-2.5 py-0.5 rounded-full {{ $rankBadgeClass }}">{{ $rankLabel }}</span>
                        {{-- Score --}}
                        <div class="sm:mt-3 text-center">
                            <div class="text-xl font-extrabold leading-none {{ $isTop1 ? 'text-white' : 'text-[#0F766E]' }}" title="Skor SMART: {{ number_format($score, 4, ',', '.') }}">{{ number_format($score * 100, 1, ',', '.') }}%</div>
                            <div class="text-xs font-semibold opacity-70 mt-1">Skor personal</div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="flex-1 p-5">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                            <div class="flex items-start gap-3.5">
                                @if($toko?->gambar)
                                    <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-[12px] object-cover border border-[#E2E8F0] shadow-soft shrink-0 mt-0.5" />
                                @else
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0 border border-[#0F766E]/20 mt-0.5">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $rankBadgeClass }}">{{ $rankLabel }}</span>

                                        {{-- Distance Badge (Haversine Formula Result) --}}
                                        @if($distanceKm !== null)
                                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-[#E6F4F1] text-[#0F766E] border border-[#0F766E]/20 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ number_format($distanceKm, 2, ',', '.') }} km (garis lurus)
                                            </span>
                                        @endif

                                        {{-- Status Jam Operasional (Buka / Tutup) --}}
                                        @if($toko?->is_buka !== null)
                                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $toko->is_buka ? 'bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0]' : 'bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA]' }} flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $toko->is_buka ? 'bg-[#16A34A]' : 'bg-[#DC2626]' }}"></span>
                                                {{ $toko->is_buka ? 'Buka Sekarang' : 'Tutup' }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-extrabold text-[#0F172A]">{{ $toko?->nama ?? 'Toko #'.$item['toko_id'] }}</h3>
                                    <p class="text-xs text-[#526071] flex items-center gap-1 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#0F766E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>{{ $toko?->alamat ?? 'Alamat tidak tersedia' }}</span>
                                    </p>
                                </div>
                            </div>
                            @if($toko)
                                <div class="flex flex-wrap items-center gap-2 shrink-0">
                                    <a href="https://www.google.com/maps/dir/?api=1&origin={{ $userLat }},{{ $userLng }}&destination={{ $toko->latitude }},{{ $toko->longitude }}" 
                                       target="_blank" 
                                       class="flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-[#F3F5F8] text-[#0F172A] border border-[#CBD5E1] text-xs font-bold rounded-[10px] transition min-h-[44px]">
                                        <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        <span>Rute Maps</span>
                                    </a>
                                    <a href="{{ route('toko.show', $toko->id) }}" class="flex items-center gap-1.5 px-4 py-2 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] transition min-h-[44px] shadow-soft">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span>Detail Toko</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        @if($toko)
                            {{-- Toko Meta --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($toko->jam_operasional)
                                    <span class="text-[11px] bg-[#F8FAFC] text-[#526071] px-2.5 py-1 rounded-[6px] font-medium flex items-center gap-1 border border-[#E2E8F0]">
                                        <svg class="w-3.5 h-3.5 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $toko->jam_operasional }}</span>
                                    </span>
                                @endif
                                @if($toko->media_sosial)
                                    <a href="https://instagram.com/{{ ltrim($toko->media_sosial, '@') }}" target="_blank" class="text-[11px] bg-[#FDF2F8] hover:bg-[#FCE7F3] text-[#BE185D] px-2.5 py-1 rounded-[6px] font-medium flex items-center gap-1 border border-[#FBCFE8] transition">
                                        <svg class="w-3.5 h-3.5 text-[#BE185D]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                        </svg>
                                        <span>{{ $toko->media_sosial }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Utility Scores Per Kriteria --}}
                        <div class="border-t border-[#E2E8F0] pt-4">
                            <p class="text-xs font-bold text-[#526071] uppercase tracking-wider mb-2.5">Nilai Per Kriteria</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                                @foreach($preferensiUser as $pref)
                                    @php
                                        $utilVal = $utilities[$pref->kriteria_id] ?? 0;
                                        $utilPct = number_format($utilVal * 100, 1, ',', '.');
                                        $utilWidth = min(100, $utilVal * 100);
                                    @endphp
                                    <div class="p-2.5 rounded-[10px] border border-[#E2E8F0] bg-[#F8FAFC]">
                                        <p class="text-xs font-semibold text-[#526071] truncate mb-1" title="{{ $pref->kriteria->nama_kriteria }}">{{ $pref->kriteria->nama_kriteria }}</p>
                                        <p class="text-[#0F766E] text-sm font-extrabold font-mono mb-1.5">{{ $utilPct }}%</p>
                                        <div class="h-1.5 bg-[#E2E8F0] rounded-full overflow-hidden">
                                            <div class="bg-[#0F766E] h-1.5 rounded-full" style="width: {{ $utilWidth }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Accordion Transparansi Perhitungan Matriks SPK --}}
    <div class="mt-10 bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
        <button type="button" 
                onclick="document.getElementById('transparansi-spk-content').classList.toggle('hidden'); document.getElementById('icon-chevron-spk').classList.toggle('rotate-180');"
                class="w-full p-5 bg-[#F8FAFC] hover:bg-[#F1F5F9] border-b border-[#E2E8F0] text-[#0F172A] flex justify-between items-center text-left transition cursor-pointer min-h-[44px]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] border border-[#0F766E]/20 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-[#0F172A] flex items-center gap-2">
                        Detail Transparansi Perhitungan Sistem (Keperluan Akademik)
                    </h3>
                    <p class="text-xs text-[#526071] mt-0.5">Klik untuk melihat pembuktian langkah perhitungan dan matriks keputusan</p>
                </div>
            </div>
            <svg id="icon-chevron-spk" class="w-5 h-5 text-[#526071] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div id="transparansi-spk-content" class="hidden p-6 space-y-8 bg-white border-t border-[#E2E8F0]">
            
            {{-- 1. Matriks Nilai Alternatif Asli --}}
            <div>
                <h4 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[11px] flex items-center justify-center font-sans">1</span>
                    <span>Matriks Nilai Alternatif Asli (X)</span>
                </h4>
                <p class="text-xs text-[#526071] mb-3">Nilai awal kriteria dari tiap toko. Nilai kriteria <em>Lokasi</em> diambil langsung dari jarak fisik GPS (KM, Cost), dan <em>Ulasan</em> disesuaikan dengan skor Bayesian Average (0–100).</p>

                <div class="overflow-x-auto rounded-[12px] border border-[#E2E8F0] bg-white shadow-soft">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-[#0F172A] border-b border-[#E2E8F0]">
                                <th class="p-3 font-bold">Toko</th>
                                @foreach($preferensiUser as $pref)
                                    <th class="p-3 font-bold text-center">
                                        {{ $pref->kriteria->nama_kriteria }}
                                        <span class="block text-[10px] font-normal text-[#526071]">({{ ucfirst($pref->kriteria->jenis) }})</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @foreach($rankings as $item)
                                @php $toko = $item['toko']; @endphp
                                <tr class="hover:bg-[#F8FAFC]">
                                    <td class="p-3 font-bold text-[#0F172A]">
                                        {{ $toko?->nama }}
                                        <span class="block text-[10px] font-mono text-[#526071]">ID: #{{ $item['toko_id'] }}</span>
                                    </td>
                                    @foreach($preferensiUser as $pref)
                                        @php
                                            $rawVal = $item['raw_values'][$pref->kriteria_id] ?? null;
                                        @endphp
                                        <td class="p-3 font-mono text-center font-bold text-[#334155]">
                                            @if($pref->kriteria->nama_kriteria === 'Lokasi')
                                                {{ $rawVal !== null ? number_format($rawVal, 2, ',', '.') . ' km' : '-' }}
                                            @else
                                                {{ $rawVal !== null ? number_format($rawVal, 1, ',', '.') : '-' }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. Matriks Nilai Utility SMART --}}
            <div>
                <h4 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[11px] flex items-center justify-center font-sans">2</span>
                    <span>Matriks Nilai Utility SMART (u(a_i) skala 0,0000 - 1,0000)</span>
                </h4>
                <p class="text-xs text-[#526071] mb-3">
                    Formula Benefit: u(a_i) = (C_i - C_min) / (C_max - C_min) | Formula Cost: u(a_i) = (C_max - C_i) / (C_max - C_min)
                </p>
                @php
                    $minMax = [];
                    foreach ($preferensiUser as $pref) {
                        $kolom = collect($rankings)->map(fn ($r) => $r['raw_values'][$pref->kriteria_id] ?? null)->filter(fn ($v) => $v !== null);
                        $minMax[$pref->kriteria_id] = ['min' => $kolom->min(), 'max' => $kolom->max()];
                    }
                @endphp

                <div class="overflow-x-auto rounded-[12px] border border-[#E2E8F0] bg-white shadow-soft">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-[#0F172A] border-b border-[#E2E8F0]">
                                <th class="p-3 font-bold">Toko</th>
                                @foreach($preferensiUser as $pref)
                                    <th class="p-3 font-bold text-center">
                                        {{ $pref->kriteria->nama_kriteria }}
                                        <span class="block text-[10px] font-mono text-[#0F766E]">w = {{ number_format($weights[$pref->kriteria_id] ?? 0, 4, ',', '.') }}</span>
                                        <span class="block text-[10px] font-mono font-normal text-[#526071]">min {{ number_format($minMax[$pref->kriteria_id]['min'] ?? 0, 1, ',', '.') }} | max {{ number_format($minMax[$pref->kriteria_id]['max'] ?? 0, 1, ',', '.') }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0]">
                            @foreach($rankings as $item)
                                @php $toko = $item['toko']; @endphp
                                <tr class="hover:bg-[#F8FAFC]">
                                    <td class="p-3 font-bold text-[#0F172A]">
                                        {{ $toko?->nama }}
                                    </td>
                                    @foreach($preferensiUser as $pref)
                                        @php
                                            $uVal = $item['utilities'][$pref->kriteria_id] ?? 0;
                                        @endphp
                                        <td class="p-3 font-mono text-center font-bold {{ $uVal >= 0.7 ? 'text-[#0F766E] bg-[#E6F4F1]/40' : ($uVal >= 0.4 ? 'text-[#92400E] bg-[#FEF3C7]/40' : 'text-[#526071]') }}">
                                            {{ number_format($uVal, 4, ',', '.') }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. Tabel Skor Akhir & Peringkat --}}
            <div>
                <h4 class="text-xs font-extrabold text-[#0F172A] uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[11px] flex items-center justify-center font-sans">3</span>
                    <span>Matriks Perkalian Utility x Bobot ROC & Total Skor Akhir (V_i)</span>
                </h4>

                <div class="overflow-x-auto rounded-[12px] border border-[#E2E8F0] bg-white shadow-soft">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#F8FAFC] text-[#0F172A] border-b border-[#E2E8F0]">
                                <th class="p-3 font-bold text-center">Rank</th>
                                <th class="p-3 font-bold">Nama Toko</th>
                                @foreach($preferensiUser as $pref)
                                    <th class="p-3 font-bold text-center">
                                        u × w
                                        <span class="block text-[10px] font-normal text-[#526071]">{{ $pref->kriteria->nama_kriteria }}</span>
                                    </th>
                                @endforeach
                                <th class="p-3 font-bold text-center">Jarak GPS</th>
                                <th class="p-3 font-bold text-center">Total Skor Akhir (V<sub>i</sub> = Σ u × w)</th>
                                <th class="p-3 font-bold text-center">Status Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E2E8F0] font-mono">
                            @foreach($rankings as $idx => $item)
                                @php
                                    $toko = $item['toko'];
                                    $r = $idx + 1;
                                @endphp
                                <tr class="{{ $r == 1 ? 'bg-[#E6F4F1]/30 font-bold' : 'hover:bg-[#F8FAFC]' }}">
                                    <td class="p-3 text-center font-bold {{ $r == 1 ? 'text-[#0F766E]' : 'text-[#526071]' }}">
                                        #{{ $r }}
                                    </td>
                                    <td class="p-3 font-sans font-bold text-[#0F172A]">
                                        {{ $toko?->nama }}
                                    </td>
                                    @foreach($preferensiUser as $pref)
                                        <td class="p-3 text-center text-[#334155]">
                                            {{ number_format(($item['utilities'][$pref->kriteria_id] ?? 0) * ($weights[$pref->kriteria_id] ?? 0), 4, ',', '.') }}
                                        </td>
                                    @endforeach
                                    <td class="p-3 text-center text-[#526071]">
                                        {{ $item['jarak_km'] !== null ? number_format($item['jarak_km'], 2, ',', '.') . ' km' : '—' }}
                                    </td>
                                    <td class="p-3 text-center font-bold text-[#0F766E] text-sm">
                                        {{ number_format($item['score'], 6, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-center font-sans">
                                        @if($r == 1)
                                            <span class="px-2.5 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A] font-extrabold text-[10px]">Pilihan Utama #1</span>
                                        @elseif($r <= 3)
                                            <span class="px-2.5 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0] font-bold text-[10px]">Sangat Direkomendasikan</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-[#F1F5F9] text-[#526071] border border-[#E2E8F0] font-medium text-[10px]">Alternatif Tambahan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-[#F8FAFC] border-t border-[#E2E8F0] font-mono">
                                <td class="p-3 font-sans font-bold text-[#0F172A]" colspan="2">Σ Bobot ROC</td>
                                @foreach($preferensiUser as $pref)
                                    <td class="p-3 text-center font-bold text-[#0F766E]">{{ number_format($weights[$pref->kriteria_id] ?? 0, 4, ',', '.') }}</td>
                                @endforeach
                                <td class="p-3 text-center font-bold text-[#0F172A]" colspan="3">= {{ number_format(array_sum($weights), 4, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="text-xs text-[#526071] mt-3">Bobot ROC: W<sub>k</sub> = (1/m) × Σ<sub>i=k..m</sub> (1/i). Total bobot selalu 1. Skor SMART bersifat relatif terhadap toko lain yang dibandingkan (min-max), sehingga skor 100% berarti terbaik di antara toko yang ada, bukan sempurna secara mutlak.</p>
            </div>

        </div>
    </div>

    {{-- CTA Bottom --}}
    <div class="mt-10 text-center py-8 border-t border-[#E2E8F0]">
        <p class="text-sm text-[#526071] mb-4">Ingin mencetak laporan resmi atau mengubah prioritas kriteria dan titik lokasi GPS?</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('user.hasil.cetak') }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-sm font-bold rounded-[10px] transition shadow-soft min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Laporan SPK (PDF)</span>
            </a>
            <a href="{{ route('user.preferensi') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-[#CBD5E1] hover:border-[#0F766E] text-[#0F172A] hover:text-[#0F766E] bg-white hover:bg-[#E6F4F1]/30 text-sm font-bold rounded-[10px] transition shadow-soft min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Atur Ulang Preferensi & GPS</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const userPos = { lat: {{ $userLat }}, lng: {{ $userLng }} };

    const storesData = [
        @foreach($rankings as $idx => $item)
            @if($item['toko'] && $item['toko']->latitude !== null && $item['toko']->longitude !== null)
            {
                rank: {{ $idx + 1 }},
                id: {{ $item['toko']->id }},
                nama: "{{ addslashes($item['toko']->nama) }}",
                alamat: "{{ addslashes($item['toko']->alamat) }}",
                score: "{{ number_format($item['score'], 4, ',', '.') }}",
                jarak: "{{ number_format($item['jarak_km'], 2, ',', '.') }}",
                lat: {{ $item['toko']->latitude }},
                lng: {{ $item['toko']->longitude }}
            },
            @endif
        @endforeach
    ];

    if (storesData.length === 0) return;

    const map = L.map('osm-map-hasil').setView([userPos.lat, userPos.lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Marker Posisi User (Teal with white dot)
    const userIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:22px; height:22px; border-radius:50%;
            background:#0F766E; border:3px solid #fff;
            box-shadow:0 2px 6px rgba(15,118,110,0.4);
        "></div>`,
        iconSize: [22, 22],
        iconAnchor: [11, 11],
    });

    L.marker([userPos.lat, userPos.lng], { icon: userIcon })
        .addTo(map)
        .bindPopup(`<div style="font-family:Plus Jakarta Sans, sans-serif; min-width:140px; padding:4px;">
            <strong style="color:#0F766E; font-size:12px;">Posisi Anda</strong><br>
            <span style="font-size:11px; color:#526071;">Lat: ${userPos.lat}<br>Lng: ${userPos.lng}</span>
        </div>`);

    const bounds = [[userPos.lat, userPos.lng]];

    // Marker Bernomor: Toko Rekomendasi
    const rankColors = ['#D97706', '#64748B', '#C2410C'];

    storesData.forEach(store => {
        const bgColor = rankColors[store.rank - 1] ?? '#0F766E';

        const tokoIcon = L.divIcon({
            className: '',
            html: `<div style="
                width:30px; height:30px; border-radius:50%;
                background:${bgColor}; border:3px solid #fff;
                box-shadow:0 2px 8px rgba(15,23,42,0.25);
                display:flex; align-items:center; justify-content:center;
                font-weight:800; font-size:13px; color:#fff;
                font-family:Plus Jakarta Sans, sans-serif;
            ">${store.rank}</div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
        });

        L.marker([store.lat, store.lng], { icon: tokoIcon })
            .addTo(map)
            .bindPopup(`<div style="font-family:Plus Jakarta Sans, sans-serif; min-width:200px; max-width:240px; padding:4px;">
                <div style="margin-bottom:6px;">
                    <span style="background:${bgColor}; color:#fff; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700;">
                        Peringkat #${store.rank}
                    </span>
                    <span style="background:#DCFCE7; color:#166534; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700; margin-left:4px;">
                        ${store.jarak} km
                    </span>
                </div>
                <b style="font-size:13px; color:#0F172A;">${store.nama}</b><br>
                <span style="font-size:11px; color:#526071;">Skor Rekomendasi: <strong style="color:#0F766E;">${store.score}</strong></span><br>
                <span style="font-size:11px; color:#526071;">${store.alamat}</span><br><br>
                <a href="/toko/${store.id}"
                   style="font-size:11px; color:#0F766E; font-weight:700; text-decoration:none;">
                    Lihat Detail Toko &rarr;
                </a>
            </div>`);

        bounds.push([store.lat, store.lng]);
    });

    map.fitBounds(bounds, { padding: [30, 30] });
})();
</script>
@endpush
