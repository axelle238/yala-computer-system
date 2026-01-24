<div class="p-6 max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Flash Sale Baru</h1>
        <a href="{{ route('marketing.flash-sale.index') }}" class="text-gray-500 hover:text-gray-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- Product Search -->
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                @if($product_id)
                    <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div>
                            <div class="font-bold text-blue-800">{{ $selectedProductName }}</div>
                            <div class="text-xs text-blue-600">Harga Asli: Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                        </div>
                        <button type="button" wire:click="$set('product_id', null)" class="text-xs text-red-600 font-bold hover:underline">Ganti</button>
                    </div>
                @else
                    <input type="text" wire:model.live.debounce.300ms="searchProduct" class="w-full rounded-lg border-gray-300" placeholder="Ketik nama produk...">
                    @if(!empty($searchResults))
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            @foreach($searchResults as $product)
                                <button type="button" wire:click="selectProduct({{ $product->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="font-bold">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif
                @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon (Flash Sale)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                        <input type="number" wire:model="discount_price" class="w-full pl-10 rounded-lg border-gray-300">
                    </div>
                    @error('discount_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quota (Stok Promo)</label>
                    <input type="number" wire:model="quota" class="w-full rounded-lg border-gray-300">
                    @error('quota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="datetime-local" wire:model="start_time" class="w-full rounded-lg border-gray-300">
                    @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                    <input type="datetime-local" wire:model="end_time" class="w-full rounded-lg border-gray-300">
                    @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">
                    Simpan Flash Sale
                </button>
            </div>
        </form>
    </div>
</div>
