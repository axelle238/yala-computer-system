<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $po ? 'Edit Purchase Order' : 'Buat PO Baru' }}
            </h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">No. PO: <span class="font-mono bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold">{{ $po_number }}</span></p>
        </div>
        
        <div class="flex gap-2">
            @if($po && $status === 'ordered')
                <button wire:click="receiveStock" wire:confirm="Pastikan fisik barang sudah diterima. Stok akan bertambah otomatis. Lanjutkan?" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-600/20 font-bold transition-all">
                    Terima Barang (Masuk Gudang)
                </button>
            @endif
            <a href="{{ route('purchase-orders.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
                Kembali
            </a>
        </div>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Header Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Supplier</label>
                    <select wire:model.live="supplier_id" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Order</label>
                    <input wire:model="order_date" type="date" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select wire:model="status" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" {{ $po && $po->status === 'received' ? 'disabled' : '' }}>
                        <option value="draft">Draft (Konsep)</option>
                        <option value="ordered">Ordered (Dipesan)</option>
                        <option value="received">Received (Diterima)</option>
                        <option value="cancelled">Cancelled (Batal)</option>
                    </select>
                </div>
            </div>

            <!-- Items Table -->
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Daftar Barang</h3>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-700 font-bold">
                            <tr>
                                <th class="p-4 w-1/2">Produk</th>
                                <th class="p-4 text-center">Qty</th>
                                <th class="p-4 text-right">Harga Satuan</th>
                                <th class="p-4 text-right">Subtotal</th>
                                <th class="p-4 text-center w-10">#</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($items as $index => $item)
                                <tr>
                                    <td class="p-2">
                                        <select wire:model.live="items.{{ $index }}.product_id" wire:change="updatePrice({{ $index }})" class="w-full px-3 py-2 border border-slate-200 rounded-lg">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->id }}">{{ $prod->name }} (Stok: {{ $prod->stock_quantity }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-2">
                                        <input wire:model.live="items.{{ $index }}.qty" type="number" min="1" class="w-20 px-3 py-2 border border-slate-200 rounded-lg text-center">
                                    </td>
                                    <td class="p-2 text-right">
                                        <input wire:model.live="items.{{ $index }}.price" type="number" class="w-32 px-3 py-2 border border-slate-200 rounded-lg text-right">
                                    </td>
                                    <td class="p-2 text-right font-bold text-slate-700">
                                        Rp {{ number_format(($items[$index]['qty'] * $items[$index]['price']), 0, ',', '.') }}
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" wire:click="removeItem({{ $index }})" class="text-rose-500 hover:bg-rose-50 p-2 rounded-full transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200">
                            <tr>
                                <td colspan="3" class="p-4 text-right font-bold text-slate-600">TOTAL ESTIMASI</td>
                                <td class="p-4 text-right font-extrabold text-slate-900 text-lg">Rp {{ number_format($this->total, 0, ',', '.') }}</td>
                                <td class="p-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button type="button" wire:click="addItem" class="mt-4 text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    + Tambah Baris Barang
                </button>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan</label>
                <textarea wire:model="notes" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
