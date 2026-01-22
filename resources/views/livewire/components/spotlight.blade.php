<div 
    x-data="{ 
        isOpen: @entangle('isOpen'), 
        open() { this.isOpen = true; this.$nextTick(() => $refs.searchInput.focus()); },
        close() { this.isOpen = false; }
    }"
    @keydown.window.ctrl.k.prevent="open()"
    @keydown.window.cmd.k.prevent="open()"
    @keydown.escape.window="close()"
    class="relative z-[100]"
>
    <!-- Trigger Button (Visible in Header) -->
    <div class="hidden md:block fixed top-4 right-20 z-50">
        <button @click="open()" class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-xs text-slate-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <span>Cari (Ctrl+K)</span>
        </button>
    </div>

    <!-- Modal Backdrop -->
    <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" style="display: none;"></div>

    <!-- Modal Content -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="close()"
         class="fixed inset-0 z-[200] flex items-start justify-center pt-24 px-4 pointer-events-none"
         style="display: none;">
        
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl overflow-hidden border border-slate-200 pointer-events-auto">
            <!-- Input -->
            <div class="relative border-b border-slate-100">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input x-ref="searchInput" wire:model.live.debounce.100ms="search" type="text" class="w-full py-4 pl-12 pr-4 text-slate-800 bg-transparent border-none focus:ring-0 placeholder-slate-400 text-lg" placeholder="Cari menu, produk, atau tiket servis...">
                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <button @click="close()" class="px-2 py-1 text-xs font-bold text-slate-400 bg-slate-100 rounded border border-slate-200">ESC</button>
                </div>
            </div>

            <!-- Results -->
            <div class="max-h-[60vh] overflow-y-auto bg-slate-50/50">
                @if(count($results) > 0)
                    <div class="py-2">
                        @foreach($results as $result)
                            <a href="{{ $result['url'] }}" class="flex items-center gap-4 px-4 py-3 mx-2 rounded-lg hover:bg-blue-600 hover:text-white group transition-colors cursor-pointer">
                                <div class="w-8 h-8 rounded bg-white border border-slate-200 flex items-center justify-center text-slate-500 group-hover:text-blue-600">
                                    <!-- Icons Logic -->
                                    @if($result['icon'] == 'home') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                    @elseif($result['icon'] == 'box') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    @elseif($result['icon'] == 'plus') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    @elseif($result['icon'] == 'cube') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    @elseif($result['icon'] == 'ticket') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
                                    @elseif($result['icon'] == 'shopping-cart') <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    @else <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-800 group-hover:text-white">{{ $result['title'] }}</div>
                                    <div class="text-xs text-slate-500 group-hover:text-blue-100">{{ $result['subtitle'] }}</div>
                                </div>
                                <div class="ml-auto text-xs font-mono text-slate-400 group-hover:text-blue-200">
                                    {{ ucfirst($result['type']) }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @elseif(strlen($search) >= 2)
                    <div class="py-12 text-center text-slate-500">
                        <p class="font-medium">Tidak ada hasil ditemukan.</p>
                        <p class="text-xs mt-1">Coba kata kunci lain.</p>
                    </div>
                @else
                    <div class="py-12 text-center text-slate-400">
                        <p class="text-xs">Ketik minimal 2 karakter untuk mencari...</p>
                    </div>
                @endif
            </div>
            
            <div class="bg-slate-100 px-4 py-2 border-t border-slate-200 flex justify-between items-center text-[10px] text-slate-500">
                <div class="flex gap-2">
                    <span><span class="font-bold">↑↓</span> navigasi</span>
                    <span><span class="font-bold">enter</span> pilih</span>
                </div>
                <span>Yala Computer System v6.0</span>
            </div>
        </div>
    </div>
</div>
