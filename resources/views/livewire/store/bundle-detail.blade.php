<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8 font-mono">
            <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Home</a>
            <span>/</span>
            <span class="text-slate-300">Bundles</span>
            <span>/</span>
            <span class="text-cyan-500">{{ $bundle->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left: Image -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/10 to-transparent opacity-50"></div>
                    
                    <div class="relative z-10 aspect-square flex items-center justify-center">
                        @if($bundle->image_path)
                            <img src="{{ asset('storage/' . $bundle->image_path) }}" class="max-w-full max-h-full object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex flex-wrap gap-4 justify-center items-center opacity-80">
                                <!-- Dynamic Icons based on items -->
                                @foreach($bundle->items->take(4) as $item)
                                    <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center border border-slate-200 dark:border-slate-600">
                                        @if($item->product->image_path)
                                            <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-16 h-16 object-contain">
                                        @else
                                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 bg-rose-500 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-lg">Bundle Hemat</span>
                    </div>
                </div>
            </div>

            <!-- Right: Details -->
            <div>
                <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter leading-tight">{{ $bundle->name }}</h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg mb-8 leading-relaxed">{{ $bundle->description }}</p>

                <!-- Pricing -->
                <div class="bg-slate-100 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-500 font-bold uppercase text-xs tracking-wider">Harga Normal</span>
                        <span class="text-slate-400 line-through decoration-rose-500 font-mono">Rp {{ number_format($bundle->original_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-slate-900 dark:text-white font-bold uppercase text-sm tracking-wider">Harga Bundle</span>
                        <span class="text-4xl font-black text-cyan-500 font-mono">Rp {{ number_format($bundle->bundle_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                        <span class="text-emerald-500 font-bold text-sm bg-emerald-500/10 px-3 py-1 rounded-lg">Hemat Rp {{ number_format($bundle->original_price - $bundle->bundle_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Items List -->
                <div class="mb-8">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 uppercase text-sm tracking-wider">Isi Paket</h3>
                    <div class="space-y-3">
                        @foreach($bundle->items as $item)
                            <div class="flex items-center gap-4 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700">
                                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <a href="{{ route('product.detail', $item->product_id) }}" class="font-bold text-slate-800 dark:text-white text-sm hover:text-cyan-500 transition-colors">{{ $item->product->name }}</a>
                                    <p class="text-xs text-slate-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono text-xs text-slate-400">Rp {{ number_format($item->product->sell_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button wire:click="addToCart" class="w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all text-lg uppercase tracking-wider flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Beli Paket Ini
                </button>
            </div>
        </div>
    </div>
</div>
