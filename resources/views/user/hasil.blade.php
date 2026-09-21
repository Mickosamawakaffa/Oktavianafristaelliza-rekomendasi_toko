@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Toko Terbaik')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-400 text-slate-900 flex items-center justify-center shadow-lg shrink-0">
                <i class="fa-solid fa-ranking-star text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">Hasil Rekomendasi Toko</h1>
                <p class="text-sm text-slate-500 mt-0.5">Dihitung otomatis menggunakan metode <strong class="text-emerald-700">ROC + SMART + Haversine Distance (GPS)</strong></p>
            </div>
        </div>
        <a href="{{ route('user.preferensi') }}" class="shrink-0 flex items-center gap-2 px-4 py-2.5 border border-slate-300 hover:border-emerald-400 text-slate-700 hover:text-emerald-700 text-xs font-semibold rounded-xl transition-all bg-white hover:bg-emerald-50">
            <i class="fa-solid fa-sliders text-xs"></i>
            Ubah Preferensi & GPS
        </a>
    </div>

    {{-- User GPS Banner --}}
    <div class="mb-6 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base shrink-0">
                <i class="fa-solid fa-location-crosshairs"></i>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Titik Acuan GPS Lokasi Kamu</span>
                <span class="text-xs font-bold text-slate-800 font-mono">
                    Latitude: {{ $userLat }}, Longitude: {{ $userLng }}
                </span>
            </div>
        </div>
        <span class="text-xs px-3 py-1 bg-emerald-50 text-emerald-800 font-semibold rounded-full border border-emerald-200 flex items-center gap-1.5">
            <i class="fa-solid fa-route text-emerald-600"></i> Jarak Dihitung via Haversine
        </span>
    </div>

    {{-- Bobot & Preferensi Summary --}}
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 mb-8 border border-slate-700/50">
        <h2 class="text-sm font-bold text-white flex items-center gap-2 mb-4">
            <i class="fa-solid fa-weight-hanging text-amber-400"></i>
            Preferensi & Bobot ROC Kamu
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($preferensiUser as $index => $pref)
                @php
                    $pct = isset($weights[$pref->kriteria_id]) ? number_format($weights[$pref->kriteria_id] * 100, 1) : '0.0';
                    $barWidth = isset($weights[$pref->kriteria_id]) ? min(100, $weights[$pref->kriteria_id] * 100) : 0;
                    $barColors = ['bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-violet-500'];
                    $barColor = $barColors[$index] ?? 'bg-slate-500';
                @endphp
                <div class="bg-white/5 border border-white/10 rounded-xl p-3 text-center">
                    <div class="text-xs font-bold text-amber-400 mb-0.5">Rank {{ $pref->urutan }}</div>
                    <div class="text-[11px] font-semibold text-white mb-2 truncate">{{ $pref->kriteria->nama_kriteria }}</div>
                    <div class="text-xl font-extrabold text-emerald-400 font-mono">{{ $pct }}%</div>
                    <div class="mt-1.5 h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="{{ $barColor }} h-1.5 rounded-full" style="width: {{ $barWidth }}%"></div>
                    </div>
                    <div class="mt-1.5 text-[10px] px-1.5 py-0.5 rounded {{ $pref->kriteria->jenis === 'benefit' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }} font-medium">
                        {{ ucfirst($pref->kriteria->jenis) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Peta Persebaran Toko (OpenStreetMap + Leaflet) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i>
                    Peta Persebaran Toko Rekomendasi
                </h2>
                <p class="text-xs text-slate-500">Marker biru melambangkan posisi Kamu. Marker bernomor menunjukkan peringkat rekomendasi toko.</p>
            </div>
            <span class="text-[10px] font-bold px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                <i class="fa-solid fa-map text-emerald-600"></i> OpenStreetMap
            </span>
        </div>

        {{-- Map container --}}
        <div id="osm-map-hasil" class="w-full h-80 sm:h-96 rounded-xl border border-slate-300 shadow-inner overflow-hidden"></div>
    </div>

    {{-- Rankings List --}}
    <h2 class="text-lg font-bold text-slate-900 mb-5 flex items-center gap-2">
        <i class="fa-solid fa-list-ol text-slate-600"></i>
        Ranking Toko ({{ $rankings->count() }} Toko Ditemukan)
    </h2>

    <div class="space-y-4">
        @foreach($rankings as $idx => $item)
            @php
                $toko = $item['toko'];
                $score = $item['score'];
                $utilities = $item['utilities'];
                $distanceKm = $item['jarak_km'];
                $rank = $idx + 1;

                // Styling badge peringkat
                $rankStyles = match($rank) {
                    1 => ['bg' => 'bg-amber-500', 'text' => 'text-slate-900', 'label' => '🥇 Terbaik', 'border' => 'border-amber-300', 'glow' => 'shadow-amber-100'],
                    2 => ['bg' => 'bg-slate-400', 'text' => 'text-white', 'label' => '🥈 Runner-up', 'border' => 'border-slate-300', 'glow' => ''],
                    3 => ['bg' => 'bg-orange-600', 'text' => 'text-white', 'label' => '🥉 Peringkat 3', 'border' => 'border-orange-300', 'glow' => ''],
                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => "Peringkat {$rank}", 'border' => 'border-slate-200', 'glow' => ''],
                };
            @endphp

            <div id="toko-card-{{ $rank }}" class="bg-white rounded-2xl border {{ $rankStyles['border'] }} shadow-sm {{ $rank <= 3 ? 'shadow-md ' . $rankStyles['glow'] : '' }} overflow-hidden transition-all hover:shadow-lg hover:-translate-y-0.5 duration-200">
                <div class="flex flex-col sm:flex-row gap-0">

                    {{-- Rank Badge Sidebar --}}
                    <div class="{{ $rankStyles['bg'] }} px-6 py-5 flex flex-row sm:flex-col items-center justify-between sm:justify-center gap-4 sm:gap-2 sm:min-w-[90px] sm:text-center">
                        <div class="flex flex-col sm:block items-center gap-1">
                            <span class="{{ $rankStyles['text'] }} text-3xl font-extrabold leading-none">{{ $rank }}</span>
                            <span class="{{ $rankStyles['text'] }} text-[10px] font-bold opacity-80 hidden sm:block">dari {{ $rankings->count() }}</span>
                        </div>
                        <span class="{{ $rankStyles['text'] }} text-[10px] font-bold sm:hidden opacity-80">{{ $rankStyles['label'] }}</span>
                        {{-- Score --}}
                        <div class="sm:mt-3 text-center">
                            <div class="{{ $rankStyles['text'] }} text-lg font-extrabold font-mono leading-none">{{ number_format($score, 4) }}</div>
                            <div class="{{ $rankStyles['text'] }} text-[9px] font-semibold opacity-70">Skor SMART</div>
                        </div>
                    </div>

                    {{-- Main Content --}}
                    <div class="flex-1 p-5">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                            <div class="flex items-start gap-3.5">
                                @if($toko?->gambar)
                                    <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-cover border border-slate-200 shadow-xs shrink-0 mt-0.5" />
                                @endif
                                <div>
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        @if($rank <= 3)
                                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $rankStyles['bg'] }} {{ $rankStyles['text'] }}">{{ $rankStyles['label'] }}</span>
                                        @endif

                                        {{-- Distance Badge (Haversine Formula Result) --}}
                                        @if($distanceKm !== null)
                                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
                                                <i class="fa-solid fa-location-arrow text-[10px]"></i>
                                                {{ $distanceKm }} km dari Anda
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg font-extrabold text-slate-900">{{ $toko?->nama ?? 'Toko #'.$item['toko_id'] }}</h3>
                                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-location-dot text-emerald-600 text-[10px]"></i>
                                        {{ $toko?->alamat ?? 'Alamat tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                            @if($toko)
                                <a href="{{ route('toko.show', $toko->id) }}" class="shrink-0 flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-colors">
                                    <i class="fa-solid fa-eye text-[10px]"></i>
                                    Detail Toko
                                </a>
                            @endif
                        </div>

                        @if($toko)
                            {{-- Toko Meta --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($toko->jam_operasional)
                                    <span class="text-[11px] bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg font-medium flex items-center gap-1 border border-slate-200">
                                        <i class="fa-regular fa-clock text-[10px]"></i> {{ $toko->jam_operasional }}
                                    </span>
                                @endif
                                @if($toko->media_sosial)
                                    <span class="text-[11px] bg-pink-50 text-pink-700 px-2.5 py-1 rounded-lg font-medium flex items-center gap-1 border border-pink-200">
                                        <i class="fa-brands fa-instagram text-[10px]"></i> {{ $toko->media_sosial }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Utility Scores Per Kriteria --}}
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">Nilai Utility Per Kriteria</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                                @foreach($preferensiUser as $pref)
                                    @php
                                        $utilVal = $utilities[$pref->kriteria_id] ?? 0;
                                        $utilPct = number_format($utilVal * 100, 1);
                                        $utilBg = $utilVal >= 0.7 ? 'bg-emerald-50 border-emerald-200' : ($utilVal >= 0.4 ? 'bg-amber-50 border-amber-200' : 'bg-rose-50 border-rose-200');
                                        $utilText = $utilVal >= 0.7 ? 'text-emerald-700' : ($utilVal >= 0.4 ? 'text-amber-700' : 'text-rose-700');
                                        $utilBar = $utilVal >= 0.7 ? 'bg-emerald-500' : ($utilVal >= 0.4 ? 'bg-amber-400' : 'bg-rose-400');
                                    @endphp
                                    <div class="p-2 rounded-lg border {{ $utilBg }}">
                                        <p class="text-[10px] font-semibold text-slate-500 truncate mb-1">{{ $pref->kriteria->nama_kriteria }}</p>
                                        <p class="{{ $utilText }} text-sm font-extrabold font-mono mb-1">{{ $utilPct }}%</p>
                                        <div class="h-1.5 bg-white rounded-full overflow-hidden border border-white/60">
                                            <div class="{{ $utilBar }} h-1.5 rounded-full" style="width: {{ $utilPct }}%"></div>
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

    {{-- CTA Bottom --}}
    <div class="mt-10 text-center py-8 border-t border-slate-200">
        <p class="text-sm text-slate-500 mb-4">Ingin mengubah prioritas kriteria atau titik lokasi GPS kamu?</p>
        <a href="{{ route('user.preferensi') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-md hover:shadow-emerald-200">
            <i class="fa-solid fa-rotate-left"></i>
            Atur Ulang Preferensi & GPS
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const userPos = { lat: {{ $userLat }}, lng: {{ $userLng }} };

    const storesData = [
        @foreach($rankings as $idx => $item)
            @if($item['toko'])
            {
                rank: {{ $idx + 1 }},
                id: {{ $item['toko']->id }},
                nama: "{{ addslashes($item['toko']->nama) }}",
                alamat: "{{ addslashes($item['toko']->alamat) }}",
                score: "{{ number_format($item['score'], 4) }}",
                jarak: "{{ $item['jarak_km'] }}",
                lat: {{ $item['toko']->latitude ?? -8.1695 }},
                lng: {{ $item['toko']->longitude ?? 113.7152 }}
            },
            @endif
        @endforeach
    ];

    if (storesData.length === 0) return;

    // Inisialisasi peta di tengah posisi user
    const map = L.map('osm-map-hasil').setView([userPos.lat, userPos.lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
    }).addTo(map);

    // ── Marker Biru: Posisi User ───────────────────────────────────
    const userIcon = L.divIcon({
        className: '',
        html: `<div style="
            width:20px; height:20px; border-radius:50%;
            background:#0284c7; border:3px solid #fff;
            box-shadow:0 2px 6px rgba(0,0,0,0.4);
        "></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10],
    });

    L.marker([userPos.lat, userPos.lng], { icon: userIcon })
        .addTo(map)
        .bindPopup(`<div style="font-family:sans-serif; min-width:130px;">
            <strong style="color:#0284c7;">📍 Posisi Anda</strong><br>
            <span style="font-size:11px; color:#475569;">Lat: ${userPos.lat}<br>Lng: ${userPos.lng}</span>
        </div>`);

    const bounds = [[userPos.lat, userPos.lng]];

    // ── Marker Bernomor: Toko Rekomendasi ──────────────────────────
    // Warna berdasarkan peringkat
    const rankColors = ['#f59e0b', '#94a3b8', '#ea580c'];

    storesData.forEach(store => {
        const bgColor = rankColors[store.rank - 1] ?? '#0f172a';

        const tokoIcon = L.divIcon({
            className: '',
            html: `<div style="
                width:30px; height:30px; border-radius:50%;
                background:${bgColor}; border:3px solid #fff;
                box-shadow:0 2px 8px rgba(0,0,0,0.35);
                display:flex; align-items:center; justify-content:center;
                font-weight:800; font-size:13px; color:#fff;
                font-family:sans-serif;
            ">${store.rank}</div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
        });

        L.marker([store.lat, store.lng], { icon: tokoIcon })
            .addTo(map)
            .bindPopup(`<div style="font-family:sans-serif; min-width:200px; max-width:240px;">
                <div style="margin-bottom:6px;">
                    <span style="background:${bgColor}; color:#fff; padding:2px 8px; border-radius:4px; font-size:10px; font-weight:700;">
                        Peringkat #${store.rank}
                    </span>
                    <span style="background:#dcfce7; color:#166534; padding:2px 8px; border-radius:4px; font-size:10px; font-weight:700; margin-left:4px;">
                        ${store.jarak} km
                    </span>
                </div>
                <b style="font-size:13px; color:#0f172a;">${store.nama}</b><br>
                <span style="font-size:11px; color:#64748b;">Skor SMART: <strong>${store.score}</strong></span><br>
                <span style="font-size:11px; color:#475569;">${store.alamat}</span><br><br>
                <a href="/toko/${store.id}"
                   style="font-size:11px; color:#0284c7; font-weight:700; text-decoration:none;">
                    Lihat Detail Toko &rarr;
                </a>
            </div>`);

        bounds.push([store.lat, store.lng]);
    });

    // Sesuaikan zoom agar semua marker terlihat
    map.fitBounds(bounds, { padding: [30, 30] });
})();
</script>
@endpush
