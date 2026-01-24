<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Logistics & Manifest</h1>
            <p class="text-gray-500">Kelola pengiriman masal dan serah terima kurir.</p>
        </div>
        @if($viewMode === 'list')
            <button wire:click="toggleMode('create')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold text-sm shadow-lg">
                + Buat Manifest Baru
            </button>
        @else
            <button wire:click="toggleMode('list')" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                &larr; Kembali
            </button>
        @endif
    </div>

    @if($viewMode === 'list')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                    <tr>
                        <th class="px-6 py-4">No. Manifest</th>
                        <th class="px-6 py-4">Kurir</th>
                        <th class="px-6 py-4">Total Paket</th>
                        <th class="px-6 py-4">Dibuat Oleh</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($manifests as $manifest)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $manifest->manifest_number }}</td>
                            <td class="px-6 py-4 uppercase font-bold text-indigo-600">{{ $manifest->courier_name }}</td>
                            <td class="px-6 py-4">{{ $manifest->orders_count }} Paket</td>
                            <td class="px-6 py-4">{{ $manifest->creator->name }}</td>
                            <td class="px-6 py-4">{{ $manifest->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="printManifest({{ $manifest->id }})" class="text-indigo-600 font-bold hover:underline">
                                    Cetak Surat Jalan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada manifest pengiriman.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-gray-100">{{ $manifests->links() }}</div>
        </div>

    @else
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter Kurir</label>
                    <select wire:model.live="courierFilter" class="w-full rounded-lg border-gray-300">
                        <option value="jne">JNE</option>
                        <option value="jnt">J&T Express</option>
                        <option value="sicepat">SiCepat</option>
                        <option value="gosend">GoSend / Grab</option>
                    </select>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-sm text-gray-500">Terpilih: <span class="font-bold text-indigo-600">{{ count($selectedOrders) }}</span> pesanan</p>
                </div>
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                        <tr>
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-4 py-3">No. Pesanan</th>
                            <th class="px-4 py-3">Penerima</th>
                            <th class="px-4 py-3">Alamat Tujuan</th>
                            <th class="px-4 py-3">Berat (Est)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-4 py-3 font-mono font-medium">{{ $order->order_number }}</td>
                                <td class="px-4 py-3">{{ $order->guest_name }}</td>
                                <td class="px-4 py-3 truncate max-w-xs">{{ $order->shipping_city }}</td>
                                <td class="px-4 py-3">1 kg</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Tidak ada pesanan "Processing" untuk kurir ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button wire:click="createManifest" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg">
                    Generate Manifest & Update Status
                </button>
            </div>
        </div>
    @endif
</div>