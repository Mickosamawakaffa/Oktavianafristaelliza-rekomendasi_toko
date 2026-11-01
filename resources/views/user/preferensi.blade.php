@extends('layouts.app')

@section('title', 'Atur Preferensi Kriteria Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-start gap-4 mb-8">
        <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0 border border-[#0F766E]/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A] leading-tight">Preferensi Kriteria Kamu</h1>
            <p class="text-sm text-[#526071] mt-0.5">Seret dan urutkan kriteria dari yang <strong class="text-[#0F766E]">paling penting</strong> (atas) ke <strong class="text-[#526071]">paling tidak penting</strong> (bawah). Bobot ROC dihitung otomatis dari urutan ini.</p>
        </div>
    </div>


    <form id="preferensi-form" action="{{ route('user.preferensi.simpan') }}" method="POST">
        @csrf

        {{-- GPS Geolocation Card --}}
        <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-5 shadow-soft mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                <div>
                    <h2 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Posisi Lokasi Kamu Saat Ini (GPS)</span>
                    </h2>
                    <p class="text-xs text-[#526071] mt-0.5">Jarak ke toko dihitung menggunakan formula Haversine berdasarkan posisi ini.</p>
                </div>
                <button
                    type="button"
                    id="btn-detect-gps"
                    onclick="detectUserLocation()"
                    class="px-3.5 py-2 bg-[#E6F4F1] hover:bg-[#d0ece5] text-[#0F766E] font-bold text-xs rounded-[10px] border border-[#0F766E]/20 transition flex items-center gap-1.5 shrink-0 min-h-[44px]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Deteksi GPS Otomatis</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div>
                    <label class="block text-[11px] font-semibold text-[#526071] mb-1">Latitude User</label>
                    <input 
                        type="text" 
                        id="user_lat" 
                        name="user_lat" 
                        value="{{ old('user_lat', session('user_lat', '-8.1724')) }}" 
                        class="w-full px-3 py-2 rounded-[10px] border border-[#CBD5E1] bg-[#F8FAFC] text-xs font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                    />
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-[#526071] mb-1">Longitude User</label>
                    <input 
                        type="text" 
                        id="user_lng" 
                        name="user_lng" 
                        value="{{ old('user_lng', session('user_lng', '113.7000')) }}" 
                        class="w-full px-3 py-2 rounded-[10px] border border-[#CBD5E1] bg-[#F8FAFC] text-xs font-mono font-bold text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                    />
                </div>
            </div>
            <p id="gps-status" class="text-[11px] text-[#526071] italic mt-2">
                Default: Pusat Kota Jember (-8.1724, 113.7000). Klik "Deteksi GPS Otomatis" untuk memakai lokasi HP/perangkat kamu.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- LEFT: Drag-and-Drop Form (3/5) --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#E2E8F0] flex justify-between items-center bg-[#F8FAFC]">
                        <h2 class="font-bold text-[#0F172A] text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            <span>Urutan Prioritas Kriteria</span>
                        </h2>
                        <span class="text-[11px] font-semibold text-[#0F766E] bg-[#E6F4F1] px-2.5 py-1 rounded-full border border-[#0F766E]/20">
                            {{ $kriteria->count() }} Kriteria
                        </span>
                    </div>

                    <div class="p-6">
                        {{-- Instruction Banner --}}
                        <div class="bg-[#F8FAFC] border border-dashed border-[#CBD5E1] rounded-[12px] p-3.5 mb-5 flex items-start gap-3 text-xs text-[#526071]">
                            <svg class="w-5 h-5 text-[#0F766E] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            <div>
                                <p class="font-semibold text-[#0F172A] mb-0.5">Cara Penggunaan</p>
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
                                    $isBenefit = $k->jenis === 'benefit';
                                @endphp
                                <li
                                    data-id="{{ $k->id }}"
                                    class="sortable-item flex items-center gap-3 p-3.5 rounded-[12px] border border-[#E2E8F0] bg-white hover:border-[#0F766E] hover:bg-[#E6F4F1]/30 cursor-grab active:cursor-grabbing select-none group transition shadow-sm"
                                >
                                    <div class="drag-handle flex items-center gap-2.5">
                                        <div class="rank-badge w-8 h-8 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-xs font-extrabold shrink-0 group-hover:bg-[#0B5C56] transition">
                                            {{ $index + 1 }}
                                        </div>

                                        <svg class="w-4 h-4 text-[#CBD5E1] group-hover:text-[#0F766E] transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0 drag-handle">
                                        <span class="text-sm font-bold text-[#0F172A]">{{ $k->nama_kriteria }}</span>
                                    </div>

                                    @if($isBenefit)
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#166534] border border-[#BBF7D0] flex items-center gap-1 shrink-0">
                                            <svg class="w-3 h-3 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                            </svg>
                                            Benefit
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA] flex items-center gap-1 shrink-0">
                                            <svg class="w-3 h-3 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                            </svg>
                                            Cost
                                        </span>
                                    @endif

                                    {{-- Tombol Fallback Naik / Turun --}}
                                    <div class="flex flex-col gap-0.5 shrink-0 pl-1 border-l border-[#E2E8F0]">
                                        <button
                                            type="button"
                                            onclick="moveItemUp(this)"
                                            class="btn-move-up w-7 h-7 rounded flex items-center justify-center text-[#526071] hover:text-[#0F766E] hover:bg-[#E6F4F1] disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-[#CBD5E1] transition"
                                            title="Geser Naik"
                                            aria-label="Geser Naik"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="moveItemDown(this)"
                                            class="btn-move-down w-7 h-7 rounded flex items-center justify-center text-[#526071] hover:text-[#0F766E] hover:bg-[#E6F4F1] disabled:opacity-20 disabled:hover:bg-transparent disabled:hover:text-[#CBD5E1] transition"
                                            title="Geser Turun"
                                            aria-label="Geser Turun"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            id="submit-btn"
                            class="w-full py-3.5 px-4 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition shadow-soft flex items-center justify-center gap-2 min-h-[44px]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span>Hitung Rekomendasi Toko</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>

                        @if($preferensiSaya->isNotEmpty())
                            <p class="text-center mt-3">
                                <a href="{{ route('user.hasil') }}" class="text-xs text-[#0F766E] hover:underline font-semibold inline-flex items-center gap-1 min-h-[44px]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span>Lihat Hasil Rekomendasi Sebelumnya</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Penjelasan Bobot ROC (2/5) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Bobot Preview (Real-time) --}}
                <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#E2E8F0] bg-[#F8FAFC]">
                        <h3 class="font-bold text-[#0F172A] text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                            <span>Preview Bobot ROC</span>
                        </h3>
                        <p class="text-[11px] text-[#526071] mt-0.5">Diperbarui otomatis saat kamu mengubah urutan.</p>
                    </div>
                    <div class="p-5">
                        <div id="bobot-preview" class="space-y-3">
                            {{-- Akan diisi JavaScript --}}
                        </div>
                    </div>
                </div>

                {{-- Penjelasan ROC --}}
                <div class="bg-[#0E2A3B] rounded-[16px] p-5 text-white shadow-soft">
                    <h3 class="font-bold text-sm flex items-center gap-2 mb-3 text-white">
                        <svg class="w-4 h-4 text-[#5EEAD4]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Cara Kerja ROC + SMART + Haversine</span>
                    </h3>
                    <div class="space-y-3 text-[11px] text-[#CBD5E1] leading-relaxed">
                        <div class="flex gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">1</span>
                            <p><strong class="text-white">Formula Haversine</strong> menghitung jarak riil (km) dari posisi GPS kamu ke lokasi fisik tiap toko di Jember.</p>
                        </div>
                        <div class="flex gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">2</span>
                            <p><strong class="text-white">ROC (Rank Order Centroid)</strong> menghitung persentase bobot kriteria berdasarkan ranking pilihan kamu.</p>
                        </div>
                        <div class="flex gap-2.5">
                            <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-[10px] font-bold shrink-0 mt-0.5">3</span>
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

    function formatNumberCommas(num) {
        return num.toString().replace('.', ',');
    }

    function updateAfterSort() {
        const items = sortableList.querySelectorAll('.sortable-item');
        const m = items.length;
        const weights = calculateRocWeights(m);

        hiddenInputs.innerHTML = '';
        bobotPreview.innerHTML = '';

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
            const pctDisplay = formatNumberCommas(pct);

            bobotPreview.innerHTML += `
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-bold text-[#526071] w-4 text-right">${index + 1}.</span>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[11px] font-semibold text-[#0F172A] truncate max-w-[140px]">${nama}</span>
                            <span class="text-[11px] font-bold text-[#0F766E] font-mono">${pctDisplay}%</span>
                        </div>
                        <div class="h-2 bg-[#F3F5F8] rounded-full overflow-hidden border border-[#E2E8F0]">
                            <div class="bg-[#0F766E] h-2 rounded-full transition-all duration-300" style="width: ${pct}%"></div>
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
            status.className = 'text-[11px] text-[#991B1B] font-semibold mt-2';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#0F766E] inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mendeteksi...';
        status.textContent = 'Meminta izin lokasi GPS...';
        status.className = 'text-[11px] text-[#0F766E] italic mt-2';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                document.getElementById('user_lat').value = lat;
                document.getElementById('user_lng').value = lng;

                status.textContent = `Lokasi GPS Berhasil Dideteksi! (${lat}, ${lng})`;
                status.className = 'text-[11px] text-[#166534] font-semibold mt-2';

                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Terdeteksi';
            },
            (error) => {
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Coba Lagi';
                status.textContent = 'Gagal mendeteksi lokasi (Izin ditolak atau signal GPS tidak ditemukan). Menggunakan koordinat default.';
                status.className = 'text-[11px] text-[#991B1B] italic mt-2';
            }
        );
    }

    // Inisialisasi Sortable.js
    Sortable.create(sortableList, {
        animation: 150,
        filter: '.btn-move-up, .btn-move-down',
        preventOnFilter: false,
        ghostClass: 'bg-[#E6F4F1]',
        chosenClass: 'ring-2 ring-[#0F766E]',
        dragClass: 'shadow-lg',
        onEnd: updateAfterSort,
    });

    document.addEventListener('DOMContentLoaded', updateAfterSort);
    updateAfterSort();
</script>
@endpush
