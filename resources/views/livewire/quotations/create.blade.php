<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Buat <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Penawaran</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Dokumen penawaran harga resmi untuk klien korporat.</p>
        </div>
        <a href="{{ route('admin.penawaran.indeks') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-bold hover:bg-slate-200 transition">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <!-- Customer Info -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Informasi Klien</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pilih Pelanggan</label>
                    <select wire:model="customer_id" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->company ?? 'Personal' }}</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Berlaku Hingga</label>
                    <input type="date" wire:model="valid_until" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4">Rincian Barang</h3>
            
            <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-xl mb-4">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 w-24">Qty</th>
                            <th class="px-4 py-3 w-40 text-right">Harga Satuan</th>
                            <th class="px-4 py-3 w-40 text-right">Subtotal</th>
                            <th class="px-4 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($items as $index => $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-4 py-2 relative">
                                    @if($item['product_id'])
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $item['product_name'] }}</div>
                                        <button wire:click="items.{{ $index }}.product_id = ''" class="text-xs text-blue-500 hover:underline">Ganti</button>
                                    @else
                                        <input type="text" wire:keydown.enter="updateProductSearch($event.target.value)" placeholder="Cari Produk..." class="w-full bg-transparent border-none focus:ring-0 p-0 text-sm">
                                        @if(!empty($searchResults) && $loop->last) <!-- Simple dropdown logic -->
                                            <div class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg">
                                                @foreach($searchResults as $res)
                                                    <button wire:click="selectProduct({{ $index }}, {{ $res->id }})" class="block w-full text-left px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-700 text-xs">
                                                        {{ $res->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" wire:model.live="items.{{ $index }}.qty" wire:change="calculateRow({{ $index }})" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 text-center font-bold text-xs">
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" wire:model.live="items.{{ $index }}.price" wire:change="calculateRow({{ $index }})" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 text-right text-xs">
                                </td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-700 dark:text-slate-300">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <button wire:click="removeItem({{ $index }})" class="text-rose-500 hover:text-rose-700">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 dark:bg-slate-900 font-bold">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right uppercase text-xs text-slate-500">Total Penawaran</td>
                            <td class="px-4 py-3 text-right font-mono text-lg text-blue-600">
                                Rp {{ number_format(collect($items)->sum('subtotal'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <button wire:click="addItem" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 transition">
                + Tambah Baris
            </button>
        </div>

        <!-- Footer Notes -->
        <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Catatan Tambahan</label>
                <textarea wire:model="notes" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Syarat & Ketentuan</label>
                <textarea wire:model="terms" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500"></textarea>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
            <button wire:click="save" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Simpan Penawaran
            </button>
        </div>
    </div>
</div>
