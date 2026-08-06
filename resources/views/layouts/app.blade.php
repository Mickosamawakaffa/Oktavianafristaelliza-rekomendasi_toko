<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekomendasi Toko Fashion Secondhand Jember')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        },
                        indigo: {
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet.js CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo & Title -->
                <a href="{{ route('toko.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white font-bold text-xl shadow-md group-hover:scale-105 transition-transform">
                        <i class="fa-solid me-0 fa-store text-lg"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-lg sm:text-xl text-slate-900 tracking-tight">ThriftJember</span>
                        <span class="block text-xs font-medium text-emerald-600">SPK ROC-SMART</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('toko.index') }}" class="hidden sm:flex text-sm font-semibold text-slate-700 hover:text-emerald-600 transition-colors items-center gap-1.5 py-2 px-3 rounded-lg hover:bg-slate-100">
                        <i class="fa-solid fa-store text-xs"></i>
                        <span>Daftar Toko</span>
                    </a>

                    @auth
                        {{-- Dropdown User --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors text-sm font-medium text-slate-700">
                                <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold uppercase">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:block max-w-24 truncate">{{ Auth::user()->name }}</span>
                                @php
                                    $roleLabels = ['admin' => 'Admin', 'user' => 'User', 'pemilik_toko' => 'Pemilik'];
                                    $roleColors = ['admin' => 'bg-violet-100 text-violet-700', 'user' => 'bg-emerald-100 text-emerald-700', 'pemilik_toko' => 'bg-amber-100 text-amber-700'];
                                    $role = Auth::user()->role;
                                @endphp
                                <span class="hidden sm:inline text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $roleColors[$role] ?? 'bg-slate-100 text-slate-600' }}">{{ $roleLabels[$role] ?? $role }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 top-12 w-52 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <i class="fa-solid fa-gauge text-violet-500 w-4 text-center"></i> Dashboard Admin
                                        </a>
                                    @elseif(Auth::user()->role === 'pemilik_toko')
                                        <a href="{{ route('pemilik.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <i class="fa-solid fa-store text-amber-500 w-4 text-center"></i> Dashboard Pemilik
                                        </a>
                                    @else
                                        <a href="{{ route('user.preferensi') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                            <i class="fa-solid fa-sliders text-emerald-500 w-4 text-center"></i> Preferensi Saya
                                        </a>
                                    @endif
                                </div>
                                <div class="border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-emerald-600 transition-colors px-3 py-2 rounded-lg hover:bg-slate-100">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors px-4 py-2 rounded-lg shadow-sm">
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

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xs">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <span class="text-white font-semibold text-sm">SPK Pemilihan Toko Fashion Secondhand Jember</span>
                </div>
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} ThriftJember. Powered by ROC & SMART Method.
                </p>
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
