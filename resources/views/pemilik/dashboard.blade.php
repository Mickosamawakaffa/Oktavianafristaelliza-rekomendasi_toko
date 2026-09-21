@extends('layouts.app')

@section('title', 'Dashboard Pemilik Toko')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-orange-400 text-slate-900 flex items-center justify-center shadow-md">
                <i class="fa-solid fa-store text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Dashboard Pemilik Toko</h1>
                <p class="text-sm text-slate-500">Selamat datang, <span class="font-semibold text-amber-600">{{ Auth::user()->name }}</span></p>
            </div>
        </div>
        <a href="{{ route('pemilik.toko.create') }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl transition shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Toko Baru
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    @if($tokoSaya->isEmpty())
        {{-- Jika belum punya toko terdaftar --}}
        <div class="bg-white rounded-2xl border border-dashed border-amber-300 p-12 text-center shadow-xs">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto text-amber-500 text-3xl mb-4">
                <i class="fa-solid fa-store-slash"></i>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Toko Terdaftar</h2>
            <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">
                Anda belum mendaftarkan toko Anda. Daftarkan toko Anda sekarang untuk diverifikasi oleh Admin dan direkomendasikan kepada para pemburu fashion thrift.
            </p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('pemilik.toko.create') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-plus mr-1"></i> Daftarkan Toko Sekarang
                </a>
            </div>
        </div>
    @else
        {{-- Info jumlah toko --}}
        <div class="mb-6 px-5 py-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-info text-amber-500 text-lg"></i>
                <p class="text-sm text-amber-800">
                    Kamu memiliki <strong>{{ $tokoSaya->count() }} toko</strong> terdaftar pada akun ini.
                </p>
            </div>
        </div>

        {{-- Daftar Toko --}}
        <div class="space-y-6">
            @foreach($tokoSaya as $toko)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    {{-- Toko Header Banner --}}
                    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-3.5">
                            @if($toko->gambar)
                                <img src="{{ asset('storage/' . $toko->gambar) }}" alt="{{ $toko->nama }}" class="w-12 h-12 rounded-xl object-cover border border-white/20 shadow-md shrink-0" />
                            @else
                                <div class="w-12 h-12 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-xl shrink-0">
                                    <i class="fa-solid fa-store"></i>
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <h2 class="text-base font-extrabold text-white">{{ $toko->nama }}</h2>
                                    {{-- Status Verifikasi Badge --}}
                                    @if($toko->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Disetujui
                                        </span>
                                    @elseif($toko->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center gap-1">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Menunggu Verifikasi
                                        </span>
                                    @elseif($toko->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-xmark text-[9px]"></i> Ditolak
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-300 flex items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-[10px] text-emerald-400"></i>
                                    {{ $toko->alamat }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2">
                            @if($toko->status === 'approved')
                                <a href="{{ route('toko.show', $toko->id) }}" target="_blank" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition flex items-center gap-1">
                                    <i class="fa-solid fa-eye text-[10px]"></i> Lihat Publik
                                </a>
                            @endif
                            <a href="{{ route('pemilik.toko.edit', $toko->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-lg transition flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                            </a>
                            <form action="{{ route('pemilik.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko {{ $toko->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg transition flex items-center gap-1">
                                    <i class="fa-solid fa-trash text-[10px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Toko Body --}}
                    <div class="p-6">
                        {{-- Alert jika status REJECTED --}}
                        @if($toko->status === 'rejected')
                            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-triangle-exclamation text-rose-500 text-lg mt-0.5 shrink-0"></i>
                                    <div class="text-xs">
                                        <p class="font-bold mb-1">Pengajuan Toko Ini Ditolak oleh Admin</p>
                                        <p class="text-rose-700 mb-2"><strong>Alasan Penolakan:</strong> "{{ $toko->catatan_verifikasi ?? 'Data toko belum memenuhi syarat.' }}"</p>
                                        <p class="text-slate-600 mb-3">Silakan perbaiki informasi toko Anda (seperti foto plang toko, alamat, atau media sosial) lalu simpan ulang agar dapat ditinjau kembali oleh Admin.</p>
                                        <a href="{{ route('pemilik.toko.edit', $toko->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-xs transition">
                                            <i class="fa-solid fa-pen-to-square"></i> Perbaiki & Ajukan Ulang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif($toko->status === 'pending')
                            <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3">
                                <i class="fa-solid fa-hourglass-half text-amber-500 text-lg mt-0.5 shrink-0 animate-spin"></i>
                                <div class="text-xs">
                                    <p class="font-bold mb-0.5">Sedang Menunggu Verifikasi Admin</p>
                                    <p class="text-amber-800">Admin sedang memeriksa data toko Anda. Pastikan media sosial Instagram dan kontak toko Anda dapat dihubungi. Setelah disetujui, toko Anda akan langsung masuk ke sistem rekomendasi.</p>
                                </div>
                            </div>
                        @endif

                        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
                            {{ $toko->deskripsi ?? 'Belum ada deskripsi toko.' }}
                        </p>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                            {{-- Status Penilaian Kriteria --}}
                            <div class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                @if($toko->status !== 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200 mb-1">
                                        <i class="fa-solid fa-lock text-[10px]"></i> Menunggu Approval
                                    </span>
                                    <p class="text-[10px] text-slate-400 text-center">Dinilai setelah disetujui</p>
                                @elseif($toko->nilai_alternatif_count >= $totalKriteria && $totalKriteria > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 mb-1">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Penilaian Lengkap
                                    </span>
                                    <p class="text-[10px] text-slate-400 text-center">{{ $toko->nilai_alternatif_count }} dari {{ $totalKriteria }} kriteria</p>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 border border-amber-200 mb-1">
                                        <i class="fa-solid fa-clock text-[10px]"></i> Proses Penilaian
                                    </span>
                                    <p class="text-[10px] text-slate-400 text-center">Menunggu penilaian kriteria</p>
                                @endif
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $toko->jam_operasional ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-slate-500">Jam Operasional</p>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded-xl border border-slate-100 col-span-2 sm:col-span-1">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $toko->media_sosial ?? '-' }}</p>
                                <p class="text-[11px] font-semibold text-slate-500">Media Sosial</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
