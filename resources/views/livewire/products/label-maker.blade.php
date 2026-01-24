<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Label <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-500">Maker</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Generator barcode dan label harga fisik.</p>
        </div>
        
        <button wire:click="print" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Label
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Search & Select -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Cari Produk</h3>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500" placeholder="Scan Barcode / Ketik Nama...">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse($products as $product)
                        <button wire:click="addToQueue({{ $product->id }})" class="w-full text-left p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-600 transition-all flex justify-between items-center group">
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">{{ $product->sku }}</p>
                            </div>
                            <span class="text-xs font-bold text-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity">+ Tambah</span>
                        </button>
                    @empty
                        @if(strlen($search) > 2)
                            <p class="text-center text-slate-400 text-sm py-4">Produk tidak ditemukan.</p>
                        @else
                            <p class="text-center text-slate-400 text-sm py-4">Ketik minimal 3 karakter untuk mencari.</p>
                        @endif
                    @endforelse
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Pengaturan Cetak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Jenis Label</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border {{ $labelType === 'price_tag' ? 'border-cyan-500 bg-cyan-50 dark:bg-cyan-900/20' : 'border-slate-200 dark:border-slate-600' }}">
                                <input type="radio" wire:model="labelType" value="price_tag" class="text-cyan-600 focus:ring-cyan-500">
                                <div>
                                    <span class="font-bold text-sm block dark:text-white">Price Tag (Rak)</span>
                                    <span class="text-xs text-slate-500">Nama, Harga, Barcode</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border {{ $labelType === 'barcode_sticker' ? 'border-cyan-500 bg-cyan-50 dark:bg-cyan-900/20' : 'border-slate-200 dark:border-slate-600' }}">
                                <input type="radio" wire:model="labelType" value="barcode_sticker" class="text-cyan-600 focus:ring-cyan-500">
                                <div>
                                    <span class="font-bold text-sm block dark:text-white">Stiker Barcode</span>
                                    <span class="text-xs text-slate-500">Hanya Barcode & SKU</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Ukuran Kertas</label>
                        <select wire:model="paperSize" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-cyan-500">
                            <option value="a4">Kertas A4 (Sticker Sheet)</option>
                            <option value="thermal_100x150">Thermal 100x150mm</option>
                            <option value="thermal_50x30">Thermal 50x30mm</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Queue -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden flex flex-col h-[600px]">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Antrian Cetak ({{ count($queue) }})</h3>
                <button wire:click="clearQueue" class="text-xs text-rose-500 font-bold hover:underline">Bersihkan</button>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
                @forelse($queueItems as $item)
                    <div class="flex gap-3 items-center bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-3 rounded-xl shadow-sm">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ $item->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $item->sku }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   value="{{ $queue[$item->id] }}" 
                                   wire:change="updateQty({{ $item->id }}, $event.target.value)"
                                   class="w-16 text-center text-sm font-bold bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-1 py-1">
                            <button wire:click="removeFromQueue({{ $item->id }})" class="text-slate-400 hover:text-rose-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        <p class="text-sm">Antrian kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>