<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.outside="open = false">
    <!-- Bell Icon -->
    <button @click="open = !open" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors relative group">
        <div class="absolute inset-0 bg-slate-200 dark:bg-slate-700 rounded-full scale-0 group-hover:scale-100 transition-transform"></div>
        <svg class="w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full border border-white dark:border-slate-900 animate-pulse z-20"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-100 dark:border-slate-700 z-50 overflow-hidden"
         style="display: none;">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white">Notifikasi</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllRead" class="text-[10px] font-bold text-indigo-600 hover:underline">Tandai Semua Dibaca</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto custom-scrollbar" wire:poll.10s>
            @forelse($notifications as $notif)
                <div wire:click="markAsRead('{{ $notif->id }}')" class="p-4 border-b border-slate-50 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors flex gap-3 relative group">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800 dark:text-white mb-0.5">{{ $notif->data['title'] ?? 'System Notification' }}</p>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $notif->data['message'] ?? 'No detail.' }}</p>
                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Tidak ada notifikasi baru.
                </div>
            @endforelse
        </div>

        <div class="p-2 text-center bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700">
            <a href="#" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">Lihat Semua Riwayat</a>
        </div>
    </div>
</div>
