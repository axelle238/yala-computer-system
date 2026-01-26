<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Perbandingan <span class="text-cyan-500">Produk</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Bandingkan spesifikasi dan harga untuk keputusan terbaik.</p>
        </div>

        @if(count($products) > 0)
            <div class="overflow-x-auto pb-6">
                <div class="min-w-max">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 w-48 bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 sticky left-0 z-10">
                                    <span class="text-xs font-bold uppercase text-slate-500">Spesifikasi</span>
                                </th>
                                @foreach($products as $product)
                                    <th class="p-4 w-72 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 align-top relative group">
                                        <button wire:click="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 text-slate-300 hover:text-rose-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        
                                        <div class="mb-4 h-40 flex items-center justify-center bg-slate-50 dark:bg-slate-800 rounded-xl overflow-hidden">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-full max-w-full object-contain mix-blend-multiply dark:mix-blend-normal">
                                            @else
                                                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        
                                        <h3 class="font-bold text-slate-900 dark:text-white mb-2 leading-tight h-10 line-clamp-2">
                                            <a href="{{ route('toko.produk.detail', $product->id) }}" class="hover:text-cyan-500">{{ $product->name }}</a>
                                        </h3>
                                        <div class="text-xl font-black text-cyan-600 font-mono mb-4">
                                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                        </div>
                                        <button wire:click="addToCart({{ $product->id }})" class="w-full py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-lg hover:bg-cyan-600 dark:hover:bg-cyan-400 transition-colors text-sm">
                                            + Keranjang
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            <!-- Category -->
                            <tr>
                                <td class="p-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 sticky left-0">Kategori</td>
                                @foreach($products as $product)
                                    <td class="p-4 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900">{{ $product->category->name }}</td>
                                @endforeach
                            </tr>
                            
                            <!-- Stock -->
                            <tr>
                                <td class="p-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 sticky left-0">Ketersediaan</td>
                                @foreach($products as $product)
                                    <td class="p-4 bg-white dark:bg-slate-900">
                                        @if($product->stock_quantity > 0)
                                            <span class="text-emerald-600 font-bold">Ready Stock ({{ $product->stock_quantity }})</span>
                                        @else
                                            <span class="text-rose-500 font-bold">Habis</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>

                            <!-- Dynamic Specs -->
                            <!-- Note: In a real complex app, specs would be EAV or JSON. Assuming JSON 'specifications' column or parsing description for now. -->
                            @php
                                // Mock specs keys for visual structure
                                $specKeys = ['Processor', 'RAM', 'Storage', 'Graphics', 'Warranty']; 
                            @endphp
                            
                            @foreach($specKeys as $key)
                                <tr>
                                    <td class="p-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 sticky left-0">{{ $key }}</td>
                                    @foreach($products as $product)
                                        <td class="p-4 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900">
                                            {{-- Try to find key in specs array, else fallback --}}
                                            {{ $product->specifications[$key] ?? '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            <!-- Description (Excerpt) -->
                            <tr>
                                <td class="p-4 font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 sticky left-0 align-top">Deskripsi</td>
                                @foreach($products as $product)
                                    <td class="p-4 text-slate-500 text-xs leading-relaxed bg-white dark:bg-slate-900 align-top min-w-[250px]">
                                        {{ Str::limit($product->description, 150) }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum Ada Produk</h3>
                <p class="text-slate-500 mb-8">Pilih produk dari katalog untuk mulai membandingkan.</p>
                <a href="{{ route('toko.katalog') }}" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg transition-all">
                    Buka Katalog
                </a>
            </div>
        @endif

    </div>
</div>