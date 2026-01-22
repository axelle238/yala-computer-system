<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-600">Transfer</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Mutasi stok antar gudang / lokasi.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Locations -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                <!-- Arrow Icon -->
                <div class="hidden md:flex absolute inset-0 items-center justify-center pointer-events-none">
                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Dari Gudang (Source)</label>
                    <div class="relative">
                        <select wire:model="source_warehouse_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-orange-500 font-bold appearance-none">
                            <option value="">Pilih Asal...</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Ke Gudang (Destination)</label>
                    <div class="relative">
                        <select wire:model="destination_warehouse_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-orange-500 font-bold appearance-none">
                            <option value="">Pilih Tujuan...</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-900 dark:text-white">Item yang Dipindahkan</h3>
                    <button wire:click="addItem" class="text-xs font-bold text-orange-600 hover:underline">+ Tambah Item</button>
                </div>

                <div class="space-y-3">
                    @foreach($transferItems as $index => $item)
                        <div class="flex gap-4 items-start animate-slide-in-right">
                            <div class="flex-1">
                                <select wire:model="transferItems.{{ $index }}.product_id" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                                    <option value="">Pilih Produk...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Total Stok: {{ $product->stock_quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <input wire:model="transferItems.{{ $index }}.qty" type="number" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-center" placeholder="Qty" min="1">
                            </div>
                            <button wire:click="removeItem({{ $index }})" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan Transfer</label>
                <textarea wire:model="notes" rows="2" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-orange-500"></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button wire:click="save" class="px-8 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-orange-600/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    Proses Transfer
                </button>
            </div>
        </div>
    </div>
</div>