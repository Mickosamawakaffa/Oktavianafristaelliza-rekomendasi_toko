@extends('layouts.app')

@section('title', 'Tambah Toko Baru - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Tambah Toko Baru</h1>
            <p class="text-xs text-slate-500">Isi formulir di bawah ini untuk menambahkan toko ke sistem.</p>
        </div>
        <a href="{{ route('admin.toko.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1">
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
        <form action="{{ route('admin.toko.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama Toko --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Toko <span class="text-rose-500">*</span></label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}" 
                    required 
                    placeholder="Contoh: Thrift Vintage Jember" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea 
                    id="alamat" 
                    name="alamat" 
                    rows="2" 
                    required 
                    placeholder="Jl. Kalimantan No. 12, Sumbersari, Jember" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >{{ old('alamat') }}</textarea>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Toko</label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="3" 
                    placeholder="Penjelasan produk yang dijual, keunggulan toko, dll." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Media Sosial --}}
                <div>
                    <label for="media_sosial" class="block text-xs font-semibold text-slate-700 mb-1">Media Sosial (Instagram)</label>
                    <input 
                        type="text" 
                        id="media_sosial" 
                        name="media_sosial" 
                        value="{{ old('media_sosial') }}" 
                        placeholder="@thriftvintage_jbr" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <label for="jam_operasional" class="block text-xs font-semibold text-slate-700 mb-1">Jam Operasional</label>
                    <input 
                        type="text" 
                        id="jam_operasional" 
                        name="jam_operasional" 
                        value="{{ old('jam_operasional') }}" 
                        placeholder="10:00 - 21:00" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
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
                        value="{{ old('latitude', '-8.1695') }}" 
                        placeholder="-8.1695" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                {{-- Longitude --}}
                <div>
                    <label for="longitude" class="block text-xs font-semibold text-slate-700 mb-1">Longitude</label>
                    <input 
                        type="text" 
                        id="longitude" 
                        name="longitude" 
                        value="{{ old('longitude', '113.7152') }}" 
                        placeholder="113.7152" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>
            </div>

            {{-- Pemilik Toko --}}
            <div>
                <label for="pemilik_id" class="block text-xs font-semibold text-slate-700 mb-1">Pemilik Toko (User Terdaftar)</label>
                <select 
                    id="pemilik_id" 
                    name="pemilik_id" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >
                    <option value="">-- Belum ada pemilik --</option>
                    @foreach($pemiliks as $p)
                        <option value="{{ $p->id }}" {{ old('pemilik_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.toko.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                    Simpan Toko
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
