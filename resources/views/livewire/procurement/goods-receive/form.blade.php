<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Proses Penerimaan Barang</h2>
            <p class="text-slate-500">PO #{{ $po->po_number }} • {{ $po->supplier->name }}</p>
        </div>
        <button wire:click="submitGrn" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Selesai & Simpan Stok
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Panel -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Info Penerimaan</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Terima</label>
                        <input wire:model="receivedDate" type="date" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Dipesan</th>
                            <th class="px-6 py-4 text-center">Diterima</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($items as $productId => $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</div>
                                    @if($item['has_serial'])
                                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-bold uppercase tracking-wider">Serial Number</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-slate-500">{{ $item['ordered_qty'] }}</td>
                                <td class="px-6 py-4 text-center font-bold {{ $item['received_qty'] == $item['ordered_qty'] ? 'text-emerald-600' : ($item['received_qty'] > 0 ? 'text-amber-600' : 'text-slate-400') }}">
                                    {{ $item['received_qty'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="openItemModal({{ $productId }})" class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 px-3 py-1.5 rounded-lg transition">
                                        Input Terima
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Input Item -->
    @if($activeItemId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $items[$activeItemId]['name'] }}</h3>
                        <p class="text-xs text-slate-500">Order: {{ $items[$activeItemId]['ordered_qty'] }} Unit</p>
                    </div>
                    <button wire:click="$set('activeItemId', null)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="p-6 overflow-y-auto">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah Diterima</label>
                        <input wire:model.live="tempQty" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-lg font-bold" min="0" max="{{ $items[$activeItemId]['ordered_qty'] }}">
                    </div>

                    @if($items[$activeItemId]['has_serial'] && $tempQty > 0)
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                            <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                Scan Serial Number
                            </h4>
                            <div class="space-y-2">
                                @for($i=0; $i<$tempQty; $i++)
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-indigo-400 font-mono w-4">{{ $i+1 }}.</span>
                                        <input wire:model="tempSerials.{{ $i }}" type="text" class="flex-1 rounded-lg border-slate-300 dark:border-slate-600 text-sm font-mono uppercase focus:ring-indigo-500" placeholder="Scan SN...">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="saveItem" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition">Simpan Item</button>
                </div>
            </div>
        </div>
    @endif
</div>
