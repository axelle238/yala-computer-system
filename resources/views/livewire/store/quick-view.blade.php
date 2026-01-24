<div>
    @if($isOpen && $product)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm animate-fade-in"
             x-data
             @keydown.window.escape="$wire.close()">
            
            <div class="bg-white dark:bg-slate-800 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden relative flex flex-col md:flex-row"
                 @click.away="$wire.close()">
                
                <button wire:click="close" class="absolute top-4 right-4 z-10 p-2 bg-white/10 hover:bg-white/20 rounded-full text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <!-- Image -->
                <div class="w-full md:w-1/2 bg-slate-100 dark:bg-slate-900 flex items-center justify-center p-8 relative">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-[300px] object-contain drop-shadow-xl">
                    @else
                        <svg class="w-24 h-24 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    @endif
                </div>

                <!-- Info -->
                <div class="w-full md:w-1/2 p-8 flex flex-col">
                    <div class="mb-auto">
                        <span class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-2 block">{{ $product->category->name ?? 'Unknown' }}</span>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-4 leading-tight">{{ $product->name }}</h2>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <span class="text-3xl font-bold font-mono text-slate-900 dark:text-white">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            @if($product->stock_quantity > 0)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-bold uppercase">Ready Stock</span>
                            @else
                                <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded text-xs font-bold uppercase">Habis</span>
                            @endif
                        </div>

                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-6 line-clamp-4">
                            {{ $product->description }}
                        </p>

                        <!-- Key Specs -->
                        <div class="grid grid-cols-2 gap-4 text-xs mb-8">
                            @foreach(array_slice($product->specifications ?? [], 0, 4) as $key => $val)
                                <div class="bg-slate-50 dark:bg-slate-700/50 p-2 rounded border border-slate-100 dark:border-slate-700">
                                    <span class="block text-slate-400 font-bold uppercase mb-0.5">{{ str_replace('_', ' ', $key) }}</span>
                                    <span class="block text-slate-800 dark:text-slate-200 font-mono">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('product.detail', $product->id) }}" class="px-6 py-3 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                            Detail Lengkap
                        </a>
                        <button wire:click="addToCart" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all {{ $product->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}>
                            {{ $product->stock_quantity > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
