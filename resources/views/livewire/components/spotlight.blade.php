<div x-data="{ 
        isOpen: @entangle('isOpen'),
        focusInput() {
            setTimeout(() => {
                this.$refs.searchInput.focus();
            }, 50);
        }
    }"
    x-on:keydown.window.ctrl.k.prevent="$wire.open()"
    x-on:focus-spotlight.window="focusInput()"
    class="relative z-[100]"
    style="display: none;"
    x-show="isOpen">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
         x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$wire.close()"></div>

    <!-- Spotlight Overlay -->
    <div class="fixed inset-0 z-[101] overflow-y-auto p-4 sm:p-6 md:p-20">
        <div class="mx-auto max-w-2xl transform divide-y divide-slate-100 dark:divide-slate-700 overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl transition-all border border-slate-200 dark:border-slate-700"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="$wire.close()">
            
            <div class="relative">
                <svg class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.200ms="search" 
                       x-ref="searchInput"
                       type="text" 
                       class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-0 sm:text-sm" 
                       placeholder="Cari Menu, Produk, Order, atau Customer..."
                       @keydown.escape="$wire.close()">
            </div>

            @if(!empty($results))
                <div class="max-h-96 scroll-py-3 overflow-y-auto p-3 custom-scrollbar">
                    @foreach($results as $group => $items)
                        @if(count($items) > 0)
                            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 mt-2 px-3">{{ $group }}</div>
                            <ul class="text-sm text-slate-700 dark:text-slate-200 space-y-1">
                                @foreach($items as $item)
                                    <li>
                                        <a href="{{ $item['route'] ?? '#' }}" class="group flex cursor-pointer select-none items-center rounded-xl px-3 py-2 hover:bg-indigo-600 hover:text-white transition-colors">
                                            <div class="flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 group-hover:bg-white/20 group-hover:text-white">
                                                <!-- Dynamic Icon -->
                                                @if($item['icon'] == 'home') <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                                @elseif($item['icon'] == 'shopping-cart') <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                                @elseif($item['icon'] == 'box') <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                @elseif($item['icon'] == 'user') <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                @elseif($item['icon'] == 'receipt-tax') <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" /></svg>
                                                @else <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                @endif
                                            </div>
                                            <div class="ml-4 flex-auto">
                                                <p class="font-medium text-slate-900 dark:text-white group-hover:text-white">
                                                    {{ $item['label'] }}
                                                </p>
                                                @if(isset($item['sub']))
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 group-hover:text-indigo-100">
                                                        {{ $item['sub'] }}
                                                    </p>
                                                @endif
                                            </div>
                                            <svg class="h-5 w-5 flex-none text-slate-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </div>
            @endif

            @if(empty($results) && strlen($search) > 1)
                <div class="px-6 py-14 text-center text-sm sm:px-14">
                    <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 font-semibold text-slate-900 dark:text-white">Tidak ditemukan hasil</p>
                    <p class="mt-2 text-slate-500">Coba kata kunci lain atau gunakan navigasi manual.</p>
                </div>
            @endif
            
            <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center text-xs text-slate-500">
                <div class="flex gap-2">
                    <span class="bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded">↑↓</span> navigasi
                    <span class="bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded">enter</span> pilih
                    <span class="bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded">esc</span> tutup
                </div>
                <div>Yala System v2.5</div>
            </div>
        </div>
    </div>
</div>
