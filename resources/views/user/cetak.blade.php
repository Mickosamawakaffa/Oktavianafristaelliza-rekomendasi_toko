<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Rekomendasi SPK - ThriftJember</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        ink: '#0F172A',
                        body: '#334155',
                        muted: '#526071',
                        primary: '#0F766E',
                        'primary-dark': '#0B5C56',
                        'primary-tint': '#E6F4F1',
                        border: '#E2E8F0',
                    }
                }
            }
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 11pt;
            }
            .page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            tr, table {
                page-break-inside: avoid;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 12mm 15mm 12mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-[#0F172A] font-sans antialiased py-6 sm:py-10">

    {{-- Action Bar (Hidden on print) --}}
    <div class="no-print max-w-4xl mx-auto px-4 mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.hasil') }}" class="px-4 py-2 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Hasil
            </a>
            <span class="text-xs text-slate-500 hidden sm:inline">Pratinjau Dokumen Cetak / PDF</span>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-xl shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Printable Document Container --}}
    <div class="page-container max-w-4xl mx-auto bg-white border border-slate-200 shadow-lg rounded-2xl p-8 sm:p-12">
        
        {{-- Kop Dokumen Resmi --}}
        <div class="border-b-2 border-slate-800 pb-4 mb-6">
            <div class="flex items-center justify-between gap-4">
                <div class="w-16 h-16 rounded-xl bg-[#0F766E] text-white flex items-center justify-center font-extrabold text-2xl shrink-0">
                    TJ
                </div>
                <div class="text-center flex-1">
                    <h1 class="text-base sm:text-lg font-extrabold uppercase tracking-wide text-slate-900 leading-tight">
                        SISTEM PENDUKUNG KEPUTUSAN REKOMENDASI TOKO THRIFT
                    </h1>
                    <h2 class="text-xs sm:text-sm font-bold text-slate-700 tracking-wide mt-0.5">
                        KABUPATEN JEMBER — JAWA TIMUR
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Implementasi Kombinasi Metode <strong>ROC</strong> (Rank Order Centroid) + <strong>SMART</strong> (Simple Multi-Attribute Rating Technique) + <strong>Haversine Formula</strong>
                    </p>
                </div>
                <div class="w-16 hidden sm:block shrink-0 text-right text-[10px] text-slate-400">
                    Dokumen<br>Hasil SPK
                </div>
            </div>
            <div class="mt-3 border-t border-slate-300 pt-1 flex justify-between text-[10px] text-slate-500">
                <span>No. Dokumen: <strong>TJ-SPK/{{ date('Ymd') }}/{{ Auth::id() }}</strong></span>
                <span>Waktu Cetak: <strong>{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</strong></span>
            </div>
        </div>

        {{-- Metadata Pengujian Pengguna --}}
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6 text-xs">
            <h3 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] mb-2.5 pb-1 border-b border-slate-200">
                Informasi Parameter Perhitungan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-6 text-[11px]">
                <div>
                    <span class="text-slate-500">Nama Pengguna:</span>
                    <strong class="text-slate-800 ml-1">{{ Auth::user()->name }} ({{ Auth::user()->email }})</strong>
                </div>
                <div>
                    <span class="text-slate-500">Metode Pengurutan:</span>
                    <strong class="text-[#0F766E] ml-1">ROC-SMART + Haversine Distance (GPS)</strong>
                </div>
                <div>
                    <span class="text-slate-500">Titik Koordinat Acuan GPS:</span>
                    <strong class="font-mono text-slate-800 ml-1">{{ $userLat }}, {{ $userLng }}</strong>
                </div>
                <div>
                    <span class="text-slate-500">Status Koordinat:</span>
                    <strong class="{{ $gpsDefault ? 'text-amber-700' : 'text-emerald-700' }} ml-1">
                        {{ $gpsDefault ? 'Default Pusat Kota (Alun-alun Jember)' : 'Deteksi GPS Perangkat User' }}
                    </strong>
                </div>
                <div>
                    <span class="text-slate-500">Total Toko Dinilai:</span>
                    <strong class="text-slate-800 ml-1">{{ $rankings->count() }} Toko Terverifikasi</strong>
                </div>
                <div>
                    <span class="text-slate-500">Jumlah Kriteria:</span>
                    <strong class="text-slate-800 ml-1">{{ $preferensiUser->count() }} Kriteria Penilaian</strong>
                </div>
            </div>
        </div>

        {{-- 1. Tabel Bobot Kriteria ROC --}}
        <div class="mb-6">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[10px] flex items-center justify-center font-bold">1</span>
                Prioritas dan Pembobotan Kriteria (Metode ROC)
            </h3>
            <table class="w-full text-left border-collapse text-[11px] border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 font-bold">
                        <th class="p-2 text-center w-12 border-r border-slate-300">Rank</th>
                        <th class="p-2 border-r border-slate-300">Nama Kriteria</th>
                        <th class="p-2 text-center w-24 border-r border-slate-300">Jenis</th>
                        <th class="p-2 text-right w-28 border-r border-slate-300">Bobot Desimal</th>
                        <th class="p-2 text-right w-24">Bobot (%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php $sumWeight = 0; @endphp
                    @foreach($preferensiUser as $pref)
                        @php
                            $w = $weights[$pref->kriteria_id] ?? 0;
                            $sumWeight += $w;
                        @endphp
                        <tr>
                            <td class="p-2 text-center font-bold border-r border-slate-200">#{{ $pref->urutan }}</td>
                            <td class="p-2 font-semibold text-slate-900 border-r border-slate-200">{{ $pref->kriteria->nama_kriteria }}</td>
                            <td class="p-2 text-center border-r border-slate-200">
                                <span class="font-medium {{ $pref->kriteria->jenis === 'benefit' ? 'text-emerald-700' : 'text-rose-700' }}">
                                    {{ ucfirst($pref->kriteria->jenis) }}
                                </span>
                            </td>
                            <td class="p-2 text-right font-mono border-r border-slate-200">{{ number_format($w, 6, ',', '.') }}</td>
                            <td class="p-2 text-right font-mono font-bold">{{ number_format($w * 100, 2, ',', '.') }}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 font-bold border-t-2 border-slate-300 text-slate-900">
                        <td colspan="3" class="p-2 text-center uppercase tracking-wider text-[10px] border-r border-slate-300">Total Bobot</td>
                        <td class="p-2 text-right font-mono border-r border-slate-300">{{ number_format($sumWeight, 6, ',', '.') }}</td>
                        <td class="p-2 text-right font-mono text-[#0F766E]">{{ number_format($sumWeight * 100, 1, ',', '.') }}%</td>
                    </tr>
                </tfoot>
            </table>
            <p class="text-[10px] text-slate-500 italic mt-1">
                * Formula ROC: W_k = (1/m) × ∑ (1/i) untuk i = k sampai m. Total bobot konsisten tepat 1,000000 (100%).
            </p>
        </div>

        {{-- 2. Tabel Matriks Nilai Alternatif Asli --}}
        <div class="mb-6">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[10px] flex items-center justify-center font-bold">2</span>
                Matriks Nilai Alternatif Asli (X)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[10px] border border-slate-300">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 font-bold">
                            <th class="p-2 border-r border-slate-300 w-8 text-center">No</th>
                            <th class="p-2 border-r border-slate-300 min-w-[140px]">Nama Toko</th>
                            @foreach($preferensiUser as $pref)
                                <th class="p-2 text-center border-r border-slate-300 last:border-r-0">
                                    {{ $pref->kriteria->nama_kriteria }}
                                    <span class="block text-[8px] font-normal text-slate-500">({{ ucfirst($pref->kriteria->jenis) }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($rankings as $idx => $item)
                            @php $toko = $item['toko']; @endphp
                            <tr>
                                <td class="p-2 text-center text-slate-500 border-r border-slate-200">{{ $idx + 1 }}</td>
                                <td class="p-2 font-bold text-slate-900 border-r border-slate-200">
                                    {{ $toko?->nama ?? 'Toko #'.$item['toko_id'] }}
                                </td>
                                @foreach($preferensiUser as $pref)
                                    @php
                                        $val = $item['raw_values'][$pref->kriteria_id] ?? null;
                                    @endphp
                                    <td class="p-2 text-center font-mono border-r border-slate-200 last:border-r-0">
                                        @if($pref->kriteria->nama_kriteria === 'Lokasi')
                                            {{ $val !== null ? number_format($val, 2, ',', '.') . ' km' : '-' }}
                                        @else
                                            {{ $val !== null ? number_format($val, 1, ',', '.') : '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-[10px] text-slate-500 italic mt-1">
                * Nilai kriteria Lokasi dihitung dari jarak fisik riil GPS pengguna menggunakan formula Haversine.
            </p>
        </div>

        {{-- 3. Tabel Matriks Nilai Utility SMART --}}
        <div class="mb-6">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[10px] flex items-center justify-center font-bold">3</span>
                Matriks Nilai Utility SMART [u(a_i) Skala 0,0000 - 1,0000]
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[10px] border border-slate-300">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800 border-b border-slate-300 font-bold">
                            <th class="p-2 border-r border-slate-300 w-8 text-center">No</th>
                            <th class="p-2 border-r border-slate-300 min-w-[140px]">Nama Toko</th>
                            @foreach($preferensiUser as $pref)
                                <th class="p-2 text-center border-r border-slate-300 last:border-r-0">
                                    {{ $pref->kriteria->nama_kriteria }}
                                    <span class="block text-[8px] font-mono text-[#0F766E]">w={{ number_format($weights[$pref->kriteria_id] ?? 0, 4, ',', '.') }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($rankings as $idx => $item)
                            @php $toko = $item['toko']; @endphp
                            <tr>
                                <td class="p-2 text-center text-slate-500 border-r border-slate-200">{{ $idx + 1 }}</td>
                                <td class="p-2 font-bold text-slate-900 border-r border-slate-200">
                                    {{ $toko?->nama ?? 'Toko #'.$item['toko_id'] }}
                                </td>
                                @foreach($preferensiUser as $pref)
                                    @php
                                        $u = $item['utilities'][$pref->kriteria_id] ?? 0;
                                    @endphp
                                    <td class="p-2 text-center font-mono border-r border-slate-200 last:border-r-0 {{ $u >= 0.7 ? 'font-bold text-[#0F766E]' : '' }}">
                                        {{ number_format($u, 4, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-[10px] text-slate-500 italic mt-1">
                * Rumus Benefit: u(a_i) = (C_i - C_min) / (C_max - C_min). Rumus Cost: u(a_i) = (C_max - C_i) / (C_max - C_min).
            </p>
        </div>

        {{-- 4. Tabel Hasil Akhir & Peringkat Rekomendasi --}}
        <div class="mb-8">
            <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 mb-2 flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-[#0F766E] text-white text-[10px] flex items-center justify-center font-bold">4</span>
                Hasil Akhir dan Peringkat Rekomendasi (V_i)
            </h3>
            <table class="w-full text-left border-collapse text-[11px] border border-slate-300">
                <thead>
                    <tr class="bg-slate-900 text-white font-bold">
                        <th class="p-2.5 text-center w-14">Rank</th>
                        <th class="p-2.5">Nama Toko Thrift</th>
                        <th class="p-2.5 text-center w-24">Jarak (GPS)</th>
                        <th class="p-2.5 text-center w-28">Skor Akhir (V_i)</th>
                        <th class="p-2.5 text-center w-36">Status Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($rankings as $idx => $item)
                        @php
                            $toko = $item['toko'];
                            $rank = $idx + 1;
                        @endphp
                        <tr class="{{ $rank === 1 ? 'bg-amber-50/70 font-bold' : '' }}">
                            <td class="p-2.5 text-center font-bold {{ $rank === 1 ? 'text-[#92400E]' : 'text-slate-700' }}">
                                #{{ $rank }}
                            </td>
                            <td class="p-2.5 text-slate-900 font-semibold">
                                {{ $toko?->nama ?? 'Toko #'.$item['toko_id'] }}
                                <span class="block text-[10px] font-normal text-slate-500">{{ $toko?->alamat }}</span>
                            </td>
                            <td class="p-2.5 text-center text-slate-600 font-mono">
                                {{ $item['jarak_km'] !== null ? number_format($item['jarak_km'], 2, ',', '.') . ' km' : '-' }}
                            </td>
                            <td class="p-2.5 text-center font-mono font-extrabold text-sm text-[#0F766E]">
                                {{ number_format($item['score'], 6, ',', '.') }}
                            </td>
                            <td class="p-2.5 text-center text-[10px] font-bold">
                                @if($rank === 1)
                                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-300">
                                        Pilihan Utama #1
                                    </span>
                                @elseif($rank <= 3)
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-900 border border-emerald-300">
                                        Sangat Direkomendasikan
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                        Alternatif Tambahan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Kolom Pengesahan / Tanda Tangan --}}
        <div class="border-t border-slate-300 pt-6 mt-8 flex justify-between text-xs text-slate-700">
            <div class="max-w-[260px] text-[10px] text-slate-500">
                <p class="font-bold text-slate-700 mb-1">Catatan Sistem:</p>
                <p>Dokumen ini digenerate secara otomatis oleh sistem pendukung keputusan ThriftJember berdasarkan urutan preferensi subjektif pengguna saat diakses.</p>
            </div>

            <div class="text-center min-w-[200px]">
                <p class="mb-1">Jember, {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
                <p class="font-semibold text-slate-600 mb-14">Penguji / Pengguna Sistem,</p>
                <p class="font-bold text-slate-900 border-b border-slate-400 pb-1 underline">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-[10px] text-slate-500">NIP / ID: {{ Auth::id() }}</p>
            </div>
        </div>

    </div>

</body>
</html>
