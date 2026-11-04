@extends('layouts.app')

@section('title', 'Daftar Toko Thrift Terbaik di Jember - Rekomendasi Pilihan')

@section('content')
@php
    $sortLabels = [
        'rekomendasi'      => 'rekomendasi terbaik',
        'rating_tertinggi' => 'rating tertinggi',
        'nama'             => 'nama A-Z',
        'terbaru'          => 'toko terbaru',
    ];
    $adaFilter = request('search') || request('buka_saja') || request('sort') || request('kriteria_order');
@endphp

<!-- Hero -->
<section class="bg-hero text-white pt-12 pb-20 lg:pt-16 lg:pb-24 px-4 sm:px-6 lg:px-8 border-b border-[#1a3a4e] relative overflow-hidden">
    <div class="absolute -top-32 -right-24 w-[28rem] h-[28rem] rounded-full bg-hero-accent/10 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-40 -left-24 w-[28rem] h-[28rem] rounded-full bg-primary/25 blur-3xl" aria-hidden="true"></div>

    <div class="max-w-4xl mx-auto text-center relative">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-hero-accent/10 text-hero-accent border border-hero-accent/25 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Sistem Pendukung Keputusan Jember</span>
        </div>

        <h1 class="text-[clamp(30px,5vw,52px)] font-extrabold text-white tracking-tight mb-4 leading-tight">
            Temukan Toko Thrift <span class="text-hero-accent">Terbaik</span> di Jember
        </h1>

        <p class="text-[#CBD5E1] text-base max-w-2xl mx-auto mb-8 leading-relaxed">
            Peringkat toko dihitung dari kriteria yang Anda anggap penting: harga, kualitas, lokasi, dan ulasan.
        </p>

        <div class="max-w-2xl mx-auto">
            <form action="{{ route('toko.index') }}" method="GET" role="search">
                @if(request('buka_saja'))
                    <input type="hidden" name="buka_saja" value="1">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('kriteria_order'))
                    <input type="hidden" name="kriteria_order" value="{{ is_array(request('kriteria_order')) ? implode(',', request('kriteria_order')) : request('kriteria_order') }}">
                @endif

                <label for="search" class="sr-only">Cari toko berdasarkan nama atau alamat</label>
                <div class="flex items-center bg-white rounded-[14px] p-1.5 shadow-[0_8px_24px_rgba(15,23,42,0.18)] border border-border">
                    <div class="pl-3.5 pr-2 text-muted">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="search"
                        id="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama toko atau alamat..."
                        autocomplete="off"
                        class="w-full py-3 px-2 bg-transparent text-ink placeholder-muted text-sm sm:text-base focus:outline-none"
                    />
                    <button type="submit" class="px-5 sm:px-7 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-btn transition-colors shrink-0 shadow-sm min-h-[44px]">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Kontainer Utama -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

    @guest
    <div class="-mt-[52px] mb-8 relative z-10">
        <div class="bg-white rounded-card border border-border p-6 sm:p-7 shadow-[0_8px_24px_rgba(15,23,42,0.08)] flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-[12px] bg-primary-tint text-primary flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-ink">Ingin rekomendasi yang disesuaikan untuk Anda?</h2>
                    <p class="text-sm text-muted mt-1 leading-relaxed">
                        Masuk atau daftar untuk menyimpan prioritas kriteria dan memakai lokasi GPS Anda agar hasilnya lebih akurat.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto shrink-0">
                <a href="{{ route('login') }}"
                   class="flex-1 md:flex-none px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn transition-colors shadow-sm min-h-[44px] flex items-center justify-center">
                    Masuk Sekarang
                </a>
                <a href="{{ route('register') }}"
                   class="flex-1 md:flex-none px-5 py-2.5 bg-white hover:bg-primary-tint text-primary font-bold text-sm rounded-btn border border-primary transition-colors min-h-[44px] flex items-center justify-center">
                    Daftar Akun
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="pt-6"></div>
    @endguest

    <!-- Info prioritas kustom aktif -->
    @if(request('kriteria_order'))
        <div class="mb-6 p-4 rounded-[12px] bg-primary-tint border border-primary/30 flex flex-wrap items-center justify-between gap-3 text-sm">
            <div class="flex items-center gap-2 text-primary-dark font-medium">
                <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Prioritas kriteria pilihan Anda aktif. Daftar toko telah disesuaikan otomatis.</span>
            </div>
            <button type="button" onclick="openPrioritasModal()" class="text-sm font-bold text-primary hover:text-primary-dark hover:underline flex items-center gap-1 min-h-[44px]">
                <span>Ubah prioritas</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Catatan cara membaca skor -->
    <p class="mb-6 text-xs text-muted">
        @if($pakaiGps)
            Skor di bawah memperhitungkan jarak dari lokasi GPS yang Anda atur. Rincian rekomendasi ada di halaman <a href="{{ route('user.hasil') }}" class="font-bold text-primary hover:underline">Hasil Rekomendasi</a>.
        @else
            Skor rekomendasi saat ini menggunakan data toko umum. Masuk sebagai pengguna dan tentukan lokasi Anda agar rekomendasi semakin tepat dan personal.
        @endif
    </p>

    <!-- Info hasil pencarian -->
    @if(request('search'))
        <div class="mb-6 p-4 rounded-[12px] bg-white border border-border shadow-soft flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-body">
                Hasil untuk "<span class="font-bold text-ink">{{ request('search') }}</span>":
                <span class="font-extrabold text-primary">{{ $tokos->count() }}</span> toko ditemukan
            </p>
            <a href="{{ route('toko.index', array_filter(request()->except('search'))) }}" class="text-sm font-bold text-[#991B1B] hover:underline flex items-center gap-1 min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Hapus pencarian</span>
            </a>
        </div>
    @endif

    <!-- Bar kontrol -->
    <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4 mb-3">
        <div class="flex flex-wrap items-center gap-3">
            @php
                $bukaQuery = request()->all();
                if (request('buka_saja')) {
                    unset($bukaQuery['buka_saja']);
                } else {
                    $bukaQuery['buka_saja'] = 1;
                }
            @endphp
            <a href="{{ route('toko.index', $bukaQuery) }}"
               aria-pressed="{{ request('buka_saja') ? 'true' : 'false' }}"
               class="px-4 py-2.5 rounded-btn text-sm font-semibold border transition-all flex items-center gap-2 min-h-[44px]
               {{ request('buka_saja')
                   ? 'bg-success-bg text-success-text border-success-dot shadow-soft'
                   : 'bg-white text-body border-border-strong hover:border-primary hover:text-primary' }}">
                <span class="w-2.5 h-2.5 rounded-full bg-success-dot"></span>
                <span>Buka Sekarang</span>
            </a>

            <button type="button" onclick="openPrioritasModal()"
                    class="px-4 py-2.5 rounded-btn text-sm font-semibold bg-primary-tint text-primary-dark border border-primary/30 hover:bg-[#d5eee8] transition-all flex items-center gap-2 min-h-[44px]">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Atur Prioritas Kriteria</span>
                @if(request('kriteria_order'))
                    <span class="w-2 h-2 rounded-full bg-primary" aria-label="aktif"></span>
                @endif
            </button>

            @if($adaFilter)
                <a href="{{ route('toko.index') }}"
                   class="px-3.5 py-2.5 rounded-btn text-sm font-semibold text-[#991B1B] hover:text-[#7f1d1d] bg-[#FEF2F2] border border-[#FECACA] transition flex items-center gap-1.5 min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Semua</span>
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2 w-full lg:w-auto">
            <label for="sortDropdown" class="text-sm font-semibold text-muted shrink-0">Urutkan:</label>
            <div class="relative flex-1 lg:flex-none">
                <select id="sortDropdown" onchange="handleSortChange(this.value)"
                        class="w-full lg:min-w-[250px] px-3.5 py-2.5 pr-9 rounded-btn bg-white border border-border-strong text-ink text-sm font-medium focus:outline-none focus:border-primary cursor-pointer shadow-soft appearance-none min-h-[44px]">
                    <option value="rekomendasi" {{ ($sort ?? 'rekomendasi') === 'rekomendasi' ? 'selected' : '' }}>Rekomendasi Terbaik</option>
                    <option value="rating_tertinggi" {{ ($sort ?? '') === 'rating_tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                    <option value="nama" {{ ($sort ?? '') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="terbaru" {{ ($sort ?? '') === 'terbaru' ? 'selected' : '' }}>Toko Terbaru</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-muted">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <p class="text-sm text-muted mb-6" aria-live="polite">
        Menampilkan <span class="font-bold text-ink">{{ $tokos->count() }}</span> toko, diurutkan berdasarkan {{ $sortLabels[$sort ?? 'rekomendasi'] ?? $sortLabels['rekomendasi'] }}.
    </p>

    <!-- Grid Kartu Toko -->
    @if($tokos->isEmpty())
        <div class="bg-white rounded-card p-10 sm:p-12 text-center border border-border shadow-soft max-w-md mx-auto my-12">
            <div class="w-16 h-16 bg-page rounded-full flex items-center justify-center mx-auto text-muted mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-ink text-lg mb-1">Toko Tidak Ditemukan</h3>
            <p class="text-muted text-sm mb-6 leading-relaxed">
                @if(request('search'))
                    Tidak ada toko yang cocok dengan kata kunci "{{ request('search') }}". Coba kata kunci lain atau hapus filter.
                @elseif(request('buka_saja'))
                    Saat ini tidak ada toko yang sedang buka. Coba lagi nanti atau tampilkan semua toko.
                @else
                    Belum ada toko yang datanya lengkap untuk direkomendasikan.
                @endif
            </p>
            <a href="{{ route('toko.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-btn hover:bg-primary-dark transition-colors shadow-soft min-h-[44px]">
                <span>Tampilkan Semua Toko</span>
            </a>
        </div>
    @else
        <div class="store-grid">
            @foreach($tokos as $toko)
                @php
                    $isTopRank = ($toko->roc_rank === 1);
                    $adaKoordinat = $toko->latitude && $toko->longitude;
                    $mapsUrl = $adaKoordinat
                        ? "https://www.google.com/maps/search/?api=1&query={$toko->latitude},{$toko->longitude}"
                        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($toko->alamat);

                    // Placeholder inisial untuk toko tanpa foto (warna konsisten per toko)
                    $inisial = \Illuminate\Support\Str::upper(
                        collect(preg_split('/\s+/', trim($toko->nama)))->filter()->take(2)->map(fn ($kata) => mb_substr($kata, 0, 1))->implode('')
                    );
                    $palet = [
                        'from-[#E6F4F1] to-[#B7DDD6] text-primary-dark',
                        'from-[#E0F2FE] to-[#BAE6FD] text-[#075985]',
                        'from-[#FEF3C7] to-[#FDE68A] text-[#92400E]',
                        'from-[#EDE9FE] to-[#DDD6FE] text-[#5B21B6]',
                    ][$toko->id % 4];

                    $rankBadge = match($toko->roc_rank) {
                        1 => ['class' => 'bg-gold-bg text-gold-text border-[#FDE68A]', 'label' => '#1 Rekomendasi'],
                        2 => ['class' => 'bg-silver-bg text-silver-text border-border-strong', 'label' => '#2 Rekomendasi'],
                        3 => ['class' => 'bg-bronze-bg text-bronze-text border-[#FED7AA]', 'label' => '#3 Rekomendasi'],
                        default => ['class' => 'bg-white text-muted border-border', 'label' => '#' . $toko->roc_rank],
                    };
                @endphp
                <article class="group bg-white rounded-card flex flex-col overflow-hidden transition-all duration-200 hover:-translate-y-0.5
                     {{ $isTopRank
                         ? 'border-2 border-primary shadow-teal'
                         : 'border border-border shadow-soft hover:shadow-card' }}">

                    {{-- Foto toko + lencana --}}
                    <a href="{{ route('toko.show', $toko->id) }}" class="relative block overflow-hidden bg-primary-tint {{ $toko->gambar ? 'aspect-[16/10]' : 'h-28' }}" tabindex="-1" aria-hidden="true">
                        @if($toko->gambar)
                            <img src="{{ asset('storage/' . $toko->gambar) }}" alt="" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $palet }} flex items-center justify-center">
                                <span class="text-5xl font-extrabold tracking-tight opacity-60 select-none">{{ $inisial }}</span>
                            </div>
                        @endif

                        <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border shadow-soft {{ $rankBadge['class'] }}">
                            @if($isTopRank)
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endif
                            <span>{{ $rankBadge['label'] }}</span>
                        </span>

                        @if($toko->is_buka !== null)
                            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold shadow-soft
                                {{ $toko->is_buka ? 'bg-success-bg text-success-text' : 'bg-[#FEF2F2] text-[#991B1B]' }}">
                                <span class="w-2 h-2 rounded-full {{ $toko->is_buka ? 'bg-success-dot' : 'bg-[#DC2626]' }}"></span>
                                <span>{{ $toko->is_buka ? 'Buka' : 'Tutup' }}</span>
                            </span>
                        @endif
                    </a>

                    <div class="p-5 flex flex-col flex-1 gap-4">
                        {{-- Nama, rating, alamat --}}
                        <div>
                            <h3 class="text-lg font-bold text-ink leading-snug">
                                <a href="{{ route('toko.show', $toko->id) }}" class="hover:text-primary transition-colors">{{ $toko->nama }}</a>
                            </h3>

                            <div class="flex items-center gap-1.5 mt-1 text-sm">
                                @if($toko->rating_ulasan !== null)
                                    <svg class="w-4 h-4 text-star" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-ink">{{ number_format($toko->rating_ulasan, 1, ',', '.') }}</span>
                                    <span class="text-muted">({{ number_format($toko->jumlah_ulasan ?? 0, 0, ',', '.') }} ulasan)</span>
                                @else
                                    <span class="text-muted">Belum ada ulasan</span>
                                @endif
                            </div>

                            <p class="text-sm text-muted flex items-start gap-1.5 mt-2">
                                <svg class="w-4 h-4 text-primary mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="line-clamp-2">{{ $toko->alamat }}</span>
                            </p>
                        </div>

                        {{-- Skor Rekomendasi --}}
                        <div class="bg-[#F8FAFC] rounded-[12px] p-3.5 border border-border">
                            <div class="flex items-baseline justify-between mb-2">
                                <span class="text-sm font-semibold text-muted">{{ $pakaiGps ? 'Skor Rekomendasi (sesuai GPS Anda)' : 'Skor Rekomendasi' }}</span>
                                <span class="text-[28px] font-extrabold text-primary leading-none">{{ number_format($toko->roc_percent, 1, ',', '.') }}%</span>
                            </div>
                            <div class="w-full bg-border rounded-full h-2 overflow-hidden" role="progressbar" aria-valuenow="{{ round($toko->roc_percent) }}" aria-valuemin="0" aria-valuemax="100" aria-label="Skor Rekomendasi {{ $toko->nama }}">
                                <div class="bg-primary h-2 rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $toko->roc_percent)) }}%"></div>
                            </div>
                        </div>

                        {{-- Deskripsi (hanya jika ada) --}}
                        @if($toko->deskripsi)
                            <p class="text-sm text-body line-clamp-2 leading-relaxed">{{ $toko->deskripsi }}</p>
                        @endif

                        {{-- Nilai kriteria --}}
                        @if($toko->nilaiAlternatif && $toko->nilaiAlternatif->count() > 0)
                            <div class="pt-3 border-t border-border space-y-2.5">
                                <span class="text-xs font-bold text-muted tracking-wider uppercase block">Nilai Kriteria</span>
                                <div class="space-y-2">
                                    @foreach($toko->nilaiAlternatif->take(3) as $nilai)
                                        <div>
                                            <div class="flex justify-between items-center text-sm mb-1">
                                                <span class="font-medium text-body truncate">{{ $nilai->kriteria->nama_kriteria }}</span>
                                                <span class="font-bold text-ink">{{ number_format($nilai->nilai, 1, ',', '.') }}</span>
                                            </div>
                                            <div class="w-full bg-border rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-primary h-1.5 rounded-full" style="width: {{ min(100, max(0, $nilai->nilai)) }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($toko->jam_operasional)
                            <div class="flex items-center gap-1.5 text-sm text-muted font-medium mt-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $toko->jam_operasional }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    <div class="px-5 pb-5 grid grid-cols-2 gap-3">
                        <a href="{{ route('toko.show', $toko->id) }}"
                           class="py-2.5 px-3 bg-primary hover:bg-primary-dark text-white text-sm font-bold rounded-btn transition-colors text-center flex items-center justify-center shadow-sm min-h-[44px]">
                            Lihat Detail
                        </a>
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                           class="py-2.5 px-3 bg-white hover:bg-primary-tint text-primary border border-primary text-sm font-bold rounded-btn transition-colors text-center flex items-center justify-center gap-1.5 min-h-[44px]">
                            <span>Lihat di Peta</span>
                            <span class="sr-only">(membuka tab baru)</span>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    <!-- Cara kerja -->
    <section class="mt-16" aria-labelledby="judul-cara-kerja">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h2 id="judul-cara-kerja" class="text-2xl font-extrabold text-ink tracking-tight">Bagaimana peringkat ditentukan?</h2>
            <p class="text-muted mt-2 leading-relaxed">Tiga langkah sederhana agar hasilnya objektif dan bisa Anda telusuri sendiri.</p>
        </div>
        <ol class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['Tentukan Prioritas', 'Pilih dan urutkan hal yang paling Anda utamakan, seperti harga terjangkau atau kualitas barang.', 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                ['Penilaian Cerdas', 'Sistem mengevaluasi toko berdasarkan koleksi pakaian, ulasan pembeli, hingga jarak dari lokasi Anda.', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['Rekomendasi Pilihan', 'Daftar toko terbaik ditampilkan berurutan dari yang paling cocok dengan kebutuhan belanja Anda.', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ] as $i => [$judul, $isi, $ikon])
                <li class="bg-white rounded-card border border-border shadow-soft p-6 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-[12px] bg-primary-tint text-primary flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikon }}" />
                            </svg>
                        </span>
                        <span class="text-4xl font-extrabold text-border-strong" aria-hidden="true">{{ $i + 1 }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-ink text-lg mb-1">{{ $judul }}</h3>
                        <p class="text-sm text-muted leading-relaxed">{{ $isi }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
</div>

{{-- Modal Atur Prioritas Kriteria ROC-SMART --}}
<div id="modalPrioritas"
     class="hidden fixed inset-0 z-[65] flex items-center justify-center p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="prioritasTitle">

    <!-- Overlay -->
    <div class="fixed inset-0 bg-ink/50 backdrop-blur-sm transition-opacity" onclick="closePrioritasModal()"></div>

    <!-- Panel -->
    <div class="relative z-10 w-full max-w-lg bg-white border border-border rounded-card shadow-[0_8px_24px_rgba(15,23,42,0.12)] overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-6 py-4 bg-[#F8FAFC] border-b border-border">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-btn bg-primary-tint text-primary flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
                <div>
                    <h3 id="prioritasTitle" class="text-base font-extrabold text-ink">Atur Prioritas Kriteria</h3>
                    <p class="text-sm text-muted">Urutkan dari yang paling Anda utamakan</p>
                </div>
            </div>
            <button type="button" onclick="closePrioritasModal()"
                    class="w-11 h-11 rounded-btn bg-white border border-border text-muted hover:text-ink hover:bg-page flex items-center justify-center transition"
                    aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-6 py-3 bg-primary-tint border-b border-border text-sm text-primary-dark flex items-start gap-2.5">
            <svg class="w-4 h-4 text-primary mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Gunakan tombol panah untuk mengubah urutan. Kriteria di posisi #1 mendapat bobot paling besar.</span>
        </div>

        <form id="prioritasForm" action="{{ route('toko.index') }}" method="GET" class="flex flex-col flex-1 overflow-hidden">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('buka_saja'))
                <input type="hidden" name="buka_saja" value="1">
            @endif
            <input type="hidden" name="sort" value="{{ request('sort', 'rekomendasi') }}">

            <div class="p-6 space-y-2.5 overflow-y-auto flex-1 bg-white" id="kriteriaListContainer">
                @foreach($orderedKriterias as $index => $kriteria)
                    <div class="kriteria-item flex items-center justify-between p-3.5 rounded-[12px] bg-[#F8FAFC] border border-border hover:border-primary/50 transition-all gap-3"
                         data-id="{{ $kriteria->id }}">

                        <input type="hidden" name="kriteria_order[]" value="{{ $kriteria->id }}" class="kriteria-input-id">

                        <div class="flex items-center gap-3 min-w-0">
                            <span class="rank-badge w-9 h-9 rounded-[8px] bg-primary-tint text-primary font-bold text-sm flex items-center justify-center shrink-0">
                                #{{ $index + 1 }}
                            </span>

                            <div class="min-w-0">
                                <span class="text-sm font-bold text-ink block truncate">{{ $kriteria->nama_kriteria }}</span>
                                <span class="text-xs font-medium text-muted">
                                    {{ $kriteria->jenis === 'cost' ? 'Cost: semakin kecil semakin baik' : 'Benefit: semakin besar semakin baik' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" onclick="moveKriteria(this, -1)"
                                    class="btn-move-up w-10 h-10 rounded-[8px] bg-white hover:bg-primary-tint text-body hover:text-primary flex items-center justify-center transition border border-border-strong"
                                    aria-label="Pindahkan {{ $kriteria->nama_kriteria }} ke atas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <button type="button" onclick="moveKriteria(this, 1)"
                                    class="btn-move-down w-10 h-10 rounded-[8px] bg-white hover:bg-primary-tint text-body hover:text-primary flex items-center justify-center transition border border-border-strong"
                                    aria-label="Pindahkan {{ $kriteria->nama_kriteria }} ke bawah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 bg-[#F8FAFC] border-t border-border flex flex-wrap items-center justify-between gap-3">
                <button type="button" onclick="resetKriteriaOrder()"
                        class="px-3.5 py-2 text-sm font-bold text-muted hover:text-ink transition flex items-center gap-1.5 min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset Default</span>
                </button>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="closePrioritasModal()"
                            class="px-4 py-2.5 rounded-btn text-sm font-semibold text-body hover:bg-white transition border border-border-strong min-h-[44px]">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn transition-colors shadow-sm min-h-[44px]">
                        Terapkan &amp; Hitung Ulang
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

    let lastFocusedBeforeModal = null;

    function openPrioritasModal() {
        const modal = document.getElementById('modalPrioritas');
        lastFocusedBeforeModal = document.activeElement;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        const firstBtn = modal.querySelector('.btn-move-down');
        if (firstBtn) firstBtn.focus();
    }

    function closePrioritasModal() {
        const modal = document.getElementById('modalPrioritas');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (lastFocusedBeforeModal) lastFocusedBeforeModal.focus();
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
        btn.focus();
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
