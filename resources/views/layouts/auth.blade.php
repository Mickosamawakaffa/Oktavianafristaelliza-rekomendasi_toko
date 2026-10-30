<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk / Daftar') - ThriftJember</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
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
                        success: {
                            bg: '#DCFCE7',
                            text: '#166534',
                            dot: '#16A34A',
                        },
                    },
                    boxShadow: {
                        soft: '0 1px 2px rgba(15, 23, 42, 0.05)',
                        card: '0 8px 24px rgba(15, 23, 42, 0.05)',
                    },
                    borderRadius: {
                        card: '16px',
                        btn: '10px',
                    }
                }
            }
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
            --color-page: #F3F5F8;
            --color-surface: #FFFFFF;
            --color-border: #E2E8F0;
            --color-border-strong: #CBD5E1;
            --color-success-bg: #DCFCE7;
            --color-success-text: #166534;
            --color-success-dot: #16A34A;
        }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--color-page);
            color: var(--color-body);
        }
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 3px solid var(--color-primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-[#F3F5F8] text-[#334155] min-h-screen flex items-center justify-center p-4 sm:p-8 antialiased selection:bg-[#E6F4F1] selection:text-[#0F766E]">

    <div class="w-full max-w-md my-8">
        {{-- Link Kembali ke Daftar Toko --}}
        <div class="mb-6">
            <a href="{{ route('toko.index') }}" 
               class="inline-flex items-center gap-2 text-sm text-[#526071] hover:text-[#0F766E] transition-colors font-semibold px-3 py-2 rounded-[10px] hover:bg-white min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        {{-- Logo & Judul Aplikasi --}}
        <div class="text-center mb-8">
            <a href="{{ route('toko.index') }}" class="inline-flex flex-col items-center gap-2 group">
                <div class="w-13 h-13 p-3 rounded-[12px] bg-[#0F766E] flex items-center justify-center text-white shadow-sm group-hover:bg-[#0B5C56] transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold text-[#0F172A] tracking-tight">ThriftJember</span>
                <span class="text-xs font-medium text-[#526071]">Sistem Rekomendasi Toko Thrift Jember (ROC-SMART)</span>
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('status'))
            <div class="mb-5 flex items-center gap-3 p-4 rounded-[12px] bg-[#DCFCE7] border border-[#BBF7D0] text-[#166534] text-sm">
                <svg class="w-5 h-5 shrink-0 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 p-4 rounded-[12px] bg-[#FEF2F2] border border-[#FECACA] text-[#991B1B] text-sm">
                <svg class="w-5 h-5 shrink-0 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Auth Card Putih di Tengah --}}
        <div class="bg-white border border-[#E2E8F0] rounded-[16px] p-6 sm:p-8 shadow-[0_8px_24px_rgba(15,23,42,0.05)]">
            @yield('auth-content')
        </div>
    </div>
</body>
</html>
