<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Logistics <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Hub</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen pengiriman, resi, dan manifest kurir.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 pb-1">
        <button wire:click="$set('activeTab', 'ready_to_ship')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'ready_to_ship' ? 'text-blue-600 border-b-2 border-blue-500' : 'text-slate-500 hover:text-slate-800' }}">
            Siap Dikirim
        </button>
        <button wire:click="$set('activeTab', 'shipped')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'shipped' ? 'text-blue-600 border-b-2 border-blue-500' : 'text-slate-500 hover:text-slate-800' }}">
            Sudah Resi (Belum Pickup)
        </button>
        <button wire:click="$set('activeTab', 'manifests')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'manifests' ? 'text-blue-600 border-b-2 border-blue-500' : 'text-slate-500 hover:text-slate-800' }}">
            Manifest Keluar
        </button>
    </div>

    <!-- Content: Ready to Ship -->
    @if($activeTab === 'ready_to_ship')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Kurir</th>
                        <th class="px-6 py-4">Input Resi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $order->order_number }}
                                <span class="block text-[10px] text-slate-400">{{ $order->created_at->format('d M H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="font-bold">{{ $order->guest_name }}</span><br>
                                {{ $order->shipping_city }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-[10px] font-bold uppercase">{{ $order->shipping_courier }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" wire:model.defer="bulkTrackingNumber.{{ $order->id }}" class="w-full px-3 py-1.5 text-xs border rounded-lg font-mono uppercase" placeholder="Scan Resi...">
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="shipOrder({{ $order->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">Update Status</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Tidak ada pesanan perlu dikirim.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $orders->links() }}</div>
        </div>
    @endif

    <!-- Content: Shipped (Create Manifest) -->
    @if($activeTab === 'shipped')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Siap Manifest</h3>
                <button wire:click="$set('showManifestModal', true)" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 disabled:opacity-50" {{ empty($selectedOrders) ? 'disabled' : '' }}>
                    Buat Manifest ({{ count($selectedOrders) }})
                </button>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 w-10"><input type="checkbox" class="rounded"></th>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Kurir</th>
                        <th class="px-6 py-4">Resi</th>
                        <th class="px-6 py-4 text-right">Tgl Kirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}" class="rounded text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-6 py-4 uppercase font-bold text-xs">{{ $order->shipping_courier }}</td>
                            <td class="px-6 py-4 font-mono">{{ $order->shipment->tracking_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-xs">{{ $order->shipment->shipped_at->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Semua pesanan sudah dimanifestasikan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $orders->links() }}</div>
        </div>
    @endif

    <!-- Content: Manifests -->
    @if($activeTab === 'manifests')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No. Manifest</th>
                        <th class="px-6 py-4">Kurir</th>
                        <th class="px-6 py-4 text-center">Jumlah Paket</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($manifests as $m)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $m->manifest_number }}</td>
                            <td class="px-6 py-4 uppercase font-bold">{{ $m->courier_name }}</td>
                            <td class="px-6 py-4 text-center font-mono">{{ $m->shipments_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded text-[10px] uppercase font-bold text-slate-500">{{ str_replace('_', ' ', $m->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-xs font-bold text-indigo-600 hover:underline">Cetak Surat Jalan</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada manifest.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">{{ $manifests->links() }}</div>
        </div>
    @endif

    <!-- Manifest Modal -->
    @if($showManifestModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Buat Manifest Penjemputan</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kurir</label>
                        <select wire:model="manifestCourier" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm">
                            <option value="jne">JNE</option>
                            <option value="jnt">J&T</option>
                            <option value="sicepat">SiCepat</option>
                        </select>
                    </div>
                    <p class="text-xs text-slate-400">Anda akan membuat surat jalan untuk <strong>{{ count($selectedOrders) }}</strong> paket terpilih.</p>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('showManifestModal', false)" class="px-4 py-2 text-slate-500 font-bold text-sm">Batal</button>
                    <button wire:click="createManifest" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition">Buat</button>
                </div>
            </div>
        </div>
    @endif
</div>
