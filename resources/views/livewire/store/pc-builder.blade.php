<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 font-sans relative">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                PC <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Builder</span> Simulator
            </h1>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-lg">
                Rakit komputer impianmu dengan simulasi harga dan kompatibilitas real-time. Simpan konfigurasi atau langsung beli.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Component Slots -->
            <div class="lg:w-2/3 space-y-4 animate-fade-in-up delay-100">
                
                <!-- Errors -->
                @if(!empty($compatibilityIssues))
                    <div class="bg-rose-100 border-l-4 border-rose-500 text-rose-700 p-4 rounded-r shadow-sm mb-4">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Masalah Kompatibilitas (Error):
                        </p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach($compatibilityIssues as $issue)
                                <li>{{ $issue }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Warnings -->
                @if(!empty($compatibilityWarnings))
                    <div class="bg-amber-100 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r shadow-sm mb-4">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Peringatan (Warning):
                        </p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach($compatibilityWarnings as $warn)
                                <li>{{ $warn }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Info -->
                @if(!empty($compatibilityInfo))
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r shadow-sm mb-4">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Saran Sistem:
                        </p>
                        <ul class="list-disc list-inside mt-2 text-sm">
                            @foreach($compatibilityInfo as $info)
                                <li>{{ $info }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @foreach($partsList as $slug => $meta)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:border-blue-400 transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <!-- Icon/Image Placeholder -->
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 flex-shrink-0">
                                    @if(isset($selection[$slug]['image']) && $selection[$slug]['image'])
                                        <img src="{{ asset('storage/' . $selection[$slug]['image']) }}" class="w-full h-full object-cover rounded-xl" alt="Part">
                                    @else
                                        <!-- Dynamic Icon based on slug -->
                                        @if($meta['icon'] == 'cpu') <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                        @elseif($meta['icon'] == 'server') <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                                        @elseif($meta['icon'] == 'memory') <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                        @else <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                        @endif
                                    @endif
                                </div>
                                
                                <div>
                                    <h3 class="text-xs md:text-sm font-bold uppercase tracking-wider text-slate-500 mb-1">{{ $meta['label'] }}</h3>
                                    @if($selection[$slug])
                                        <div class="font-bold text-slate-900 dark:text-white text-base md:text-lg leading-tight">{{ $selection[$slug]['name'] }}</div>
                                        <div class="text-blue-600 font-mono font-bold mt-1">Rp {{ number_format($selection[$slug]['price'], 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-slate-400 text-sm italic">Belum dipilih</div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                @if($selection[$slug])
                                    <div class="flex flex-col md:flex-row gap-2">
                                        <button wire:click="openSelector('{{ $slug }}')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors">
                                            Ganti
                                        </button>
                                        <button wire:click="removePart('{{ $slug }}')" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <button wire:click="openSelector('{{ $slug }}')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all text-sm flex items-center gap-2">
                                        + Pilih <span class="hidden md:inline">Komponen</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Column: Summary (Sticky) -->
            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    <!-- Pricing Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-200">
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Rakitan</label>
                            <input type="text" wire:model.blur="buildName" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl font-bold text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-slate-500 font-bold">Estimasi Daya</span>
                            <span class="font-mono font-bold text-slate-900 dark:text-white flex items-center gap-1">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" /></svg>
                                {{ $estimatedWattage }} Watt
                            </span>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700 pt-6 pb-2">
                            <p class="text-slate-500 text-sm font-bold uppercase mb-1">Total Estimasi Harga</p>
                            <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </h2>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mt-8">
                            <button wire:click="saveBuild" class="col-span-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                Simpan
                            </button>
                            <button wire:click="printPdf" class="col-span-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                Cetak
                            </button>
                            <button wire:click="addToCart" class="col-span-2 px-6 py-4 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 text-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Masukkan Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Component Selector -->
    @if($showSelector)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase">
                            Pilih {{ $partsList[$currentCategory]['label'] }}
                        </h3>
                        <p class="text-sm text-slate-500">Cari dan pilih komponen terbaik untuk rakitanmu.</p>
                    </div>
                    <button wire:click="closeSelector" class="p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-full transition-colors">
                        <svg class="w-6 h-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Toolbar -->
                <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                    <input wire:model.live.debounce.300ms="searchQuery" type="text" class="w-full bg-slate-100 dark:bg-slate-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 text-sm font-bold" placeholder="Cari nama produk, brand, atau spesifikasi...">
                </div>

                <!-- List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50 dark:bg-slate-900/50">
                    @forelse($products as $product)
                        <div wire:click="selectProduct({{ $product->id }})" class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-blue-500 hover:ring-1 hover:ring-blue-500 transition-all group flex flex-col md:flex-row gap-4 items-center">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-lg flex-shrink-0 flex items-center justify-center">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>
                            <div class="flex-1 text-center md:text-left">
                                <h4 class="font-bold text-slate-800 dark:text-white group-hover:text-blue-600 transition-colors">{{ $product->name }}</h4>
                                <div class="text-xs text-slate-500 line-clamp-1">{{ $product->sku }} | Stok: {{ $product->stock_quantity }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-lg text-blue-600 font-mono">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400">
                            <p>Tidak ada produk ditemukan untuk kategori ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="p-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
                    {{ $products->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    @endif
</div>