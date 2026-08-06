@extends('layouts.app')

@section('title', 'Dashboard Pemilik Toko')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-400 text-slate-900 flex items-center justify-center shadow-md">
                <i class="fa-solid fa-store text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Dashboard Pemilik Toko</h1>
                <p class="text-sm text-slate-500">Selamat datang, <span class="font-semibold text-amber-600">{{ Auth::user()->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('pemilik.toko.create') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Toko Saya
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    @if($tokoSaya->isEmpty())
        {{-- Jika belum punya toko terdaftar --}}
        <div class="bg-white rounded-2xl border border-dashed border-amber-300 p-12 text-center shadow-xs">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto text-amber-500 text-3xl mb-4">
                <i class="fa-solid fa-store-slash"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Toko Terdaftar</h2>
            <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">
                Anda belum mendaftarkan toko Anda. Daftarkan toko Anda sekarang untuk ditampilkan di sistem rekomendasi.
            </p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('pemilik.toko.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm rounded-xl transition">
                    <i class="fa-solid fa-plus mr-1"></i> Daftarkan Toko Sekarang
                </a>
            </div>
        </div>
    @else
        {{-- Info jumlah toko --}}
        <div class="mb-6 px-5 py-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-amber-500 text-lg"></i>
                <p class="text-sm text-amber-800">
                    Kamu memiliki <strong>{{ $tokoSaya->count() }} toko</strong> terdaftar atas nama akun ini.
                </p>
            </div>
        </div>

        {{-- Daftar Toko --}}
        <div class="space-y-6">
            @foreach($tokoSaya as $toko)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    {{-- Toko Header Banner --}}
                    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-xl shrink-0">
                                <i class="fa-solid fa-shirt"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-white">{{ $toko->nama }}</h2>
                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[10px]"></i>
                                    {{ $toko->alamat }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('toko.show', $toko->id) }}" target="_blank" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition flex items-center gap-1">
                                <i class="fa-solid fa-eye text-[10px]"></i> Publik
                            </a>
                            <a href="{{ route('pemilik.toko.edit', $toko->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-lg transition flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                            </a>
                            <form action="{{ route('pemilik.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko {{ $toko->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition flex items-center gap-1">
                                    <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Toko Body --}}
                    <div class="p-6">
                        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                            {{ $toko->deskripsi ?? 'Belum ada deskripsi toko.' }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-lg font-extrabold text-amber-600">{{ $toko->nilaiAlternatif->count() }}</p>
                                <p class="text-[11px] font-semibold text-slate-500">Kriteria Ternilai</p>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $toko->jam_operasional ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-slate-500">Jam Operasional</p>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100 col-span-2 sm:col-span-1">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $toko->media_sosial ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-slate-500">Media Sosial</p>
                            </div>
                        </div>

                        {{-- Nilai Kriteria Toko --}}
                        @if($toko->nilaiAlternatif->count() > 0)
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Nilai Kriteria Toko</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($toko->nilaiAlternatif as $nilai)
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                        <p class="text-[11px] font-semibold text-slate-500 mb-1 truncate">{{ $nilai->kriteria->nama_kriteria }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-2 bg-amber-500 rounded-full" style="width: {{ $nilai->nilai }}%"></div>
                                            </div>
                                            <span class="font-extrabold text-xs text-slate-800 font-mono">{{ $nilai->nilai }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
