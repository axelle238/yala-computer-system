<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fade-in-up">
            <div>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Product <span class="text-blue-600">Comparison</span>
                </h1>
                <p class="text-slate-500 text-sm">Bandingkan spesifikasi hingga 4 produk sekaligus.</p>
            </div>
            @if($products->count() > 0)
                <button wire:click="clearComparison" class="px-4 py-2 bg-rose-100 hover:bg-rose-200 text-rose-600 rounded-lg text-sm font-bold transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Bersihkan
                </button>
            @endif
        </div>

        @if($products->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 text-center border border-slate-200 dark:border-slate-700 shadow-sm animate-fade-in-up">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada produk untuk dibandingkan</h3>
                <p class="text-slate-500 mb-6">Jelajahi katalog kami dan klik tombol "Bandingkan" pada produk.</p>
                <a href="{{ route('store.catalog') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all inline-flex items-center gap-2">
                    Ke Katalog
                </a>
            </div>
        @else
            <div class="overflow-x-auto pb-4 custom-scrollbar animate-fade-in-up delay-100">
                <table class="w-full min-w-[800px] border-collapse">
                    <thead>
                        <tr>
                            <th class="p-4 w-48 bg-slate-50 dark:bg-slate-900 sticky left-0 z-10"></th>
                            @foreach($products as $product)
                                <th class="p-4 w-72 align-top text-left bg-white dark:bg-slate-800 border-x border-slate-100 dark:border-slate-700 min-w-[280px]">
                                    <div class="relative group">
                                        <button wire:click="removeProduct({{ $product->id }})" class="absolute -top-2 -right-2 p-1 bg-white dark:bg-slate-700 shadow-md rounded-full text-slate-400 hover:text-rose-500 border border-slate-200 dark:border-slate-600 opacity-0 group-hover:opacity-100 transition-all z-20">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        
                                        <div class="aspect-square bg-slate-50 dark:bg-slate-900 rounded-xl mb-4 flex items-center justify-center p-4">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                                            @else
                                                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        
                                        <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight mb-2 min-h-[3.5rem] line-clamp-2">
                                            <a href="{{ route('product.detail', $product->id) }}" class="hover:text-blue-600 transition-colors">{{ $product->name }}</a>
                                        </h3>
                                        
                                        <p class="font-mono font-black text-blue-600 text-xl mb-4">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        
                                        <button wire:click="$dispatch('addToCart', {productId: {{ $product->id }}})" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors">
                                            + Keranjang
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <!-- Basic Info -->
                        <tr class="bg-slate-50 dark:bg-slate-900/50">
                            <td class="p-4 font-bold text-slate-500 uppercase text-xs sticky left-0 bg-slate-50 dark:bg-slate-900/50 z-10 border-y border-slate-200 dark:border-slate-700">Kategori</td>
                            @foreach($products as $product)
                                <td class="p-4 border border-slate-100 dark:border-slate-700 font-medium text-slate-700 dark:text-slate-300">
                                    {{ $product->category->name }}
                                </td>
                            @endforeach
                        </tr>
                        
                        <!-- Dynamic Specs -->
                        @foreach($specKeys as $key)
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="p-4 font-bold text-slate-600 dark:text-slate-400 capitalize sticky left-0 bg-white dark:bg-slate-900 z-10 border-b border-slate-100 dark:border-slate-700 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                    {{ str_replace('_', ' ', $key) }}
                                </td>
                                @foreach($products as $product)
                                    <td class="p-4 border border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                                        {{ $product->specifications[$key] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <!-- Extra Info -->
                        <tr>
                            <td class="p-4 font-bold text-slate-600 dark:text-slate-400 capitalize sticky left-0 bg-white dark:bg-slate-900 z-10 border-b border-slate-100 dark:border-slate-700">Garansi</td>
                            @foreach($products as $product)
                                <td class="p-4 border border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-400">
                                    {{ $product->warranty_duration ?? '-' }} Bulan
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>