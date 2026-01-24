<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-white transition-colors rounded-full hover:bg-white/5">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        @if($count > 0)
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-slate-900 animate-pulse"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl origin-top-right"
         style="display: none;">
        
        <div class="p-4 border-b border-white/10 flex justify-between items-center bg-slate-950/50">
            <h3 class="font-bold text-white text-sm">Notifikasi</h3>
            @if($count > 0)
                <button wire:click="markAllRead" class="text-[10px] text-cyan-400 hover:underline">Tandai Baca Semua</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $notif)
                <div wire:click="markAsRead('{{ $notif->id }}')" class="p-4 border-b border-white/5 hover:bg-white/5 transition-colors cursor-pointer group relative">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 mt-1.5 rounded-full bg-cyan-500 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-200 group-hover:text-white">{{ $notif->data['title'] ?? 'Info' }}</p>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $notif->data['message'] ?? '-' }}</p>
                            <p class="text-[10px] text-slate-600 mt-2 font-mono">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-10 h-10 text-slate-700 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                    <p class="text-xs text-slate-500">Tidak ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
