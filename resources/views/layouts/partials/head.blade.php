{{-- Head bersama untuk semua layout (app, auth, error). Satu sumber untuk font, token desain Tailwind, dan CSS dasar. --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="ThriftJember membantu Anda menemukan toko thrift terbaik di Jember lewat sistem pendukung keputusan ROC-SMART yang objektif dan transparan.">
<meta name="theme-color" content="#0F766E">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230F766E'/%3E%3Cpath d='M11 13v-2a5 5 0 0 1 10 0v2M8.5 12h15l1 12h-17l1-12z' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

<!-- Google Fonts: Plus Jakarta Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind CSS CDN + token desain -->
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
                },
            },
        },
    }
</script>

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

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
