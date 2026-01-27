<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bundle Produk</h1>
        @if($viewMode === 'list')
            <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Buat Bundle
            </button>
        @else
            <button wire:click="$set('viewMode', 'list')" class="text-gray-600 hover:text-gray-900">
                &larr; Kembali ke Daftar
            </button>
        @endif
    </div>

    @if($viewMode === 'list')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <input wire:model.live.debounce.300ms="cari" type="text" placeholder="Cari bundle..." class="w-full md:w-64 rounded-lg border-gray-300 text-sm">
            </div>
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-center">Item</th>
                        <th class="px-6 py-4 text-center">Stok (Virtual)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bundles as $bundle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold">{{ $bundle->name }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($bundle->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $bundle->item_count }}</td>
                            <td class="px-6 py-4 text-center">{{ $bundle->stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $bundle->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $bundle->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="edit({{ $bundle->id }})" class="text-blue-600 hover:underline">Ubah</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $bundles->links() }}</div>
        </div>

    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-800">Detail Bundle</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Bundle</label>
                    <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi</label>
                    <textarea wire:model="description" class="w-full rounded-lg border-gray-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga Jual</label>
                    <input type="number" wire:model="price" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Gambar</label>
                    <input type="file" wire:model="image" class="w-full text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" wire:model="isActive" class="rounded border-gray-300">
                    <label class="text-sm">Aktif</label>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">
                <h3 class="font-bold text-gray-800">Isi Bundle (Komponen)</h3>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="cariProduk" placeholder="Cari produk untuk ditambah..." class="w-full rounded-lg border-gray-300">
                    @if(!empty($searchResults))
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg">
                            @foreach($searchResults as $product)
                                <button wire:click="addProductToBundle({{ $product->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-50 border-b last:border-0">
                                    {{ $product->name }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    @foreach($bundleItems as $index => $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-bold text-sm">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($item['price']) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" wire:model="bundleItems.{{ $index }}.qty" class="w-16 rounded border-gray-300 text-center text-sm">
                                <button wire:click="removeProductFromBundle({{ $index }})" class="text-red-500">&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t">
                    @php $totalValue = array_reduce($bundleItems, fn($c, $i) => $c + ($i['price'] * $i['qty']), 0); @endphp
                    <p class="text-sm text-gray-500">Total Harga Komponen: Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>

                <button wire:click="save" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                    Simpan Bundle
                </button>
            </div>
        </div>
    @endif
</div>
