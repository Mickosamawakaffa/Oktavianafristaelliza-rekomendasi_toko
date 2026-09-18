@extends('layouts.app')

@section('title', 'Edit Nilai Kriteria Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Nilai Kriteria Toko</h1>
            <p class="text-xs text-slate-500">Input/Ubah nilai kriteria (skala 1–100) untuk <strong class="text-amber-700">{{ $toko->nama }}</strong></p>
        </div>
        <a href="{{ route('admin.nilai-alternatif.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <p class="font-bold flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan validasi:</p>
            @foreach($errors->all() as $error)
                <p>- {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
        <form action="{{ route('admin.nilai-alternatif.update', $toko->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach($kriterias as $k)
                    @if($kriteriaUlasanId && $k->id === $kriteriaUlasanId)
                        {{-- ============================================================ --}}
                        {{-- KRITERIA ULASAN: Input rating (1-5) + jumlah ulasan + preview --}}
                        {{-- ============================================================ --}}
                        <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-sm text-slate-900 block flex items-center gap-1.5">
                                        <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                                        {{ $k->nama_kriteria }}
                                    </span>
                                    <span class="text-[11px] font-semibold {{ $k->jenis === 'benefit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Jenis: {{ ucfirst($k->jenis) }} — Skor dihitung otomatis dari rating
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- Rating Ulasan (1-5) --}}
                                <div>
                                    <label for="rating_ulasan" class="block text-[11px] font-semibold text-slate-600 mb-1">
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
                                        class="w-full px-3 py-2 rounded-xl border border-indigo-300 font-mono text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="4.30"
                                    />
                                </div>

                                {{-- Jumlah Ulasan --}}
                                <div>
                                    <label for="jumlah_ulasan" class="block text-[11px] font-semibold text-slate-600 mb-1">
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
                                        class="w-full px-3 py-2 rounded-xl border border-indigo-300 font-mono text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        placeholder="25"
                                    />
                                </div>
                            </div>

                            {{-- Preview Skor Konversi --}}
                            <div class="flex items-center gap-2 p-2.5 rounded-lg bg-white border border-indigo-200">
                                <span class="text-[11px] font-semibold text-slate-600">Skor Ulasan (1–100):</span>
                                <span id="skor-ulasan-preview" class="font-mono font-extrabold text-lg text-indigo-700">
                                    {{ $toko->skor_ulasan ?? '—' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">/100</span>
                            </div>

                            {{-- Hidden input yang menyimpan skor konversi ke nilai[kriteria_id] --}}
                            <input
                                type="hidden"
                                id="nilai_ulasan_hidden"
                                name="nilai[{{ $k->id }}]"
                                value="{{ old("nilai.{$k->id}", $nilaiMap[$k->id] ?? ($toko->skor_ulasan ?? 50)) }}"
                            />

                            {{-- Warning: Jumlah ulasan kurang representatif --}}
                            <div
                                id="warning-ulasan"
                                class="p-3 rounded-lg bg-amber-50 border border-amber-300 text-amber-800 text-xs font-semibold flex items-center gap-2"
                                style="display: {{ ($toko->jumlah_ulasan !== null && $toko->jumlah_ulasan < 10) ? 'flex' : 'none' }};"
                            >
                                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                                Jumlah ulasan toko ini kurang dari 10, skor ulasan mungkin kurang representatif.
                            </div>
                        </div>
                    @else
                        {{-- ============================================================ --}}
                        {{-- KRITERIA LAIN: Input manual biasa (skala 1–100) — TIDAK DIUBAH --}}
                        {{-- ============================================================ --}}
                        @php
                            $val = old("nilai.{$k->id}", $nilaiMap[$k->id] ?? 50);
                        @endphp
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">{{ $k->nama_kriteria }}</span>
                                <span class="text-[11px] font-semibold {{ $k->jenis === 'benefit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Jenis: {{ ucfirst($k->jenis) }}
                                </span>
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
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-amber-500"
                                />
                                <span class="text-xs font-semibold text-slate-400 font-mono">/100</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.nilai-alternatif.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Simpan Nilai Alternatif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /**
     * Update preview skor ulasan dan hidden input saat rating_ulasan diubah.
     * Rumus: skor = (rating / 5) * 100
     */
    function updateSkorUlasanPreview() {
        const ratingInput = document.getElementById('rating_ulasan');
        const previewEl = document.getElementById('skor-ulasan-preview');
        const hiddenInput = document.getElementById('nilai_ulasan_hidden');

        const rating = parseFloat(ratingInput.value);

        if (!isNaN(rating) && rating >= 1 && rating <= 5) {
            const skor = Math.round((rating / 5) * 1000) / 10; // 1 desimal
            previewEl.textContent = skor;
            hiddenInput.value = skor;
        } else {
            previewEl.textContent = '—';
            hiddenInput.value = '';
        }
    }

    /**
     * Tampilkan/sembunyikan warning jika jumlah_ulasan < 10.
     */
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

    // Jalankan saat halaman dimuat untuk set state awal yang benar
    document.addEventListener('DOMContentLoaded', function() {
        updateSkorUlasanPreview();
        updateWarningUlasan();
    });
</script>
@endpush
