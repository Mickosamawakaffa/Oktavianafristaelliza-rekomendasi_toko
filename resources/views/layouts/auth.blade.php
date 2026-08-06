<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk / Daftar') - ThriftJember SPK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center p-4 sm:p-8">

    {{-- Background Gradient Blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-600/20 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-teal-600/10 rounded-full filter blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('toko.index') }}" class="inline-flex flex-col items-center gap-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-900 text-2xl font-bold shadow-lg">
                    <i class="fa-solid fa-store"></i>
                </div>
                <span class="text-2xl font-extrabold text-white tracking-tight">ThriftJember</span>
                <span class="text-xs text-emerald-400 font-medium">SPK ROC-SMART</span>
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="mb-4 flex items-center gap-3 p-4 rounded-xl bg-rose-900/50 border border-rose-500/30 text-rose-300 text-sm">
                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Auth Card --}}
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            @yield('auth-content')
        </div>

        {{-- Back Link --}}
        <div class="text-center mt-6">
            <a href="{{ route('toko.index') }}" class="text-xs text-slate-400 hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Toko
            </a>
        </div>
    </div>
</body>
</html>
