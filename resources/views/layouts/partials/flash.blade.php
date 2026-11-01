{{-- Notifikasi global (toast). Membaca session: success, error, info, warning, status. --}}
@php
    $toasts = [];
    foreach (['success' => 'success', 'status' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info'] as $key => $type) {
        if (session()->has($key)) {
            $toasts[] = ['type' => $type, 'message' => session($key)];
        }
    }

    $toastStyles = [
        'success' => ['box' => 'bg-[#F0FDF4] border-[#BBF7D0] text-[#166534]', 'icon' => 'text-[#16A34A]', 'title' => 'Berhasil', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'error'   => ['box' => 'bg-[#FEF2F2] border-[#FECACA] text-[#991B1B]', 'icon' => 'text-[#DC2626]', 'title' => 'Terjadi Kesalahan', 'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'warning' => ['box' => 'bg-[#FFFBEB] border-[#FDE68A] text-[#92400E]', 'icon' => 'text-[#D97706]', 'title' => 'Perhatian', 'path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        'info'    => ['box' => 'bg-[#EFF6FF] border-[#BFDBFE] text-[#1E40AF]', 'icon' => 'text-[#2563EB]', 'title' => 'Informasi', 'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
@endphp

@if(count($toasts) > 0)
    <div class="fixed top-24 right-4 left-4 sm:left-auto sm:w-full sm:max-w-sm z-[60] space-y-3 pointer-events-none" aria-live="polite">
        @foreach($toasts as $toast)
            @php $s = $toastStyles[$toast['type']]; @endphp
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, {{ $toast['type'] === 'error' ? 12000 : 6000 }})"
                 x-show="show"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 role="{{ $toast['type'] === 'error' ? 'alert' : 'status' }}"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-[14px] border shadow-[0_8px_24px_rgba(15,23,42,0.12)] {{ $s['box'] }}">
                <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $s['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['path'] }}" />
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold leading-tight">{{ $s['title'] }}</p>
                    <p class="text-sm mt-0.5 leading-relaxed break-words">{{ $toast['message'] }}</p>
                </div>
                <button type="button" @click="show = false" class="shrink-0 -mr-1 -mt-1 w-8 h-8 rounded-lg flex items-center justify-center opacity-60 hover:opacity-100 hover:bg-black/5 transition" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
