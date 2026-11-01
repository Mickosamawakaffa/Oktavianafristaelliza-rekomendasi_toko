<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Masuk / Daftar') - ThriftJember</title>
    @include('layouts.partials.head')
</head>
<body class="bg-page text-body min-h-screen antialiased selection:bg-primary-tint selection:text-primary">

    {{-- Notifikasi global (status reset password, error, dll.) --}}
    @include('layouts.partials.flash')

    <div class="min-h-screen lg:grid lg:grid-cols-2">

        {{-- Panel merek (hanya tampil di layar lebar) --}}
        <aside class="hidden lg:flex flex-col justify-between bg-hero text-white p-12 xl:p-16 relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-hero-accent/10 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-32 -left-24 w-96 h-96 rounded-full bg-primary/30 blur-3xl" aria-hidden="true"></div>

            <a href="{{ route('toko.index') }}" class="relative flex items-center gap-3 w-fit">
                <div class="w-11 h-11 rounded-btn bg-primary flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-xl font-extrabold tracking-tight">ThriftJember</span>
            </a>

            <div class="relative max-w-md">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight mb-4">
                    Temukan toko thrift <span class="text-hero-accent">terbaik</span> di Jember.
                </h2>
                <p class="text-[#CBD5E1] leading-relaxed mb-8">
                    Rekomendasi objektif berdasarkan prioritas Anda sendiri, bukan sekadar rating.
                </p>

                <ul class="space-y-4">
                    @foreach([
                        ['Atur prioritas sendiri', 'Urutkan harga, kualitas, lokasi, dan ulasan sesuai kebutuhan Anda.'],
                        ['Jarak dihitung dari lokasi Anda', 'Pakai GPS agar toko terdekat mendapat nilai lebih tinggi.'],
                        ['Perhitungan transparan', 'Setiap skor bisa ditelusuri sampai ke rumusnya.'],
                    ] as [$judul, $isi])
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 w-6 h-6 rounded-full bg-hero-accent/15 text-hero-accent flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-bold text-sm">{{ $judul }}</p>
                                <p class="text-sm text-[#CBD5E1] leading-relaxed">{{ $isi }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="relative text-xs text-[#94A3B8]">&copy; {{ date('Y') }} ThriftJember &middot; Metode ROC-SMART</p>
        </aside>

        {{-- Area formulir --}}
        <main class="flex items-center justify-center p-4 sm:p-8">
            <div class="w-full max-w-md my-8">
                {{-- Link kembali --}}
                <div class="mb-6">
                    <a href="{{ route('toko.index') }}"
                       class="inline-flex items-center gap-2 text-sm text-muted hover:text-primary transition-colors font-semibold px-3 py-2 -ml-3 rounded-btn hover:bg-white min-h-[44px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                {{-- Logo (khusus layar kecil, karena panel merek disembunyikan) --}}
                <div class="lg:hidden text-center mb-8">
                    <a href="{{ route('toko.index') }}" class="inline-flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-[14px] bg-primary flex items-center justify-center text-white shadow-sm group-hover:bg-primary-dark transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold text-ink tracking-tight">ThriftJember</span>
                        <span class="text-xs font-medium text-muted">Sistem Rekomendasi Toko Thrift Jember (ROC-SMART)</span>
                    </a>
                </div>

                {{-- Kartu formulir --}}
                <div class="bg-white border border-border rounded-card p-6 sm:p-8 shadow-card">
                    @yield('auth-content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
