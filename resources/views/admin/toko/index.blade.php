@extends('layouts.app')

@section('title', 'Kelola Data Toko - Admin')

@section('content')
@include('admin.partials.nav')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Kelola & Verifikasi Toko</h1>
            <p class="text-xs text-slate-500">Tinjau permohonan toko baru, verifikasi data, dan kelola seluruh toko di Jember.</p>
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
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-xmark text-rose-500"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Status Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 mb-4">
        @php
            $currentStatus = request('status', 'all');
        @endphp
        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentStatus === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            <span>Semua Toko</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $currentStatus === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $countAll }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-amber-700 border border-amber-200 hover:bg-amber-50' }}">
            <i class="fa-solid fa-clock-rotate-left text-[11px]"></i>
            <span>Menunggu Verifikasi</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $currentStatus === 'pending' ? 'bg-amber-700 text-white' : 'bg-amber-100 text-amber-800 font-extrabold' }}">{{ $countPending }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentStatus === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50' }}">
            <i class="fa-solid fa-circle-check text-[11px]"></i>
            <span>Disetujui / Aktif</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $currentStatus === 'approved' ? 'bg-emerald-800 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $countApproved }}</span>
        </a>

        <a href="{{ route('admin.toko.index', array_merge(request()->except('status', 'page'), ['status' => 'rejected'])) }}"
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $currentStatus === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-rose-700 border border-rose-200 hover:bg-rose-50' }}">
            <i class="fa-solid fa-circle-xmark text-[11px]"></i>
            <span>Ditolak</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $currentStatus === 'rejected' ? 'bg-rose-800 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $countRejected }}</span>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        {{-- Search Bar --}}
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-3">
            <form action="{{ route('admin.toko.index') }}" method="GET" class="w-full sm:w-80 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari toko berdasarkan nama / alamat..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"
                />
            </form>
            <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $tokos->count() }} dari {{ $tokos->total() }} Toko</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-[10px] border-b border-slate-200">
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
                <tbody class="divide-y divide-slate-100">
                    @forelse($tokos as $index => $toko)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-400">
                                {{ $tokos->firstItem() + $index }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($toko->gambar)
                                        <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shadow-2xs shrink-0" />
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold shrink-0 border border-emerald-100">
                                            <i class="fa-solid fa-store"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-bold text-slate-900 block text-sm">{{ $toko->nama }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">{{ $toko->media_sosial ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 text-[11px] font-bold">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui
                                    </span>
                                @elseif($toko->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-300 text-[11px] font-bold animate-pulse">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Verifikasi
                                    </span>
                                @elseif($toko->status === 'rejected')
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 border border-rose-200 text-[11px] font-bold mb-1">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                        </span>
                                        @if($toko->catatan_verifikasi)
                                            <p class="text-[10px] text-rose-600 italic max-w-xs leading-tight">"{{ $toko->catatan_verifikasi }}"</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->isKriteriaLengkap())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                                        <i class="fa-solid fa-check text-[9px]"></i> 6 Kriteria Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200 text-[11px] font-semibold">
                                        <i class="fa-solid fa-circle-minus text-[9px]"></i> Belum Lengkap
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="truncate text-slate-700 font-medium">{{ $toko->alamat }}</p>
                                @if($toko->latitude && $toko->longitude)
                                    <span class="text-[10px] font-mono text-slate-400 block mt-0.5">
                                        <i class="fa-solid fa-location-dot text-emerald-500"></i> {{ $toko->latitude }}, {{ $toko->longitude }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($toko->pemilik)
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-semibold text-[11px] flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-user text-[9px]"></i> {{ $toko->pemilik->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Dibuat Admin</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                    {{-- Tombol Approval / Reject Cepat --}}
                                    @if($toko->status === 'pending' || $toko->status === 'rejected')
                                        <form action="{{ route('admin.toko.approve', $toko->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition shadow-xs flex items-center gap-1" title="Setujui Toko">
                                                <i class="fa-solid fa-check"></i> Setujui
                                            </button>
                                        </form>
                                    @endif

                                    @if($toko->status === 'pending' || $toko->status === 'approved')
                                        <button 
                                            type="button" 
                                            onclick="openRejectModal({{ $toko->id }}, '{{ addslashes($toko->nama) }}')" 
                                            class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[11px] transition flex items-center gap-1" 
                                            title="Tolak Permohonan"
                                        >
                                            <i class="fa-solid fa-xmark"></i> Tolak
                                        </button>
                                    @endif

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
                            <td colspan="7" class="py-10 text-center text-slate-400 italic">
                                <i class="fa-solid fa-inbox text-3xl text-slate-300 block mb-2"></i>
                                Tidak ada data toko pada filter ini.
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

{{-- Modal Tolak Toko --}}
<div id="modal-reject" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-rose-500"></i>
                Tolak Permohonan Toko
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="form-reject" method="POST" action="">
            @csrf
            <p class="text-xs text-slate-600 mb-3">
                Anda akan menolak pengajuan untuk toko <strong id="reject-toko-name" class="text-rose-700"></strong>. Masukkan alasan agar pemilik toko dapat memperbaiki datanya.
            </p>

            <div class="mb-4">
                <label for="catatan_verifikasi" class="block text-xs font-semibold text-slate-700 mb-1">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="catatan_verifikasi" 
                    name="catatan_verifikasi" 
                    rows="3" 
                    required 
                    placeholder="Contoh: Foto fisik toko kurang jelas / Akun Instagram tidak aktif / Titik koordinat GPS tidak valid."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-rose-500"
                ></textarea>
            </div>

            <div class="flex justify-end gap-2.5">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
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
