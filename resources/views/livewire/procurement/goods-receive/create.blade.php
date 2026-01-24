<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Penerimaan Barang (Goods Receive)</h1>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header Selection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Purchase Order (PO)</label>
                <select wire:model.live="purchaseOrderId" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih PO --</option>
                    @foreach($openPOs as $po)
                        <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name }}</option>
                    @endforeach
                </select>
                @error('purchaseOrderId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terima</label>
                <input type="date" wire:model="receiveDate" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea wire:model="notes" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="1"></textarea>
            </div>
        </div>

        @if($purchaseOrderId && count($poItems) > 0)
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Daftar Barang</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dipesan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sudah Diterima</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Terima Sekarang</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($poItems as $productId => $item)
                                <tr wire:key="row-{{ $productId }}" class="{{ $item['qty_receiving'] > 0 ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                        @if($item['has_serial_number'])
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Butuh Serial Number
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item['sku'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $item['qty_ordered'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $item['qty_received_prev'] }}</td>
                                    <td class="px-6 py-4">
                                        <input type="number" 
                                            wire:model.live.debounce.500ms="poItems.{{ $productId }}.qty_receiving" 
                                            min="0" 
                                            max="{{ $item['qty_ordered'] - $item['qty_received_prev'] }}"
                                            class="w-full text-center border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has("poItems.$productId.qty_receiving") ? 'border-red-500' : '' }}">
                                        @error("poItems.$productId.qty_receiving") 
                                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                                        @enderror
                                    </td>
                                </tr>

                                {{-- Row Khusus Serial Number --}}
                                @if($item['qty_receiving'] > 0 && $item['has_serial_number'])
                                    <tr wire:key="sn-row-{{ $productId }}" class="bg-gray-50">
                                        <td colspan="5" class="px-6 py-4">
                                            <div class="bg-white p-4 rounded border border-gray-200">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Input Serial Number ({{ $item['qty_receiving'] }} Unit)</h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach($item['serials'] as $index => $sn)
                                                        <div>
                                                            <input type="text" 
                                                                wire:model="poItems.{{ $productId }}.serials.{{ $index }}"
                                                                placeholder="Scan/Input SN #{{ $index + 1 }}"
                                                                class="w-full text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500"
                                                                onkeydown="if(event.key === 'Enter') { event.preventDefault(); const next = this.parentElement.nextElementSibling?.querySelector('input'); if(next) next.focus(); }"
                                                            >
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error("poItems.$productId.serials") 
                                                    <div class="text-red-500 text-sm mt-2 font-semibold">{{ $message }}</div> 
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="save" 
                        wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center">
                        <span wire:loading wire:target="save" class="mr-2">Processing...</span>
                        Simpan Penerimaan Barang
                    </button>
                </div>
            </div>
        @elseif($purchaseOrderId)
            <div class="text-center py-10 text-gray-500">
                Semua barang dalam PO ini sudah diterima sepenuhnya.
            </div>
        @endif
    </div>
</div>