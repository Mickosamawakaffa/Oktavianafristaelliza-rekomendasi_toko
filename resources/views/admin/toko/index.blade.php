@extends('layouts.app')

@section('title', 'Kelola Data Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A]">Kelola & Verifikasi Toko</h1>
            <p class="text-xs sm:text-sm text-[#526071]">Tinjau permohonan toko baru, verifikasi data, dan kelola seluruh toko di Jember.</p>
        </div>
        <a href="{{ route('admin.toko.create') }}" 
           class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-xs sm:text-sm rounded-[10px] shadow-sm transition-colors flex items-center gap-2 min-h-[44px]">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Toko Baru</span>
        </a>
    </div>


    {{-- Status Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 mb-5">
        @php
            $currentStatus = request('status', 'all');
        @endphp
        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
           class="px-3.5 py-2 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 min-h-[40px]
           {{ $currentStatus === 'all' ? 'bg-[#0F766E] text-white shadow-sm' : 'bg-white text-[#526071] border border-[#CBD5E1] hover:bg-[#F3F5F8]' }}">
            <span>Semua Toko</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'all' ? 'bg-[#0B5C56] text-white' : 'bg-[#F3F5F8] text-[#526071]' }}">{{ $countAll }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
           class="px-3.5 py-2 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 min-h-[40px]
           {{ $currentStatus === 'pending' ? 'bg-[#0F766E] text-white shadow-sm' : 'bg-white text-[#92400E] border border-[#CBD5E1] hover:bg-[#FEF3C7]/30' }}">
            <span>Menunggu Verifikasi</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'pending' ? 'bg-[#0B5C56] text-white' : 'bg-[#FEF3C7] text-[#92400E] font-extrabold' }}">{{ $countPending }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}"
           class="px-3.5 py-2 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 min-h-[40px]
           {{ $currentStatus === 'approved' ? 'bg-[#0F766E] text-white shadow-sm' : 'bg-white text-[#166534] border border-[#CBD5E1] hover:bg-[#DCFCE7]/30' }}">
            <span>Disetujui / Aktif</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'approved' ? 'bg-[#0B5C56] text-white' : 'bg-[#DCFCE7] text-[#166534]' }}">{{ $countApproved }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}"
           class="px-3.5 py-2 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 min-h-[40px]
           {{ $currentStatus === 'rejected' ? 'bg-[#0F766E] text-white shadow-sm' : 'bg-white text-[#991B1B] border border-[#CBD5E1] hover:bg-[#FEF2F2]/30' }}">
            <span>Ditolak</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $currentStatus === 'rejected' ? 'bg-[#0B5C56] text-white' : 'bg-[#FEF2F2] text-[#991B1B]' }}">{{ $countRejected }}</span>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
        {{-- Search Bar --}}
        <div class="p-4 border-b border-[#E2E8F0] bg-[#F8FAFC] flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('admin.toko.index') }}" method="GET" class="w-full sm:w-80 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#526071]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari toko berdasarkan nama / alamat..." 
                    class="w-full pl-9 pr-4 py-2 rounded-[10px] bg-white border border-[#CBD5E1] text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E]"
                />
            </form>
            <span class="text-xs text-[#526071] font-medium">Menampilkan {{ $tokos->count() }} dari {{ $tokos->total() }} Toko</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#334155]">
                <thead class="bg-[#F8FAFC] text-[#526071] font-bold uppercase tracking-wider text-[10px] border-b border-[#E2E8F0]">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Toko</th>
                        <th class="py-3.5 px-4">Status Verifikasi</th>
                        <th class="py-3.5 px-4">Status Nilai SPK</th>
                        <th class="py-3.5 px-4">Alamat & GPS</th>
                        <th class="py-3.5 px-4">Pemilik Toko</th>
                        <th class="py-3.5 px-4 text-center">Aksi & Approval</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($tokos as $index => $toko)
                        <tr class="hover:bg-[#F8FAFC] transition-colors">
                            <td class="py-3.5 px-4 font-bold text-[#526071]">
                                {{ $tokos->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($toko->gambar)
                                        <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-10 h-10 rounded-[8px] object-cover border border-[#E2E8F0] shrink-0" />
                                    @else
                                        <div class="w-10 h-10 rounded-[8px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center font-bold shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-[#0F172A] block text-sm">{{ $toko->nama }}</span>
                                        <span class="text-[11px] text-[#526071] font-mono">{{ $toko->media_sosial ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#166534] text-[11px] font-bold">
                                        Disetujui
                                    </span>
                                @elseif($toko->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FEF3C7] text-[#92400E] text-[11px] font-bold">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($toko->status === 'rejected')
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FEF2F2] text-[#991B1B] text-[11px] font-bold mb-1">
                                            Ditolak
                                        </span>
                                        @if($toko->catatan_verifikasi)
                                            <p class="text-[10px] text-[#991B1B] italic max-w-xs leading-tight">"{{ $toko->catatan_verifikasi }}"</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->isKriteriaLengkap())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#166534] text-[11px] font-semibold">
                                        Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#F3F5F8] text-[#526071] border border-[#E2E8F0] text-[11px] font-semibold">
                                        Belum Lengkap
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="truncate text-[#0F172A] font-medium">{{ $toko->alamat }}</p>
                                @if($toko->latitude && $toko->longitude)
                                    <span class="text-[10px] font-mono text-[#526071] block mt-0.5">
                                        {{ $toko->latitude }}, {{ $toko->longitude }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->pemilik)
                                    <span class="px-2 py-0.5 rounded-full bg-[#E6F4F1] text-[#0B5C56] font-semibold text-[11px] inline-block">
                                        {{ $toko->pemilik->name }}
                                    </span>
                                @else
                                    <span class="text-[#526071] italic text-[11px]">Dibuat Admin</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    @if($toko->status === 'pending' || $toko->status === 'rejected')
                                        <form action="{{ route('admin.toko.approve', $toko->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-2.5 py-1 rounded-[8px] bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-[11px] transition shadow-xs flex items-center gap-1 min-h-[32px]" 
                                                    title="Setujui Toko">
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                    @endif

                                    @if($toko->status === 'pending' || $toko->status === 'approved')
                                        <button 
                                            type="button" 
                                            onclick="openRejectModal({{ $toko->id }}, '{{ addslashes($toko->nama) }}')" 
                                            class="px-2.5 py-1 rounded-[8px] bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA] font-bold text-[11px] transition flex items-center gap-1 min-h-[32px]" 
                                            title="Tolak Permohonan"
                                        >
                                            <span>Tolak</span>
                                        </button>
                                    @endif

                                    <a href="{{ route('toko.show', $toko->id) }}" target="_blank" 
                                       class="p-2 rounded-[8px] bg-[#F3F5F8] hover:bg-[#E2E8F0] text-[#334155] transition min-h-[32px] flex items-center justify-center" 
                                       title="Lihat Publik">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.toko.edit', $toko->id) }}" 
                                       class="p-2 rounded-[8px] bg-[#E6F4F1] hover:bg-[#d0ece5] text-[#0F766E] transition min-h-[32px] flex items-center justify-center" 
                                       title="Edit Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-[8px] bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#991B1B] transition min-h-[32px] flex items-center justify-center" 
                                                title="Hapus Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-[#526071] italic">
                                Tidak ada data toko pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tokos->hasPages())
            <div class="p-4 border-t border-[#E2E8F0]">
                {{ $tokos->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Tolak Toko --}}
<div id="modal-reject" class="fixed inset-0 z-50 bg-[#0F172A]/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-[16px] max-w-md w-full p-6 shadow-[0_8px_24px_rgba(15,23,42,0.12)] border border-[#E2E8F0]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-extrabold text-[#0F172A]">
                Tolak Permohonan Toko
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-[#526071] hover:text-[#0F172A]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-reject" method="POST" action="">
            @csrf
            <p class="text-xs text-[#526071] mb-3">
                Anda akan menolak pengajuan untuk toko <strong id="reject-toko-name" class="text-[#991B1B]"></strong>. Berikan catatan agar pemilik dapat memperbaikinya.
            </p>

            <div class="mb-4">
                <label for="catatan_verifikasi" class="block text-xs font-semibold text-[#0F172A] mb-1">
                    Alasan Penolakan <span class="text-[#991B1B]">*</span>
                </label>
                <textarea 
                    id="catatan_verifikasi" 
                    name="catatan_verifikasi" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Foto fisik toko kurang jelas atau lokasi GPS belum tepat."
                    class="w-full px-3.5 py-2.5 rounded-[10px] border border-[#CBD5E1] text-xs text-[#0F172A] focus:outline-none focus:border-[#0F766E]"
                ></textarea>
            </div>

            <div class="flex justify-end gap-2.5">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-[#CBD5E1] rounded-[10px] text-xs font-bold text-[#334155] hover:bg-[#F3F5F8] transition min-h-[40px]">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-[#991B1B] hover:bg-[#7f1d1d] text-white rounded-[10px] text-xs font-bold transition shadow-sm min-h-[40px]">
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRejectModal(tokoId, tokoNama) {
        const modal = document.getElementById('modal-reject');
        const form = document.getElementById('form-reject');
        const nameElem = document.getElementById('reject-toko-name');

        form.action = `/admin/toko/${tokoId}/reject`;
        nameElem.textContent = `"${tokoNama}"`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        const modal = document.getElementById('modal-reject');
        modal.classList.add('hidden');
    }
</script>
@endpush
