<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white">
            Setup Flash Sale
        </h2>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
        <div class="space-y-6">
            <!-- Product Search -->
            <div class="relative">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Produk</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500 font-bold" placeholder="Cari produk...">
                
                @if(!empty($products) && !$selectedProduct)
                    <div class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 z-20 overflow-hidden">
                        @foreach($products as $product)
                            <button wire:click="selectProduct({{ $product->id }})" class="w-full text-left px-4 py-3 hover:bg-rose-50 dark:hover:bg-slate-700 flex justify-between items-center group">
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">Stok: {{ $product->stock_quantity }}</p>
                                </div>
                                <span class="font-mono font-bold text-slate-600 dark:text-slate-300">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                @error('product_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            @if($selectedProduct)
                <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Harga Normal</p>
                        <p class="text-lg font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($selectedProduct->sell_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold text-right">Profit Margin</p>
                        <p class="text-sm font-mono font-bold text-emerald-600 text-right">
                            {{ number_format((($selectedProduct->sell_price - $selectedProduct->buy_price) / $selectedProduct->sell_price) * 100, 1) }}%
                        </p>
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Harga Diskon (Flash Sale)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-500 font-bold">Rp</span>
                    </div>
                    <input wire:model="discount_price" type="number" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500 font-mono font-bold text-lg text-rose-600">
                </div>
                @error('discount_price') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Waktu Mulai</label>
                    <input wire:model="start_time" type="datetime-local" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500">
                    @error('start_time') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Waktu Berakhir</label>
                    <input wire:model="end_time" type="datetime-local" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500">
                    @error('end_time') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Quota Promo (Unit)</label>
                <input wire:model="quota" type="number" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500">
                <p class="text-[10px] text-slate-400 mt-1">Jumlah maksimal barang yang bisa dibeli dengan harga promo.</p>
                @error('quota') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('marketing.flash-sale.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Batal</a>
                <button wire:click="save" class="px-8 py-3 bg-rose-600 text-white font-bold rounded-xl shadow-lg hover:bg-rose-700 transition-all">
                    Jadwalkan Promo
                </button>
            </div>
        </div>
    </div>
</div>