@extends('layouts.app')

@section('title', 'Detail Toko - ' . $toko->nama)

@section('content')
{{-- Flash Message --}}
@if(session('success'))
    <div class="bg-emerald-50 border-b border-emerald-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center gap-2 text-emerald-800 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

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
                @if($toko->gambar)
                    <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover shadow-lg shrink-0 border-2 border-emerald-400/40" />
                @else
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-900 flex items-center justify-center font-extrabold text-3xl shadow-lg shrink-0">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                @endif
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
                @if($toko->gambar)
                    <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200 max-h-80 shadow-xs">
                        <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-full h-full object-cover max-h-80 hover:scale-102 transition duration-500" />
                    </div>
                @endif

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
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs" id="spk-section">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-emerald-600"></i>
                            Nilai Kriteria SPK Toko
                        </h2>
                        <p class="text-xs text-slate-500">Skala Penilaian Kriteria (1 - 100)</p>
                    </div>
                    {{-- Tombol Isi Nilai: HANYA muncul untuk admin yang sudah login --}}
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <button
                                type="button"
                                id="btn-open-nilai-modal"
                                onclick="document.getElementById('modal-nilai-kriteria').classList.remove('hidden');
                                         document.body.classList.add('overflow-hidden');"
                                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm"
                            >
                                <i class="fa-solid fa-{{ $toko->nilaiAlternatif->isEmpty() ? 'plus' : 'pen-to-square' }}"></i>
                                {{ $toko->nilaiAlternatif->isEmpty() ? 'Isi Nilai Kriteria' : 'Edit Nilai Kriteria' }}
                            </button>
                        @endif
                    @endauth
                </div>

                @if($toko->nilaiAlternatif->isEmpty())
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-amber-50 border border-amber-200">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                                <p class="text-xs text-amber-800">
                                    Toko ini belum memiliki nilai kriteria. Klik <strong>"Isi Nilai Kriteria"</strong> untuk mengisinya.
                                </p>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Belum dinilai admin &mdash; nilai kriteria SPK akan segera diisi oleh admin sistem.</p>
                        @endif
                    @else
                        <p class="text-xs text-slate-400 italic">Belum dinilai admin &mdash; nilai kriteria SPK akan segera diisi oleh admin sistem.</p>
                    @endauth
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($toko->nilaiAlternatif as $nilai)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-xs text-slate-800">{{ $nilai->kriteria->nama_kriteria }}</span>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $nilai->kriteria->jenis === 'cost' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-800' }} font-mono">
                                        {{ $nilai->kriteria->jenis === 'cost' ? 'Cost' : 'Benefit' }}
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

{{-- ============================================================
     MODAL: Form Isi/Edit Nilai Kriteria (hanya render untuk admin)
     ============================================================ --}}
@auth
@if(Auth::user()->role === 'admin')
<div id="modal-nilai-kriteria"
     class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="modal-title">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"
         onclick="closeNilaiModal()"></div>

    {{-- Modal Panel --}}
    <div class="relative z-10 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-slate-900 to-emerald-950">
            <div>
                <h3 id="modal-title" class="text-base font-extrabold text-white">Isi Nilai Kriteria SPK</h3>
                <p class="text-xs text-slate-400 mt-0.5">Toko: <span class="font-semibold text-emerald-400">{{ $toko->nama }}</span></p>
            </div>
            <button type="button" onclick="closeNilaiModal()"
                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Validation Errors (ditampilkan ulang jika ada error dari submit) --}}
        @if($errors->any())
            <div class="mx-6 mt-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan validasi:</p>
                @foreach($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('toko.nilai-kriteria.update', $toko->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-3 max-h-[60vh] overflow-y-auto">
                @forelse($kriterias as $k)
                    @php $val = old("nilai.{$k->id}", $nilaiMap[$k->id] ?? ''); @endphp
                    <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="min-w-0">
                            <span class="font-bold text-sm text-slate-900 block">{{ $k->nama_kriteria }}</span>
                            <span class="text-[11px] font-semibold {{ $k->jenis === 'benefit' ? 'text-emerald-600' : 'text-rose-600' }}">
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
                                class="w-20 px-3 py-2 rounded-xl border border-slate-300 font-mono text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            />
                            <span class="text-xs text-slate-400 font-mono">/100</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">Belum ada kriteria yang terdaftar di sistem.</p>
                @endforelse
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 flex justify-end gap-3 bg-slate-50">
                <button type="button" onclick="closeNilaiModal()"
                        class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Nilai Kriteria
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
    // Buka modal otomatis kalau ada error validasi (agar error tidak hilang)
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('modal-nilai-kriteria').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
    @endif
    // Tutup modal dengan tombol Escape
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
