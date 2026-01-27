<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 flex items-center justify-center" wire:poll.10s>
    <div class="w-full max-w-lg px-4">
        
        <div class="text-center mb-8 animate-fade-in-up">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Lacak <span class="text-cyan-500">Pesanan</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Masukkan nomor order untuk melihat status pengiriman.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-100">
            <form wire:submit.prevent="track" class="flex gap-2 mb-8">
                <input wire:model="orderNumber" type="text" class="flex-1 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-mono font-bold uppercase text-slate-800 dark:text-white focus:ring-cyan-500" placeholder="ORD-XXXX-XXXX">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white p-3 rounded-xl transition-all shadow-lg shadow-cyan-600/30">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            @if($order)
                <div class="border-t border-slate-100 dark:border-slate-700 pt-6 animate-fade-in">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Saat Ini</div>
                            <div class="text-xl font-black text-slate-800 dark:text-white mt-1">
                                {{ match($order->status) {
                                    'pending' => 'Menunggu Konfirmasi',
                                    'processing' => 'Sedang Dikemas',
                                    'shipped' => 'Dalam Pengiriman',
                                    'completed' => 'Pesanan Selesai',
                                    'cancelled' => 'Dibatalkan',
                                    default => 'Status Tidak Diketahui'
                                } }}
                            </div>
                        </div>
                        @if($order->shipping_tracking_number)
                            <div class="text-right">
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. Resi</div>
                                <div class="text-lg font-mono font-bold text-cyan-500 mt-1 select-all">{{ $order->shipping_tracking_number }}</div>
                                <div class="text-xs font-bold text-slate-500 uppercase">{{ $order->shipping_courier }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Timeline -->
                    <div class="relative pl-4 border-l-2 border-slate-100 dark:border-slate-700 space-y-8">
                        <!-- Created -->
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800"></div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white">Pesanan Dibuat</div>
                            <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>

                        <!-- Processing -->
                        <div class="relative {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? '' : 'opacity-30' }}">
                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'bg-emerald-500' : 'bg-slate-300' }} border-2 border-white dark:border-slate-800"></div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white">Pembayaran Diterima & Dipacking</div>
                        </div>

                        <!-- Shipped -->
                        <div class="relative {{ in_array($order->status, ['shipped', 'completed']) ? '' : 'opacity-30' }}">
                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-emerald-500' : 'bg-slate-300' }} border-2 border-white dark:border-slate-800"></div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white">Diserahkan ke Kurir</div>
                            @if($order->status == 'shipped') <div class="text-xs text-emerald-500 font-bold animate-pulse">Sedang Berjalan...</div> @endif
                        </div>

                        <!-- Delivered -->
                        <div class="relative {{ $order->status == 'completed' ? '' : 'opacity-30' }}">
                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full {{ $order->status == 'completed' ? 'bg-emerald-500' : 'bg-slate-300' }} border-2 border-white dark:border-slate-800"></div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white">Paket Diterima</div>
                        </div>
                    </div>
                </div>
            @elseif($searchPerformed)
                <div class="text-center py-8 text-rose-500 bg-rose-50 rounded-xl border border-rose-100">
                    Order ID tidak ditemukan.
                </div>
            @endif
        </div>
    </div>
</div>
