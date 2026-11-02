@extends('layouts.app')

@section('title', 'Edit Nilai Kriteria Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Edit Nilai Kriteria Toko</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Input/Ubah nilai kriteria (skala 1–100) untuk <strong class="text-[#0F766E]">{{ $toko->nama }}</strong></p>
        </div>
        <a href="{{ route('admin.nilai-alternatif.index') }}" class="text-xs font-semibold text-[#526071] hover:text-[#0F172A] flex items-center gap-1.5 min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#DC2626] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Terdapat kesalahan validasi:</span>
            </p>
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[16px] border border-[#E2E8F0] p-6 shadow-soft">
        <form action="{{ route('admin.nilai-alternatif.update', $toko->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($kriterias as $k)
                    @if($kriteriaUlasanId && $k->id === $kriteriaUlasanId)
                        {{-- Kriteria Ulasan --}}
                        <div class="p-4 rounded-[12px] bg-[#E6F4F1] border border-[#0F766E]/30 space-y-3">
                            <div>
                                <span class="font-bold text-sm text-[#0F172A] block">
                                    {{ $k->nama_kriteria }}
                                </span>
                                <span class="text-[11px] font-semibold text-[#0B5C56]">
                                    Jenis: {{ ucfirst($k->jenis) }} — Skor dihitung otomatis dari rating
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label for="rating_ulasan" class="block text-[11px] font-semibold text-[#334155] mb-1">
                                        Rating Ulasan (skala 1–5)
                                    </label>
                                    <input
                                        type="number"
                                        id="rating_ulasan"
                                        name="rating_ulasan"
                                        value="{{ old('rating_ulasan', $toko->rating_ulasan) }}"
                                        min="1"
                                        max="5"
                                        step="0.01"
                                        required
                                        oninput="updateSkorUlasanPreview()"
                                        class="w-full px-3 py-2 rounded-[8px] bg-white border border-[#CBD5E1] text-[#0F172A] font-bold text-center focus:outline-none focus:border-[#0F766E]"
                                        placeholder="4.30"
                                    />
                                </div>

                                <div>
                                    <label for="jumlah_ulasan" class="block text-[11px] font-semibold text-[#334155] mb-1">
                                        Jumlah Ulasan
                                    </label>
                                    <input
                                        type="number"
                                        id="jumlah_ulasan"
                                        name="jumlah_ulasan"
                                        value="{{ old('jumlah_ulasan', $toko->jumlah_ulasan) }}"
                                        min="0"
                                        step="1"
                                        oninput="updateWarningUlasan()"
                                        class="w-full px-3 py-2 rounded-[8px] bg-white border border-[#CBD5E1] text-[#0F172A] font-bold text-center focus:outline-none focus:border-[#0F766E]"
                                        placeholder="25"
                                    />
                                </div>
                            </div>

                            <div class="flex items-center gap-2 p-2.5 rounded-[8px] bg-white border border-[#CBD5E1]">
                                <span class="text-[11px] font-semibold text-[#526071]">Skor Ulasan (1–100):</span>
                                <span id="skor-ulasan-preview" class="font-extrabold text-lg text-[#0F766E]">
                                    {{ $toko->skor_ulasan ?? '—' }}
                                </span>
                                <span class="text-[10px] text-[#526071]">/100</span>
                            </div>

                            <input
                                type="hidden"
                                id="nilai_ulasan_hidden"
                                name="nilai[{{ $k->id }}]"
                                value="{{ old("nilai.{$k->id}", $nilaiMap[$k->id] ?? ($toko->skor_ulasan ?? 50)) }}"
                            />

                            <div
                                id="warning-ulasan"
                                class="p-3 rounded-[8px] bg-[#FEF3C7] border border-[#FDE68A] text-[#92400E] text-xs font-semibold flex items-center gap-2"
                                style="display: {{ ($toko->jumlah_ulasan !== null && $toko->jumlah_ulasan < 10) ? 'flex' : 'none' }};"
                            >
                                <svg class="w-4 h-4 text-[#92400E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Jumlah ulasan toko ini kurang dari 10, skor ulasan mungkin kurang representatif.</span>
                            </div>
                        </div>
                    @else
                        {{-- Kriteria Lain --}}
                        @php
                            $val = old("nilai.{$k->id}", $nilaiMap[$k->id] ?? 50);
                        @endphp
                        <div class="p-4 rounded-[12px] bg-[#F8FAFC] border border-[#E2E8F0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div>
                                <span class="font-bold text-sm text-[#0F172A] block">{{ $k->nama_kriteria }}</span>
                                <span class="text-[11px] font-semibold {{ $k->jenis === 'benefit' ? 'text-[#0F766E]' : 'text-[#991B1B]' }}">
                                    Jenis: {{ ucfirst($k->jenis) }}
                                </span>
                                @if($k->nama_kriteria === 'Harga')
                                    <span class="block text-[11px] text-[#526071] mt-0.5">Isi dengan skor tingkat harga 0–100 (bukan rupiah). Semakin tinggi = semakin mahal.</span>
                                @elseif($k->nama_kriteria === 'Lokasi')
                                    <span class="block text-[11px] text-[#526071] mt-0.5">Hanya cadangan untuk pengunjung tanpa GPS. Bagi pengguna yang mengatur GPS, jarak dihitung otomatis (Haversine).</span>
                                @else
                                    <span class="block text-[11px] text-[#526071] mt-0.5">Skor penilaian 0–100. Semakin tinggi = semakin baik.</span>
                                @endif
                            </div>

                            <div class="w-full sm:w-48 flex items-center gap-2">
                                <input
                                    type="number"
                                    name="nilai[{{ $k->id }}]"
                                    value="{{ $val }}"
                                    min="0"
                                    max="100"
                                    step="1"
                                    required
                                    class="w-full px-3 py-2 rounded-[8px] bg-white border border-[#CBD5E1] text-[#0F172A] font-bold text-center focus:outline-none focus:border-[#0F766E]"
                                />
                                <span class="text-xs font-semibold text-[#526071]">/100</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="pt-4 border-t border-[#E2E8F0] flex justify-end gap-3">
                <a href="{{ route('admin.nilai-alternatif.index') }}" class="px-4 py-2.5 border border-[#CBD5E1] rounded-[10px] text-xs font-bold text-[#334155] hover:bg-[#F3F5F8] transition min-h-[44px] flex items-center">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] shadow-sm transition min-h-[44px] flex items-center">
                    Simpan Nilai Alternatif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateSkorUlasanPreview() {
        const ratingInput = document.getElementById('rating_ulasan');
        const previewEl = document.getElementById('skor-ulasan-preview');
        const hiddenInput = document.getElementById('nilai_ulasan_hidden');

        if (!ratingInput || !previewEl || !hiddenInput) return;

        const rating = parseFloat(ratingInput.value);

        if (!isNaN(rating) && rating >= 1 && rating <= 5) {
            const skor = Math.round((rating / 5) * 1000) / 10;
            previewEl.textContent = skor;
            hiddenInput.value = skor;
        } else {
            previewEl.textContent = '—';
            hiddenInput.value = '';
        }
    }

    function updateWarningUlasan() {
        const jumlahInput = document.getElementById('jumlah_ulasan');
        const warningEl = document.getElementById('warning-ulasan');

        if (!jumlahInput || !warningEl) return;

        const jumlah = parseInt(jumlahInput.value);

        if (!isNaN(jumlah) && jumlah < 10) {
            warningEl.style.display = 'flex';
        } else {
            warningEl.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSkorUlasanPreview();
        updateWarningUlasan();
    });
</script>
@endpush
