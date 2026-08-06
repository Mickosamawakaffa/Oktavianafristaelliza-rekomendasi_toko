@extends('layouts.app')

@section('title', 'Kelola Data Kriteria - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Kelola Data Kriteria</h1>
            <p class="text-xs text-slate-500">Daftar kriteria yang digunakan dalam perhitungan SPK (ROC & SMART).</p>
        </div>
        <a href="{{ route('admin.kriteria.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Kriteria Baru
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
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Nama Kriteria</th>
                        <th class="py-3.5 px-4">Jenis (Type)</th>
                        <th class="py-3.5 px-4">Nilai Alternatif Terisi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kriterias as $index => $k)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                {{ $k->nama_kriteria }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($k->jenis === 'benefit')
                                    <span class="px-2.5 py-1 rounded bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold text-[11px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-trend-up text-[9px]"></i> Benefit
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded bg-rose-100 text-rose-800 border border-rose-200 font-bold text-[11px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-arrow-trend-down text-[9px]"></i> Cost
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono font-semibold text-slate-600">
                                {{ $k->nilai_alternatif_count }} nilai toko
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.kriteria.edit', $k->id) }}" class="p-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-800 transition" title="Edit Kriteria">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria {{ $k->nama_kriteria }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-800 transition" title="Hapus Kriteria">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">
                                Belum ada data kriteria. Klik tombol "Tambah Kriteria Baru".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
