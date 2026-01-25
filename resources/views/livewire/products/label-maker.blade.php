<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Label <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Maker</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Cetak label harga dan barcode untuk display toko.</p>
        </div>
        <button wire:click="printLabels" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Label
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm h-fit">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Konfigurasi Cetak</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ukuran Kertas</label>
                    <select wire:model="paperSize" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-amber-500">
                        <option value="a4">A4 (Grid Sticker 3x7)</option>
                        <option value="thermal">Thermal (Roll 58mm)</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="showPrice" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Tampilkan Harga</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="showBarcode" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Tampilkan Barcode</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Builder -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search -->
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm focus:ring-amber-500" placeholder="Cari produk untuk ditambahkan...">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                
                @if(!empty($searchResults))
                    <div class="absolute z-10 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden">
                        @foreach($searchResults as $result)
                            <button wire:click="addProduct({{ $result->id }})" class="w-full text-left px-4 py-3 hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors flex justify-between items-center border-b border-slate-100 dark:border-slate-700 last:border-0">
                                <span class="font-bold text-slate-700 dark:text-white">{{ $result->name }}</span>
                                <span class="text-xs text-slate-500 font-mono">{{ $result->sku }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- List -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700 dark:text-slate-300">Daftar Antrian Cetak</h3>
                    <span class="text-xs bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded font-bold text-slate-600 dark:text-slate-400">{{ count($selectedProducts) }} Item</span>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($selectedProducts as $index => $item)
                        <div class="p-4 flex items-center gap-4">
                            <div class="flex-1">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $item['sku'] }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="number" wire:model="selectedProducts.{{ $index }}.qty" class="w-16 text-center rounded-lg border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 py-1 text-sm font-bold" min="1">
                                <button wire:click="removeProduct({{ $index }})" class="text-rose-500 hover:text-rose-700 p-1 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Belum ada produk yang dipilih.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>