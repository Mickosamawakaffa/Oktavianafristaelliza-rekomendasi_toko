@extends('layouts.app')

@section('title', 'Matriks Nilai Alternatif - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Matriks Nilai Alternatif Toko</h1>
            <p class="text-xs text-slate-500">Kelola dan inputkan nilai kriteria (skala 1–100) untuk tiap toko fashion secondhand.</p>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Matriks Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Nama Toko</th>
                        @foreach($kriterias as $k)
                            <th class="py-3.5 px-4 text-center min-w-[120px]">
                                {{ $k->nama_kriteria }}
                                <span class="block text-[9px] font-normal {{ $k->jenis === 'benefit' ? 'text-emerald-600' : 'text-rose-600' }}">({{ ucfirst($k->jenis) }})</span>
                            </th>
                        @endforeach
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tokos as $index => $toko)
                        @php
                            $nilaiMap = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-400">
                                {{ $tokos->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                {{ $toko->nama }}
                                <span class="block text-[11px] font-normal text-slate-400 truncate">{{ $toko->alamat }}</span>
                            </td>
                            @foreach($kriterias as $k)
                                @php
                                    $val = $nilaiMap[$k->id] ?? null;
                                @endphp
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-sm">
                                    @if($val !== null)
                                        <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $val }}
                                        </span>
                                    @else
                                        <span class="text-rose-400 text-xs italic font-normal">Belum diisi</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.nilai-alternatif.edit', $toko->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold text-xs inline-flex items-center gap-1.5 transition">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    Edit Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($kriterias) + 3 }}" class="py-8 text-center text-slate-400 italic">
                                Belum ada toko terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tokos->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $tokos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
