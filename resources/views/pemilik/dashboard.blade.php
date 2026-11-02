@extends('layouts.app')

@section('title', 'Dashboard Pemilik Toko - ThriftJember')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-[12px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shadow-soft">
                <svg class="w-6 h-6 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-[#0F172A]">Dashboard Pemilik Toko</h1>
                <p class="text-xs sm:text-sm text-[#526071]">Selamat datang, <span class="font-bold text-[#0F766E]">{{ Auth::user()->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('pemilik.toko.create') }}" 
           class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-xs sm:text-sm rounded-[10px] transition-colors shadow-soft flex items-center gap-2 min-h-[44px]">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Toko Baru</span>
        </a>
    </div>


    @if($tokoSaya->isEmpty())
        {{-- Jika Belum Punya Toko --}}
        <div class="bg-white rounded-[16px] border border-dashed border-[#CBD5E1] p-12 text-center shadow-soft">
            <div class="w-16 h-16 bg-[#E6F4F1] rounded-full flex items-center justify-center mx-auto text-[#0F766E] mb-4">
                <svg class="w-8 h-8 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-[#0F172A] mb-2">Belum Ada Toko Terdaftar</h2>
            <p class="text-sm text-[#526071] mb-6 max-w-sm mx-auto">
                Anda belum mendaftarkan toko thrift Anda. Daftarkan toko Anda agar dapat diverifikasi oleh Admin dan masuk ke sistem rekomendasi ROC-SMART.
            </p>
            <div class="flex items-center justify-center">
                <a href="{{ route('pemilik.toko.create') }}" 
                   class="px-5 py-2.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white font-bold text-sm rounded-[10px] transition-colors shadow-soft min-h-[44px] flex items-center gap-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Daftarkan Toko Sekarang</span>
                </a>
            </div>
        </div>
    @else
        {{-- Info Jumlah Toko --}}
        <div class="mb-6 px-5 py-3.5 rounded-[12px] bg-white border border-[#E2E8F0] shadow-soft flex items-center gap-3">
            <div class="w-8 h-8 rounded-[8px] bg-[#E6F4F1] text-[#0F766E] flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-xs sm:text-sm text-[#334155]">
                Anda memiliki <strong class="text-[#0F172A]">{{ $tokoSaya->count() }} toko</strong> terdaftar pada akun ini.
            </p>
        </div>

        {{-- Daftar Toko --}}
        <div class="space-y-6">
            @foreach($tokoSaya as $toko)
                <div class="bg-white rounded-[16px] border border-[#E2E8F0] shadow-soft overflow-hidden">
                    {{-- Toko Header Banner --}}
                    <div class="bg-[#0E2A3B] px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-white">
                        <div class="flex items-center gap-3.5 min-w-0">
                            @if($toko->gambar)
                                <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-12 h-12 rounded-[10px] object-cover border border-white/20 shadow-sm shrink-0" />
                            @else
                                <div class="w-12 h-12 rounded-[10px] bg-[#0F766E] text-white flex items-center justify-center font-bold text-lg shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                    <h2 class="text-base font-bold text-white truncate">{{ $toko->nama }}</h2>
                                    @if($toko->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#166534]">
                                            Disetujui
                                        </span>
                                    @elseif($toko->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF3C7] text-[#92400E]">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($toko->status === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FEF2F2] text-[#991B1B]">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-[#CBD5E1] truncate">{{ $toko->alamat }}</p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if($toko->status === 'approved')
                                <a href="{{ route('toko.show', $toko->id) }}" target="_blank" 
                                   class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-[8px] border border-white/20 transition-colors flex items-center gap-1.5 min-h-[36px]">
                                    <span>Lihat Publik</span>
                                </a>
                            @endif
                            <a href="{{ route('pemilik.toko.edit', $toko->id) }}" 
                               class="px-3.5 py-1.5 bg-[#0F766E] hover:bg-[#0B5C56] text-white text-xs font-bold rounded-[8px] transition-colors shadow-sm flex items-center gap-1.5 min-h-[36px]">
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('pemilik.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm(@js('Apakah Anda yakin ingin menghapus toko ' . $toko->nama . '?'))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#991B1B] text-xs font-bold rounded-[8px] border border-[#FECACA] transition-colors flex items-center gap-1 min-h-[36px]">
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Toko Body --}}
                    <div class="p-6">
                        @if($toko->status === 'rejected')
                            <div class="mb-5 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-xs space-y-2">
                                <p class="font-bold">Pengajuan Toko Ini Ditolak oleh Admin</p>
                                <p><strong>Catatan:</strong> {{ $toko->catatan_verifikasi ?? 'Data toko belum memenuhi syarat.' }}</p>
                                <p class="text-[#526071]">Silakan perbaiki data toko Anda lalu simpan ulang agar dapat diverifikasi kembali.</p>
                                <a href="{{ route('pemilik.toko.edit', $toko->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#991B1B] hover:bg-[#7f1d1d] text-white font-bold rounded-[8px] text-xs transition">
                                    <span>Perbaiki & Ajukan Ulang</span>
                                </a>
                            </div>
                        @elseif($toko->status === 'pending')
                            <div class="mb-5 p-4 rounded-[12px] bg-[#FEF3C7] border border-[#FDE68A] text-[#92400E] text-xs">
                                <p class="font-bold mb-1">Sedang Menunggu Verifikasi Admin</p>
                                <p class="text-[#78350F]">Admin sedang meninjau informasi toko Anda. Setelah disetujui, toko Anda akan langsung masuk ke sistem rekomendasi.</p>
                            </div>
                        @endif

                        <p class="text-xs sm:text-sm text-[#334155] mb-5 leading-relaxed">
                            {{ $toko->deskripsi ?? 'Belum ada deskripsi toko.' }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center p-3.5 bg-[#F8FAFC] rounded-[12px] border border-[#E2E8F0]">
                                @if($toko->status !== 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#F3F5F8] text-[#526071] mb-1">
                                        Menunggu Approval
                                    </span>
                                    <p class="text-[10px] text-[#526071] text-center">Dinilai setelah disetujui</p>
                                @elseif($toko->nilai_alternatif_count >= $totalKriteria && $totalKriteria > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#DCFCE7] text-[#166534] mb-1">
                                        Penilaian Lengkap
                                    </span>
                                    <p class="text-[10px] text-[#526071] text-center">{{ $toko->nilai_alternatif_count }} dari {{ $totalKriteria }} kriteria</p>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#FEF3C7] text-[#92400E] mb-1">
                                        Proses Penilaian
                                    </span>
                                    <p class="text-[10px] text-[#526071] text-center">Menunggu penilaian kriteria</p>
                                @endif
                            </div>
                            <div class="text-center p-3.5 bg-[#F8FAFC] rounded-[12px] border border-[#E2E8F0]">
                                <p class="text-sm font-bold text-[#0F172A] truncate">{{ $toko->jam_operasional ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-[#526071]">Jam Operasional</p>
                            </div>
                            <div class="text-center p-3.5 bg-[#F8FAFC] rounded-[12px] border border-[#E2E8F0] col-span-2 sm:col-span-1">
                                <p class="text-sm font-bold text-[#0F172A] truncate">{{ $toko->media_sosial ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-[#526071]">Media Sosial</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
