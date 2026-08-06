<div class="bg-white border-b border-slate-200 mb-8 -mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-1 sm:space-x-4 overflow-x-auto py-2.5">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i class="fa-solid fa-gauge-high"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.toko.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.toko.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i class="fa-solid fa-store"></i>
                Data Toko
            </a>

            <a href="{{ route('admin.kriteria.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.kriteria.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i class="fa-solid fa-sliders"></i>
                Data Kriteria
            </a>

            <a href="{{ route('admin.nilai-alternatif.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 {{ request()->routeIs('admin.nilai-alternatif.*') ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <i class="fa-solid fa-table-cells"></i>
                Nilai Alternatif
            </a>
        </div>
    </div>
</div>
