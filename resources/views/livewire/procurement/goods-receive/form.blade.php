<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Penerimaan Barang (Goods Receive)</h1>
        <a href="{{ route('purchase-orders.index') }}" class="text-gray-500 hover:text-gray-900">&larr; Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Header Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Purchase Order (PO)</label>
                        <select wire:model.live="purchaseOrderId" class="w-full rounded-lg border-gray-300">
                            <option value="">-- Pilih PO --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedPo)
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg text-sm text-blue-800">
                            <p><strong>Supplier:</strong> {{ $selectedPo->supplier->name }}</p>
                            <p><strong>Tgl Order:</strong> {{ $selectedPo->order_date->format('d M Y') }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Surat Jalan (DO)</label>
                        <input type="text" wire:model="doNumber" class="w-full rounded-lg border-gray-300" placeholder="Nomor DO Supplier">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima</label>
                        <input type="date" wire:model="receivedDate" class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="lg:col-span-2">
            @if($selectedPo)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Daftar Barang</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="py-3 px-4">Produk</th>
                                    <th class="py-3 px-4 text-center">Dipesan</th>
                                    <th class="py-3 px-4 text-center">Sdh Terima</th>
                                    <th class="py-3 px-4 w-32">Terima Skrg</th>
                                    <th class="py-3 px-4 text-center">Serial Number</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($items as $pid => $item)
                                    <tr>
                                        <td class="py-3 px-4">
                                            <div class="font-medium">{{ $item['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $item['sku'] }}</div>
                                        </td>
                                        <td class="py-3 px-4 text-center">{{ $item['ordered'] }}</td>
                                        <td class="py-3 px-4 text-center">{{ $item['prev_received'] }}</td>
                                        <td class="py-3 px-4">
                                            <input type="number" 
                                                   value="{{ $item['receiving_now'] }}" 
                                                   wire:change="updateQty({{ $pid }}, $event.target.value)"
                                                   class="w-full rounded border-gray-300 text-center font-bold">
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button wire:click="openSerialModal({{ $pid }})" class="relative inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded-full text-xs font-medium transition-colors {{ count($item['serials']) != $item['receiving_now'] ? 'text-red-600 ring-1 ring-red-500' : 'text-slate-700' }}">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 20h.01M12 12h.01M3 13a4 4 0 014-4h4a4 4 0 014 4v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3z" /></svg>
                                                <span>{{ count($item['serials']) }} Input</span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="save" class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg">
                            Simpan Penerimaan
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-12 text-center border-2 border-dashed border-gray-200 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <p>Silakan pilih Purchase Order di sebelah kiri untuk mulai.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Serial Number Modal -->
    @if($showSerialModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Input Serial Number</h3>
                    <button wire:click="$set('showSerialModal', false)" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Scan atau ketik Serial Number (Satu per baris). <br> Target: <strong>{{ $items[$currentSerialProductId]['receiving_now'] }}</strong> unit.</p>
                        <textarea wire:model="serialInput" rows="10" class="w-full rounded-lg border-gray-300 font-mono text-sm" placeholder="SN001&#10;SN002&#10;..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('showSerialModal', false)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button wire:click="saveSerials" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>