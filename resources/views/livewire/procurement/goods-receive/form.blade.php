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

        <!-- Items List & Input Panel -->
        <div class="lg:col-span-2 space-y-6">
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
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 {{ $activeItemId == $productId ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
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
                                    <button wire:click="openItemModal({{ $productId }})" class="text-blue-600 hover:text-blue-800 font-bold text-xs bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-lg transition {{ $activeItemId == $productId ? 'ring-2 ring-blue-500' : '' }}">
                                        {{ $activeItemId == $productId ? 'Sedang Edit' : 'Input Terima' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Panel Input Inline (Pengganti Modal) -->
            @if($activeItemId)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border-2 border-blue-500 overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-blue-50 dark:bg-blue-900/50 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $items[$activeItemId]['name'] }}</h3>
                            <p class="text-xs text-slate-500">Input jumlah dan nomor seri yang diterima</p>
                        </div>
                        <button wire:click="$set('activeItemId', null)" class="text-slate-400 hover:text-rose-500 font-bold text-sm flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tutup Panel
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jumlah Diterima (Maks: {{ $items[$activeItemId]['ordered_qty'] }})</label>
                                <input wire:model.live="tempQty" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-2xl font-black text-blue-600 focus:ring-blue-500" min="0" max="{{ $items[$activeItemId]['ordered_qty'] }}">
                                <p class="text-[10px] text-slate-400 mt-2 italic">* Pastikan jumlah sesuai dengan fisik barang yang datang.</p>
                            </div>

                            @if($items[$activeItemId]['has_serial'] && $tempQty > 0)
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-800/50">
                                    <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        Scan Nomor Seri ({{ $tempQty }} Unit)
                                    </h4>
                                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                        @for($i=0; $i<$tempQty; $i++)
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-[10px] text-indigo-400 font-mono">{{ $i+1 }}</span>
                                                <input wire:model="tempSerials.{{ $i }}" type="text" class="w-full pl-8 pr-3 py-2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-xs font-mono uppercase focus:ring-indigo-500" placeholder="Scan SN...">
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                        <button wire:click="$set('activeItemId', null)" class="px-6 py-2 text-slate-500 font-bold">Batal</button>
                        <button wire:click="saveItem" class="px-10 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform active:scale-95">
                            Konfirmasi Item
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
