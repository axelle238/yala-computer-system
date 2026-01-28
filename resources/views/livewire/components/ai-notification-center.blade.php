<div class="fixed top-4 right-4 z-[100] flex flex-col gap-3 w-80 pointer-events-none">
    @foreach($notifications as $notif)
        <div wire:key="{{ $notif['id'] }}" 
             x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             @remove-notification-later.window="if ($event.detail.id === '{{ $notif['id'] }}') setTimeout(() => show = false, 5000)"
             class="pointer-events-auto bg-white dark:bg-slate-800 rounded-xl shadow-2xl border-l-4 border-{{ $notif['color'] }}-500 p-4 flex items-start gap-3 relative overflow-hidden backdrop-blur-sm bg-opacity-95">
            
            <!-- Icon -->
            <div class="flex-shrink-0 text-{{ $notif['color'] }}-500 mt-0.5">
                @if($notif['icon'] == 'sparkles')
                    <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                @elseif($notif['icon'] == 'check-circle')
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @elseif($notif['icon'] == 'x-circle')
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @else
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @endif
            </div>

            <!-- Content -->
            <div class="flex-1">
                <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                    {{ ucfirst($notif['type']) }}
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                    {{ $notif['message'] }}
                </p>
                <span class="text-[10px] text-slate-400 mt-2 block">{{ $notif['timestamp'] }}</span>
            </div>

            <!-- Close -->
            <button wire:click="remove('{{ $notif['id'] }}')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endforeach
</div>
