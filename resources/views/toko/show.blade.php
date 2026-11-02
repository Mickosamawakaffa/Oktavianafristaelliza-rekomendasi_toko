@extends('layouts.app')

@section('title', 'Detail Toko - ' . $toko->nama)

@section('content')
<!-- Breadcrumbs -->
<div class="bg-white border-b border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <nav class="flex text-xs font-medium text-[#526071] gap-2 items-center">
            <a href="{{ route('toko.index') }}" class="hover:text-[#0F766E] transition-colors">Daftar Toko</a>
            <svg class="w-3.5 h-3.5 text-[#CBD5E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-[#0F172A] font-semibold truncate">{{ $toko->nama }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Banner Toko (Latar Navy Gelap #0E2A3B dengan Aksen Teal) -->
    <div class="bg-[#0E2A3B] rounded-[16px] p-6 sm:p-8 text-white shadow-soft mb-8 border border-[#1a384c] relative overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div class="flex items-start gap-4">
                @if($toko->gambar)
                    <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-[12px] object-cover shadow-sm shrink-0 border border-white/20" />
                @else
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-[12px] bg-[#0F766E] text-white flex items-center justify-center font-extrabold text-2xl shadow-sm shrink-0">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                @endif
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-3 py-0.5 rounded-full text-xs font-semibold bg-[#E6F4F1] text-[#0B5C56]">
                            Toko Thrift Secondhand
                        </span>
                        @if($toko->pemilik && $toko->isApproved())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/15 text-[#5EEAD4] border border-white/20">
                                Terverifikasi Admin
                            </span>
                        @endif
                        @if($toko->is_buka !== null)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $toko->is_buka ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEF2F2] text-[#991B1B]' }} flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $toko->is_buka ? 'bg-[#16A34A]' : 'bg-[#DC2626]' }}"></span>
                                <span>{{ $toko->is_buka ? 'Buka Sekarang' : 'Tutup' }}</span>
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                        {{ $toko->nama }}
                    </h1>
                    <p class="text-[#CBD5E1] text-xs sm:text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#5EEAD4] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $toko->alamat }}</span>
                    </p>
                </div>
            </div>

            @php
                $userLat = session('user_lat');
                $userLng = session('user_lng');
                $mapsUrl = ($userLat && $userLng)
                    ? "https://www.google.com/maps/dir/?api=1&origin={$userLat},{$userLng}&destination={$toko->latitude},{$toko->longitude}"
                    : "https://www.google.com/maps/search/?api=1&query={$toko->latitude},{$toko->longitude}";
            @endphp

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto border-t md:border-t-0 border-[#1a384c] pt-4 md:pt-0">
                @if($toko->media_sosial)
                    <a href="https://instagram.com/{{ ltrim($toko->media_sosial, '@') }}" target="_blank" 
                       class="flex-1 md:flex-none px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-[10px] font-semibold text-xs transition border border-white/20 flex items-center justify-center gap-2 min-h-[44px]">
                        <span>{{ $toko->media_sosial }}</span>
                    </a>
                @endif
                <a href="{{ $mapsUrl }}" target="_blank" 
                   class="flex-1 md:flex-none px-4 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-xs rounded-[10px] transition-colors shadow-sm flex items-center justify-center gap-2 min-h-[44px]">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>{{ ($userLat && $userLng) ? 'Rute Dari Lokasi Anda' : 'Buka Rute Google Maps' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Konten Utama: 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Info Detail & Nilai Kriteria (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Card Deskripsi & Operasional -->
            <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft">
                @if($toko->gambar)
                    <div class="mb-6 rounded-[12px] overflow-hidden border border-[#E2E8F0] max-h-80 shadow-soft">
                        <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-full h-full object-cover max-h-80" />
                    </div>
                @endif

                <h2 class="text-lg font-bold text-[#0F172A] mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Informasi Toko</span>
                </h2>

                <p class="text-[#334155] text-sm leading-relaxed mb-6">
                    {{ $toko->deskripsi ?? 'Toko fashion secondhand ini menyediakan beragam pilihan produk thrift berkualitas di wilayah Jember.' }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-[#E2E8F0] pt-4">
                    <div class="flex items-center gap-3 p-3.5 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0]">
                        <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center font-bold text-base shrink-0">
                            <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-[#526071] block uppercase tracking-wider">Jam Operasional</span>
                            <span class="text-xs font-bold text-[#0F172A]">{{ $toko->jam_operasional ?? '10:00 - 21:00' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3.5 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0]">
                        <div class="w-10 h-10 rounded-[10px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center font-bold text-base shrink-0">
                            <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold text-[#526071] block uppercase tracking-wider">Koordinat Lokasi</span>
                            <span class="text-xs font-bold text-[#0F172A] font-mono">
                                {{ $toko->latitude ?? '-8.1695' }}, {{ $toko->longitude ?? '113.7152' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Nilai Kriteria Toko -->
            <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft" id="spk-section">
                <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-[#0F172A] flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>Nilai Kriteria SPK Toko</span>
                        </h2>
                        <p class="text-xs text-[#526071]">Skala Penilaian Kriteria (1 - 100)</p>
                    </div>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <button
                                type="button"
                                id="btn-open-nilai-modal"
                                onclick="document.getElementById('modal-nilai-kriteria').classList.remove('hidden');
                                         document.body.classList.add('overflow-hidden');"
                                class="flex items-center gap-2 px-4 py-2 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] transition-colors shadow-sm min-h-[44px]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>{{ $toko->nilaiAlternatif->isEmpty() ? 'Isi Nilai Kriteria' : 'Edit Nilai Kriteria' }}</span>
                            </button>
                        @endif
                    @endauth
                </div>

                @if($toko->nilaiAlternatif->isEmpty())
                    <p class="text-xs text-[#526071] italic">Nilai kriteria SPK belum diisi untuk toko ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($toko->nilaiAlternatif as $nilai)
                            <div class="p-4 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0] flex flex-col justify-between">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-xs text-[#0F172A]">{{ $nilai->kriteria->nama_kriteria }}</span>
                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $nilai->kriteria->jenis === 'cost' ? 'bg-[#FEF2F2] text-[#991B1B]' : 'bg-[#E6F4F1] text-[#0B5C56]' }}">
                                        {{ ucfirst($nilai->kriteria->jenis) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-full bg-[#E2E8F0] rounded-full h-2 overflow-hidden">
                                        <div class="bg-[#0F766E] h-2 rounded-full" style="width: {{ min(100, max(0, $nilai->nilai)) }}%"></div>
                                    </div>
                                    <span class="font-extrabold text-sm text-[#0F172A] shrink-0">{{ number_format($nilai->nilai, 1, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Kolom Kanan: Peta Lokasi (OpenStreetMap) -->
        <div class="space-y-6">
            <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-[#0F172A] text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <span>Lokasi Toko</span>
                    </h3>
                    <span class="text-[10px] px-2 py-0.5 bg-[#E6F4F1] text-[#0B5C56] font-semibold rounded-full border border-[#0F766E]/20">
                        OpenStreetMap
                    </span>
                </div>

                <!-- Leaflet Map Container -->
                <div id="osm-map" class="w-full h-80 rounded-[12px] border border-[#CBD5E1] overflow-hidden"></div>

                <div class="mt-4 pt-3 border-t border-[#E2E8F0] flex justify-between items-center text-xs">
                    <span class="text-[#526071] font-mono text-[11px]">{{ $toko->latitude }}, {{ $toko->longitude }}</span>
                    <a href="https://www.openstreetmap.org/?mlat={{ $toko->latitude }}&mlon={{ $toko->longitude }}&zoom=17" target="_blank" 
                       class="text-[#0F766E] hover:text-[#0B5C56] font-semibold flex items-center gap-1">
                        <span>Buka di OSM</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: Form Isi/Edit Nilai Kriteria (Admin Only) --}}
@auth
@if(Auth::user()->role === 'admin')
<div id="modal-nilai-kriteria"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-title">

    {{-- Overlay --}}
    <div class="fixed inset-0 bg-[#0F172A]/50 backdrop-blur-xs" onclick="closeNilaiModal()"></div>

    {{-- Modal Panel --}}
    <div class="relative z-10 w-full max-w-lg bg-white border border-[#E2E8F0] rounded-[16px] shadow-[0_8px_24px_rgba(15,23,42,0.12)] overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-[#F8FAFC] border-b border-[#E2E8F0]">
            <div>
                <h3 id="modal-title" class="text-base font-extrabold text-[#0F172A]">Isi Nilai Kriteria SPK</h3>
                <p class="text-xs text-[#526071] mt-0.5">Toko: <span class="font-bold text-[#0F766E]">{{ $toko->nama }}</span></p>
            </div>
            <button type="button" onclick="closeNilaiModal()"
                    class="w-8 h-8 rounded-[8px] bg-white border border-[#E2E8F0] text-[#526071] hover:text-[#0F172A] flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mx-6 mt-4 p-3.5 rounded-[10px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs space-y-1">
                <p class="font-bold">Terdapat kesalahan validasi:</p>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('toko.nilai-kriteria.update', $toko->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-3 max-h-[60vh] overflow-y-auto">
                @forelse($kriterias as $k)
                    @php $val = old("nilai.{$k->id}", $nilaiMap[$k->id] ?? ''); @endphp
                    <div class="flex items-center justify-between gap-4 p-3 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0]">
                        <div class="min-w-0">
                            <span class="font-bold text-sm text-[#0F172A] block">{{ $k->nama_kriteria }}</span>
                            <span class="text-[11px] font-semibold {{ $k->jenis === 'benefit' ? 'text-[#0F766E]' : 'text-[#991B1B]' }}">
                                {{ ucfirst($k->jenis) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <input
                                type="number"
                                name="nilai[{{ $k->id }}]"
                                id="nilai_{{ $k->id }}"
                                value="{{ $val }}"
                                min="0" max="100" step="1"
                                required
                                placeholder="0–100"
                                class="w-20 px-3 py-2 rounded-[8px] border border-[#CBD5E1] bg-white text-[#0F172A] font-bold text-center focus:outline-none focus:border-[#0F766E]"
                            />
                            <span class="text-xs text-[#526071]">/100</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#526071] italic text-center py-4">Belum ada kriteria yang terdaftar di sistem.</p>
                @endforelse
            </div>

            {{-- Footer Modal --}}
            <div class="px-6 py-4 border-t border-[#E2E8F0] flex justify-end gap-3 bg-[#F8FAFC]">
                <button type="button" onclick="closeNilaiModal()"
                        class="px-4 py-2 border border-[#CBD5E1] rounded-[10px] text-xs font-bold text-[#334155] hover:bg-white transition min-h-[44px]">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] shadow-sm transition min-h-[44px] flex items-center gap-2">
                    <span>Simpan Nilai Kriteria</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function closeNilaiModal() {
        document.getElementById('modal-nilai-kriteria').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('modal-nilai-kriteria').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    @endif
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNilaiModal();
    });
</script>
@endif
@endauth

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
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(
            `<div style="min-width:160px; font-family:'Plus Jakarta Sans',sans-serif;">
                <b style="font-size:13px; color:#0f172a;">${storeName}</b><br>
                <span style="font-size:11px; color:#526071;">${storeAddress}</span><br><br>
                <a href="https://www.openstreetmap.org/?mlat=${lat}&mlon=${lng}&zoom=17"
                   target="_blank"
                   style="font-size:11px; color:#0f766e; font-weight:bold; text-decoration:none;">
                    Buka di OpenStreetMap &rarr;
                </a>
            </div>`
        ).openPopup();
    })();
</script>
@endpush
