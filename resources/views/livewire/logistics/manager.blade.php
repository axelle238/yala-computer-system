<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Logistics <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Hub</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pusat pemrosesan pesanan, packing, dan pengiriman.</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
        @foreach(['pending' => 'Pesanan Baru', 'processing' => 'Sedang Dipacking', 'shipped' => 'Dalam Pengiriman', 'delivered' => 'Selesai'] as $key => $label)
            <button wire:click="$set('filterStatus', '{{ $key }}')" 
                    class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap {{ $filterStatus === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-indigo-50 dark:hover:bg-slate-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-96 pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 text-sm" placeholder="Cari No. Order / Nama Penerima...">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No. Order</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Kurir</th>
                        <th class="px-6 py-4 text-center">Item</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/20 px-2 py-1 rounded border border-indigo-100 dark:border-indigo-800">{{ $order->order_number }}</span>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('d/m H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $order->guest_name }}</div>
                                <div class="text-xs text-slate-500">{{ $order->shipping_city }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-medium uppercase">
                                {{ $order->shipping_courier }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-xs font-bold">{{ $order->items_count ?? $order->items->count() }} Pcs</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($order->status == 'pending')
                                    <span class="text-amber-500 font-bold text-xs uppercase">Menunggu</span>
                                @elseif($order->status == 'processing')
                                    <span class="text-blue-500 font-bold text-xs uppercase animate-pulse">Packing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="text-purple-500 font-bold text-xs uppercase">Dikirim</span>
                                    <div class="text-[10px] font-mono mt-1">{{ $order->tracking_number }}</div>
                                @else
                                    <span class="text-emerald-500 font-bold text-xs uppercase">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                @if($order->status == 'pending')
                                    <button wire:click="markAsProcessing({{ $order->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        Proses Packing
                                    </button>
                                @endif
                                
                                @if($order->status == 'processing')
                                    <button wire:click="openTrackingPanel({{ $order->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Input Resi
                                    </button>
                                @endif

                                <a href="{{ route('admin.pesanan.tampil', $order->id) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    Label
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada pesanan pada status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Tracking Action Panel -->
    @if($activeAction === 'tracking')
        <div class="fixed inset-x-0 bottom-0 z-50 p-4 pointer-events-none flex justify-center">
            <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-2xl border border-indigo-200 dark:border-slate-700 pointer-events-auto animate-slide-up">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Input Nomor Resi</h3>
                            <p class="text-xs text-slate-500">Order #{{ \App\Models\Order::find($selectedOrderId)->order_number }}</p>
                        </div>
                        <button wire:click="closeTrackingPanel" class="text-slate-400 hover:text-indigo-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <input wire:model="trackingNumber" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 mb-1 font-mono uppercase focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: JP1234567890">
                            @error('trackingNumber') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex justify-end gap-2 pt-2">
                            <button wire:click="closeTrackingPanel" class="px-4 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                            <button wire:click="saveTracking" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95">
                                Kirim & Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
