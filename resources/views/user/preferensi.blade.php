@extends('layouts.app')

@section('title', 'Atur Preferensi Kriteria Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-start gap-4 mb-8">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-lg shrink-0">
            <i class="fa-solid fa-sliders text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">Preferensi Kriteria Kamu</h1>
            <p class="text-sm text-slate-500 mt-0.5">Seret dan urutkan kriteria dari yang <strong class="text-emerald-700">paling penting</strong> (atas) ke <strong class="text-slate-600">paling tidak penting</strong> (bawah). Bobot ROC dihitung otomatis dari urutan ini.</p>
        </div>
    </div>

    {{-- Flash info --}}
    @if(session('info'))
        <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-500"></i> {{ session('info') }}
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    <form id="preferensi-form" action="{{ route('user.preferensi.simpan') }}" method="POST">
        @csrf

        {{-- GPS Geolocation Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-location-crosshairs text-emerald-600"></i>
                        Posisi Lokasi Kamu Saat Ini (GPS)
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Jarak ke toko dihitung menggunakan **Formula Haversine** berdasarkan posisi ini.</p>
                </div>
                <button
                    type="button"
                    id="btn-detect-gps"
                    onclick="detectUserLocation()"
                    class="px-3.5 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-xs rounded-xl border border-emerald-300 transition flex items-center gap-1.5 shrink-0"
                >
                    <i class="fa-solid fa-location-arrow"></i>
                    Deteksi GPS Otomatis
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Latitude User</label>
                    <input 
                        type="text" 
                        id="user_lat" 
                        name="user_lat" 
                        value="{{ old('user_lat', session('user_lat', '-8.1724')) }}" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Longitude User</label>
                    <input 
                        type="text" 
                        id="user_lng" 
                        name="user_lng" 
                        value="{{ old('user_lng', session('user_lng', '113.7000')) }}" 
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>
            </div>
            <p id="gps-status" class="text-[11px] text-slate-400 italic mt-2">
                Default: Pusat Kota Jember (-8.1724, 113.7000). Klik "Deteksi GPS Otomatis" untuk memakai lokasi HP/perangkat kamu.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- LEFT: Drag-and-Drop Form (3/5) --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <i class="fa-solid fa-sort text-emerald-600"></i>
                            Urutan Prioritas Kriteria
                        </h2>
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-1 rounded border border-emerald-200">
                            {{ $kriteria->count() }} Kriteria
                        </span>
                    </div>

                    <div class="p-6">
                        {{-- Instruction Banner --}}
                        <div class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-3.5 mb-5 flex items-start gap-3 text-xs text-slate-600">
                            <i class="fa-solid fa-hand-pointer text-emerald-600 mt-0.5 text-base"></i>
                            <div>
                                <p class="font-semibold text-slate-800 mb-0.5">Cara Penggunaan</p>
                                <p>Klik dan <strong>seret baris kriteria</strong> untuk mengubah urutannya. Nomor di kiri berubah otomatis. Klik <strong>Hitung Rekomendasi</strong> untuk melihat hasil ranking toko.</p>
                            </div>
                        </div>

                        {{-- Hidden inputs akan diisi JS setelah sortable --}}
                        <div id="hidden-inputs"></div>

                        {{-- Sortable List --}}
                        <ul id="sortable-list" class="space-y-2.5 mb-6">
                            @php
                                $displayOrder = $preferensiSaya->isNotEmpty()
                                    ? $preferensiSaya->map(fn($p) => $p->kriteria)
                                    : $kriteria;
                            @endphp

                            @foreach($displayOrder as $index => $k)
                                @php
                                    $jenisColors = [
                                        'benefit' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'fa-arrow-trend-up'],
                                        'cost' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'icon' => 'fa-arrow-trend-down'],
                                    ];
                                    $colors = $jenisColors[$k->jenis] ?? $jenisColors['benefit'];
                                @endphp
                                <li
                                    data-id="{{ $k->id }}"
                                    class="sortable-item flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-emerald-300 hover:bg-emerald-50/30 cursor-grab active:cursor-grabbing select-none group transition-colors shadow-2xs"
                                >
                                    <div class="drag-handle flex items-center gap-2.5">
                                        <div class="rank-badge w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-extrabold shrink-0 group-hover:bg-emerald-600 transition-colors">
                                            {{ $index + 1 }}
                                        </div>

                                        <i class="fa-solid fa-grip-vertical text-slate-300 group-hover:text-emerald-500 transition-colors text-base shrink-0"></i>
                                    </div>

                                    <div class="flex-1 min-w-0 drag-handle">
                                        <span class="text-sm font-bold text-slate-800">{{ $k->nama_kriteria }}</span>
                                    </div>

                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} flex items-center gap-1 shrink-0">
                                        <i class="fa-solid {{ $colors['icon'] }} text-[9px]"></i>
                                        {{ ucfirst($k->jenis) }}
                                    </span>

                                    {{-- Tombol Fallback Naik / Turun --}}
                                    <div class="flex flex-col gap-0.5 shrink-0 pl-1 border-l border-slate-100">
                                        <button
                                            type="button"
                                            onclick="moveItemUp(this)"
                                            class="btn-move-up w-6 h-6 rounded flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-100/60 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition"
                                            title="Geser Naik"
                                        >
                                            <i class="fa-solid fa-chevron-up text-[10px]"></i>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="moveItemDown(this)"
                                            class="btn-move-down w-6 h-6 rounded flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-100/60 disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-slate-400 transition"
                                            title="Geser Turun"
                                        >
                                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            id="submit-btn"
                            class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:shadow-emerald-200 flex items-center justify-center gap-2.5"
                        >
                            <i class="fa-solid fa-calculator"></i>
                            Hitung Rekomendasi Toko
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                        @if($preferensiSaya->isNotEmpty())
                            <p class="text-center mt-3">
                                <a href="{{ route('user.hasil') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                                    <i class="fa-solid fa-chart-bar mr-1"></i>Lihat Hasil Rekomendasi Sebelumnya
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Penjelasan Bobot ROC (2/5) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Bobot Preview (Real-time) --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-weight-hanging text-amber-500"></i>
                            Preview Bobot ROC
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Diperbarui otomatis saat kamu mengubah urutan.</p>
                    </div>
                    <div class="p-5">
                        <div id="bobot-preview" class="space-y-2">
                            {{-- Akan diisi JavaScript --}}
                        </div>
                    </div>
                </div>

                {{-- Penjelasan ROC --}}
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white">
                    <h3 class="font-bold text-sm flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-circle-info text-emerald-400"></i>
                        Cara Kerja ROC + SMART + Haversine
                    </h3>
                    <div class="space-y-3 text-[11px] text-slate-300 leading-relaxed">
                        <div class="flex gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">1</span>
                            <p><strong class="text-white">Haversine Formula</strong> menghitung jarak riil (km) dari posisi GPS kamu ke lokasi fisik tiap toko di Jember.</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-5 h-5 rounded-full bg-teal-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">2</span>
                            <p><strong class="text-white">ROC (Rank Order Centroid)</strong> menghitung persentase bobot kriteria berdasarkan ranking pilihan kamu.</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="w-5 h-5 rounded-full bg-violet-600 text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">3</span>
                            <p><strong class="text-white">SMART</strong> mengurutkan toko terbaik berdasarkan kombinasi jarak GPS dan kriteria toko pilihan kamu.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    const sortableList = document.getElementById('sortable-list');
    const hiddenInputs = document.getElementById('hidden-inputs');
    const bobotPreview = document.getElementById('bobot-preview');

    function calculateRocWeights(m) {
        const weights = [];
        for (let k = 1; k <= m; k++) {
            let sum = 0;
            for (let i = k; i <= m; i++) {
                sum += 1 / i;
            }
            weights.push(((1 / m) * sum));
        }
        return weights;
    }

    function updateAfterSort() {
        const items = sortableList.querySelectorAll('.sortable-item');
        const m = items.length;
        const weights = calculateRocWeights(m);

        hiddenInputs.innerHTML = '';
        bobotPreview.innerHTML = '';

        const colors = ['bg-emerald-500', 'bg-teal-500', 'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-violet-500'];

        items.forEach((item, index) => {
            // Update Nomor Urut di Kiri
            const badge = item.querySelector('.rank-badge');
            if (badge) badge.textContent = index + 1;

            // Update Status Disabled pada Tombol Panah
            const btnUp = item.querySelector('.btn-move-up');
            const btnDown = item.querySelector('.btn-move-down');
            if (btnUp) btnUp.disabled = (index === 0);
            if (btnDown) btnDown.disabled = (index === m - 1);

            // Re-generate hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'urutan_kriteria[]';
            input.value = item.dataset.id;
            hiddenInputs.appendChild(input);

            // Re-generate preview bobot ROC
            const nama = item.querySelector('.min-w-0 span').textContent.trim();
            const bobot = weights[index];
            const pct = (bobot * 100).toFixed(2);
            const color = colors[index] || 'bg-slate-500';

            bobotPreview.innerHTML += `
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold text-slate-600 w-4 text-right">${index + 1}.</span>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-0.5">
                            <span class="text-[11px] font-medium text-slate-700 truncate max-w-[140px]">${nama}</span>
                            <span class="text-[11px] font-bold text-slate-800">${pct}%</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="${color} h-2 rounded-full transition-all duration-300" style="width: ${pct}%"></div>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // Tombol Panah Naik
    function moveItemUp(btn) {
        const item = btn.closest('.sortable-item');
        const prev = item.previousElementSibling;
        if (prev) {
            sortableList.insertBefore(item, prev);
            updateAfterSort();
        }
    }

    // Tombol Panah Turun
    function moveItemDown(btn) {
        const item = btn.closest('.sortable-item');
        const next = item.nextElementSibling;
        if (next) {
            sortableList.insertBefore(next, item);
            updateAfterSort();
        }
    }

    // Deteksi GPS Geolocation
    function detectUserLocation() {
        const status = document.getElementById('gps-status');
        const btn = document.getElementById('btn-detect-gps');

        if (!navigator.geolocation) {
            status.textContent = 'Geolocation tidak didukung oleh browser ini.';
            status.className = 'text-[11px] text-rose-500 italic mt-2';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi...';
        status.textContent = 'Meminta izin lokasi GPS...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                document.getElementById('user_lat').value = lat;
                document.getElementById('user_lng').value = lng;

                status.textContent = `Lokasi GPS Berhasil Dideteksi! (${lat}, ${lng})`;
                status.className = 'text-[11px] text-emerald-600 font-semibold mt-2';

                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Terdeteksi';
            },
            (error) => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-location-arrow"></i> Coba Lagi';
                status.textContent = 'Gagal mendeteksi lokasi (Izin ditolak atau signal GPS tidak ditemukan). Menggunakan koordinat default.';
                status.className = 'text-[11px] text-amber-600 italic mt-2';
            }
        );
    }

    // Inisialisasi Sortable.js
    Sortable.create(sortableList, {
        animation: 150,
        filter: '.btn-move-up, .btn-move-down',
        preventOnFilter: false,
        ghostClass: 'bg-emerald-50/70',
        chosenClass: 'ring-2',
        dragClass: 'shadow-xl',
        onEnd: updateAfterSort,
    });

    // Inisialisasi awal saat halaman dibuka
    document.addEventListener('DOMContentLoaded', updateAfterSort);
    updateAfterSort();
</script>
@endpush
