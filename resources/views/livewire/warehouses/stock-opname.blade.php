<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Stock Opname (Audit Stok)</h1>
        @if($viewMode === 'list')
            <button wire:click="toggleMode('create')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                + Mulai Opname Baru
            </button>
        @else
            <button wire:click="toggleMode('list')" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                &larr; Kembali ke List
            </button>
        @endif
    </div>

    @if($viewMode === 'list')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                    <tr>
                        <th class="px-6 py-4">No. Dokumen</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($opnames as $opname)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $opname->opname_number }}</td>
                            <td class="px-6 py-4">{{ $opname->opname_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $opname->creator->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold uppercase 
                                    {{ $opname->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $opname->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="show({{ $opname->id }})" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada data Stock Opname.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $opnames->links() }}
            </div>
        </div>

    @elseif($viewMode === 'create')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Opname</label>
                    <input type="date" wire:model="opnameDate" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" wire:model="notes" class="w-full rounded-lg border-gray-300" placeholder="Contoh: Audit Tahunan Q1">
                </div>
            </div>

            <div class="mb-6 relative">
                <label class="block text-sm font-medium text-gray-700 mb-1">Scan / Cari Produk</label>
                <input type="text" wire:model.live.debounce.300ms="searchProduct" class="w-full rounded-lg border-gray-300" placeholder="Ketik Nama atau SKU...">
                @if(!empty($searchProducts))
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @foreach($searchProducts as $product)
                            <button wire:click="addProduct({{ $product->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                <span class="font-bold">{{ $product->name }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ $product->sku }}) - Stok: {{ $product->stock_quantity }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 border-b">
                        <tr>
                            <th class="py-2 px-3">Produk</th>
                            <th class="py-2 px-3 w-32 text-center">Stok Sistem</th>
                            <th class="py-2 px-3 w-32 text-center">Fisik (Hitung)</th>
                            <th class="py-2 px-3 w-32 text-center">Selisih</th>
                            <th class="py-2 px-3">Catatan Item</th>
                            <th class="py-2 px-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($items as $pid => $item)
                            <tr>
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $item['sku'] }}</div>
                                </td>
                                <td class="py-2 px-3 text-center text-gray-500 bg-gray-50">
                                    {{ $item['system'] }}
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" 
                                           value="{{ $item['physical'] }}"
                                           wire:change="updatePhysical({{ $pid }}, $event.target.value)"
                                           class="w-full text-center border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 font-bold {{ $item['diff'] != 0 ? 'text-blue-600' : '' }}">
                                </td>
                                <td class="py-2 px-3 text-center font-bold {{ $item['diff'] < 0 ? 'text-red-600' : ($item['diff'] > 0 ? 'text-emerald-600' : 'text-gray-400') }}">
                                    {{ $item['diff'] > 0 ? '+' : '' }}{{ $item['diff'] }}
                                </td>
                                <td class="py-2 px-3">
                                    <input type="text" wire:model="items.{{ $pid }}.notes" class="w-full text-xs border-gray-200 rounded" placeholder="Alasan...">
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <button wire:click="removeItem({{ $pid }})" class="text-red-500 hover:text-red-700">&times;</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button wire:click="save" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30">
                    Simpan & Submit Opname
                </button>
            </div>
        </div>

    @elseif($viewMode === 'show' && $selectedOpname)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $selectedOpname->opname_number }}</h2>
                    <p class="text-gray-500 text-sm">Tanggal: {{ $selectedOpname->opname_date->format('d M Y') }} | Oleh: {{ $selectedOpname->creator->name }}</p>
                </div>
                <div>
                    @if($selectedOpname->status === 'pending_approval')
                        <button wire:click="approve" wire:confirm="Setujui Opname ini? Stok akan diperbarui otomatis." class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-lg">
                            Setujui & Update Stok
                        </button>
                    @else
                        <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-bold text-sm border border-gray-200">
                            Sudah Disetujui
                        </div>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 border-b">
                        <tr>
                            <th class="py-3 px-4">Produk</th>
                            <th class="py-3 px-4 text-center">Stok Sistem</th>
                            <th class="py-3 px-4 text-center">Stok Fisik</th>
                            <th class="py-3 px-4 text-center">Selisih</th>
                            <th class="py-3 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($selectedOpname->items as $item)
                            <tr class="{{ $item->difference != 0 ? 'bg-yellow-50/50' : '' }}">
                                <td class="py-3 px-4 font-medium">{{ $item->product->name }}</td>
                                <td class="py-3 px-4 text-center text-gray-500">{{ $item->system_stock }}</td>
                                <td class="py-3 px-4 text-center font-bold">{{ $item->physical_stock }}</td>
                                <td class="py-3 px-4 text-center font-bold {{ $item->difference < 0 ? 'text-red-600' : ($item->difference > 0 ? 'text-emerald-600' : 'text-gray-400') }}">
                                    {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}
                                </td>
                                <td class="py-3 px-4 text-gray-500 italic">{{ $item->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
