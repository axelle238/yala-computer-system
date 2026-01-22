<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $product ? 'Edit Produk' : 'Tambah Produk Baru' }}
            </h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Lengkapi informasi detail produk untuk inventaris.</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Basic Info Section -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Informasi Dasar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk</label>
                        <input wire:model.blur="name" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Contoh: Laptop ASUS ROG Zephyrus G14">
                        @error('name') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <select wire:model="category_id" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Supplier</label>
                        <select wire:model="supplier_id" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Inventory & Pricing -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Stok & Harga</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kode SKU</label>
                        <input wire:model="sku" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all uppercase" placeholder="AUTO-GEN">
                        @error('sku') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Barcode (Opsional)</label>
                        <input wire:model="barcode" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                         <label class="block text-sm font-bold text-slate-700 mb-2">Stok Awal</label>
                         <input wire:model="stock_quantity" type="number" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Beli (Modal)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">Rp</span>
                            <input wire:model="buy_price" type="number" class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Jual</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">Rp</span>
                            <input wire:model="sell_price" type="number" class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                     <div>
                         <label class="block text-sm font-bold text-slate-700 mb-2">Alert Stok Minim</label>
                         <input wire:model="min_stock_alert" type="number" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Media & Description -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Media & Detail</h3>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Produk</label>
                    <textarea wire:model="description" rows="4" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Foto Produk</label>
                    <div class="flex items-center gap-6">
                        @if ($image)
                            <div class="w-32 h-32 rounded-xl border border-slate-200 overflow-hidden relative">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                            </div>
                        @elseif ($image_path)
                             <div class="w-32 h-32 rounded-xl border border-slate-200 overflow-hidden relative">
                                <img src="{{ asset('storage/' . $image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-32 h-32 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <label class="cursor-pointer">
                            <span class="inline-block px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                                Pilih File...
                            </span>
                            <input wire:model="image" type="file" class="hidden" accept="image/*">
                        </label>
                        <div wire:loading wire:target="image" class="text-sm text-blue-600 font-medium animate-pulse">
                            Mengupload...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $product ? 'Simpan Perubahan' : 'Simpan Produk Baru' }}
                </button>
            </div>
        </div>
    </form>
</div>
