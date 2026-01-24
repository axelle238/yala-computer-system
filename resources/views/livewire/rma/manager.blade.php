<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Retur & Garansi (RMA)</h2>
        <button wire:click="$set('viewMode', 'create')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat RMA Baru
        </button>
    </div>

    <!-- LIST MODE -->
    @if($viewMode == 'list')
        <div class="bg-white shadow rounded-xl overflow-hidden border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">No. RMA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Asal Order</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($rmas as $rma)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-indigo-600">{{ $rma->rma_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $rma->customer_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $rma->order ? $rma->order->order_number : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $rma->status_color }}">
                                    {{ $rma->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="openProcess({{ $rma->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Proses</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data RMA.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-slate-200">{{ $rmas->links() }}</div>
        </div>
    @endif

    <!-- CREATE MODE -->
    @if($viewMode == 'create')
        <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Buat Permintaan Retur Baru</h3>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Cari Nomor Order / Invoice</label>
                <div class="flex gap-2">
                    <input type="text" wire:model="searchOrder" class="w-full rounded-lg border-slate-300" placeholder="Contoh: ORD-20240101-1234">
                    <button wire:click="searchOrderAction" class="bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-700">Cari</button>
                </div>
                @error('searchOrder') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            @if($foundOrder)
                <div class="bg-indigo-50 p-4 rounded-lg mb-6 border border-indigo-100">
                    <div class="flex justify-between mb-2">
                        <span class="font-bold text-indigo-900">Order Ditemukan!</span>
                        <span class="text-sm text-indigo-700">{{ $foundOrder->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm text-indigo-800 mb-4">
                        <div>Nama: {{ $guestName }}</div>
                        <div>Telp: {{ $guestPhone }}</div>
                    </div>

                    <div class="space-y-2">
                        @foreach($foundOrder->items as $item)
                            <div class="flex items-start gap-3 bg-white p-3 rounded border border-indigo-100">
                                <input type="checkbox" wire:model="selectedOrderItems.{{ $item->id }}.selected" class="mt-1 rounded text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                    <div class="text-xs text-slate-500">SKU: {{ $item->product->sku }} | Harga: Rp {{ number_format($item->price) }}</div>
                                    
                                    @if($selectedOrderItems[$item->id]['selected'] ?? false)
                                        <div class="mt-2 grid grid-cols-2 gap-2">
                                            <input type="text" wire:model="selectedOrderItems.{{ $item->id }}.reason" placeholder="Alasan Retur (Wajib)" class="text-xs border-slate-300 rounded">
                                            <input type="number" wire:model="selectedOrderItems.{{ $item->id }}.qty" max="{{ $item->quantity }}" min="1" placeholder="Qty" class="text-xs border-slate-300 rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Tambahan</label>
                    <textarea wire:model="createNotes" class="w-full rounded-lg border-slate-300" rows="3"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="$set('viewMode', 'list')" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                    <button wire:click="storeRma" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold">Simpan RMA</button>
                </div>
            @endif
        </div>
    @endif

    <!-- PROCESS MODE -->
    @if($viewMode == 'process' && $activeRma)
        <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <div class="flex justify-between items-start mb-6 border-b pb-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Proses RMA #{{ $activeRma->rma_number }}</h3>
                    <p class="text-sm text-slate-500">Pelanggan: {{ $activeRma->customer_name }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $activeRma->status_color }}">{{ $activeRma->status_label }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Info Barang -->
                <div>
                    <h4 class="font-bold text-slate-700 mb-3 uppercase text-xs">Barang Diretur</h4>
                    <div class="space-y-3">
                        @foreach($activeRma->items as $item)
                            <div class="bg-slate-50 p-3 rounded border border-slate-200">
                                <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                <div class="text-sm text-rose-600 mt-1">Masalah: "{{ $item->problem_description }}"</div>
                                <div class="text-xs text-slate-500 mt-1">Kondisi: {{ $item->condition ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Update -->
                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                    <h4 class="font-bold text-indigo-900 mb-3 uppercase text-xs">Update Status & Resolusi</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1">Status Pengerjaan</label>
                            <select wire:model="processStatus" class="w-full rounded-lg border-indigo-200 focus:ring-indigo-500">
                                <option value="requested">Permintaan Baru</option>
                                <option value="approved">Disetujui (Kirim ke Toko)</option>
                                <option value="received">Diterima Toko (Cek Fisik)</option>
                                <option value="vendor_process">Klaim ke Distributor</option>
                                <option value="resolved">Selesai</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1">Jenis Resolusi (Jika Selesai)</label>
                            <select wire:model="processResolution" class="w-full rounded-lg border-indigo-200 focus:ring-indigo-500">
                                <option value="">-- Belum Ditentukan --</option>
                                <option value="repair">Servis / Perbaikan</option>
                                <option value="replacement">Tukar Baru (Swap)</option>
                                <option value="refund">Refund Dana</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-indigo-800 mb-1">Catatan Admin</label>
                            <textarea wire:model="processNote" class="w-full rounded-lg border-indigo-200" rows="3" placeholder="Contoh: Barang sudah dikirim ke Jakarta..."></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button wire:click="$set('viewMode', 'list')" class="px-4 py-2 text-indigo-600 hover:bg-indigo-100 rounded-lg">Kembali</button>
                            <button wire:click="updateStatus" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold">Simpan Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
