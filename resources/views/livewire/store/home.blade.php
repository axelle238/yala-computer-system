<div wire:poll.10s x-data="{ wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'), toggleWishlist(id) { if(this.wishlist.includes(id)) { this.wishlist = this.wishlist.filter(i => i !== id); } else { this.wishlist.push(id); } localStorage.setItem('wishlist', JSON.stringify(this.wishlist)); } }">
    <!-- Notification Toast -->
    <div x-data="{ show: false, message: '' }" 
         x-on:notify.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed top-24 right-4 z-[999] bg-white/90 backdrop-blur-md text-slate-800 px-6 py-4 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] flex items-center gap-4 border border-white/50 ring-1 ring-slate-900/5"
         style="display: none;">
        <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <div>
            <h6 class="font-bold text-sm">Notifikasi</h6>
            <p x-text="message" class="text-xs text-slate-500 mt-0.5"></p>
        </div>
    </div>

    <!-- Floating Cart -->
    <button wire:click="toggleCart" class="fixed bottom-8 right-8 z-40 group">
        <div class="absolute inset-0 bg-blue-500 rounded-full animate-pulse opacity-20"></div>
        <div class="relative w-16 h-16 bg-slate-900 text-white rounded-full shadow-2xl shadow-blue-900/50 flex items-center justify-center transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            @if(count($cart) > 0)
                <span class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-slate-900 transform translate-x-1 -translate-y-1">
                    {{ count($cart) }}
                </span>
            @endif
        </div>
    </button>

    <!-- Cart Sidebar -->
    @if($isCartOpen)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="toggleCart"></div>
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <!-- ... Cart content (unchanged logic, just styling if needed) ... -->
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-bold text-slate-900" id="slide-over-title">Keranjang Belanja</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" wire:click="toggleCart" class="relative -m-2 p-2 text-slate-400 hover:text-slate-500">
                                            <span class="absolute -inset-0.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <div class="flow-root">
                                        @if($this->cartItems->count() > 0)
                                            <ul role="list" class="-my-6 divide-y divide-slate-100">
                                                @foreach($this->cartItems as $item)
                                                    <li class="flex py-6">
                                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-slate-200">
                                                             @if($item->image_path)
                                                                <img src="{{ asset('storage/' . $item->image_path) }}" class="h-full w-full object-cover object-center">
                                                            @else
                                                                <div class="h-full w-full bg-slate-100 flex items-center justify-center text-slate-300"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4 flex flex-1 flex-col">
                                                            <div>
                                                                <div class="flex justify-between text-base font-bold text-slate-900">
                                                                    <h3>{{ $item->name }}</h3>
                                                                    <p class="ml-4">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                                </div>
                                                                <p class="mt-1 text-sm text-slate-500">{{ $item->category->name }}</p>
                                                            </div>
                                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                                <div class="flex items-center border border-slate-200 rounded-lg">
                                                                    <button wire:click="updateCartQty({{ $item->id }}, -1)" class="px-3 py-1 text-slate-600 hover:bg-slate-50">-</button>
                                                                    <span class="px-2 font-bold text-slate-800">{{ $item->cart_qty }}</span>
                                                                    <button wire:click="updateCartQty({{ $item->id }}, 1)" class="px-3 py-1 text-slate-600 hover:bg-slate-50">+</button>
                                                                </div>
                                                                <button type="button" wire:click="removeFromCart({{ $item->id }})" class="font-medium text-rose-600 hover:text-rose-500">Hapus</button>
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
                            <div class="border-t border-slate-100 px-4 py-6 sm:px-6 bg-slate-50">
                                <div class="flex justify-between text-base font-bold text-slate-900">
                                    <p>Total Pesanan</p>
                                    <p>Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-6">
                                    <button wire:click="checkout" class="w-full flex items-center justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all gap-2" {{ empty($cart) ? 'disabled' : '' }}>
                                        Checkout via WhatsApp
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section (Slider) -->
    <div class="relative pt-8 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-white/40 backdrop-blur-xl rounded-[2.5rem] p-2 border border-white/50 shadow-2xl shadow-blue-900/5 relative overflow-hidden">
            <div class="relative bg-slate-900 rounded-[2rem] overflow-hidden min-h-[500px] flex items-center" x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }" x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 6000)">
                
                @forelse($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}" 
                         x-transition:enter="transition transform duration-1000 ease-out"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition transform duration-1000 ease-in"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 w-full h-full">
                        
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>

                        <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-16 max-w-3xl">
                            <h1 class="text-4xl md:text-7xl font-extrabold text-white tracking-tight leading-[1.1] mb-6 font-tech">
                                {{ $banner->title }}
                            </h1>
                            <div class="flex gap-4">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-full font-bold transition-all flex items-center gap-2">
                                        Lihat Penawaran
                                    </a>
                                @else
                                    <a href="#katalog" class="px-8 py-4 bg-white text-slate-900 rounded-full font-bold transition-all hover:bg-slate-200">
                                        Belanja Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Static Banner -->
                    <div class="absolute inset-0 w-full h-full flex items-center justify-center text-white">
                        <h1 class="text-4xl font-bold">Welcome to Yala Computer</h1>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Main Content (Sidebar + Grid) -->
    <div id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 relative z-10 flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Filter (Desktop) -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-24 space-y-8">
                <!-- Search -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/50 rounded-2xl p-4 shadow-lg shadow-slate-200/50">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-4 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-cyan-500/50 transition-all placeholder-slate-400" placeholder="Cari produk...">
                </div>

                <!-- Category -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/50 rounded-2xl p-6 shadow-lg shadow-slate-200/50">
                    <h3 class="font-tech font-bold text-slate-900 text-lg mb-4">Kategori</h3>
                    <div class="space-y-2">
                        <button wire:click="$set('category', '')" class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $category === '' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">Semua Produk</button>
                        @foreach($categories as $cat)
                            <button wire:click="$set('category', {{ $cat->id }})" class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium transition-all flex justify-between items-center {{ $category == $cat->id ? 'bg-cyan-50 text-cyan-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="bg-white/80 backdrop-blur-xl border border-white/50 rounded-2xl p-6 shadow-lg shadow-slate-200/50">
                    <h3 class="font-tech font-bold text-slate-900 text-lg mb-4">Harga</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Max: Rp <span x-text="new Intl.NumberFormat('id-ID').format($wire.maxPrice)"></span></label>
                            <input type="range" wire:model.live.debounce.500ms="maxPrice" min="0" max="50000000" step="500000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-cyan-600 mt-2">
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="$set('sort', 'price_asc')" class="flex-1 py-2 bg-slate-100 rounded-lg text-xs font-bold text-slate-600 hover:bg-cyan-100 hover:text-cyan-700 transition-colors {{ $sort === 'price_asc' ? 'bg-cyan-100 text-cyan-700 ring-1 ring-cyan-200' : '' }}">Termurah</button>
                            <button wire:click="$set('sort', 'price_desc')" class="flex-1 py-2 bg-slate-100 rounded-lg text-xs font-bold text-slate-600 hover:bg-cyan-100 hover:text-cyan-700 transition-colors {{ $sort === 'price_desc' ? 'bg-cyan-100 text-cyan-700 ring-1 ring-cyan-200' : '' }}">Termahal</button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Product Grid Area -->
        <div class="flex-1">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden mb-6 flex gap-2 overflow-x-auto no-scrollbar pb-2">
                <button wire:click="$set('category', '')" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold bg-white border border-slate-200 shadow-sm {{ $category === '' ? 'text-cyan-600 border-cyan-200' : 'text-slate-600' }}">All</button>
                @foreach($categories as $cat)
                    <button wire:click="$set('category', {{ $cat->id }})" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold bg-white border border-slate-200 shadow-sm {{ $category == $cat->id ? 'text-cyan-600 border-cyan-200' : 'text-slate-600' }}">{{ $cat->name }}</button>
                @endforeach
            </div>

            <!-- Active Filters Badge -->
            @if($search || $category || $maxPrice < 50000000)
                <div class="mb-6 flex flex-wrap gap-2 items-center">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Filters:</span>
                    @if($search) <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">"{{ $search }}" <button wire:click="$set('search', '')" class="hover:text-rose-500">x</button></span> @endif
                    @if($category) <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-sm">Kat: {{ \App\Models\Category::find($category)->name }} <button wire:click="$set('category', '')" class="hover:text-rose-500">x</button></span> @endif
                    <button wire:click="$set('search', ''); $set('category', ''); $set('maxPrice', 50000000)" class="text-xs font-bold text-rose-500 hover:underline ml-2">Clear</button>
                </div>
            @endif

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 relative">
                
                <!-- Loading Skeleton Overlay -->
                <div wire:loading.flex class="absolute inset-0 z-50 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 bg-white/80 backdrop-blur-sm transition-all duration-300">
                    @for($i=0; $i<6; $i++)
                        <div class="bg-white rounded-3xl border border-slate-100 p-4 animate-pulse">
                            <div class="h-56 bg-slate-200 rounded-2xl mb-4"></div>
                            <div class="px-2">
                                <div class="flex justify-between mb-2">
                                    <div class="h-4 bg-slate-200 rounded w-16"></div>
                                    <div class="h-4 bg-slate-200 rounded w-8"></div>
                                </div>
                                <div class="h-6 bg-slate-200 rounded w-full mb-2"></div>
                                <div class="h-6 bg-slate-200 rounded w-2/3"></div>
                                <div class="mt-4 pt-4 border-t border-slate-50 flex justify-between items-center">
                                    <div class="h-8 bg-slate-200 rounded w-24"></div>
                                    <div class="h-8 w-8 bg-slate-200 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                @forelse($products as $product)
                    <div wire:key="{{ $product->id }}" 
                         wire:click="openProduct({{ $product->id }})"
                         class="reveal group relative bg-white rounded-3xl border border-slate-100 p-4 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-900/10 hover:-translate-y-2 cursor-pointer overflow-hidden">
                        
                        <!-- Hover Glow Effect -->
                        <div class="relative h-56 bg-slate-50 rounded-2xl overflow-hidden mb-4 flex items-center justify-center group-hover:bg-white transition-colors">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-[80%] max-w-[80%] object-contain mix-blend-multiply transition-transform duration-700 group-hover:scale-110 group-hover:rotate-2">
                            @else
                                <svg class="w-16 h-16 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            @endif
                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-center items-center p-6 text-center z-10 pointer-events-none">
                            <p class="text-white text-xs font-bold uppercase tracking-widest mb-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-100">Spesifikasi</p>
                            <p class="text-white/90 text-sm line-clamp-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-200">
                                {{ $product->description ? Str::limit($product->description, 80) : 'Lihat detail untuk info lengkap.' }}
                            </p>
                        </div>

                        <!-- Floating Action Button (Add to Cart) -->
                        <button wire:click.stop="addToCart({{ $product->id }})" class="ripple absolute bottom-4 right-4 w-12 h-12 bg-slate-900 text-white rounded-full flex items-center justify-center shadow-lg transform translate-y-20 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 hover:bg-cyan-600 z-20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </button>

                        <!-- Wishlist Button -->
                        <button @click.stop="toggleWishlist({{ $product->id }})" class="absolute top-4 right-4 p-2 rounded-full bg-white/80 backdrop-blur-sm shadow-sm hover:bg-rose-50 transition-colors z-20" :class="{ 'text-rose-500': wishlist.includes({{ $product->id }}), 'text-slate-400': !wishlist.includes({{ $product->id }}) }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :fill="wishlist.includes({{ $product->id }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </button>
                    </div>

                        <!-- Info -->
                        <div class="px-2">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-bold tracking-wider text-cyan-600 uppercase bg-cyan-50 px-2 py-1 rounded-md">{{ $product->category->name }}</span>
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    <span class="text-xs font-bold text-slate-700">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                                </div>
                            </div>
                            <h3 class="font-bold text-slate-900 text-lg leading-snug mb-2 line-clamp-2 group-hover:text-cyan-700 transition-colors">{{ $product->name }}</h3>
                            
                            <div class="flex items-end justify-between mt-4 border-t border-slate-50 pt-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-400 font-medium">Price</span>
                                    <span class="text-xl font-tech font-bold text-slate-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                </div>
                                <button wire:click.stop="addToCompare({{ $product->id }})" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Bandingkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div class="inline-flex p-6 bg-slate-50 rounded-full mb-4"><svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></div>
                        <h3 class="text-xl font-bold text-slate-900">Produk tidak ditemukan</h3>
                        <p class="text-slate-500 mt-2">Coba atur ulang filter pencarian Anda.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    @include('livewire.store.partials.modals')
</div>
