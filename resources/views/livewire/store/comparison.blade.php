<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Bandingkan <span class="text-blue-600">Produk</span>
            </h1>
            @if(count($products) > 0)
                <button wire:click="clearAll" class="text-red-600 font-bold hover:underline text-sm">Hapus Semua</button>
            @endif
        </div>

        @if(count($products) === 0)
            <div class="text-center py-24 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada produk untuk dibandingkan.</h3>
                <p class="text-slate-500 mb-6">Jelajahi katalog dan klik tombol "Bandingkan" pada produk.</p>
                <a href="{{ route('store.catalog') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                    Ke Katalog
                </a>
            </div>
        @else
            <div class="overflow-x-auto pb-6">
                <div class="min-w-[800px]">
                    <table class="w-full text-left border-collapse">
                        <!-- Product Info Row -->
                        <thead>
                            <tr>
                                <th class="p-4 w-48 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 sticky left-0 z-10">
                                    <span class="text-xs font-bold uppercase text-slate-500">Produk</span>
                                </th>
                                @foreach($products as $product)
                                    <th class="p-6 w-64 align-top bg-white dark:bg-slate-800 border-b border-r border-slate-200 dark:border-slate-700 relative group">
                                        <button wire:click="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <div class="h-32 mb-4 flex items-center justify-center bg-slate-100 dark:bg-slate-700 rounded-xl overflow-hidden">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <h3 class="font-bold text-slate-900 dark:text-white mb-2 leading-tight h-12 overflow-hidden">{{ $product->name }}</h3>
                                        <p class="font-black text-blue-600 text-lg mb-4">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        <a href="{{ route('product.detail', $product->id) }}" class="block w-full py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-bold text-center rounded-lg text-sm transition-colors">
                                            Lihat Detail
                                        </a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Basic Info -->
                            <tr>
                                <td class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 sticky left-0 z-10 font-bold text-sm text-slate-600">SKU</td>
                                @foreach($products as $product)
                                    <td class="p-4 bg-white dark:bg-slate-800 border-b border-r border-slate-200 dark:border-slate-700 text-sm text-slate-500">
                                        {{ $product->sku }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 sticky left-0 z-10 font-bold text-sm text-slate-600">Kategori</td>
                                @foreach($products as $product)
                                    <td class="p-4 bg-white dark:bg-slate-800 border-b border-r border-slate-200 dark:border-slate-700 text-sm text-slate-500">
                                        {{ $product->category->name ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Dynamic Specs -->
                            @foreach($specKeys as $key)
                                <tr>
                                    <td class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 sticky left-0 z-10 font-bold text-sm text-slate-600 capitalize">
                                        {{ str_replace('_', ' ', $key) }}
                                    </td>
                                    @foreach($products as $product)
                                        <td class="p-4 bg-white dark:bg-slate-800 border-b border-r border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white">
                                            {{ $product->specifications[$key] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
