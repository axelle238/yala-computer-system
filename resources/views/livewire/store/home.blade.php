<div class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans">
    
    <!-- Hero Slider -->
    @if($banners->count() > 0)
        <div class="relative w-full h-[400px] md:h-[500px] overflow-hidden group" x-data="{ active: 0, count: {{ $banners->count() }}, timer: null }" x-init="timer = setInterval(() => active = (active + 1) % count, 5000)">
            @foreach($banners as $index => $banner)
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                     x-show="active === {{ $index }}"
                     x-transition:enter="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-transparent flex items-center">
                        <div class="container mx-auto px-4 lg:px-8">
                            <div class="max-w-xl animate-fade-in-up">
                                <span class="text-cyan-400 font-bold tracking-widest uppercase text-sm mb-2 block">Featured</span>
                                <h2 class="text-4xl md:text-6xl font-black font-tech text-white leading-tight mb-4 shadow-black drop-shadow-lg">{{ $banner->title }}</h2>
                                <p class="text-slate-300 text-lg mb-8 leading-relaxed drop-shadow-md">{{ $banner->description }}</p>
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="inline-flex items-center gap-2 px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all shadow-lg hover:shadow-cyan-500/50 hover:-translate-y-1">
                                        Explore Now <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-20">
                @foreach($banners as $index => $banner)
                    <button @click="active = {{ $index }}; clearInterval(timer); timer = setInterval(() => active = (active + 1) % count, 5000)" 
                            class="w-3 h-3 rounded-full transition-all border border-white/50"
                            :class="active === {{ $index }} ? 'bg-cyan-500 w-8' : 'bg-white/20 hover:bg-white'">
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Flash Sale Section -->
    @if($flashSales->count() > 0 && $flashSaleEnabled)
        <section class="py-12 bg-slate-900 border-y border-white/5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-rose-600/10 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-1.5 h-8 bg-rose-500 rounded-full animate-pulse"></span>
                            <h2 class="text-3xl font-black font-tech text-white uppercase tracking-tighter">Flash <span class="text-rose-500">Sale</span></h2>
                        </div>
                        <p class="text-slate-400">Penawaran terbatas waktu. Segera dapatkan sebelum kehabisan!</p>
                    </div>
                    <div class="flex gap-2 font-mono font-bold text-2xl text-white items-center bg-white/5 px-6 py-2 rounded-xl border border-white/10" x-data="{ 
                        endTime: new Date('{{ $flashSales->first()->end_time }}').getTime(),
                        now: new Date().getTime(),
                        time: { h: 0, m: 0, s: 0 },
                        init() {
                            setInterval(() => {
                                this.now = new Date().getTime();
                                let distance = this.endTime - this.now;
                                if(distance < 0) distance = 0;
                                this.time.h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                this.time.m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                this.time.s = Math.floor((distance % (1000 * 60)) / 1000);
                            }, 1000);
                        }
                    }">
                        <span class="text-rose-500">ENDS IN:</span>
                        <span x-text="String(time.h).padStart(2, '0')">00</span>:
                        <span x-text="String(time.m).padStart(2, '0')">00</span>:
                        <span x-text="String(time.s).padStart(2, '0')">00</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($flashSales as $sale)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-rose-500/30 group hover:border-rose-500 transition-all shadow-lg hover:shadow-rose-500/20">
                            <div class="relative aspect-square p-4 bg-white dark:bg-slate-900/50 flex items-center justify-center">
                                <span class="absolute top-3 left-3 bg-rose-500 text-white text-xs font-black px-2 py-1 rounded shadow-sm">-{{ number_format((($sale->product->sell_price - $sale->discount_price) / $sale->product->sell_price) * 100) }}%</span>
                                @if($sale->product->image_path)
                                    <img src="{{ asset('storage/' . $sale->product->image_path) }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <svg class="w-16 h-16 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-slate-900 dark:text-white mb-1 truncate">{{ $sale->product->name }}</h3>
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-lg font-black text-rose-500">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-slate-500 line-through decoration-rose-500">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-slate-700 h-1.5 rounded-full overflow-hidden mb-1">
                                    <div class="bg-rose-500 h-full" style="width: {{ ($sale->sold_quantity / $sale->quota) * 100 }}%"></div>
                                </div>
                                <div class="flex justify-between text-[10px] text-slate-400 uppercase font-bold">
                                    <span>Terjual: {{ $sale->sold_quantity }}</span>
                                    <span>Sisa: {{ $sale->quota - $sale->sold_quantity }}</span>
                                </div>
                                <button wire:click="addToCart({{ $sale->product->id }})" class="w-full mt-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-sm font-bold rounded-lg transition-colors">
                                    AMBIL SEKARANG
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Category Grid -->
    <section class="py-16 bg-slate-50 dark:bg-slate-900/50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white mb-8 text-center uppercase tracking-widest">Kategori Pilihan</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($categories->take(6) as $cat)
                    <a href="{{ route('store.catalog', ['category' => $cat->slug]) }}" class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm hover:shadow-lg border border-slate-200 dark:border-slate-700 hover:border-cyan-500 text-center group transition-all">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-cyan-500 group-hover:text-white transition-colors text-slate-400">
                            <!-- Simple Icon mapping logic or random -->
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white group-hover:text-cyan-500 transition-colors">{{ $cat->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $cat->products_count }} Produk</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Main Product Catalog (Latest) -->
    <section class="py-16 container mx-auto px-4">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">New <span class="text-cyan-500">Arrivals</span></h2>
                <p class="text-slate-500">Produk terbaru yang baru saja mendarat.</p>
            </div>
            <a href="{{ route('store.catalog') }}" class="text-sm font-bold text-cyan-600 hover:text-cyan-500 flex items-center gap-1">
                Lihat Semua <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 hover:border-cyan-500 hover:shadow-cyan-500/20 transition-all group flex flex-col relative overflow-hidden">
                    <button wire:click="addToCompare({{ $product->id }})" class="absolute top-3 right-3 p-2 bg-slate-900/50 backdrop-blur text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-20 hover:bg-cyan-600" title="Bandingkan">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    </button>

                    <a href="{{ route('product.detail', $product->id) }}" class="block relative aspect-square mb-4 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-900">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                    </a>

                    <div class="flex-1 flex flex-col">
                        <div class="text-[10px] text-cyan-500 font-bold uppercase tracking-wider mb-1">{{ $product->category->name }}</div>
                        <h3 class="font-bold text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-cyan-400 transition-colors">
                            <a href="{{ route('product.detail', $product->id) }}">{{ $product->name }}</a>
                        </h3>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="font-black text-lg text-slate-900 dark:text-white">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            <button wire:click="addToCart({{ $product->id }})" class="p-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-cyan-600 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Service Tracking & CTA -->
    <section class="py-20 bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden border-t border-white/5">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-black font-tech mb-4 uppercase tracking-tighter leading-none">Service <br><span class="text-cyan-500">Tracking</span> Center</h2>
                <p class="text-slate-400 mb-8 max-w-md">Perangkat Anda sedang dalam perbaikan? Cek status pengerjaan secara real-time cukup dengan nomor tiket.</p>
                
                <form wire:submit.prevent="trackService" class="flex gap-2 max-w-md">
                    <input wire:model="trackingNumber" type="text" class="flex-1 bg-white/10 border border-white/20 rounded-xl px-4 py-3 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-500 font-mono font-bold" placeholder="SRV-XXXX-XXXX">
                    <button type="submit" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all">Lacak</button>
                </form>
                @error('trackingNumber') <span class="text-rose-500 text-sm mt-2 block">{{ $message }}</span> @enderror
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-cyan-500/20 blur-[100px] rounded-full"></div>
                <div class="relative bg-white/5 border border-white/10 backdrop-blur-xl p-8 rounded-3xl">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Garansi Resmi</h4>
                            <p class="text-sm text-slate-400">Setiap produk yang Anda beli dijamin original dan bergaransi resmi distributor Indonesia.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Rakit PC Express</h4>
                            <p class="text-sm text-slate-400">Layanan rakit PC profesional dengan manajemen kabel rapi, siap dalam 1x24 jam untuk part ready stock.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
