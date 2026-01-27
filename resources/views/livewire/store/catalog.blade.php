<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-8 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-4">
            <button class="w-full py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm"
                    x-data x-on:click="$dispatch('open-filter-drawer')">
                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                Filter & Urutkan
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters (Desktop) -->
            <aside class="hidden lg:block w-64 flex-shrink-0 space-y-8">
                <!-- Search -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Pencarian</h3>
                    <div class="relative">
                        <input wire:model.live.debounce.500ms="cari" type="text" class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-blue-500" placeholder="Nama produk...">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Kategori</h3>
                    <div class="space-y-1">
                        <button wire:click="$set('kategori', '')" class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors {{ $kategori === '' ? 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                            Semua Produk
                        </button>
                        @foreach($daftarKategori as $cat)
                            <button wire:click="$set('kategori', '{{ $cat->slug }}')" class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors flex justify-between items-center group {{ $kategori === $cat->slug ? 'bg-blue-50 text-blue-700 font-bold dark:bg-blue-900/30 dark:text-blue-400' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs text-slate-400 bg-slate-200 dark:bg-slate-700 px-1.5 py-0.5 rounded-md group-hover:bg-white dark:group-hover:bg-slate-600">{{ $cat->produk_count }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-3 uppercase text-xs tracking-wider">Harga</h3>
                    <div class="space-y-4">
                        <div class="flex gap-2 items-center">
                            <input wire:model.live.debounce.500ms="hargaMin" type="number" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-mono" placeholder="Rp Min">
                            <span class="text-slate-400">-</span>
                            <input wire:model.live.debounce.500ms="hargaMaks" type="number" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-mono" placeholder="Rp Maks">
                        </div>
                        <input type="range" wire:model.live.debounce.300ms="hargaMaks" min="0" max="50000000" step="500000" class="w-full h-1 bg-slate-200 rounded-lg appearance-none cursor-pointer dark:bg-slate-700 accent-blue-600">
                    </div>
                </div>

                <button wire:click="$set('cari', ''); $set('kategori', ''); $set('hargaMin', 0); $set('hargaMaks', 50000000);" class="w-full py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-lg text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Bersihkan Filter
                </button>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Toolbar -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-sm text-slate-500">
                        Menampilkan <span class="font-bold text-slate-900 dark:text-white">{{ $produk->total() }}</span> produk
                    </p>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase hidden sm:block">Urutkan:</label>
                        <select wire:model.live="urutkan" class="bg-white dark:bg-slate-800 border-none rounded-lg text-sm font-bold focus:ring-0 cursor-pointer shadow-sm pl-3 pr-8 py-2">
                            <option value="terbaru">Terbaru</option>
                            <option value="harga_rendah">Harga Terendah</option>
                            <option value="harga_tinggi">Harga Tertinggi</option>
                        </select>
                    </div>
                </div>

                <!-- Product Grid -->
                @if($produk->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                        @foreach($produk as $item)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700/50 hover:shadow-lg hover:border-blue-200 dark:hover:border-blue-800 transition-all group flex flex-col relative overflow-hidden">
                                
                                <!-- Badges -->
                                <div class="absolute top-3 left-3 z-10 flex flex-col gap-1">
                                    @if($item->stock_quantity <= 5 && $item->stock_quantity > 0)
                                        <span class="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-md shadow-sm">Sisa {{ $item->stock_quantity }}</span>
                                    @elseif($item->stock_quantity == 0)
                                        <span class="px-2 py-0.5 bg-slate-500 text-white text-[10px] font-bold rounded-md shadow-sm">Stok Habis</span>
                                    @endif
                                </div>

                                <!-- Image -->
                                <div class="relative group/image">
                                    <a href="{{ route('toko.produk.detail', $item->id) }}" class="block relative aspect-square mb-4 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900">
                                        @if($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $item->name }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <!-- Content -->
                                <div class="p-4 md:p-6">
                                    <div class="text-xs text-slate-400 mb-1">{{ $item->kategori->name }}</div>
                                    <h3 class="font-tech font-bold text-slate-100 group-hover:text-cyan-400 transition-colors line-clamp-2 leading-tight mb-2">{{ $item->name }}</h3>
                                    
                                    <div class="mt-auto pt-2 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                                        <span class="font-black text-lg text-blue-600 dark:text-blue-400 font-mono">
                                            Rp {{ number_format($item->sell_price, 0, ',', '.') }}
                                        </span>
                                        <div class="flex gap-1">
                                            <button wire:click="tambahKeBandingkan({{ $item->id }})" class="p-2 bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-blue-600 rounded-lg transition-colors" title="Bandingkan">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                            </button>
                                            <button wire:click="tambahKeKeranjang({{ $item->id }})" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-blue-600 hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $produk->links() }}
                    </div>

                    <!-- Compare Float Bar -->
                    @if(session()->has('compare_products') && count(session('compare_products')) > 0)
                        <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in-up">
                            <div class="bg-slate-900 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-4 border border-slate-700">
                                <span class="font-bold text-sm">{{ count(session('compare_products')) }} Produk Dipilih</span>
                                <a href="{{ route('toko.bandingkan') }}" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-500 rounded-full text-xs font-bold transition-colors">
                                    Bandingkan Sekarang
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Produk tidak ditemukan</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-6">Coba ubah kata kunci pencarian atau kurangi filter yang Anda gunakan.</p>
                        <button wire:click="$set('cari', ''); $set('kategori', ''); $set('hargaMin', 0); $set('hargaMaks', 50000000);" class="px-6 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl hover:opacity-90 transition-opacity">
                            Reset Semua Filter
                        </button>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
