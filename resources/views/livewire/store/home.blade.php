<div class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans">
    
    <!-- Hero Slider -->
    @if($banner->count() > 0)
        <div class="relative w-full h-[500px] md:h-[600px] overflow-hidden group bg-slate-950" x-data="{ aktif: 0, jumlah: {{ $banner->count() }}, timer: null }" x-init="timer = setInterval(() => aktif = (aktif + 1) % jumlah, 6000)">
            @foreach($banner as $indeks => $itemBanner)
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                     x-show="aktif === {{ $indeks }}"
                     x-transition:enter="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="opacity-100"
                     x-transition:leave-end="opacity-0">
                    <img src="{{ asset('storage/' . $itemBanner->image_path) }}" class="w-full h-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/80 to-transparent flex items-center">
                        <div class="container mx-auto px-4 lg:px-8">
                            <div class="max-w-2xl animate-fade-in-up space-y-6">
                                <span class="inline-block px-3 py-1 bg-cyan-500/20 text-cyan-400 font-bold tracking-[0.2em] uppercase text-xs rounded border border-cyan-500/30 backdrop-blur-md">
                                    Toko Resmi
                                </span>
                                <h2 class="text-5xl md:text-7xl font-black font-tech text-white leading-none shadow-black drop-shadow-2xl uppercase tracking-tight">
                                    {{ $itemBanner->title }}
                                </h2>
                                <p class="text-slate-300 text-lg md:text-xl font-light leading-relaxed drop-shadow-md max-w-lg border-l-4 border-cyan-500 pl-4">
                                    {{ $itemBanner->description }}
                                </p>
                                @if($itemBanner->link_url)
                                    <div class="pt-4">
                                        <a href="{{ $itemBanner->link_url }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-slate-900 font-black uppercase tracking-widest rounded-none hover:bg-cyan-400 hover:text-slate-900 transition-all transform hover:-translate-y-1 shadow-[0_0_20px_rgba(255,255,255,0.3)] clip-path-polygon">
                                            Belanja Sekarang <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- Indikator -->
            <div class="absolute bottom-10 right-10 flex gap-2 z-20">
                @foreach($banner as $indeks => $itemBanner)
                    <button @click="aktif = {{ $indeks }}; clearInterval(timer); timer = setInterval(() => aktif = (aktif + 1) % jumlah, 6000)" 
                            class="h-1 transition-all duration-300 rounded-full"
                            :class="aktif === {{ $indeks }} ? 'bg-cyan-400 w-12' : 'bg-white/20 w-4 hover:bg-white/50'">
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <!-- Fallback Hero -->
        <div class="relative w-full h-[500px] bg-slate-950 flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="text-center z-10 space-y-4">
                <h1 class="text-6xl font-black font-tech text-white tracking-tighter uppercase">Yala <span class="text-cyan-500">Computer</span></h1>
                <p class="text-slate-400 text-xl font-light tracking-widest">FUTURE TECH STORE</p>
            </div>
        </div>
    @endif

    <!-- Fitur: Rakit PC -->
    <section class="relative py-20 bg-slate-950 border-y border-white/5 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-cyan-900/10 to-transparent pointer-events-none"></div>
        <div class="container mx-auto px-4 relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 space-y-6">
                <h2 class="text-4xl md:text-5xl font-black font-tech text-white uppercase leading-none">Rakit PC <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">Impianmu</span></h2>
                <p class="text-slate-400 text-lg max-w-md">Simulasi rakit PC dengan fitur cek kompatibilitas otomatis. Pilih komponen, lihat estimasi harga, dan pesan langsung dirakit oleh teknisi ahli kami.</p>
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('toko.rakit-pc') }}" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        Mulai Simulasi
                    </a>
                </div>
            </div>
            <div class="flex-1 relative">
                <!-- Visual Rakit PC Case -->
                <div class="relative w-full aspect-video bg-slate-900 rounded-2xl border border-white/10 shadow-2xl overflow-hidden flex items-center justify-center group">
                    <div class="absolute inset-0 bg-cyan-500/5 group-hover:bg-cyan-500/10 transition-colors"></div>
                    <div class="grid grid-cols-2 gap-4 opacity-50 transform group-hover:scale-105 transition-transform duration-700">
                        <div class="w-32 h-32 bg-slate-800 rounded-lg border border-white/5 animate-pulse"></div>
                        <div class="w-32 h-32 bg-slate-800 rounded-lg border border-white/5"></div>
                        <div class="w-32 h-32 bg-slate-800 rounded-lg border border-white/5"></div>
                        <div class="w-32 h-32 bg-slate-800 rounded-lg border border-white/5 animate-pulse delay-75"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="font-tech font-black text-6xl text-white/10 group-hover:text-white/20 transition-colors">PC BUILDER</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pratinjau Katalog Utama -->
    <section class="py-16 container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
            <div>
                <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">Katalog <span class="text-cyan-600 dark:text-cyan-400">Produk</span></h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Komponen terbaik untuk kebutuhan komputasi Anda.</p>
            </div>
            <a href="{{ route('toko.katalog') }}" class="group flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-cyan-600 dark:hover:text-cyan-400 transition-colors">
                Lihat Semua Produk
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($produk as $itemProduk)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:border-cyan-500 dark:hover:border-cyan-500 hover:shadow-xl hover:shadow-cyan-500/10 transition-all group flex flex-col relative overflow-hidden">
                    
                    @if($itemProduk->stock_quantity <= 5)
                        <div class="absolute top-2 left-2 z-20 bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider">
                            Stok Terbatas
                        </div>
                    @endif

                    <a href="{{ route('toko.produk.detail', $itemProduk->id) }}" class="block relative aspect-square p-4 bg-white dark:bg-slate-900/50 overflow-hidden">
                        @if($itemProduk->image_path)
                            <img src="{{ asset('storage/' . $itemProduk->image_path) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-200 dark:text-slate-700">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                    </a>

                    <div class="p-4 flex-1 flex flex-col">
                        <div class="text-[10px] text-cyan-600 dark:text-cyan-400 font-bold uppercase tracking-wider mb-1 opacity-80">{{ $itemProduk->category->name }}</div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 leading-tight mb-3 line-clamp-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors text-sm">
                            <a href="{{ route('toko.produk.detail', $itemProduk->id) }}">{{ $itemProduk->name }}</a>
                        </h3>
                        
                        <div class="mt-auto pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <span class="font-black text-base text-slate-900 dark:text-white">Rp {{ number_format($itemProduk->sell_price, 0, ',', '.') }}</span>
                            <button wire:click="tambahKeKeranjang({{ $itemProduk->id }})" class="w-8 h-8 flex items-center justify-center bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-cyan-600 hover:text-white transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Bar Fitur / Layanan (V3 Enhanced) -->
    <section class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 via-purple-500/5 to-pink-500/5 opacity-50 pointer-events-none"></div>
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left relative z-10">
            <!-- Feature 1 -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-5 p-6 rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 hover:border-blue-500/30 transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20 flex-shrink-0 transform rotate-3 group-hover:rotate-6 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 dark:text-white text-lg uppercase tracking-tight">Produk Original</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">Jaminan 100% keaslian produk dengan garansi resmi distributor Indonesia.</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-5 p-6 rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 hover:border-purple-500/30 transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-500/20 flex-shrink-0 transform -rotate-3 group-hover:-rotate-6 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 dark:text-white text-lg uppercase tracking-tight">Rakit Express</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">Pengerjaan kilat oleh teknisi bersertifikat dengan cable management premium.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-5 p-6 rounded-2xl bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 hover:border-emerald-500/30 transition-all hover:-translate-y-1">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 flex-shrink-0 transform rotate-3 group-hover:rotate-6 transition-transform">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 dark:text-white text-lg uppercase tracking-tight">Layanan Prioritas</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">Dukungan purna jual responsif untuk konsultasi dan klaim garansi tanpa ribet.</p>
                </div>
            </div>
        </div>
    </section>
</div>