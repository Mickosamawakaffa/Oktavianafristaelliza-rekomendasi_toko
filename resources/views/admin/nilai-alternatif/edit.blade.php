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
