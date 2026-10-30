<div class="bg-white border-b border-[#E2E8F0] mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex space-x-2 overflow-x-auto py-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2.5 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 shrink-0 min-h-[40px]
               {{ request()->routeIs('admin.dashboard') ? 'bg-[#0F766E] text-white shadow-sm' : 'text-[#526071] hover:bg-[#F3F5F8] hover:text-[#0F172A]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.toko.index') }}" 
               class="px-4 py-2.5 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 shrink-0 min-h-[40px]
               {{ request()->routeIs('admin.toko.*') ? 'bg-[#0F766E] text-white shadow-sm' : 'text-[#526071] hover:bg-[#F3F5F8] hover:text-[#0F172A]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>Data Toko</span>
            </a>

            <a href="{{ route('admin.kriteria.index') }}" 
               class="px-4 py-2.5 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 shrink-0 min-h-[40px]
               {{ request()->routeIs('admin.kriteria.*') ? 'bg-[#0F766E] text-white shadow-sm' : 'text-[#526071] hover:bg-[#F3F5F8] hover:text-[#0F172A]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span>Data Kriteria</span>
            </a>

            <a href="{{ route('admin.nilai-alternatif.index') }}" 
               class="px-4 py-2.5 rounded-[10px] text-xs font-bold transition-colors flex items-center gap-2 shrink-0 min-h-[40px]
               {{ request()->routeIs('admin.nilai-alternatif.*') ? 'bg-[#0F766E] text-white shadow-sm' : 'text-[#526071] hover:bg-[#F3F5F8] hover:text-[#0F172A]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Nilai Alternatif</span>
            </a>
        </div>
    </div>
</div>
