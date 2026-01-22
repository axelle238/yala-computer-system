<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Catat Transaksi</h2>
        <p class="text-slate-500 mt-1 text-sm font-medium">Input barang masuk atau keluar (Penjualan/Restock).</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 space-y-6">
            
            <!-- Type Selection Cards -->
            <div class="grid grid-cols-2 gap-4">
                <label class="relative cursor-pointer group">
                    <input type="radio" wire:model.live="type" value="out" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2 hover:border-blue-200">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center peer-checked:bg-blue-600 peer-checked:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700 peer-checked:text-blue-700">Barang Keluar</span>
                        <span class="text-[10px] text-slate-400">Penjualan / Pemakaian</span>
                    </div>
                </label>

                <label class="relative cursor-pointer group">
                    <input type="radio" wire:model.live="type" value="in" class="peer sr-only">
                    <div class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all text-center h-full flex flex-col items-center justify-center gap-2 hover:border-emerald-200">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center peer-checked:bg-emerald-600 peer-checked:text-white transition-colors">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-700 peer-checked:text-emerald-700">Barang Masuk</span>
                        <span class="text-[10px] text-slate-400">Restock / Pembelian</span>
                    </div>
                </label>
            </div>

            <!-- Product Selection -->
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Pilih Produk</label>
                
                <!-- Simple Searchable Select Implementation -->
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live="productSearch" 
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                        placeholder="Ketik nama produk atau SKU..."
                    >
                    
                    <select wire:model.live="product_id" size="5" class="mt-2 block w-full border border-slate-200 rounded-xl bg-white p-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled>-- Pilih Produk dari Hasil Pencarian --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" class="p-2 border-b border-slate-50 hover:bg-blue-50 rounded cursor-pointer flex justify-between">
                                {{ $product->name }} (Stok: {{ $product->stock_quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('product_id') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Quantity & Reference -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">Jumlah (Qty)</label>
                    <div class="flex items-center">
                        <button type="button" wire:click="$decrement('quantity')" class="p-3 bg-slate-100 rounded-l-xl hover:bg-slate-200 border border-r-0 border-slate-200 text-slate-600">-</button>
                        <input type="number" wire:model="quantity" class="w-full text-center border-y border-slate-200 py-3 bg-white font-bold text-slate-800 focus:outline-none" min="1">
                        <button type="button" wire:click="$increment('quantity')" class="p-3 bg-slate-100 rounded-r-xl hover:bg-slate-200 border border-l-0 border-slate-200 text-slate-600">+</button>
                    </div>
                    @error('quantity') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold text-slate-700">No. Referensi (Opsional)</label>
                    <input type="text" wire:model="reference_number" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Contoh: INV-001">
                </div>
            </div>

            <!-- Member Info -->
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">No. HP Member (Opsional)</label>
                <input type="text" wire:model="customer_phone" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="08... (Poin otomatis masuk)">
            </div>

            @if($type === 'out')
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Serial Number Barang</label>
                <textarea wire:model="serial_numbers" rows="2" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Masukkan SN dipisah koma (Contoh: SN123, SN124)"></textarea>
                <p class="text-[10px] text-slate-400">Penting untuk klaim garansi.</p>
            </div>
            @endif

            <!-- Notes -->
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Catatan</label>
                <textarea wire:model="notes" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Tambahkan keterangan transaksi..."></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl text-slate-500 font-semibold hover:bg-slate-50 transition-colors">Batal</a>
                <button wire:click="save" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>
