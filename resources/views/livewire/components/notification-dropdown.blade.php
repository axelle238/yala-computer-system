<div class="relative" x-data="{ open: false }">
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative p-2 text-slate-400 hover:text-white transition-colors rounded-full hover:bg-white/10">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-slate-900 animate-pulse">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden z-50"
         style="display: none;">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="font-bold text-sm text-slate-800 dark:text-white">Notifikasi</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs font-bold text-blue-500 hover:text-blue-600">Tandai Baca Semua</button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="p-4 border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30 dark:bg-blue-900/10' }}">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @if(isset($notification->data['type']) && $notification->data['type'] == 'order')
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                </div>
                            @elseif(isset($notification->data['type']) && $notification->data['type'] == 'rma')
                                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-800 dark:text-white line-clamp-1">
                                {{ $notification->data['title'] ?? 'Notifikasi Baru' }}
                            </p>
                            <p class="text-xs text-slate-500 mb-1 line-clamp-2">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <span class="text-[10px] text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @if(!$notification->read_at)
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="w-full mt-2 text-[10px] font-bold text-blue-500 hover:text-blue-600 text-right">Tandai Dibaca</button>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">
                    <p class="text-xs">Tidak ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>
        
        <!-- View All Link (Optional) -->
        {{-- <div class="p-2 bg-slate-50 dark:bg-slate-900/50 text-center">
            <a href="#" class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-blue-500">Lihat Semua</a>
        </div> --}}
    </div>
</div>
