<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                My <span class="text-pink-500">Wishlist</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Daftar produk impian Anda. Simpan sekarang, beli nanti.</p>
        </div>

        @if($items->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-100">
                @foreach($items as $item)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all group relative">
                        <button wire:click="remove({{ $item->id }})" class="absolute top-3 right-3 text-slate-300 hover:text-rose-500 transition z-10">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="h-48 bg-slate-100 dark:bg-slate-900 rounded-xl mb-4 overflow-hidden flex items-center justify-center relative">
                            @if($item->product->image_path)
                                <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            @else
                                <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <h3 class="font-bold text-slate-900 dark:text-white line-clamp-2 min-h-[2.5rem]">
                                <a href="{{ route('store.product.detail', $item->product->id) }}">{{ $item->product->name }}</a>
                            </h3>
                            <div class="font-mono text-cyan-600 font-black text-lg">Rp {{ number_format($item->product->sell_price, 0, ',', '.') }}</div>
                            
                            <button wire:click="moveToCart({{ $item->id }}, {{ $item->product->id }})" class="w-full py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-lg hover:bg-cyan-600 dark:hover:bg-cyan-400 transition-colors text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Pindahkan ke Keranjang
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
                <div class="w-24 h-24 bg-pink-50 dark:bg-pink-900/20 rounded-full flex items-center justify-center mx-auto mb-6 text-pink-400">
                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Wishlist Masih Kosong</h3>
                <p class="text-slate-500 mb-8">Jelajahi katalog kami dan simpan produk favorit Anda di sini.</p>
                <a href="{{ route('store.catalog') }}" class="px-8 py-3 bg-pink-600 hover:bg-pink-500 text-white font-bold rounded-xl shadow-lg transition-all">
                    Mulai Belanja
                </a>
            </div>
        @endif

    </div>
</div>
