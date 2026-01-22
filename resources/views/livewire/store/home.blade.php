<div wire:poll.10s>
    <!-- Notification Toast (AlpineJS) -->
    <div x-data="{ show: false, message: '' }" 
         x-on:notify.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 z-[60] bg-slate-900 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 border border-slate-700"
         style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="message" class="font-semibold text-sm"></span>
    </div>

    <!-- Floating Cart Button -->
    <button wire:click="toggleCart" class="fixed bottom-8 right-8 z-40 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-2xl shadow-blue-600/40 transition-all transform hover:scale-110 group">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if(count($cart) > 0)
                <span class="absolute -top-3 -right-3 bg-rose-500 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-slate-900">
                    {{ count($cart) }}
                </span>
            @endif
        </div>
        <span class="sr-only">Keranjang</span>
    </button>

    <!-- Cart Sidebar (Slide-over) -->
    @if($isCartOpen)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="toggleCart"></div>

                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-bold text-slate-900" id="slide-over-title">Keranjang Belanja</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" wire:click="toggleCart" class="relative -m-2 p-2 text-slate-400 hover:text-slate-500">
                                            <span class="absolute -inset-0.5"></span>
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
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
                                                                <div class="h-full w-full bg-slate-100 flex items-center justify-center text-slate-300">
                                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                </div>
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
                                            <div class="text-center py-12">
                                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <h3 class="mt-2 text-sm font-semibold text-slate-900">Keranjang Kosong</h3>
                                                <p class="mt-1 text-sm text-slate-500">Mulailah belanja hardware impian Anda.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 px-4 py-6 sm:px-6 bg-slate-50">
                                <div class="flex justify-between text-base font-bold text-slate-900">
                                    <p>Total Pesanan</p>
                                    <p>Rp {{ number_format($this->cartTotal, 0, ',', '.') }}</p>
                                </div>
                                <p class="mt-0.5 text-sm text-slate-500">Ongkir akan dihitung setelah checkout.</p>
                                <div class="mt-6">
                                    <button wire:click="checkout" class="w-full flex items-center justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all gap-2 {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}" {{ empty($cart) ? 'disabled' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Checkout via WhatsApp
                                    </button>
                                </div>
                                <div class="mt-6 flex justify-center text-center text-sm text-slate-500">
                                    <p>
                                        atau
                                        <button type="button" wire:click="toggleCart" class="font-bold text-blue-600 hover:text-blue-500">
                                            Lanjut Belanja
                                            <span aria-hidden="true"> &rarr;</span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section (Dynamic Slider) -->
    <div class="relative bg-slate-900 overflow-hidden" x-data="{ activeSlide: 0, slides: {{ $banners->count() }}, timer: null }" x-init="timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000); $watch('activeSlide', () => { clearInterval(timer); timer = setInterval(() => { activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1 }, 5000) })">
        
        <!-- Slides -->
        <div class="relative h-[500px] md:h-[600px]">
            @forelse($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" 
                     x-transition:enter="transition transform duration-700 ease-out"
                     x-transition:enter-start="opacity-0 translate-x-10"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition transform duration-700 ease-in"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-10"
                     class="absolute inset-0 w-full h-full">
                    
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/60 to-transparent z-10"></div>
                        <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Content -->
                    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-500/30 text-blue-400 text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-sm">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                Promo Spesial
                            </div>
                            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6 drop-shadow-lg">
                                {{ $banner->title }}
                            </h1>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-1">
                                    Lihat Penawaran
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            @else
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="#katalog" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-1 text-center">
                                        Belanja Sekarang
                                    </a>
                                    <a href="{{ route('pc-builder') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-full font-bold backdrop-blur-sm transition-all text-center">
                                        Simulasi Rakit PC
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback Static Banner if no banners -->
                <div class="absolute inset-0 w-full h-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-blue-900/40 z-10"></div>
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-30">
                    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                        <div class="max-w-2xl">
                            <h1 class="text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                                Masa Depan Teknologi <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Ada Di Sini.</span>
                            </h1>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="#katalog" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold shadow-lg shadow-blue-600/30 transition-all text-center">Belanja Sekarang</a>
                                <a href="{{ route('pc-builder') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-full font-bold backdrop-blur-sm transition-all text-center">
                                    Simulasi Rakit PC
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Dots Indicators -->
        @if($banners->count() > 1)
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 flex space-x-3">
                @foreach($banners as $index => $banner)
                    <button @click="activeSlide = {{ $index }}" :class="{ 'bg-blue-500 w-8': activeSlide === {{ $index }}, 'bg-white/30 w-3 hover:bg-white': activeSlide !== {{ $index }} }" class="h-3 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <div id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 min-h-screen">
        
        <!-- Flash Sale Section -->
        @if($flashSales->count() > 0)
            <div class="mb-16 bg-gradient-to-r from-rose-600 to-orange-500 rounded-3xl p-8 md:p-12 shadow-2xl shadow-rose-500/30 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between mb-8 gap-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white/20 rounded-full backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold text-white tracking-tight">FLASH SALE</h2>
                            <p class="text-white/80 font-medium">Diskon Kilat Terbatas!</p>
                        </div>
                    </div>
                    
                    <!-- Countdown Timer -->
                    <div class="flex gap-4 text-center" x-data="{
                        endTime: new Date('{{ $flashSales->first()->end_time->format('Y-m-d H:i:s') }}').getTime(),
                        now: new Date().getTime(),
                        time: { d: 0, h: 0, m: 0, s: 0 },
                        init() {
                            setInterval(() => {
                                this.now = new Date().getTime();
                                let distance = this.endTime - this.now;
                                if (distance < 0) distance = 0;
                                this.time.d = Math.floor(distance / (1000 * 60 * 60 * 24));
                                this.time.h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                this.time.m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                this.time.s = Math.floor((distance % (1000 * 60)) / 1000);
                            }, 1000);
                        }
                    }">
                        <div class="bg-white/20 backdrop-blur-md rounded-xl p-3 w-16 md:w-20 border border-white/30">
                            <span class="block text-2xl md:text-3xl font-extrabold text-white" x-text="time.h">00</span>
                            <span class="text-[10px] text-white/80 uppercase font-bold tracking-wider">Jam</span>
                        </div>
                        <div class="bg-white/20 backdrop-blur-md rounded-xl p-3 w-16 md:w-20 border border-white/30">
                            <span class="block text-2xl md:text-3xl font-extrabold text-white" x-text="time.m">00</span>
                            <span class="text-[10px] text-white/80 uppercase font-bold tracking-wider">Menit</span>
                        </div>
                        <div class="bg-white rounded-xl p-3 w-16 md:w-20 shadow-lg transform scale-110">
                            <span class="block text-2xl md:text-3xl font-extrabold text-rose-600" x-text="time.s">00</span>
                            <span class="text-[10px] text-rose-600 uppercase font-bold tracking-wider">Detik</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($flashSales as $sale)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-transform hover:-translate-y-1 cursor-pointer group" wire:click="openProduct({{ $sale->product_id }})">
                            <div class="relative h-48 bg-slate-100 flex items-center justify-center p-4">
                                @if($sale->product->image_path)
                                    <img src="{{ asset('storage/' . $sale->product->image_path) }}" class="max-h-full max-w-full object-contain mix-blend-multiply">
                                @else
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                                <div class="absolute top-2 right-2 bg-rose-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                    -{{ round((($sale->product->sell_price - $sale->discount_price) / $sale->product->sell_price) * 100) }}%
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="h-10 mb-2">
                                    <h3 class="font-bold text-slate-900 leading-tight line-clamp-2 group-hover:text-rose-600 transition-colors">{{ $sale->product->name }}</h3>
                                </div>
                                <div class="flex items-end justify-between mt-4">
                                    <div>
                                        <p class="text-xs text-slate-400 line-through">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</p>
                                        <p class="text-lg font-extrabold text-rose-600">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 mb-1 w-16">
                                            <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ ($sale->quota / 10) * 100 }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-slate-500 font-bold">Sisa {{ $sale->quota }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter & Search Toolbar (Sticky) -->
        <div class="sticky top-24 z-30 bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-slate-100 p-4 mb-10 transition-all">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <!-- Categories -->
                <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <div class="flex gap-2">
                        <button 
                            wire:click="$set('category', '')"
                            class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $category === '' ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                        >
                            Semua Produk
                        </button>
                        @foreach($categories as $cat)
                            <button 
                                wire:click="$set('category', {{ $cat->id }})"
                                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-all {{ $category == $cat->id ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                            >
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Search Input -->
                <div class="w-full md:w-80 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm" 
                        placeholder="Cari hardware impian..."
                    >
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div wire:key="{{ $product->id }}" wire:click="openProduct({{ $product->id }})" class="group bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 transform hover:-translate-y-1 relative flex flex-col h-full cursor-pointer">
                    
                    <!-- Badge Stok -->
                    <div class="absolute top-4 right-4 z-10">
                        @if($product->stock_quantity > 5)
                            <span class="px-3 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Ready
                            </span>
                        @elseif($product->stock_quantity > 0)
                            <span class="px-3 py-1 bg-amber-500/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Limited
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-500/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm">
                                Habis
                            </span>
                        @endif
                    </div>

                    <!-- Image Area -->
                    <div class="h-56 bg-slate-50 relative overflow-hidden flex items-center justify-center p-6 group-hover:bg-blue-50/30 transition-colors">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="text-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content Area -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-1 text-xs font-bold text-blue-600 uppercase tracking-wide">
                            {{ $product->category->name }}
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg leading-snug mb-1 group-hover:text-blue-700 transition-colors line-clamp-2">
                            {{ $product->name }}
                        </h3>
                        
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 mb-2">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($product->reviews_avg_rating) ? 'text-amber-400 fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            @endfor
                            <span class="text-[10px] text-slate-400">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400 block">Harga</span>
                                <span class="text-lg font-extrabold text-slate-900">
                                    Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <button wire:click.stop="addToCompare({{ $product->id }})" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Bandingkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                </button>
                                <button class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Produk Tidak Ditemukan</h3>
                    <button wire:click="$set('search', '')" class="mt-4 px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-semibold hover:bg-slate-800 transition-colors">
                        Reset Pencarian
                    </button>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Services Section -->
    <div id="services" class="bg-slate-50 py-24 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900">Layanan Profesional</h2>
                <p class="mt-4 text-slate-500">Bukan sekadar toko komputer, kami adalah partner teknologi Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Rakit PC Custom</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Konsultasikan kebutuhan gaming atau workstation Anda. Teknisi kami akan merakit dengan manajemen kabel terbaik.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all">
                    <div class="w-14 h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Service & Repair</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Perbaikan laptop, pembersihan debu, ganti thermal paste, hingga instalasi ulang OS dengan garansi pengerjaan.</p>
                </div>
                 <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl transition-all">
                    <div class="w-14 h-14 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Konsultasi IT</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Solusi jaringan kantor, pengadaan server, dan CCTV untuk keamanan bisnis Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Tracking Section -->
    <div class="bg-white py-16 border-t border-slate-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Cek Status Servis</h2>
            <p class="text-slate-500 mb-8">Pantau progres perbaikan perangkat Anda secara real-time.</p>
            
            <form wire:submit="trackService" class="relative max-w-lg mx-auto mb-8">
                <input wire:model="trackingNumber" type="text" class="block w-full pl-6 pr-32 py-4 border-2 border-slate-200 rounded-full bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-mono text-center text-lg uppercase placeholder-slate-400" placeholder="Masukkan No. Tiket (SRV-...)">
                <button type="submit" class="absolute right-2 top-2 bottom-2 px-6 bg-slate-900 hover:bg-blue-600 text-white rounded-full font-bold transition-all">
                    Lacak
                </button>
            </form>
            @error('trackingNumber') <p class="text-rose-500 font-bold mb-4">{{ $message }}</p> @enderror

            @if($trackingResult)
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 text-left animate-fade-in-up">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4 mb-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor Tiket</span>
                            <h3 class="text-xl font-extrabold text-slate-900 font-mono">{{ $trackingResult->ticket_number }}</h3>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-bold {{ $trackingResult->status_color }}">
                            {{ $trackingResult->status_label }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-slate-400 mb-1">Perangkat</span>
                            <span class="font-semibold text-slate-800">{{ $trackingResult->device_name }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-400 mb-1">Estimasi Biaya</span>
                            <span class="font-semibold text-slate-800">Rp {{ number_format($trackingResult->estimated_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-slate-400 mb-1">Keluhan</span>
                            <span class="font-medium text-slate-600">{{ $trackingResult->problem_description }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Comparison Floating Bar -->
    @if(count($compareList) > 0)
        <div class="fixed bottom-0 left-0 right-0 z-[80] bg-white border-t border-slate-200 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] p-4 transform transition-transform duration-300">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="font-bold text-slate-800">{{ count($compareList) }} Produk Dipilih</span>
                    <div class="flex gap-2">
                        @foreach($compareList as $id)
                            @php $prod = \App\Models\Product::find($id); @endphp
                            @if($prod)
                                <div class="relative w-10 h-10 bg-slate-100 rounded-lg border border-slate-200 overflow-hidden group">
                                    @if($prod->image_path)
                                        <img src="{{ asset('storage/' . $prod->image_path) }}" class="w-full h-full object-cover">
                                    @endif
                                    <button wire:click="removeFromCompare({{ $id }})" class="absolute inset-0 bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('compareList', [])" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-rose-600 transition-colors">Reset</button>
                    <button wire:click="openCompare" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                        Bandingkan Sekarang
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Comparison Modal -->
    @if($showCompareModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" wire:click="closeCompare"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white w-full max-w-5xl rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-xl font-extrabold text-slate-900">Perbandingan Spesifikasi</h3>
                        <button wire:click="closeCompare" class="p-2 bg-slate-100 rounded-full hover:bg-rose-100 hover:text-rose-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="p-4 w-1/4"></th>
                                    @foreach($compareList as $id)
                                        @php $prod = \App\Models\Product::find($id); @endphp
                                        <th class="p-4 w-1/4 align-top">
                                            <div class="h-32 bg-slate-50 rounded-xl mb-4 flex items-center justify-center p-2">
                                                @if($prod->image_path)
                                                    <img src="{{ asset('storage/' . $prod->image_path) }}" class="max-h-full object-contain mix-blend-multiply">
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-sm line-clamp-2">{{ $prod->name }}</h4>
                                            <p class="text-lg font-extrabold text-blue-600 mt-2">Rp {{ number_format($prod->sell_price, 0, ',', '.') }}</p>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <tr>
                                    <td class="p-4 font-bold text-slate-500">Kategori</td>
                                    @foreach($compareList as $id)
                                        <td class="p-4">
                                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">
                                                {{ \App\Models\Product::find($id)->category->name }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="p-4 font-bold text-slate-500">Garansi</td>
                                    @foreach($compareList as $id)
                                        <td class="p-4">{{ \App\Models\Product::find($id)->warranty_duration }} Bulan</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="p-4 font-bold text-slate-500">Deskripsi</td>
                                    @foreach($compareList as $id)
                                        <td class="p-4 text-slate-600">{{ Str::limit(\App\Models\Product::find($id)->description, 100) }}</td>
                                    @endforeach
                                </tr>
                                <!-- Tech Specs (Dynamic JSON) -->
                                @php 
                                    // Ambil semua key spesifikasi unik dari produk yang dibandingkan
                                    $allSpecs = [];
                                    foreach($compareList as $id) {
                                        $prod = \App\Models\Product::find($id);
                                        if($prod->specifications) {
                                            // Asumsi specifications disimpan sebagai array/json [Key => Value]
                                            // Namun saat ini di DB masih nullable text/json.
                                            // Kita skip detail JSON kompleks untuk MVP ini, tampilkan raw specs jika ada.
                                        }
                                    }
                                @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Details Modal -->
    @if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                
                <!-- Close Button -->
                <button wire:click="closeModal" class="absolute top-4 right-4 p-2 bg-slate-100 rounded-full text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition-colors z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="flex flex-col md:flex-row">
                    <!-- Image Side -->
                    <div class="w-full md:w-1/2 bg-slate-50 p-8 flex items-center justify-center">
                         @if($selectedProduct->image_path)
                            <img src="{{ asset('storage/' . $selectedProduct->image_path) }}" class="max-w-full max-h-[400px] object-contain mix-blend-multiply">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        @endif
                    </div>

                    <!-- Info Side -->
                    <div class="w-full md:w-1/2 p-8 bg-white">
                        <div class="mb-2">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-700">
                                {{ $selectedProduct->category->name }}
                            </span>
                        </div>
                        
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">{{ $selectedProduct->name }}</h2>
                        <div class="flex items-center gap-4 mb-6">
                             <div class="flex items-center gap-1 text-xs text-slate-500 font-mono bg-slate-100 px-2 py-1 rounded">
                                SKU: {{ $selectedProduct->sku }}
                            </div>
                        </div>

                        <div class="prose prose-sm text-slate-600 mb-8 max-h-40 overflow-y-auto pr-2">
                            <p>{{ $selectedProduct->description ?? 'Tidak ada deskripsi detail.' }}</p>
                        </div>

                        <div class="border-t border-slate-100 pt-6 mt-auto">
                            <div class="flex justify-between items-end mb-6">
                                <div>
                                    <p class="text-sm text-slate-500 mb-1">Harga Satuan</p>
                                    <p class="text-3xl font-extrabold text-slate-900">
                                        Rp {{ number_format($selectedProduct->sell_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500 mb-1">Status Stok</p>
                                    @if($selectedProduct->stock_quantity > 0)
                                        <p class="text-emerald-600 font-bold flex items-center justify-end gap-1">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Tersedia ({{ $selectedProduct->stock_quantity }})
                                        </p>
                                    @else
                                        <p class="text-rose-600 font-bold">Habis</p>
                                    @endif
                                </div>
                            </div>

                            @if($selectedProduct->stock_quantity > 0)
                                <button wire:click="addToCart({{ $selectedProduct->id }})" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            @else
                                <button disabled class="w-full py-4 bg-slate-100 text-slate-400 rounded-xl font-bold cursor-not-allowed border border-slate-200">
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
