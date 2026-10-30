<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ThriftJember - Rekomendasi Toko Thrift Jember')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Design Tokens Extended -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        ink: '#0F172A',
                        body: '#334155',
                        muted: '#526071',
                        primary: {
                            DEFAULT: '#0F766E',
                            dark: '#0B5C56',
                            tint: '#E6F4F1',
                        },
                        hero: {
                            DEFAULT: '#0E2A3B',
                            accent: '#5EEAD4',
                        },
                        page: '#F3F5F8',
                        surface: '#FFFFFF',
                        border: {
                            DEFAULT: '#E2E8F0',
                            strong: '#CBD5E1',
                        },
                        star: '#F59E0B',
                        success: {
                            bg: '#DCFCE7',
                            text: '#166534',
                            dot: '#16A34A',
                        },
                        gold: {
                            bg: '#FEF3C7',
                            text: '#92400E',
                        },
                        silver: {
                            bg: '#E2E8F0',
                            text: '#334155',
                        },
                        bronze: {
                            bg: '#FFEDD5',
                            text: '#9A3412',
                        },
                    },
                    boxShadow: {
                        soft: '0 1px 2px rgba(15, 23, 42, 0.05)',
                        card: '0 8px 24px rgba(15, 23, 42, 0.05)',
                        teal: '0 8px 24px rgba(15, 118, 110, 0.12)',
                    },
                    borderRadius: {
                        card: '16px',
                        btn: '10px',
                    }
                }
            }
        }
    </script>

    <!-- Leaflet.js CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --color-ink: #0F172A;
            --color-body: #334155;
            --color-muted: #526071;
            --color-primary: #0F766E;
            --color-primary-dark: #0B5C56;
            --color-primary-tint: #E6F4F1;
            --color-hero: #0E2A3B;
            --color-hero-accent: #5EEAD4;
            --color-page: #F3F5F8;
            --color-surface: #FFFFFF;
            --color-border: #E2E8F0;
            --color-border-strong: #CBD5E1;
            --color-success-bg: #DCFCE7;
            --color-success-text: #166534;
            --color-success-dot: #16A34A;
            --color-gold-bg: #FEF3C7;
            --color-gold-text: #92400E;
            --color-silver-bg: #E2E8F0;
            --color-silver-text: #334155;
            --color-bronze-bg: #FFEDD5;
            --color-bronze-text: #9A3412;
            --color-star: #F59E0B;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--color-page);
            color: var(--color-body);
        }

        /* Keyboard Accessibility Focus */
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid var(--color-primary);
            outline-offset: 2px;
        }

        .store-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        @media (max-width: 400px) {
            .store-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F3F5F8] text-[#334155] antialiased min-h-screen flex flex-col justify-between selection:bg-[#E6F4F1] selection:text-[#0F766E]">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-[#E2E8F0] sticky top-0 z-50 shadow-[0_1px_2px_rgba(15,23,42,0.05)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center gap-4">
                <!-- Logo Kotak Teal + "ThriftJember" + Subjudul -->
                <a href="{{ route('toko.index') }}" class="flex items-center gap-3 group shrink-0 min-h-[44px]">
                    <div class="w-11 h-11 rounded-[10px] bg-[#0F766E] flex items-center justify-center text-white shadow-sm group-hover:bg-[#0B5C56] transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-xl text-[#0F172A] tracking-tight block leading-tight">ThriftJember</span>
                        <span class="text-xs font-medium text-[#526071] block">Rekomendasi Toko Thrift Jember</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('toko.index') }}" class="text-sm font-semibold text-[#0F766E] hover:text-[#0B5C56] transition-colors py-2.5 px-3 rounded-[10px] hover:bg-[#E6F4F1] flex items-center gap-1.5 min-h-[44px]">
                        <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Daftar Toko</span>
                    </a>

                    @auth
                        {{-- Dropdown User --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 px-3.5 py-2 rounded-[10px] border border-[#CBD5E1] bg-white hover:bg-[#F3F5F8] transition-colors text-sm font-medium text-[#0F172A] min-h-[44px]"
                                    aria-haspopup="true" :aria-expanded="open">
                                <div class="w-7 h-7 rounded-full bg-[#0F766E] text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:block max-w-[120px] truncate text-sm font-medium">{{ Auth::user()->name }}</span>
                                @php
                                    $roleLabels = ['admin' => 'Admin', 'user' => 'Pengguna', 'pemilik_toko' => 'Pemilik'];
                                    $role = Auth::user()->role;
                                @endphp
                                <span class="hidden sm:inline text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#E6F4F1] text-[#0B5C56]">
                                    {{ $roleLabels[$role] ?? $role }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-[#526071]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 top-13 w-56 bg-white border border-[#E2E8F0] rounded-[16px] shadow-[0_8px_24px_rgba(15,23,42,0.08)] z-50 overflow-hidden py-1.5"
                                 style="display: none;">
                                <div class="px-4 py-3 border-b border-[#E2E8F0] bg-[#F3F5F8]">
                                    <p class="text-xs font-bold text-[#0F172A] truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-[#526071] truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-[#334155] hover:bg-[#E6F4F1] hover:text-[#0F766E] transition-colors">
                                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Dashboard Admin
                                        </a>
                                    @elseif(Auth::user()->role === 'pemilik_toko')
                                        <a href="{{ route('pemilik.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-[#334155] hover:bg-[#E6F4F1] hover:text-[#0F766E] transition-colors">
                                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            Dashboard Pemilik
                                        </a>
                                    @else
                                        <a href="{{ route('user.preferensi') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-[#334155] hover:bg-[#E6F4F1] hover:text-[#0F766E] transition-colors">
                                            <svg class="w-4 h-4 text-[#0F766E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                            </svg>
                                            Preferensi Saya
                                        </a>
                                    @endif
                                </div>
                                <div class="border-t border-[#E2E8F0] pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-[#991B1B] hover:bg-[#FEF2F2] transition-colors">
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
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-[#334155] hover:text-[#0F766E] transition-colors px-3 py-2.5 rounded-[10px] hover:bg-white min-h-[44px] flex items-center">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-[#0F766E] hover:bg-[#0B5C56] transition-colors px-4 py-2.5 rounded-[10px] shadow-sm min-h-[44px] flex items-center">
                            Daftar
                        </a>
                    @endauth
                </nav>

                {{-- Alpine.js for dropdown --}}
                <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Latar Hero (Navy Gelap #0E2A3B) dan Teks #CBD5E1 -->
    <footer class="bg-[#0E2A3B] text-[#CBD5E1] border-t border-[#1a384c] mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-[10px] bg-[#0F766E] flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-white font-extrabold text-base tracking-tight block">ThriftJember</span>
                        <span class="text-xs text-[#CBD5E1]">Sistem Pendukung Keputusan Pemilihan Toko Thrift Jember</span>
                    </div>
                </div>
                <p class="text-xs text-[#CBD5E1]">
                    &copy; {{ date('Y') }} ThriftJember. Berdasarkan metode ROC-SMART yang objektif dan transparan.
                </p>
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
