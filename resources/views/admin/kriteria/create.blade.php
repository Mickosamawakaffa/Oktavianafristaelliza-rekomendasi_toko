@extends('layouts.app')

@section('title', 'Tambah Kriteria - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Tambah Kriteria Baru</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Tambahkan kriteria baru untuk penilaian SPK.</p>
        </div>
        <a href="{{ route('admin.kriteria.index') }}" class="text-xs font-semibold text-[#526071] hover:text-[#0F172A] flex items-center gap-1.5 min-h-[44px]">
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
        <form action="{{ route('admin.kriteria.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama Kriteria --}}
            <div>
                <label for="nama_kriteria" class="block text-xs font-semibold text-[#0F172A] mb-1.5">
                    Nama Kriteria <span class="text-[#991B1B]">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_kriteria" 
                    name="nama_kriteria" 
                    value="{{ old('nama_kriteria') }}" 
                    required 
                    placeholder="Contoh: Kualitas Produk, Harga, Ulasan" 
                    class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] bg-white text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E] focus:ring-1 focus:ring-[#0F766E] min-h-[44px]"
                />
            </div>

            {{-- Jenis --}}
            <div>
                <label class="block text-xs font-semibold text-[#0F172A] mb-2">
                    Jenis Kriteria <span class="text-[#991B1B]">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="benefit" {{ old('jenis', 'benefit') === 'benefit' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3.5 rounded-[12px] border-2 border-[#E2E8F0] bg-white peer-checked:border-[#0F766E] peer-checked:bg-[#E6F4F1] transition-all text-[#526071] peer-checked:text-[#0B5C56] flex items-center gap-2.5">
                            <div>
                                <span class="text-xs font-bold block text-[#0F172A]">Benefit</span>
                                <span class="text-[10px] text-[#526071] block">Nilai lebih tinggi lebih baik</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="jenis" value="cost" {{ old('jenis') === 'cost' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-3.5 rounded-[12px] border-2 border-[#E2E8F0] bg-white peer-checked:border-[#0F766E] peer-checked:bg-[#E6F4F1] transition-all text-[#526071] peer-checked:text-[#0B5C56] flex items-center gap-2.5">
                            <div>
                                <span class="text-xs font-bold block text-[#0F172A]">Cost</span>
                                <span class="text-[10px] text-[#526071] block">Nilai lebih rendah lebih baik</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-[#E2E8F0] flex justify-end gap-3">
                <a href="{{ route('admin.kriteria.index') }}" class="px-4 py-2.5 border border-[#CBD5E1] rounded-[10px] text-xs font-bold text-[#334155] hover:bg-[#F3F5F8] transition min-h-[44px] flex items-center">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[10px] shadow-sm transition min-h-[44px] flex items-center">
                    Simpan Kriteria
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
