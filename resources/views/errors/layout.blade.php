<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title') - ThriftJember</title>
    @include('layouts.partials.head')
</head>
{{-- Layout mandiri: sengaja tidak memakai Auth/DB agar tetap tampil saat terjadi gangguan server. --}}
<body class="bg-page text-body min-h-screen antialiased flex flex-col selection:bg-primary-tint selection:text-primary">

    <header class="bg-white border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group min-h-[44px]">
                <div class="w-11 h-11 rounded-btn bg-primary flex items-center justify-center text-white shadow-sm group-hover:bg-primary-dark transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="font-extrabold text-xl text-ink tracking-tight">ThriftJember</span>
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="w-full max-w-lg text-center">
            <div class="mx-auto w-20 h-20 rounded-[20px] bg-primary-tint text-primary flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="@yield('icon')" />
                </svg>
            </div>

            <p class="text-sm font-bold text-primary tracking-widest uppercase mb-2">Kode @yield('code')</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-ink tracking-tight mb-3">@yield('heading')</h1>
            <p class="text-muted leading-relaxed mb-8">@yield('message')</p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <a href="{{ url('/') }}" class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-btn shadow-sm transition-colors min-h-[44px] flex items-center justify-center">
                        Kembali ke Beranda
                    </a>
                @endif
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-xs text-muted">
        &copy; {{ date('Y') }} ThriftJember
    </footer>
</body>
</html>
