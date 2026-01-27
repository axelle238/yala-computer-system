<div x-data="{ open: @entangle('isOpen') }" 
     @keydown.window.escape="open = false"
     class="relative z-50">
    
    <!-- Trigger Button (Usually in Header) -->
    <button @click="open = !open" class="relative group p-2 rounded-full hover:bg-white/10 transition-colors">
        <svg class="w-6 h-6 text-slate-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        @if($count > 0)
            <span class="absolute top-0 right-0 w-5 h-5 bg-cyan-500 text-slate-900 text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-slate-900 animate-bounce">
                {{ $count }}
            </span>
        @endif
    </button>

    <!-- Overlay -->
    <div x-show="open" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" 
         @click="open = false"
         style="display: none;"></div>

    <!-- Slide-over Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-md w-full bg-white dark:bg-slate-900 shadow-2xl border-l border-white/10 flex flex-col z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-950">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                Keranjang Belanja
                <span class="text-xs bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full">{{ $count }} Items</span>
            </h2>
            <button @click="open = false" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Items -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
            @forelse($products as $item)
                <div class="flex gap-4 group relative">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-2 leading-tight">
                                <a href="{{ route('toko.produk.detail', $item->id) }}" class="hover:text-cyan-500 transition-colors">{{ $item->name }}</a>
                            </h3>
                            <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-rose-500 p-1 opacity-0 group-hover:opacity-100 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2">
                            <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                                <button wire:click="updateQty({{ $item->id }}, -1)" class="px-2 py-1 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors">-</button>
                                <span class="px-2 py-1 text-xs font-bold text-slate-800 dark:text-white min-w-[1.5rem] text-center">{{ $item->cart_qty }}</span>
                                <button wire:click="updateQty({{ $item->id }}, 1)" class="px-2 py-1 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors">+</button>
                            </div>
                            <span class="font-mono font-bold text-cyan-600 dark:text-cyan-400">Rp {{ number_format($item->sell_price * $item->cart_qty, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-1">Keranjang Kosong</h3>
                    <p class="text-sm text-slate-500 mb-6">Ayo isi dengan barang impianmu!</p>
                    <button @click="open = false" class="px-6 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-sm">
                        Lanjut Belanja
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-6 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Subtotal</span>
                <span class="text-2xl font-black font-mono text-slate-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            @if($count > 0)
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('toko.keranjang') }}" class="py-3 text-center bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-white font-bold rounded-xl transition-colors">
                        Lihat Cart
                    </a>
                    <a href="{{ route('toko.pembayaran.aman') }}" class="py-3 text-center bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all">
                        Checkout
                    </a>
                </div>
            @else
                <button disabled class="w-full py-3 bg-slate-200 dark:bg-slate-800 text-slate-400 font-bold rounded-xl cursor-not-allowed">
                    Checkout
                </button>
            @endif
        </div>
    </div>
</div>
