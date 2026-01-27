<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase">Order #{{ $order->order_number }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ match($order->status) {
                        'pending' => 'bg-slate-100 text-slate-600',
                        'processing' => 'bg-blue-100 text-blue-600',
                        'shipped' => 'bg-indigo-100 text-indigo-600',
                        'completed' => 'bg-emerald-100 text-emerald-600',
                        'cancelled' => 'bg-rose-100 text-rose-600',
                        default => 'bg-slate-100'
                    } }}">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ $order->created_at->format('d M Y, H:i') }}
                <span>•</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $order->guest_name }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('orders.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold transition">
                Kembali
            </a>
            <button wire:click="printLabel" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-white rounded-xl font-bold flex items-center gap-2 hover:bg-slate-200 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Label Pengiriman
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Workflow Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Proses Order
                </h3>
                <div class="flex flex-wrap gap-3">
                    @if($order->status == 'pending')
                        <button wire:click="updateStatus('processing')" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition shadow-lg shadow-blue-500/30">
                            Proses Pesanan
                        </button>
                        <button wire:click="updateStatus('cancelled')" wire:confirm="Batalkan pesanan ini?" class="px-6 py-2 bg-rose-100 text-rose-600 hover:bg-rose-200 rounded-xl font-bold transition">
                            Batalkan
                        </button>
                    @elseif($order->status == 'processing')
                        <div class="flex-1 flex gap-2 items-end">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-slate-500 uppercase">Nomor Resi (Tracking ID)</label>
                                <input type="text" wire:model="tracking_number" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-mono font-bold" placeholder="Input Resi...">
                            </div>
                            <button wire:click="markAsShipped" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition shadow-lg shadow-indigo-500/30">
                                Kirim Barang
                            </button>
                        </div>
                    @elseif($order->status == 'shipped')
                        <button wire:click="updateStatus('completed')" wire:confirm="Selesaikan pesanan?" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold transition shadow-lg shadow-emerald-500/30">
                            Tandai Selesai
                        </button>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white">Rincian Barang</h3>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs text-slate-500 uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3 text-right">Harga</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($order->item as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                {{ $item->product->name }}
                                <div class="text-xs font-normal text-slate-500">{{ $item->product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-500 uppercase text-xs">Subtotal</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-800 dark:text-white">Rp {{ number_format($order->item->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-6 py-2 text-right font-bold text-slate-500 uppercase text-xs">Ongkos Kirim</td>
                            <td class="px-6 py-2 text-right font-mono font-bold text-slate-800 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="px-6 py-2 text-right font-bold text-slate-500 uppercase text-xs">Diskon</td>
                            <td class="px-6 py-2 text-right font-mono font-bold text-emerald-600">- Rp {{ number_format($order->discount_amount + $order->voucher_discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="text-lg">
                            <td colspan="3" class="px-6 py-4 text-right font-black text-slate-800 dark:text-white uppercase">Grand Total</td>
                            <td class="px-6 py-4 text-right font-black font-mono text-blue-600 dark:text-blue-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Pelanggan
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Nama</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ $order->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Kontak</p>
                        <p class="font-mono text-slate-600 dark:text-slate-300">{{ $order->guest_whatsapp }}</p>
                        <p class="text-slate-600 dark:text-slate-300">{{ $order->pengguna->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Alamat Kirim</p>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold">Kurir</p>
                        <p class="font-mono font-bold bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded inline-block">{{ strtoupper($order->shipping_courier) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    Pembayaran
                </h3>
                
                <div class="mb-4">
                    <span class="block text-xs text-slate-500 uppercase font-bold mb-1">Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5
                        {{ $order->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 
                          ($order->payment_status == 'pending_approval' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400') }}">
                        <span class="w-2 h-2 rounded-full {{ $order->payment_status == 'paid' ? 'bg-emerald-500' : ($order->payment_status == 'pending_approval' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                        {{ $order->payment_status }}
                    </span>
                </div>

                @if($order->payment_proof_path)
                    <div class="mb-6">
                        <span class="block text-xs text-slate-500 uppercase font-bold mb-2">Bukti Transfer</span>
                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl">
                            <img src="{{ asset('storage/' . $order->payment_proof_path) }}" class="w-full object-cover transition-transform group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold">Lihat Gambar</span>
                            </div>
                        </a>
                    </div>

                    @if($order->payment_status == 'pending_approval')
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="verifyPayment" wire:confirm="Verifikasi pembayaran dan kurangi stok?" class="py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-sm shadow-lg shadow-emerald-500/30 transition">
                                Terima
                            </button>
                            <button wire:click="rejectPayment" wire:confirm="Tolak pembayaran?" class="py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-sm transition">
                                Tolak
                            </button>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl text-center">
                        <p class="text-xs text-slate-400 italic">Belum ada bukti transfer.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
