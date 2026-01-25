<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        @if($hasUnread)
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white dark:border-slate-900 animate-pulse"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         style="display: none;"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50 origin-top-right">
        
        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white">Notifikasi</h3>
            @if($hasUnread)
                <button wire:click="markAsRead" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Tandai Dibaca</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notif)
                <a href="{{ $notif['route'] }}" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 border-b border-slate-50 dark:border-slate-700 last:border-0 transition-colors group">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @if($notif['type'] == 'warning')
                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                            @elseif($notif['type'] == 'success')
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $notif['title'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $notif['message'] }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">{{ $notif['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="py-8 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p class="text-xs">Tidak ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>