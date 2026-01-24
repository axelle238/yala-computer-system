<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Workbench Teknisi</h1>
            <p class="text-gray-500">Tiket #{{ $ticket->ticket_number }} - {{ $ticket->device_name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $ticket->status_color }}">
                {{ $ticket->status_label }}
            </span>
            <a href="{{ route('services.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Details & Status --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Problem Description --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Detail Masalah</h3>
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 whitespace-pre-line">
                    {{ $ticket->problem_description }}
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500">Pelanggan</span>
                        <span class="font-medium">{{ $ticket->customer_name }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Kontak</span>
                        <span class="font-medium">{{ $ticket->customer_phone }}</span>
                    </div>
                </div>
            </div>

            {{-- Sparepart Management --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center justify-between">
                    <span>Penggunaan Sparepart & Jasa</span>
                    <span class="text-sm font-normal text-gray-500">Otomatis potong stok</span>
                </h3>

                {{-- Add Part Form --}}
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    @if(!$selectedProduct)
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live="partSearch" 
                                placeholder="Cari produk (Ketik nama/SKU)..." 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                            @if(!empty($searchResults))
                                <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                    @foreach($searchResults as $product)
                                        <button 
                                            wire:click="selectProduct({{ $product->id }})"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-50 border-b border-gray-100 last:border-0"
                                        >
                                            <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 flex justify-between">
                                                <span>SKU: {{ $product->sku }}</span>
                                                <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    Stok: {{ $product->stock }}
                                                </span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Produk Dipilih</label>
                                <div class="flex items-center justify-between p-2 bg-white border border-gray-200 rounded-lg">
                                    <span class="font-medium">{{ $selectedProduct->name }}</span>
                                    <button wire:click="$set('selectedProduct', null)" class="text-red-500 hover:text-red-700 text-sm">Batal</button>
                                </div>
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                                <input type="number" wire:model="quantity" min="1" class="w-full rounded-lg border-gray-300">
                            </div>
                            <button 
                                wire:click="addPart"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                            >
                                Tambah
                            </button>
                        </div>
                        @error('quantity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    @endif
                </div>

                {{-- Parts List --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 border-b">
                            <tr>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4 text-center">Qty</th>
                                <th class="py-3 px-4 text-right">Harga</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($ticket->items as $item)
                                <tr>
                                    <td class="py-3 px-4 font-medium">{{ $item->item_name }}</td>
                                    <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <button 
                                            wire:click="removePart({{ $item->id }})"
                                            wire:confirm="Yakin ingin menghapus item ini? Stok akan dikembalikan."
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada sparepart atau jasa yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($ticket->items->count() > 0)
                            <tfoot class="bg-gray-50 font-bold text-gray-800">
                                <tr>
                                    <td colspan="3" class="py-3 px-4 text-right">Total Estimasi</td>
                                    <td class="py-3 px-4 text-right">Rp {{ number_format($ticket->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>

        {{-- Right Column: Actions --}}
        <div class="space-y-6">
            
            {{-- Workflow Actions --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Update Status</h3>
                
                <div class="space-y-3">
                    <textarea 
                        wire:model="noteInput" 
                        rows="3" 
                        placeholder="Catatan teknisi (Internal)..."
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                    ></textarea>

                    <div class="grid grid-cols-1 gap-2">
                        @if($ticket->status === 'pending')
                            <button wire:click="updateStatus('diagnosing')" class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Mulai Pengecekan</button>
                        @elseif($ticket->status === 'diagnosing')
                            <button wire:click="updateStatus('waiting_part')" class="w-full py-2 px-4 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Butuh Sparepart</button>
                            <button wire:click="updateStatus('repairing')" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Mulai Perbaikan</button>
                        @elseif($ticket->status === 'waiting_part')
                            <button wire:click="updateStatus('repairing')" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Sparepart Ready, Lanjut Repair</button>
                        @elseif($ticket->status === 'repairing')
                            <button wire:click="updateStatus('ready')" class="w-full py-2 px-4 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Selesai (Siap Diambil)</button>
                        @elseif($ticket->status === 'ready')
                            <button wire:click="updateStatus('picked_up')" class="w-full py-2 px-4 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Telah Diambil Customer</button>
                        @endif
                        
                        @if(!in_array($ticket->status, ['picked_up', 'cancelled']))
                             <button wire:click="updateStatus('cancelled')" wire:confirm="Yakin batalkan tiket ini?" class="w-full py-2 px-4 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 mt-2">Batalkan Tiket</button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- History Log --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Riwayat Pengerjaan</h3>
                <div class="relative border-l border-gray-200 ml-3 space-y-6">
                    @foreach($ticket->histories as $history)
                        <div class="mb-4 ml-6 relative">
                            <span class="absolute -left-[31px] flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 ring-4 ring-white">
                                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                            </span>
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-semibold text-gray-900">{{ ucfirst($history->status) }}</h4>
                                <span class="text-xs text-gray-500">{{ $history->created_at->format('d/m H:i') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $history->user->name ?? 'System' }}</p>
                            @if($history->notes)
                                <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                    {{ $history->notes }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
