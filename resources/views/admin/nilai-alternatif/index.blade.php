@extends('layouts.app')

@section('title', 'Matriks Nilai Alternatif - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Matriks Nilai Alternatif Toko</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Kelola dan inputkan nilai kriteria (skala 1–100) untuk tiap toko fashion secondhand.</p>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-[12px] bg-[#DCFCE7] border border-[#BBF7D0] text-[#166534] text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-[#16A34A] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Matriks Table Card --}}
    <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#334155]">
                <thead class="bg-[#F8FAFC] text-[#526071] font-bold uppercase tracking-wider text-[10px] border-b border-[#E2E8F0]">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Nama Toko</th>
                        @foreach($kriterias as $k)
                            <th class="py-3.5 px-4 text-center min-w-[120px]">
                                {{ $k->nama_kriteria }}
                                <span class="block text-[9px] font-normal {{ $k->jenis === 'benefit' ? 'text-[#0F766E]' : 'text-[#991B1B]' }}">
                                    ({{ ucfirst($k->jenis) }})
                                </span>
                            </th>
                        @endforeach
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($tokos as $index => $toko)
                        @php
                            $nilaiMap = $toko->nilaiAlternatif->pluck('nilai', 'kriteria_id')->toArray();
                        @endphp
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="py-3.5 px-4 font-bold text-[#526071]">
                                {{ $tokos->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#0F172A] text-sm">
                                <div class="flex items-center gap-2">
                                    <span>{{ $toko->nama }}</span>
                                    @if($toko->isKriteriaLengkap())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534] text-[10px] font-bold">
                                            Lengkap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#FEF3C7] text-[#92400E] text-[10px] font-bold">
                                            Belum Lengkap
                                        </span>
                                    @endif
                                </div>
                                <span class="block text-[11px] font-normal text-[#526071] truncate">{{ $toko->alamat }}</span>
                            </td>
                            @foreach($kriterias as $k)
                                @php
                                    $val = $nilaiMap[$k->id] ?? null;
                                @endphp
                                <td class="py-3.5 px-4 text-center font-bold text-sm">
                                    @if($val !== null)
                                        <span class="px-2.5 py-1 rounded-[8px] bg-[#F8FAFC] text-[#0F172A] border border-[#E2E8F0]">
                                            {{ number_format($val, 1, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-[#991B1B] text-xs italic font-normal">Belum diisi</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.nilai-alternatif.edit', $toko->id) }}" 
                                   class="px-3.5 py-1.5 rounded-[8px] bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-xs inline-flex items-center gap-1.5 transition-colors shadow-sm min-h-[32px]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span>Edit Nilai</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($kriterias) + 3 }}" class="py-8 text-center text-[#526071] italic">
                                Belum ada toko terdaftar di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tokos->hasPages())
            <div class="p-4 border-t border-[#E2E8F0]">
                {{ $tokos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
