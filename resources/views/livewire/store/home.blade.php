<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 relative">
    
    <!-- Hero Banner Slider -->
    <div class="relative overflow-hidden group">
        <div class="flex transition-transform duration-500 ease-out" style="transform: translateX(0%);" x-data="{ activeSlide: 0 }" x-init="setInterval(() => { activeSlide = activeSlide < {{ $banners->count() - 1 }} ? activeSlide + 1 : 0 }, 5000)">
            <!-- Banners loop handled via JS/Alpine usually, but here simplifying with first banner or simple grid for MVP if no JS lib for slider is ready. 
                 Let's stick to simple CSS Snap or grid if multiple banners. For now, assuming standard static implementation or single hero for simplicity in backend code generation. 
                 Actually, let's make it a nice grid of banners.
            -->
        </div>
        
        @if($banners->isNotEmpty())
            <div class="relative h-[400px] md:h-[500px] overflow-hidden">
                <!-- Simple Fade Slider Logic via CSS/Alpine could go here, for now Static Hero based on first banner -->
                @foreach($banners as $index => $banner)
                    <div class="absolute inset-0 transition-opacity duration-1000 {{ $loop->first ? 'opacity-100 relative' : 'opacity-0' }}">
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
                        <div class="absolute bottom-0 left-0 p-8 md:p-16 max-w-3xl">
                            <h2 class="text-4xl md:text-6xl font-black text-white font-tech mb-4 tracking-tighter shadow-black drop-shadow-lg leading-none">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-lg text-slate-200 mb-8 font-light max-w-xl">{{ $banner->description }}</p>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" class="inline-block px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-black rounded-none skew-x-[-10deg] transition-transform hover:translate-x-2">
                                    <span class="skew-x-[10deg] inline-block uppercase tracking-widest">Lihat Penawaran</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Fallback Hero -->
            <div class="relative h-[400px] bg-slate-800 flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 cyber-grid opacity-20"></div>
                <div class="text-center z-10 p-4">
                    <h1 class="text-5xl md:text-7xl font-black font-tech text-white mb-2 uppercase tracking-tighter">
                        Yala <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Computer</span>
                    </h1>
                    <p class="text-slate-400 text-xl tracking-wide font-light">Pusat Belanja Hardware IT & Gaming Gear Terlengkap</p>
                </div>
            </div>
        @endif
    </div>

    <div class="container mx-auto px-4 lg:px-8 -mt-8 relative z-10">
        <!-- Service Features -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-12">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 flex items-center gap-4 group hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-xl flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Produk Original</h4>
                    <p class="text-xs text-slate-500">Garansi Resmi 100%</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 flex items-center gap-4 group hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Pengiriman Cepat</h4>
                    <p class="text-xs text-slate-500">Aman & Terpercaya</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 flex items-center gap-4 group hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Service Center</h4>
                    <p class="text-xs text-slate-500">Layanan Purna Jual</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 flex items-center gap-4 group hover:-translate-y-1 transition-transform">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Harga Terbaik</h4>
                    <p class="text-xs text-slate-500">Bersaing & Kompetitif</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 mt-16">
            
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4 space-y-8 animate-fade-in-up">
                
                <!-- Search -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 uppercase text-sm tracking-wider">Pencarian</h3>
                    <div class="relative">
                        <input wire:model.live.debounce.500ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 focus:border-cyan-500 transition-all text-sm" placeholder="Cari produk...">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>

                <!-- Categories -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 uppercase text-sm tracking-wider">Kategori</h3>
                    <div class="space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" wire:model.live="category" value="" class="w-4 h-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded-full">
                            <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-cyan-500 transition-colors">Semua Kategori</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" wire:model.live="category" value="{{ $cat->id }}" class="w-4 h-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded-full">
                                <span class="text-sm text-slate-600 dark:text-slate-300 group-hover:text-cyan-500 transition-colors">{{ $cat->name }}</span>
                                <span class="ml-auto text-xs bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded-full text-slate-400">{{ $cat->products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-6 uppercase text-sm tracking-wider">Range Harga</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-xs text-slate-500 mb-1 block">Minimum</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 text-xs">Rp</span>
                                <input type="number" wire:model.live.debounce.500ms="minPrice" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 mb-1 block">Maksimum</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 text-xs">Rp</span>
                                <input type="number" wire:model.live.debounce.500ms="maxPrice" class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm">
                            </div>
                        </div>
                        
                        <input type="range" min="0" max="{{ $priceRangeMax }}" step="100000" wire:model.live.debounce.300ms="maxPrice" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-cyan-600">
                        <div class="text-xs text-center text-slate-400">Geser untuk set max harga</div>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="lg:w-3/4 animate-fade-in-up delay-100">
                
                <!-- Toolbar -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="text-sm text-slate-500">
                        Menampilkan <span class="font-bold text-slate-900 dark:text-white">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> dari <span class="font-bold">{{ $products->total() }}</span> produk
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">Urutkan:</span>
                        <select wire:model.live="sort" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 pl-3 pr-8 focus:ring-cyan-500">
                            <option value="latest">Terbaru</option>
                            <option value="price_asc">Harga Terendah</option>
                            <option value="price_desc">Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @forelse($products as $product)
                        <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-cyan-500/50 hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300 flex flex-col overflow-hidden relative">
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
                                @if($product->stock_quantity < 1)
                                    <span class="px-2 py-1 bg-rose-500/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded shadow-lg">Habis</span>
                                @elseif($product->stock_quantity < 5)
                                    <span class="px-2 py-1 bg-amber-500/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded shadow-lg">Stok Menipis</span>
                                @endif
                                
                                @if($product->category->name == 'Bundling')
                                    <span class="px-2 py-1 bg-purple-500/90 backdrop-blur text-white text-[10px] font-bold uppercase rounded shadow-lg">Bundle</span>
                                @endif
                            </div>

                            <!-- Image -->
                            <div class="relative h-48 md:h-56 bg-slate-100 dark:bg-slate-700/50 overflow-hidden cursor-pointer" wire:click="openProduct({{ $product->id }})">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                
                                <!-- Quick Actions Overlay -->
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-[2px]">
                                    <button wire:click.stop="addToCart({{ $product->id }})" class="p-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75" title="Tambah ke Keranjang">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </button>
                                    <button wire:click.stop="addToCompare({{ $product->id }})" class="p-3 bg-white hover:bg-slate-100 text-slate-800 rounded-xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-100" title="Bandingkan">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="text-[10px] font-bold uppercase text-slate-400 mb-1 tracking-wide">{{ $product->category->name }}</div>
                                <h3 class="font-bold text-slate-900 dark:text-white mb-2 leading-tight group-hover:text-cyan-500 transition-colors cursor-pointer" wire:click="openProduct({{ $product->id }})">
                                    {{ $product->name }}
                                </h3>
                                
                                <!-- Rating Stars -->
                                <div class="flex items-center gap-1 mb-3">
                                    @php $rating = round($product->reviews_avg_rating ?? 0); @endphp
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $rating ? 'text-amber-400 fill-current' : 'text-slate-300 dark:text-slate-600' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                    <span class="text-xs text-slate-400 ml-1">({{ $product->reviews_count ?? 0 }})</span>
                                </div>

                                <div class="mt-auto pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                    <div class="font-black text-lg text-slate-900 dark:text-white font-mono">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center text-slate-400 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                            <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <p class="text-lg font-bold">Produk tidak ditemukan.</p>
                            <p class="text-sm">Coba sesuaikan filter pencarian Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>