<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-4">
            <button class="w-full py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm"
                    x-data x-on:click="$dispatch('open-filter-drawer')">
                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                Filter & Sort
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters (Desktop) -->
            <aside class="hidden lg:block w-64 flex-shrink-0 space-y-8">
                <!-- Search -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Pencarian</h3>
                    <div class="relative">
                        <input wire:model.live.debounce.500ms="search" type="text" class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-blue-500" placeholder="Nama produk...">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Kategori</h3>
                    <div class="space-y-1">
                        <button wire:click="$set('category', '')" class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $category === '' ? 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                            Semua Produk
                        </button>
                        @foreach($categories as $cat)
                            <button wire:click="$set('category', '{{ $cat->slug }}')" class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors flex justify-between items-center group {{ $category === $cat->slug ? 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs text-slate-400 bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded-md group-hover:bg-white dark:group-hover:bg-slate-600">{{ $cat->products_count }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Harga</h3>
                    <div class="space-y-4">
                        <div class="flex gap-2 items-center">
                            <input wire:model.live.debounce.500ms="min_price" type="number" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-mono" placeholder="Min">
                            <span class="text-slate-400">-</span>
                            <input wire:model.live.debounce.500ms="max_price" type="number" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-mono" placeholder="Max">
                        </div>
                        <input type="range" wire:model.live.debounce.300ms="max_price" min="0" max="50000000" step="500000" class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer dark:bg-slate-700 accent-blue-600">
                    </div>
                </div>

                <!-- Dynamic Filters -->
                @if(!empty($availableFilters))
                    @foreach($availableFilters as $key => $options)
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">{{ ucfirst($key) }}</h3>
                            <div class="space-y-2">
                                @foreach($options as $option)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" wire:model.live="specs.{{ $key }}" value="{{ $option }}" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 focus:ring-blue-500 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-blue-600 transition-colors">{{ $option }}</span>
                                    </label>
                                @endforeach
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" wire:model.live="specs.{{ $key }}" value="" class="w-4 h-4 text-slate-400 bg-slate-100 border-slate-300 focus:ring-slate-500 dark:bg-slate-700 dark:border-slate-600">
                                    <span class="text-sm text-slate-400 group-hover:text-slate-600 transition-colors italic">Semua</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif

                <button wire:click="resetFilters" class="w-full py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Reset Filter
                </button>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Toolbar -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span class="font-bold text-slate-900 dark:text-white">{{ $products->total() }}</span> produk
                    </p>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase hidden sm:block">Urutkan:</label>
                        <select wire:model.live="sort" class="bg-white dark:bg-slate-800 border-none rounded-lg text-sm font-bold focus:ring-0 cursor-pointer shadow-sm pl-3 pr-8 py-2">
                            <option value="newest">Terbaru</option>
                            <option value="price_low">Harga Terendah</option>
                            <option value="price_high">Harga Tertinggi</option>
                            <option value="name">Nama (A-Z)</option>
                        </select>
                    </div>
                </div>

                <!-- Product Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/50 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-800 transition-all group flex flex-col relative overflow-hidden">
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                    @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                        <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-md shadow-sm">Sisa {{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity == 0)
                                        <span class="px-2 py-0.5 bg-slate-500 text-white text-[10px] font-bold rounded-md shadow-sm">Stok Habis</span>
                                    @endif
                                </div>

                                <!-- Image -->
                                <div class="relative group/image">
                                    <a href="{{ route('product.detail', $product->id) }}" class="block relative aspect-square mb-4 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $product->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <!-- Quick View Button -->
                                    <button wire:click="$dispatch('openQuickView', { productId: {{ $product->id }} })" 
                                            class="absolute bottom-2 right-2 p-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur rounded-lg text-slate-500 hover:text-blue-600 shadow-sm opacity-0 group-hover/image:opacity-100 transition-all transform translate-y-2 group-hover/image:translate-y-0"
                                            title="Quick View">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 flex flex-col">
                                    <div class="text-xs text-slate-400 mb-1">{{ $product->category->name }}</div>
                                    <h3 class="font-bold text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                        <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                                    </h3>
                                    
                                    <div class="mt-auto pt-2 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                                        <span class="font-black text-lg text-blue-600 dark:text-blue-400 font-mono">
                                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                        </span>
                                        <a href="{{ route('product.detail', $product->id) }}" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Produk tidak ditemukan</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-6">Coba ubah kata kunci pencarian atau kurangi filter yang Anda gunakan.</p>
                        <button wire:click="resetFilters" class="px-6 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:opacity-90 transition-opacity">
                            Reset Semua Filter
                        </button>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
