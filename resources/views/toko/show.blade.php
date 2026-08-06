@extends('layouts.app')

@section('title', 'Detail Toko - ' . $toko->nama)

@section('content')
<!-- Breadcrumbs -->
<div class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex text-xs font-medium text-slate-500 gap-2 items-center">
            <a href="{{ route('toko.index') }}" class="hover:text-emerald-600">Daftar Toko</a>
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
            <span class="text-slate-800 font-semibold truncate">{{ $toko->nama }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Banner Toko -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl mb-8 border border-slate-700/50 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl text-white pointer-events-none">
            <i class="fa-solid fa-store"></i>
        </div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-900 flex items-center justify-center font-extrabold text-3xl shadow-lg shrink-0">
                    <i class="fa-solid fa-shirt"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Toko Thrift Secondhand
                        </span>
                        @if($toko->pemilik)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                <i class="fa-solid fa-user-check text-[10px]"></i> Terverifikasi Pemilik
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                        {{ $toko->nama }}
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-emerald-400"></i>
                        {{ $toko->alamat }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto border-t md:border-t-0 border-slate-700/60 pt-4 md:pt-0">
                @if($toko->media_sosial)
                    <a href="https://instagram.com/{{ ltrim($toko->media_sosial, '@') }}" target="_blank" class="flex-1 md:flex-none px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-semibold text-xs transition border border-white/20 flex items-center justify-center gap-2">
                        <i class="fa-brands fa-instagram text-rose-400 text-sm"></i>
                        <span>{{ $toko->media_sosial }}</span>
                    </a>
                @endif
                <a href="https://www.google.com/maps/search/?api=1&query={{ $toko->latitude }},{{ $toko->longitude }}" target="_blank" class="flex-1 md:flex-none px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-sm"></i>
                    <span>Buka Rute Google Maps</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Konten Utama: 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Info Detail & Nilai Kriteria (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Card Deskripsi & Operasional -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-emerald-600"></i>
                    Informasi Toko
                </h2>

                <p class="text-slate-700 text-sm leading-relaxed mb-6">
                    {{ $toko->deskripsi ?? 'Toko fashion secondhand ini menyediakan beragam pilihan produk thrift berkualitas di wilayah Jember.' }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base shrink-0">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 block uppercase">Jam Operasional</span>
                            <span class="text-xs font-bold text-slate-800">{{ $toko->jam_operasional ?? '10:00 - 21:00' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-base shrink-0">
                            <i class="fa-solid fa-map-pin"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-slate-400 block uppercase">Koordinat Lokasi</span>
                            <span class="text-xs font-bold text-slate-800 font-mono">
                                {{ $toko->latitude ?? '-8.1695' }}, {{ $toko->longitude ?? '113.7152' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Nilai Kriteria Toko -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-emerald-600"></i>
                            Nilai Kriteria SPK Toko
                        </h2>
                        <p class="text-xs text-slate-500">Skala Penilaian Kriteria (1 - 100)</p>
                    </div>
                </div>

                @if($toko->nilaiAlternatif->isEmpty())
                    <p class="text-xs text-slate-400 italic">Belum ada nilai kriteria yang diinputkan untuk toko ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($toko->nilaiAlternatif as $nilai)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-xs text-slate-800">{{ $nilai->kriteria->nama_kriteria }}</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-mono">
                                        {{ $nilai->jenis === 'cost' ? 'Cost' : 'Benefit' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ min(100, max(0, $nilai->nilai)) }}%"></div>
                                    </div>
                                    <span class="font-extrabold text-sm text-emerald-700 font-mono shrink-0">{{ $nilai->nilai }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Kolom Kanan: Peta Lokasi (OpenStreetMap) -->
        <div class="space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-emerald-500"></i>
                        Lokasi Toko
                    </h3>
                    <span class="text-[10px] px-2 py-0.5 bg-emerald-100 text-emerald-800 font-semibold rounded border border-emerald-200 flex items-center gap-1">
                        <i class="fa-solid fa-map text-emerald-600"></i> OpenStreetMap
                    </span>
                </div>

                <!-- Leaflet Map Container -->
                <div id="osm-map" class="w-full h-80 rounded-xl border border-slate-300 overflow-hidden shadow-inner"></div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-mono text-[11px]">{{ $toko->latitude }}, {{ $toko->longitude }}</span>
                    <a href="https://www.openstreetmap.org/?mlat={{ $toko->latitude }}&mlon={{ $toko->longitude }}&zoom=17" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1">
                        <span>Buka di OSM</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const lat = {{ $toko->latitude ?? -8.1695 }};
        const lng = {{ $toko->longitude ?? 113.7152 }};
        const storeName = "{{ addslashes($toko->nama) }}";
        const storeAddress = "{{ addslashes($toko->alamat) }}";

        const map = L.map('osm-map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(
            `<div style="min-width:160px; font-family:sans-serif;">
                <b style="font-size:13px; color:#0f172a;">${storeName}</b><br>
                <span style="font-size:11px; color:#475569;">${storeAddress}</span><br><br>
                <a href="https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}&zoom=17"
                   target="_blank"
                   style="font-size:11px; color:#16a34a; font-weight:bold; text-decoration:none;">
                    Buka di OpenStreetMap &rarr;
                </a>
            </div>`
        ).openPopup();
    })();
</script>
@endpush
