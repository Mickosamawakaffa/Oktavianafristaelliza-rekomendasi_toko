@extends('layouts.app')

@section('title', 'Kelola Data Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Kelola Data Toko</h1>
            <p class="text-xs text-slate-500">Tambah, ubah, dan hapus data toko fashion secondhand di Jember.</p>
        </div>
        <a href="{{ route('admin.toko.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Toko Baru
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Search Bar --}}
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('admin.toko.index') }}" method="GET" class="w-full sm:w-80 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari toko..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
            </form>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $tokos->total() }} Toko</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Nama Toko</th>
                        <th class="py-3.5 px-4">Status Data</th>
                        <th class="py-3.5 px-4">Alamat</th>
                        <th class="py-3.5 px-4">Jam Operasional</th>
                        <th class="py-3.5 px-4">Pemilik Toko</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tokos as $index => $toko)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-400">
                                {{ $tokos->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 block text-sm">{{ $toko->nama }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $toko->media_sosial ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->isKriteriaLengkap())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-[11px] font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold">
                                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Belum Lengkap
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-xs truncate">
                                {{ $toko->alamat }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    {{ $toko->jam_operasional ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->pemilik)
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-semibold text-[11px] flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-user text-[9px]"></i> {{ $toko->pemilik->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Belum ada</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('toko.show', $toko->id) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Lihat Publik">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.toko.edit', $toko->id) }}" class="p-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-800 transition" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-800 transition" title="Hapus Data">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">
                                Belum ada data toko. Klik tombol "Tambah Toko Baru" untuk membuat toko.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tokos->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tokos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
