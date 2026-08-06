@extends('layouts.app')

@section('title', 'Edit Toko Saya - Pemilik Toko')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Edit Toko Saya</h1>
            <p class="text-xs text-slate-500">Ubah informasi untuk toko <strong class="text-amber-700">{{ $toko->nama }}</strong></p>
        </div>
        <a href="{{ route('pemilik.dashboard') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
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
        <form action="{{ route('pemilik.toko.update', $toko->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Toko --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Toko <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama', $toko->nama) }}" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                />
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap Toko <span class="text-rose-500">*</span></label>
                <textarea 
                    id="alamat" 
                    name="alamat" 
                    rows="2" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                >{{ old('alamat', $toko->alamat) }}</textarea>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Toko</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="3" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                >{{ old('deskripsi', $toko->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Media Sosial --}}
                <div>
                    <label for="media_sosial" class="block text-xs font-semibold text-slate-700 mb-1">Media Sosial (Instagram)</label>
                    <input 
                        type="text" 
                        id="media_sosial" 
                        name="media_sosial" 
                        value="{{ old('media_sosial', $toko->media_sosial) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                    />
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <label for="jam_operasional" class="block text-xs font-semibold text-slate-700 mb-1">Jam Operasional</label>
                    <input 
                        type="text" 
                        id="jam_operasional" 
                        name="jam_operasional" 
                        value="{{ old('jam_operasional', $toko->jam_operasional) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Latitude --}}
                <div>
                    <label for="latitude" class="block text-xs font-semibold text-slate-700 mb-1">Latitude</label>
                    <input 
                        type="text" 
                        id="latitude" 
                        name="latitude" 
                        value="{{ old('latitude', $toko->latitude) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500"
                    />
                </div>

                {{-- Longitude --}}
                <div>
                    <label for="longitude" class="block text-xs font-semibold text-slate-700 mb-1">Longitude</label>
                    <input 
                        type="text" 
                        id="longitude" 
                        name="longitude" 
                        value="{{ old('longitude', $toko->longitude) }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-amber-500"
                    />
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('pemilik.dashboard') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
