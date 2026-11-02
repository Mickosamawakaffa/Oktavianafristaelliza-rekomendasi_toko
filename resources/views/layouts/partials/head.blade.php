{{-- Head bersama untuk semua layout (app, auth, error). Satu sumber untuk font, token desain Tailwind, dan CSS dasar. --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="ThriftJember membantu Anda menemukan toko thrift terbaik di Jember lewat sistem pendukung keputusan ROC-SMART yang objektif dan transparan.">
<meta name="theme-color" content="#0F766E">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230F766E'/%3E%3Cpath d='M11 13v-2a5 5 0 0 1 10 0v2M8.5 12h15l1 12h-17l1-12z' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

<!-- Aset lokal (font Plus Jakarta Sans + Tailwind hasil build). Build ulang: lihat tools/tailwind/README.md -->
<link rel="preload" href="{{ asset('fonts/plus-jakarta-sans-latin-600-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">

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
    }

    html { scroll-behavior: smooth; }

    body {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        background-color: var(--color-page);
        color: var(--color-body);
    }

    /* Alpine: sembunyikan elemen sampai Alpine siap agar tidak berkedip */
    [x-cloak] { display: none !important; }

    /* Aksesibilitas keyboard */
    a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible, summary:focus-visible {
        outline: 3px solid var(--color-primary);
        outline-offset: 2px;
    }

    /* Hormati preferensi pengguna yang mengurangi animasi */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
