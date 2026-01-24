<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                My <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-500">Wishlist</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Daftar produk impian yang ingin Anda miliki.</p>
        </div>

        @if($items->isEmpty())
            <div class="max-w-md mx-auto text-center py-16 bg-white dark:bg-slate-800 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <div class="w-20 h-20 bg-pink-50 dark:bg-pink-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Wishlist Kosong</h3>
                <p class="text-slate-500 mb-6">Simpan barang favoritmu agar mudah ditemukan nanti.</p>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-xl shadow-lg shadow-pink-600/30 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Jelajahi Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-xl hover:border-pink-500/50 transition-all group flex flex-col relative animate-fade-in-up">
                        
                        <button wire:click="remove({{ $item->id }})" class="absolute top-2 right-2 z-10 p-2 bg-white/80 dark:bg-slate-900/80 backdrop-blur rounded-full text-slate-400 hover:text-rose-500 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ route('product.detail', $item->product_id) }}" class="block mb-4 relative overflow-hidden rounded-xl bg-slate-100 dark:bg-slate-700 h-48 flex items-center justify-center">
                            @if($item->product->image_path)
                                <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                        </a>

                        <div class="flex-1 flex flex-col">
                            <div class="text-[10px] font-bold uppercase text-slate-400 mb-1">{{ $item->product->category->name }}</div>
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2 line-clamp-2 leading-tight">
                                <a href="{{ route('product.detail', $item->product_id) }}" class="hover:text-pink-500 transition-colors">{{ $item->product->name }}</a>
                            </h3>
                            <div class="font-mono text-lg font-black text-pink-600 mb-4">
                                Rp {{ number_format($item->product->sell_price, 0, ',', '.') }}
                            </div>
                            
                            <div class="mt-auto">
                                @if($item->product->stock_quantity > 0)
                                    <button wire:click="moveToCart({{ $item->id }})" class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        Pindahkan ke Cart
                                    </button>
                                @else
                                    <button disabled class="w-full py-3 bg-slate-200 dark:bg-slate-700 text-slate-400 font-bold rounded-xl cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
