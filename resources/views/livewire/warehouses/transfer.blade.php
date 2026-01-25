<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Warehouse <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Transfer</span>
            </h2>
            <p class="text-slate-500 mt-1 text-sm">Pindahkan stok antar lokasi gudang atau toko.</p>
        </div>
        <button wire:click="$set('showForm', true)" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Buat Mutasi Baru
        </button>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 dark:bg-slate-700 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">No. Transfer</th>
                    <th class="px-6 py-4">Dari Gudang</th>
                    <th class="px-6 py-4">Ke Gudang</th>
                    <th class="px-6 py-4 text-center">Tanggal</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($transfers as $trf)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-blue-600">{{ $trf->transfer_number }}</td>
                        <td class="px-6 py-4">{{ $trf->source->name ?? 'Gudang Utama' }}</td>
                        <td class="px-6 py-4">{{ $trf->destination->name ?? 'Toko Cabang' }}</td>
                        <td class="px-6 py-4 text-center text-slate-500">{{ $trf->transfer_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-bold uppercase">Selesai</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada mutasi stok.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $transfers->links() }}</div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-lg text-slate-800">Form Mutasi Stok</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500">&times;</button>
                </div>
                <div class="p-6 overflow-y-auto space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Gudang (Source)</label>
                            <select wire:model="source_warehouse_id" class="w-full rounded-lg border-slate-300">
                                @foreach($warehouses as $w) <option value="{{ $w->id }}">{{ $w->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ke Gudang (Destination)</label>
                            <select wire:model="dest_warehouse_id" class="w-full rounded-lg border-slate-300">
                                <option value="">-- Pilih --</option>
                                @foreach($warehouses as $w) <option value="{{ $w->id }}">{{ $w->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Item Mutasi</label>
                        <table class="w-full text-sm border border-slate-200 rounded-lg overflow-hidden">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="p-2 text-left">Produk</th>
                                    <th class="p-2 w-24">Qty</th>
                                    <th class="p-2 w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td class="p-2 border-t border-slate-100">
                                            <select wire:model="items.{{ $index }}.product_id" class="w-full text-sm border-slate-300 rounded">
                                                <option value="">Pilih Produk</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }} (Sisa: {{ $p->stock_quantity }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="p-2 border-t border-slate-100">
                                            <input type="number" wire:model="items.{{ $index }}.qty" class="w-full text-sm border-slate-300 rounded text-center">
                                        </td>
                                        <td class="p-2 border-t border-slate-100 text-center">
                                            <button wire:click="removeItem({{ $index }})" class="text-rose-500 hover:text-rose-700">&times;</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button wire:click="addItem" class="mt-2 text-xs font-bold text-blue-600 hover:underline">+ Tambah Baris Item</button>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan</label>
                        <textarea wire:model="notes" class="w-full rounded-lg border-slate-300" rows="2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button wire:click="save" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg">Proses Mutasi</button>
                </div>
            </div>
        </div>
    @endif
</div>