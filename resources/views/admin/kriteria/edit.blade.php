@extends('layouts.app')

@section('title', 'Edit Kriteria - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Kriteria</h1>
            <p class="text-xs text-slate-500">Ubah kriteria <strong class="text-blue-700">{{ $kriteria->nama_kriteria }}</strong></p>
        </div>
        <a href="{{ route('admin.kriteria.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1">
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
        <form action="{{ route('admin.kriteria.update', $kriteria->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Kriteria --}}
            <div>
                <label for="nama_kriteria" class="block text-xs font-semibold text-slate-700 mb-1">Nama Kriteria <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    id="nama_kriteria" 
                    name="nama_kriteria" 
                    value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            {{-- Jenis --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Jenis Kriteria <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="benefit" {{ old('jenis', $kriteria->jenis) === 'benefit' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all text-slate-600 peer-checked:text-emerald-800 flex items-center gap-2">
                            <i class="fa-solid fa-arrow-trend-up text-emerald-600"></i>
                            <div>
                                <span class="text-xs font-bold block">Benefit</span>
                                <span class="text-[10px] text-slate-500 block">Nilai lebih tinggi lebih baik</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="cost" {{ old('jenis', $kriteria->jenis) === 'cost' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all text-slate-600 peer-checked:text-rose-800 flex items-center gap-2">
                            <i class="fa-solid fa-arrow-trend-down text-rose-600"></i>
                            <div>
                                <span class="text-xs font-bold block">Cost</span>
                                <span class="text-[10px] text-slate-500 block">Nilai lebih rendah lebih baik</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.kriteria.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
