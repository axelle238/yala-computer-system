@push('meta')
    <meta property="og:title" content="{{ $product->name }} - Yala Computer">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ asset('storage/' . $product->image_path) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="product:price:amount" content="{{ $product->sell_price }}">
    <meta property="product:price:currency" content="IDR">
@endpush

<div class="min-h-screen pt-24 pb-12 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] animate-pulse delay-700"></div>
        <div class="absolute inset-0 cyber-grid opacity-30"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8 font-mono">
            <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Home</a>
            <span>/</span>
            <span class="text-cyan-500">{{ $product->category->name }}</span>
            <span>/</span>
            <span class="text-slate-300 truncate max-w-[200px]">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Product Image -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 to-blue-500/10 rounded-3xl blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="relative bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-3xl p-8 h-[500px] flex items-center justify-center overflow-hidden tech-card">
                    <div class="absolute inset-0 grid-pattern opacity-10"></div>
                    
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-full max-w-full object-contain drop-shadow-2xl transition-transform duration-700 group-hover:scale-105 group-hover:rotate-2">
                    @else
                        <svg class="w-32 h-32 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    @endif

                    <!-- Zoom Icon -->
                    <div class="absolute bottom-4 right-4 p-3 bg-slate-800/80 backdrop-blur rounded-xl text-slate-400 opacity-0 group-hover:opacity-100 transition-all cursor-pointer hover:text-cyan-400 hover:bg-slate-800">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <div class="mb-auto">
                    <span class="inline-block px-3 py-1 rounded bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold tracking-widest uppercase mb-4">{{ $product->category->name }}</span>
                    <h1 class="text-4xl md:text-5xl font-black font-tech text-white leading-tight mb-4 tracking-wide">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-5 h-5 {{ $i < round($product->reviews->avg('rating')) ? 'fill-current' : 'text-slate-700' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            @endfor
                            <span class="text-slate-400 text-sm ml-2 font-mono">({{ $product->reviews->count() }} Reviews)</span>
                        </div>
                        <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                        <div class="text-sm font-mono text-slate-400">ID: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="bg-slate-900/50 rounded-2xl p-6 border border-white/5 mb-8">
                        <div class="flex items-end gap-4 mb-2">
                            <span class="text-4xl font-bold text-cyan-400 font-mono">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            @if($product->stock_quantity > 0)
                                <span class="flex items-center gap-2 text-sm font-bold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20 mb-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Ready Stock
                                </span>
                            @else
                                <span class="flex items-center gap-2 text-sm font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20 mb-1">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Stok Habis
                                </span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-sm">Harga sudah termasuk PPN. Garansi Resmi {{ $product->warranty_duration ?? '12' }} Bulan.</p>
                    </div>

                    <div class="prose prose-invert prose-slate max-w-none text-slate-300 font-light leading-relaxed mb-8">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 mt-8 pt-8 border-t border-white/10 sticky bottom-0 bg-slate-950/80 backdrop-blur-md p-4 -mx-4 lg:static lg:bg-transparent lg:p-0">
                    @if($product->stock_quantity > 0)
                        <button wire:click="addToCart" class="flex-1 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/40 flex items-center justify-center gap-3 group" wire:loading.attr="disabled">
                            <span wire:loading.remove class="flex items-center gap-3">
                                <svg class="w-6 h-6 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="uppercase tracking-wider">Tambah ke Keranjang</span>
                            </span>
                            <span wire:loading class="flex items-center gap-3">
                                <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span class="uppercase tracking-wider">Memproses...</span>
                            </span>
                        </button>
                    @else
                        <div class="flex-1">
                            <livewire:store.product-alert :productId="$product->id" />
                        </div>
                    @endif
                    
                    <!-- Wishlist Button -->
                    @php
                        $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                    @endphp
                    <button wire:click="$dispatch('addToWishlist', {productId: {{ $product->id }}})" class="p-4 rounded-xl border border-white/10 hover:bg-white/5 text-slate-400 hover:text-pink-500 transition-all {{ $inWishlist ? 'text-pink-500 bg-pink-500/10 border-pink-500/30' : '' }}">
                        <svg class="w-6 h-6 {{ $inWishlist ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Specifications / Details Tabs -->
        <div class="mb-20">
            <div class="border-b border-white/10 mb-8">
                <div class="flex gap-8">
                    <button class="pb-4 border-b-2 border-cyan-500 text-cyan-400 font-bold uppercase tracking-wider text-sm">Spesifikasi Detail</button>
                    <button class="pb-4 border-b-2 border-transparent text-slate-500 hover:text-slate-300 font-bold uppercase tracking-wider text-sm transition-colors">Ulasan Pembeli</button>
                </div>
            </div>
            
            <div class="bg-slate-900/50 rounded-3xl p-8 border border-white/5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                    <!-- Example Specs - In real app, this would be dynamic attributes -->
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-slate-500">Kategori</span>
                            <span class="text-slate-200 font-mono">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-slate-500">Berat</span>
                            <span class="text-slate-200 font-mono">1.2 kg</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-slate-500">Garansi</span>
                            <span class="text-slate-200 font-mono">{{ $product->warranty_duration ?? '12' }} Bulan</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-slate-500">Kondisi</span>
                            <span class="text-slate-200 font-mono">Baru</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-2">
                            <span class="text-slate-500">SKU</span>
                            <span class="text-slate-200 font-mono">PROD-{{ $product->id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Component -->
            <livewire:store.product-reviews :productId="$product->id" />
            
            <!-- Discussions Component -->
            <livewire:store.product-discussions :productId="$product->id" />
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mb-12">
                <h3 class="text-2xl font-black font-tech text-white mb-8 uppercase tracking-wide flex items-center gap-2">
                    <span class="w-2 h-8 bg-cyan-500 rounded-full"></span>
                    Produk Terkait
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.detail', $related->id) }}" class="group relative bg-slate-900 border border-white/5 hover:border-cyan-500/50 rounded-2xl p-4 transition-all duration-300 hover:shadow-lg hover:shadow-cyan-900/20 hover:-translate-y-1 block">
                            <div class="h-40 bg-slate-800/50 rounded-xl mb-4 flex items-center justify-center overflow-hidden">
                                @if($related->image_path)
                                    <img src="{{ asset('storage/' . $related->image_path) }}" class="max-h-[85%] object-contain transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <svg class="w-10 h-10 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <h4 class="font-bold text-white text-sm line-clamp-2 mb-2 group-hover:text-cyan-400 transition-colors">{{ $related->name }}</h4>
                            <p class="font-mono text-cyan-400 font-bold">Rp {{ number_format($related->sell_price, 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <livewire:store.recently-viewed />
</div>
