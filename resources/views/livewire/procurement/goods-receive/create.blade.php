<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                Penerimaan Barang (Goods Receive)
            </h2>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Form Header -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-medium text-slate-900 mb-4">Informasi Dokumen</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Pilih Purchase Order</label>
                        <select wire:model.live="poId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Pilih PO --</option>
                            @foreach($openPOs as $po)
                                <option value="{{ $po->id }}">#{{ $po->po_number }} - {{ $po->pemasok->name ?? 'Unknown' }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($purchaseOrder)
                        <div class="p-3 bg-slate-50 rounded text-sm text-slate-600 border border-slate-200">
                            <p class="font-bold text-slate-800">{{ $purchaseOrder->pemasok->name }}</p>
                            <p>Tgl Order: {{ $purchaseOrder->order_date->format('d M Y') }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700">No. GRN (Internal)</label>
                        <input type="text" wire:model="grnNumber" class="mt-1 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md bg-slate-100" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">No. Surat Jalan (Supplier) <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="supplierDoNumber" class="mt-1 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: SJ-2024-001">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tanggal Terima</label>
                        <input type="date" wire:model="receivedDate" class="mt-1 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Catatan</label>
                        <textarea wire:model="notes" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Item Grid -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg overflow-hidden border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Barang</h3>
                    <span class="text-xs text-slate-500 bg-yellow-100 px-2 py-1 rounded border border-yellow-200">
                        Input jumlah barang yang fisik diterima baik.
                    </span>
                </div>
                
                @if(!$poId)
                    <div class="p-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="mt-2 text-sm font-medium">Silakan pilih PO terlebih dahulu</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Produk</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Sudah Terima</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider w-32">Terima Skrg</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-slate-900">{{ $item['product_name'] }}</div>
                                            <div class="text-xs text-slate-500">{{ $item['sku'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                                            {{ $item['qty_ordered'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                                            {{ $item['qty_received_prev'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" 
                                                   wire:model="items.{{ $index }}.qty_input" 
                                                   min="0"
                                                   class="block w-full text-center sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 {{ $item['qty_input'] > 0 ? 'bg-emerald-50 border-emerald-300' : '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button wire:click="save" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Penerimaan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
