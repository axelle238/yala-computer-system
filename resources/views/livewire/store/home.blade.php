<div x-data="{ wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'), toggleWishlist(id) { if(this.wishlist.includes(id)) { this.wishlist = this.wishlist.filter(i => i !== id); } else { this.wishlist.push(id); } localStorage.setItem('wishlist', JSON.stringify(this.wishlist)); } }">
    
    <!-- Floating Cart -->
    <button wire:click="toggleCart" class="fixed bottom-8 right-8 z-[90] group">
        <div class="absolute inset-0 bg-cyan-500 rounded-full animate-pulse opacity-20"></div>
        <div class="relative w-16 h-16 bg-slate-900 border border-cyan-500/50 text-cyan-400 rounded-full shadow-[0_0_30px_rgba(6,182,212,0.3)] flex items-center justify-center transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-12 group-hover:bg-cyan-500 group-hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            @if(count($cart) > 0)
                <span class="absolute top-0 right-0 bg-fuchsia-500 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-slate-900 transform translate-x-1 -translate-y-1">
                    {{ count($cart) }}
                </span>
            @endif
        </div>
    </button>

    <!-- Cart Sidebar -->
    @if($isCartOpen)
        <div class="fixed inset-0 z-[100] overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" wire:click="toggleCart"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-slate-900 border-l border-white/10 shadow-2xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-bold text-white font-tech uppercase tracking-wider" id="slide-over-title">Keranjang Belanja</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" wire:click="toggleCart" class="relative -m-2 p-2 text-slate-400 hover:text-white">
                                            <span class="absolute -inset-0.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <div class="flow-root">
                                        @if($this->cartItems->count() > 0)
                                            <ul role="list" class="-my-6 divide-y divide-white/10">
                                                @foreach($this->cartItems as $item)
                                                    <li class="flex py-6">
                                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-xl border border-white/10 bg-white/5">
                                                             @if($item->image_path)
                                                                <img src="{{ asset('storage/' . $item->image_path) }}" class="h-full w-full object-cover object-center">
                                                            @else
                                                                <div class="h-full w-full flex items-center justify-center text-slate-600"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4 flex flex-1 flex-col">
                                                            <div>
                                                                <div class="flex justify-between text-base font-bold text-white">
                                                                    <h3>{{ $item->name }}</h3>
                                                                    <p class="ml-4 text-cyan-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                                </div>
                                                                <p class="mt-1 text-sm text-slate-400">{{ $item->category->name }}</p>
                                                            </div>
                                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                                <div class="flex items-center border border-white/20 rounded-lg">
                                                                    <button wire:click="updateCartQty({{ $item->id }}, -1)" class="px-3 py-1 text-slate-400 hover:text-white hover:bg-white/10 transition-colors">-</button>
                                                                    <span class="px-2 font-bold text-white">{{ $item->cart_qty }}</span>
                                                                    <button wire:click="updateCartQty({{ $item->id }}, 1)" class="px-3 py-1 text-slate-400 hover:text-white hover:bg-white/10 transition-colors">+</button>
                                                                </div>
                                                                <button type="button" wire:click="removeFromCart({{ $item->id }})" class="font-medium text-rose-500 hover:text-rose-400 transition-colors">Hapus</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-center py-12 text-slate-500">Keranjang Kosong</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-white/10 px-4 py-6 sm:px-6 bg-slate-900/50">
                                <div class="flex justify-between text-base font-bold text-white">
                                    <p>Total Pesanan</p>
                                    <p class="text-cyan-400 font-tech text-xl">Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-6">
                                    <button wire:click="checkout" class="w-full flex items-center justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 transition-all gap-2" {{ empty($cart) ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        Checkout WhatsApp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <div class="relative pt-8 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="tech-card rounded-[2rem] p-1 shadow-2xl shadow-cyan-900/10 relative overflow-hidden group">
            <div class="relative bg-slate-900 rounded-[2rem] overflow-hidden min-h-[500px] flex items-center" x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }" x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)">
                <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#06b6d4 1px, transparent 1px); background-size: 30px 30px;"></div>

                @forelse($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}" 
                         x-transition:enter="transition transform duration-1000 ease-out"
                         x-transition:enter-start="opacity-0 scale-110"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition transform duration-1000 ease-in"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 w-full h-full"
                         style="display: none;">
                        
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/40 to-transparent"></div>

                        <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-16 max-w-3xl">
                            <span class="inline-block px-3 py-1 bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 rounded text-xs font-bold uppercase tracking-widest w-fit mb-4">Featured Promo</span>
                            <h1 class="text-4xl md:text-7xl font-black text-white tracking-tight leading-[1.1] mb-6 font-tech drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">{{ $banner->title }}</h1>
                            <div class="flex gap-4">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="group relative px-8 py-4 bg-cyan-500 text-slate-900 rounded-none font-bold overflow-hidden transition-all clip-path-button">
                                        <div class="absolute inset-0 w-full h-full bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500"></div>
                                        <span class="relative flex items-center gap-2">Lihat Penawaran <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
                                    </a>
                                @else
                                    <a href="#katalog" class="px-8 py-4 border border-white/30 text-white hover:bg-white hover:text-slate-900 transition-all font-bold tracking-wider uppercase text-sm">Belanja Sekarang</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Banner -->
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center text-white z-10">
                        <div class="text-center px-4">
                            <h1 class="text-5xl md:text-8xl font-black font-tech mb-4 tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400">FUTURE <span class="text-cyan-400">TECH</span></h1>
                            <p class="text-xl text-slate-400 max-w-2xl mx-auto font-light tracking-wide">High-Performance Hardware Ecosystem for Professionals & Gamers.</p>
                             <a href="#katalog" class="mt-8 inline-block px-10 py-4 bg-cyan-500 text-slate-900 font-bold hover:bg-cyan-400 transition-all shadow-[0_0_40px_rgba(6,182,212,0.4)] hover:shadow-[0_0_60px_rgba(6,182,212,0.6)] rounded-sm">Explore Inventory</a>
                        </div>
                    </div>
                @endforelse
                
                <div class="absolute bottom-8 left-8 md:left-16 flex gap-3 z-20">
                    @for($i = 0; $i < $banners->count(); $i++)
                        <button @click="activeSlide = {{ $i }}" class="w-12 h-1 rounded transition-all duration-300" :class="activeSlide === {{ $i }} ? 'bg-cyan-400 shadow-[0_0_10px_rgba(34,211,238,0.8)]' : 'bg-white/20 hover:bg-white/40'"></button>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Sale -->
    @if($flashSales->isNotEmpty() && $flashSaleEnabled)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 relative">
        <div class="absolute -inset-4 bg-fuchsia-900/20 blur-3xl -z-10 rounded-full"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-fuchsia-500/10 border border-fuchsia-500/50 rounded-lg">
                    <svg class="w-8 h-8 text-fuchsia-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black font-tech text-white uppercase tracking-wide italic transform -skew-x-6"><span class="text-fuchsia-500">Flash</span> Sale</h2>
                    <div class="flex gap-2 items-center text-sm font-bold text-fuchsia-400">Limited Time Offers</div>
                </div>
            </div>
            <a href="{{ route('marketing.flash-sale.index') }}" class="group flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-fuchsia-400 transition-colors">VIEW ALL <span class="block w-4 h-4 border-t border-r border-current rotate-45 group-hover:translate-x-1 transition-transform"></span></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($flashSales as $sale)
                <div wire:click="openProduct({{ $sale->product_id }})" class="group cursor-pointer relative bg-slate-900 border border-fuchsia-900/50 hover:border-fuchsia-500 rounded-2xl p-4 shadow-lg overflow-hidden transition-all hover:-translate-y-2">
                    <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute top-4 left-4 z-10 bg-fuchsia-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-lg shadow-fuchsia-600/50">-{{ number_format((($sale->product->sell_price - $sale->discount_price) / $sale->product->sell_price) * 100) }}%</div>
                    <div class="h-40 bg-slate-800 rounded-xl mb-4 flex items-center justify-center overflow-hidden relative">
                        @if($sale->product->image_path)
                            <img src="{{ asset('storage/' . $sale->product->image_path) }}" class="max-h-[80%] object-contain transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                        @else
                            <svg class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <h3 class="font-bold text-white text-sm line-clamp-2 mb-2 h-10 group-hover:text-fuchsia-400 transition-colors">{{ $sale->product->name }}</h3>
                    <div class="flex flex-col mb-3"><span class="text-xs text-slate-500 line-through">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</span><span class="text-lg font-bold text-fuchsia-400 font-tech glow-text">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</span></div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Service Tracking -->
    @if($serviceTrackingEnabled)
    <div id="services" class="bg-slate-900 py-20 relative overflow-hidden mb-16 border-y border-white/5">
        <div class="absolute inset-0 cyber-grid opacity-30"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block px-4 py-2 bg-blue-500/10 border border-blue-500/30 rounded-full mb-6"><span class="text-blue-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>Live Service Status</span></div>
                    <h2 class="text-4xl md:text-5xl font-black text-white font-tech mb-6 leading-tight">TRACK YOUR <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">REPAIR PROCESS</span></h2>
                    <p class="text-slate-400 text-lg mb-8 leading-relaxed">Pantau perbaikan perangkat Anda secara real-time. Transparansi total.</p>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input wire:model="trackingNumber" type="text" placeholder="Masukkan Nomor Tiket (Cth: SVC-2026-001)" class="flex-1 w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-xl px-5 py-4 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all font-mono">
                            <button wire:click="trackService" class="bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold px-8 py-4 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">Lacak</button>
                        </div>
                        @if($trackingResult)
                            <div class="mt-6 p-5 bg-emerald-500/5 border border-emerald-500/30 rounded-xl animate-fade-in-up relative overflow-hidden">
                                <div class="flex justify-between items-start mb-2 relative z-10">
                                    <div><h4 class="font-bold text-white text-lg">{{ $trackingResult->device_name }}</h4><p class="text-xs text-slate-500 uppercase tracking-widest mt-1">{{ $trackingResult->ticket_number }}</p></div>
                                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold rounded-full uppercase border border-emerald-500/30">{{ $trackingResult->status }}</span>
                                </div>
                                <div class="mt-4 pt-4 border-t border-white/5 relative z-10"><p class="text-slate-300 text-sm mb-3"><span class="text-slate-500">Problem:</span> {{ $trackingResult->problem_description }}</p></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="hidden md:block relative">
                     <div class="relative w-full aspect-square">
                        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/20 to-blue-600/20 rounded-full blur-3xl animate-pulse"></div>
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/server-room-illustration-download-in-svg-png-gif-file-formats--data-center-storage-cloud-network-hosting-technology-pack-illustrations-3696245.png" alt="Tech Service" class="relative z-10 w-full drop-shadow-[0_20px_50px_rgba(8,145,178,0.3)] animate-float">
                     </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Catalog -->
    <div id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-10 flex flex-col lg:flex-row gap-8">
        <aside class="hidden lg:block w-72 flex-shrink-0">
            <div class="sticky top-28 space-y-6">
                <!-- Search -->
                <div class="tech-card rounded-2xl p-5 shadow-lg">
                    <h3 class="font-bold text-white text-sm uppercase tracking-wider mb-4">Pencarian</h3>
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl text-sm text-white focus:ring-1 focus:ring-cyan-500 placeholder-slate-500" placeholder="Cari produk...">
                </div>
                <!-- Category -->
                <div class="tech-card rounded-2xl p-6 shadow-lg">
                    <h3 class="font-bold text-white text-sm uppercase tracking-wider mb-4 flex justify-between">Kategori <button wire:click="$set('category', '')" class="text-[10px] text-cyan-400 hover:text-cyan-300">RESET</button></h3>
                    <div class="space-y-1">
                        @foreach($categories as $cat)
                            <button wire:click="$set('category', {{ $cat->id }})" class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center justify-between group {{ $category == $cat->id ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white border border-transparent' }}">
                                <span>{{ $cat->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 relative">
                @forelse($products as $product)
                    <div wire:key="{{ $product->id }}" wire:click="openProduct({{ $product->id }})" class="group relative bg-slate-900 border border-white/5 hover:border-cyan-500/50 rounded-3xl p-4 transition-all duration-200 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] hover:-translate-y-1 cursor-pointer overflow-hidden">
                        <div class="relative h-56 bg-slate-800/50 rounded-2xl overflow-hidden mb-5 flex items-center justify-center group-hover:bg-slate-800 transition-colors z-10">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-[85%] max-w-[85%] object-contain transition-transform duration-200 group-hover:scale-105">
                            @else
                                <svg class="w-16 h-16 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                        <div class="px-1 relative z-10">
                            <h3 class="font-bold text-white text-lg leading-snug mb-2 line-clamp-2 group-hover:text-cyan-400 transition-colors h-14 font-tech tracking-wide">{{ $product->name }}</h3>
                            <div class="flex items-end justify-between mt-2 pt-4 border-t border-white/5">
                                <span class="text-xl font-mono font-bold text-white group-hover:text-cyan-400 transition-colors">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                <button wire:click.stop="addToCart({{ $product->id }})" class="text-slate-500 hover:text-white transition-colors p-2"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg></button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center"><p class="text-slate-500">Produk tidak ditemukan.</p></div>
                @endforelse
            </div>
            <div class="mt-16">{{ $products->links() }}</div>
        </div>
    </div>

    <!-- Latest News Section -->
    @if($latestNews->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-10">
            <div class="flex justify-between items-end mb-8 border-b border-white/10 pb-4">
                <div>
                    <h2 class="text-3xl font-black font-tech text-white uppercase tracking-tight">Latest <span class="text-cyan-500">Updates</span></h2>
                    <p class="text-slate-400 text-sm mt-1">Berita terbaru dan artikel menarik seputar teknologi.</p>
                </div>
                <a href="{{ route('news.index') }}" class="text-xs font-bold text-cyan-500 hover:text-white uppercase tracking-widest transition-colors mb-1">Lihat Semua ></a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestNews as $article)
                    <a href="{{ route('news.show', $article->slug) }}" class="group block bg-slate-900 border border-white/10 rounded-2xl overflow-hidden hover:border-cyan-500/30 transition-all hover:-translate-y-1">
                        <div class="h-40 bg-slate-800 relative overflow-hidden">
                            @if($article->image_path)
                                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-700">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-slate-900/90 backdrop-blur text-white text-[10px] font-bold uppercase tracking-widest rounded">{{ $article->category }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-white text-md line-clamp-2 mb-2 group-hover:text-cyan-400 transition-colors">{{ $article->title }}</h3>
                            <p class="text-slate-500 text-xs line-clamp-2">{{ $article->excerpt }}</p>
                            <div class="mt-4 flex items-center gap-2 text-[10px] text-slate-600 uppercase font-bold tracking-widest">
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @include('livewire.store.partials.modals')
    @include('livewire.store.partials.compare-modal')
    
    <style>
        .clip-path-button { clip-path: polygon(0 0, 100% 0, 100% 70%, 90% 100%, 0 100%); }
        .glow-text { text-shadow: 0 0 10px rgba(232, 121, 249, 0.5); }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</div>
