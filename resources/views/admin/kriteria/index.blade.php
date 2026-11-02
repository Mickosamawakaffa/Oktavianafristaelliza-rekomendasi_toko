@extends('layouts.app')

@section('title', 'Kelola Data Kriteria - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Kelola Data Kriteria</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Daftar kriteria yang digunakan dalam perhitungan SPK (metode ROC & SMART).</p>
        </div>
        <a href="{{ route('admin.kriteria.create') }}" 
           class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-xs sm:text-sm rounded-[10px] shadow-sm transition-colors flex items-center gap-2 min-h-[44px]">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Kriteria Baru</span>
        </a>
    </div>


    {{-- Table Card --}}
    <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#334155]">
                <thead class="bg-[#F8FAFC] text-[#526071] font-bold uppercase tracking-wider text-[10px] border-b border-[#E2E8F0]">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Nama Kriteria</th>
                        <th class="py-3.5 px-4">Jenis (Type)</th>
                        <th class="py-3.5 px-4">Nilai Alternatif Terisi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($kriterias as $index => $k)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="py-3.5 px-4 font-bold text-[#526071]">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#0F172A] text-sm">
                                {{ $k->nama_kriteria }}
                                @if(array_key_exists($k->nama_kriteria, \App\Http\Controllers\Admin\KriteriaAdminController::KRITERIA_INTI))
                                    <span class="ml-1.5 px-2 py-0.5 rounded-full bg-[#E6F4F1] text-[#0B5C56] font-bold text-[10px] align-middle" title="Dihitung otomatis dari GPS/ulasan. Nama dan jenis dikunci.">Kriteria inti</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($k->jenis === 'benefit')
                                    <span class="px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#166534] font-bold text-[11px] inline-flex items-center gap-1">
                                        Benefit
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-[#FEF2F2] text-[#991B1B] font-bold text-[11px] inline-flex items-center gap-1">
                                        Cost
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#526071]">
                                {{ $k->nilai_alternatif_count }} nilai toko
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if(array_key_exists($k->nama_kriteria, \App\Http\Controllers\Admin\KriteriaAdminController::KRITERIA_INTI))
                                    <span class="text-[11px] font-semibold text-[#526071] italic">Dikunci sistem</span>
                                @else
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.kriteria.edit', $k->id) }}" 
                                       class="p-2 rounded-[8px] bg-[#E6F4F1] hover:bg-[#d0ece5] text-[#0F766E] transition min-h-[32px] flex items-center justify-center" 
                                       title="Edit Kriteria">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.kriteria.destroy', $k->id) }}" method="POST" onsubmit="return confirm(@js('Apakah Anda yakin ingin menghapus kriteria ' . $k->nama_kriteria . '?'))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-[8px] bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#991B1B] transition min-h-[32px] flex items-center justify-center" 
                                                title="Hapus Kriteria">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#526071] italic">
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
