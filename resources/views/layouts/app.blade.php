<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'ThriftJember - Rekomendasi Toko Thrift Jember')</title>
    @include('layouts.partials.head')

    <!-- Leaflet.js CSS for Map -->
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />

    <style>
        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 340px), 1fr));
            gap: 24px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-page text-body antialiased min-h-screen flex flex-col selection:bg-primary-tint selection:text-primary">

    {{-- Skip link untuk pengguna keyboard / screen reader --}}
    <a href="#konten-utama" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[70] focus:px-4 focus:py-2 focus:bg-primary focus:text-white focus:rounded-btn focus:font-semibold">
        Lewati ke konten utama
    </a>

    @php
        $authUser = Auth::user();
        $role = $authUser?->role;
        $roleLabels = ['admin' => 'Admin', 'user' => 'Pengguna', 'pemilik_toko' => 'Pemilik'];

        // Tautan navigasi utama (menyesuaikan peran pengguna)
        $navLinks = [
            ['label' => 'Daftar Toko', 'href' => route('toko.index'), 'active' => request()->routeIs('toko.*'), 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
        ];
        if ($role === 'user') {
            $navLinks[] = ['label' => 'Preferensi Saya', 'href' => route('user.preferensi'), 'active' => request()->routeIs('user.preferensi*'), 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'];
            $navLinks[] = ['label' => 'Hasil Rekomendasi', 'href' => route('user.hasil'), 'active' => request()->routeIs('user.hasil'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'];
        } elseif ($role === 'admin') {
            $navLinks[] = ['label' => 'Dashboard Admin', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.*'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'];
        } elseif ($role === 'pemilik_toko') {
            $navLinks[] = ['label' => 'Dashboard Pemilik', 'href' => route('pemilik.dashboard'), 'active' => request()->routeIs('pemilik.*'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'];
        }
    @endphp

    <!-- Header / Navbar -->
    <header class="bg-white/95 backdrop-blur border-b border-border sticky top-0 z-50 shadow-soft" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-[72px] items-center gap-4">
              <div class="flex items-center gap-6 lg:gap-8 min-w-0">
                <!-- Logo -->
                <a href="{{ route('toko.index') }}" class="flex items-center gap-3 group shrink-0 min-h-[44px]" aria-label="ThriftJember - Beranda">
                    <div class="w-11 h-11 rounded-btn bg-primary flex items-center justify-center text-white shadow-sm group-hover:bg-primary-dark transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl text-ink tracking-tight block leading-tight">ThriftJember</span>
                        <span class="text-xs font-medium text-muted hidden sm:block">Rekomendasi Toko Thrift Jember</span>
                    </div>
                </a>

                <!-- Navigasi Desktop -->
                <nav class="hidden md:flex items-center gap-1.5" aria-label="Navigasi utama">
                    @foreach($navLinks as $link)
                        <a href="{{ $link['href'] }}"
                           @if($link['active']) aria-current="page" @endif
                           class="text-sm font-semibold transition-colors py-2.5 px-3.5 rounded-btn flex items-center gap-2 min-h-[44px]
                           {{ $link['active'] ? 'bg-primary-tint text-primary-dark' : 'text-body hover:text-primary hover:bg-page' }}">
                            <svg class="w-4 h-4 {{ $link['active'] ? 'text-primary' : 'text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                            </svg>
                            <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
              </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    @auth
                        {{-- Dropdown akun (desktop & tablet) --}}
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                    class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-btn border border-border-strong bg-white hover:bg-page transition-colors text-sm font-medium text-ink min-h-[44px]"
                                    aria-haspopup="true" :aria-expanded="open">
                                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ substr($authUser->name, 0, 1) }}
                                </span>
                                <span class="hidden lg:block max-w-[120px] truncate text-sm font-semibold">{{ $authUser->name }}</span>
                                <svg class="w-3.5 h-3.5 text-muted transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.outside="open = false" @keydown.escape.window="open = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-2 w-64 bg-white border border-border rounded-card shadow-[0_8px_24px_rgba(15,23,42,0.1)] z-50 overflow-hidden origin-top-right">
                                <div class="px-4 py-3.5 border-b border-border bg-page">
                                    <p class="text-sm font-bold text-ink truncate">{{ $authUser->name }}</p>
                                    <p class="text-xs text-muted truncate">{{ $authUser->email }}</p>
                                    <span class="inline-block mt-2 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-primary-tint text-primary-dark">
                                        {{ $roleLabels[$role] ?? $role }}
                                    </span>
                                </div>
                                <div class="py-1.5">
                                    @foreach($navLinks as $link)
                                        @continue($loop->first)
                                        <a href="{{ $link['href'] }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-body hover:bg-primary-tint hover:text-primary transition-colors">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                                            </svg>
                                            {{ $link['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                                <div class="border-t border-border py-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-semibold text-[#991B1B] hover:bg-[#FEF2F2] transition-colors">
                                            <svg class="w-4 h-4 text-[#991B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:flex text-sm font-semibold text-body hover:text-primary transition-colors px-3.5 py-2.5 rounded-btn hover:bg-page min-h-[44px] items-center">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-primary hover:bg-primary-dark transition-colors px-4 py-2.5 rounded-btn shadow-sm min-h-[44px] flex items-center">
                            Daftar
                        </a>
                    @endauth

                    {{-- Tombol menu mobile --}}
                    <button type="button" @click="mobileOpen = !mobileOpen"
                            class="md:hidden w-11 h-11 rounded-btn border border-border-strong bg-white hover:bg-page flex items-center justify-center text-ink transition-colors"
                            :aria-expanded="mobileOpen" aria-controls="menu-mobile" aria-label="Buka menu navigasi">
                        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Panel menu mobile --}}
        <div id="menu-mobile" x-show="mobileOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-border bg-white">
            <nav class="max-w-7xl mx-auto px-4 py-3 space-y-1" aria-label="Navigasi mobile">
                @foreach($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       @if($link['active']) aria-current="page" @endif
                       class="flex items-center gap-3 px-3.5 py-3 rounded-btn text-sm font-semibold min-h-[48px]
                       {{ $link['active'] ? 'bg-primary-tint text-primary-dark' : 'text-body hover:bg-page' }}">
                        <svg class="w-5 h-5 {{ $link['active'] ? 'text-primary' : 'text-muted' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                        </svg>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach

                @auth
                    <div class="pt-3 mt-2 border-t border-border">
                        <div class="flex items-center gap-3 px-3.5 py-2">
                            <span class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold uppercase shrink-0">
                                {{ substr($authUser->name, 0, 1) }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-ink truncate">{{ $authUser->name }}</p>
                                <p class="text-xs text-muted truncate">{{ $roleLabels[$role] ?? $role }} &middot; {{ $authUser->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-3 rounded-btn text-sm font-semibold text-[#991B1B] hover:bg-[#FEF2F2] min-h-[48px]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-3 mt-2 border-t border-border grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="text-center px-4 py-3 rounded-btn border border-border-strong text-sm font-bold text-ink hover:bg-page min-h-[48px] flex items-center justify-center">Masuk</a>
                        <a href="{{ route('register') }}" class="text-center px-4 py-3 rounded-btn bg-primary hover:bg-primary-dark text-sm font-bold text-white min-h-[48px] flex items-center justify-center">Daftar</a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Notifikasi global --}}
    @include('layouts.partials.flash')

    <!-- Main Content -->
    <main id="konten-utama" class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-hero text-[#CBD5E1] border-t border-[#1a384c] mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-btn bg-primary flex items-center justify-center text-white shadow-sm shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <span class="text-white font-extrabold text-lg tracking-tight">ThriftJember</span>
                    </div>
                    <p class="text-sm leading-relaxed text-[#CBD5E1]">
                        Sistem pendukung keputusan untuk memilih toko thrift terbaik di Jember secara objektif dan transparan.
                    </p>
                </div>

                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Menu</h2>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('toko.index') }}" class="hover:text-hero-accent transition-colors">Daftar Toko</a></li>
                        @auth
                            @if($role === 'user')
                                <li><a href="{{ route('user.preferensi') }}" class="hover:text-hero-accent transition-colors">Atur Preferensi</a></li>
                                <li><a href="{{ route('user.hasil') }}" class="hover:text-hero-accent transition-colors">Hasil Rekomendasi</a></li>
                            @elseif($role === 'admin')
                                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-hero-accent transition-colors">Dashboard Admin</a></li>
                            @elseif($role === 'pemilik_toko')
                                <li><a href="{{ route('pemilik.dashboard') }}" class="hover:text-hero-accent transition-colors">Dashboard Pemilik</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-hero-accent transition-colors">Masuk</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-hero-accent transition-colors">Daftar Akun</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Metode</h2>
                    <p class="text-sm leading-relaxed text-[#CBD5E1]">
                        Peringkat dihitung dengan <strong class="text-white">ROC</strong> (pembobotan kriteria), <strong class="text-white">SMART</strong> (penilaian alternatif), dan <strong class="text-white">Haversine</strong> (jarak GPS).
                    </p>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-[#1a384c] text-xs text-[#94A3B8] text-center md:text-left">
                &copy; {{ date('Y') }} ThriftJember. Seluruh hak dilindungi.
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
    @stack('scripts')
</body>
</html>
