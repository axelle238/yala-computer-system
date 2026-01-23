<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Barcode Label Generator</h1>
            <p class="text-slate-500 dark:text-slate-400">Buat dan cetak stiker barcode untuk produk inventaris.</p>
        </div>
        <div>
            <button wire:click="printLabels" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-slate-500/20 transition-all flex items-center gap-2 {{ empty($queue) ? 'opacity-50 cursor-not-allowed' : '' }}" {{ empty($queue) ? 'disabled' : '' }}>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                CETAK LABEL
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Search & Add -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 h-fit">
            <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Pilih Produk</h3>
            <div class="relative mb-4">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-cyan-500 transition-all" placeholder="Cari SKU / Nama...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>

            <div class="space-y-2">
                @forelse($products as $product)
                    <button wire:click="addProduct({{ $product->id }})" class="w-full flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 border border-transparent hover:border-cyan-200 rounded-xl transition-all group text-left">
                        <div>
                            <div class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-cyan-600">{{ $product->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $product->sku }}</div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-cyan-500 shadow-sm">
                            +
                        </div>
                    </button>
                @empty
                    @if(strlen($search) > 2)
                        <div class="text-center text-slate-400 text-sm py-4">Produk tidak ditemukan.</div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- Queue List -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-700/30">
                <h3 class="font-bold text-slate-800 dark:text-white">Antrian Cetak ({{ count($queue) }} Item)</h3>
                @if(count($queue) > 0)
                    <button wire:click="clearQueue" class="text-xs text-rose-500 font-bold hover:underline">HAPUS SEMUA</button>
                @endif
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar min-h-[400px]">
                @forelse($queue as $index => $item)
                    <div class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 rounded-xl mb-3 shadow-sm hover:border-cyan-300 transition-colors animate-fade-in-up">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-xl font-bold text-slate-400">
                            {{ substr($item['name'], 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</h4>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                <span class="bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded font-mono border border-slate-200 dark:border-slate-700">{{ $item['sku'] }}</span>
                                <span>{{ $item['barcode'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-bold text-slate-400 uppercase">Qty:</label>
                            <input type="number" min="1" 
                                wire:change="updateQty({{ $index }}, $event.target.value)" 
                                value="{{ $item['qty_to_print'] }}" 
                                class="w-16 text-center font-bold text-slate-800 dark:text-white bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg focus:ring-cyan-500">
                        </div>
                        <button wire:click="remove({{ $index }})" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 py-12">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        <p>Belum ada produk yang dipilih.</p>
                        <p class="text-sm">Cari produk di panel kiri untuk mulai.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
