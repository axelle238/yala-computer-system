<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ $quotation ? 'Edit Penawaran: ' . $quotation->quotation_number : 'Buat Penawaran Baru' }}
        </h1>
        <a href="{{ route('quotations.index') }}" class="text-gray-500 hover:text-gray-900">&larr; Kembali</a>
    </div>

    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Item Penawaran</h3>
                
                <!-- Search Product -->
                <div class="relative mb-6">
                    <input type="text" wire:model.live.debounce.300ms="searchProduct" placeholder="Cari produk untuk ditambahkan..." class="w-full rounded-lg border-gray-300 focus:ring-blue-500">
                    @if(!empty($searchResults))
                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            @foreach($searchResults as $product)
                                <button type="button" wire:click="addProduct({{ $product->id }})" class="w-full text-left px-4 py-2 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Table -->
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="py-2 px-3">Item</th>
                            <th class="py-2 px-3 w-24">Qty</th>
                            <th class="py-2 px-3 w-32">Harga Satuan</th>
                            <th class="py-2 px-3 text-right">Total</th>
                            <th class="py-2 px-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($items as $index => $item)
                            <tr>
                                <td class="py-2 px-3">
                                    <input type="text" wire:model="items.{{ $index }}.item_name" class="w-full text-sm border-gray-200 rounded">
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" wire:model.live="items.{{ $index }}.quantity" wire:change="updateItem({{ $index }})" class="w-full text-sm border-gray-200 rounded text-center">
                                </td>
                                <td class="py-2 px-3">
                                    <input type="number" wire:model.live="items.{{ $index }}.unit_price" wire:change="updateItem({{ $index }})" class="w-full text-sm border-gray-200 rounded text-right">
                                </td>
                                <td class="py-2 px-3 text-right font-medium">
                                    Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                        &times;
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t font-bold text-gray-800 bg-gray-50">
                        <tr>
                            <td colspan="3" class="py-3 px-3 text-right">Subtotal</td>
                            <td class="py-3 px-3 text-right">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Notes -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Internal / Pesan untuk User</label>
                    <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan</label>
                    <textarea wire:model="terms_and_conditions" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                </div>
            </div>
        </div>

        <!-- Right: Settings -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Pengaturan</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                        <select wire:model="user_id" class="w-full rounded-lg border-gray-300">
                            <option value="">Pilih User...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Hingga</label>
                        <input type="date" wire:model="valid_until" class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Persetujuan</label>
                        <select wire:model="approval_status" class="w-full rounded-lg border-gray-300">
                            <option value="pending">Pending Review</option>
                            <option value="approved">Approved (Kirim ke User)</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            *Jika "Approved", user akan melihat tombol "Terima Penawaran" di dashboard mereka.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-500/30">
                        Simpan Penawaran
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
