<div class="relative w-full max-w-md mx-auto mr-4" x-data="{ focused: false }" @click.away="focused = false">
    <!-- Search Input -->
    <div class="relative group">
        <div class="absolute inset-0 bg-cyan-500/20 rounded-full blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            @focus="focused = true"
            class="w-full bg-slate-900/80 border border-white/10 rounded-full py-2.5 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-500/50 transition-all relative z-10"
            placeholder="Cari produk (Ctrl+K)..."
            x-on:keydown.window.ctrl.k.prevent="$el.focus()"
        >
        <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-3 z-20 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        
        <!-- Loading Indicator -->
        <div wire:loading class="absolute right-3 top-2.5 z-20">
            <svg class="w-4 h-4 text-cyan-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>
    </div>

    <!-- Dropdown Results -->
    <div x-show="focused" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute top-full left-0 w-full mt-2 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl"
         style="display: none;">
        
        @if(strlen($query) >= 2)
            @if(empty($results['products']) && empty($results['categories']))
                <div class="p-4 text-center text-slate-500 text-sm">
                    Tidak ada hasil untuk "{{ $query }}"
                </div>
            @else
                <!-- Categories -->
                @if($results['categories']->isNotEmpty())
                    <div class="p-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-500 px-3 py-1">Kategori</div>
                        @foreach($results['categories'] as $cat)
                            <button wire:click="selectResult('{{ $cat->name }}', 'category', '{{ $cat->slug }}')" class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/5 flex items-center gap-3 group transition-colors">
                                <div class="w-8 h-8 rounded-full bg-cyan-500/10 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-300 group-hover:text-white">{{ $cat->name }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Products -->
                @if($results['products']->isNotEmpty())
                    <div class="border-t border-white/5 p-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-slate-500 px-3 py-1">Produk</div>
                        @foreach($results['products'] as $prod)
                            <button wire:click="selectResult('{{ $prod->name }}', 'product', {{ $prod->id }})" class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/5 flex items-center gap-3 group transition-colors">
                                <div class="w-10 h-10 rounded-lg bg-slate-800 overflow-hidden flex-shrink-0">
                                    @if($prod->image_path)
                                        <img src="{{ asset('storage/' . $prod->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-600">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-bold text-slate-300 truncate group-hover:text-white">{{ $prod->name }}</div>
                                    <div class="text-xs font-mono text-cyan-500">Rp {{ number_format($prod->sell_price, 0, ',', '.') }}</div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            @endif
        @else
            <!-- Recent Searches -->
            @if(!empty($recentSearches))
                <div class="p-2">
                    <div class="flex justify-between items-center px-3 py-1 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Terakhir Dicari</span>
                        <button wire:click="clearRecent" class="text-[10px] text-rose-500 hover:text-rose-400">Hapus Semua</button>
                    </div>
                    @foreach($recentSearches as $term)
                        <button wire:click="selectResult('{{ $term }}', 'search')" class="w-full text-left px-3 py-2 rounded-lg hover:bg-white/5 flex items-center gap-3 text-sm text-slate-400 hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $term }}
                        </button>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-slate-500 text-sm">
                    Ketik untuk mulai mencari...
                </div>
            @endif
        @endif
        
        <!-- Helper Footer -->
        <div class="bg-slate-950 p-2 border-t border-white/5 flex justify-between px-4 text-[10px] text-slate-500">
            <span><strong>Enter</strong> untuk memilih</span>
            <span><strong>Esc</strong> untuk tutup</span>
        </div>
    </div>
</div>
