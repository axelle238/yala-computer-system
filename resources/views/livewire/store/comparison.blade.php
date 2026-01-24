<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                Product <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Comparison</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Bandingkan spesifikasi teknis untuk menemukan produk terbaik sesuai kebutuhan Anda.</p>
        </div>

        @if($products->isEmpty())
            <div class="max-w-md mx-auto text-center py-16 bg-white dark:bg-slate-800 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada produk</h3>
                <p class="text-slate-500 mb-6">Pilih minimal 2 produk dari halaman toko untuk dibandingkan.</p>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-600/30 transition-all inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Toko
                </a>
            </div>
        @else
            <div class="relative overflow-x-auto rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4 w-48 min-w-[200px] bg-slate-50 dark:bg-slate-900 sticky left-0 z-10 border-r border-slate-200 dark:border-slate-700">
                                <div class="flex flex-col gap-2">
                                    <span>Fitur / Spesifikasi</span>
                                    <button wire:click="clearComparison" class="text-rose-500 hover:text-rose-600 text-[10px] normal-case font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Hapus Semua
                                    </button>
                                </div>
                            </th>
                            @foreach($products as $product)
                                <th class="px-6 py-6 w-64 min-w-[250px] align-top bg-white dark:bg-slate-800 border-r border-slate-100 dark:border-slate-700 last:border-0 relative group">
                                    <button wire:click="removeProduct({{ $product->id }})" class="absolute top-2 right-2 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                    <div class="h-32 mb-4 flex items-center justify-center">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-full max-w-full object-contain">
                                        @else
                                            <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <a href="{{ route('product.detail', $product->id) }}" class="text-lg font-black text-slate-900 dark:text-white hover:text-purple-600 transition-colors line-clamp-2 mb-2 block min-h-[3.5rem]">
                                        {{ $product->name }}
                                    </a>
                                    <div class="font-mono text-xl font-bold text-purple-600">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                        <!-- Category -->
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 sticky left-0 border-r border-slate-200 dark:border-slate-700">Kategori</td>
                            @foreach($products as $product)
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-700">{{ $product->category->name }}</td>
                            @endforeach
                        </tr>
                        <!-- Warranty -->
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 sticky left-0 border-r border-slate-200 dark:border-slate-700">Garansi</td>
                            @foreach($products as $product)
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400 border-r border-slate-100 dark:border-slate-700">{{ $product->warranty_duration ?? '-' }} Bulan</td>
                            @endforeach
                        </tr>
                        <!-- Stock -->
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 sticky left-0 border-r border-slate-200 dark:border-slate-700">Stok</td>
                            @foreach($products as $product)
                                <td class="px-6 py-4 border-r border-slate-100 dark:border-slate-700">
                                    @if($product->stock_quantity > 0)
                                        <span class="text-emerald-600 font-bold text-xs bg-emerald-100 px-2 py-1 rounded">Tersedia ({{ $product->stock_quantity }})</span>
                                    @else
                                        <span class="text-rose-600 font-bold text-xs bg-rose-100 px-2 py-1 rounded">Habis</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <!-- Action -->
                        <tr class="bg-slate-50 dark:bg-slate-900">
                            <td class="px-6 py-4 bg-slate-50 dark:bg-slate-900 sticky left-0 border-r border-slate-200 dark:border-slate-700"></td>
                            @foreach($products as $product)
                                <td class="px-6 py-4 border-r border-slate-200 dark:border-slate-700">
                                    @if($product->stock_quantity > 0)
                                        <button wire:click="$dispatch('addToCart', {productId: {{ $product->id }}})" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow transition-colors text-xs uppercase tracking-wider">
                                            Ke Keranjang
                                        </button>
                                    @else
                                        <button disabled class="w-full py-3 bg-slate-200 text-slate-400 font-bold rounded-xl text-xs uppercase tracking-wider cursor-not-allowed">
                                            Stok Habis
                                        </button>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
