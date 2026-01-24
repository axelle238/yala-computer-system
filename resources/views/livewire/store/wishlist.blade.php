<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="flex justify-between items-center mb-8 animate-fade-in-up">
            <div>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Favorit <span class="text-pink-500">Saya</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Simpan produk impianmu disini.</p>
            </div>
            <a href="{{ route('store.catalog') }}" class="text-sm font-bold text-cyan-600 hover:underline">Lanjut Belanja &rarr;</a>
        </div>

        @if($items->isEmpty())
            <div class="text-center py-24 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm animate-fade-in-up">
                <div class="w-20 h-20 bg-pink-100 dark:bg-pink-900/20 text-pink-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Wishlist Kosong</h3>
                <p class="text-slate-500">Kamu belum menyimpan produk apapun.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up delay-100">
                @foreach($items as $item)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all group relative">
                        <button wire:click="remove({{ $item->id }})" class="absolute top-3 right-3 p-2 bg-white dark:bg-slate-900 text-slate-400 hover:text-pink-500 rounded-full shadow-sm z-10 transition-colors" title="Hapus">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ route('product.detail', $item->product->id) }}" class="block aspect-square bg-slate-50 dark:bg-slate-900 rounded-xl mb-4 overflow-hidden relative">
                            @if($item->product->image_path)
                                <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </a>

                        <div class="mb-4">
                            <div class="text-[10px] text-cyan-500 font-bold uppercase tracking-wider mb-1">{{ $item->product->category->name ?? 'Uncategorized' }}</div>
                            <h3 class="font-bold text-slate-800 dark:text-white line-clamp-2 mb-2 group-hover:text-cyan-600 transition-colors">
                                <a href="{{ route('product.detail', $item->product->id) }}">{{ $item->product->name }}</a>
                            </h3>
                            <div class="font-mono font-bold text-slate-900 dark:text-white">
                                Rp {{ number_format($item->product->sell_price, 0, ',', '.') }}
                            </div>
                        </div>

                        <button wire:click="moveToCart({{ $item->id }})" class="w-full py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-lg text-sm hover:opacity-90 transition-opacity">
                            Pindahkan ke Keranjang
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>