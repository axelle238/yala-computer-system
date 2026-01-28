<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <div class="flex items-center gap-4">
                <h1 class="text-4xl font-black text-slate-900 dark:text-white font-tech uppercase tracking-tighter">Order #{{ $order->order_number }}</h1>
                <span class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-widest border
                    {{ match($order->status) {
                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                    } }}">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-slate-500 mt-2 flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                {{ $order->created_at->format('d M Y, H:i') }}
                <span class="text-slate-300">•</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $order->guest_name }}</span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.pesanan.indeks') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-sm transition-all shadow-sm">
                Kembali
            </a>
            <button wire:click="printLabel" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-all shadow-lg shadow-slate-900/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Label
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Workflow Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
                <h3 class="font-black text-lg text-slate-900 dark:text-white mb-6 flex items-center gap-3 uppercase tracking-tight">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    Tindakan & Proses
                </h3>
                <div class="flex flex-wrap gap-4">
                    @if($order->status == 'pending')
                        <button wire:click="updateStatus('processing')" class="px-6 py-3 bg-white text-slate-900 border-2 border-slate-900 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Proses Pesanan
                        </button>
                        <button wire:click="updateStatus('cancelled')" wire:confirm="Batalkan pesanan ini?" class="px-6 py-3 bg-white text-rose-600 border-2 border-rose-200 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-rose-50 hover:border-rose-300 transition-all active:scale-95">
                            Batalkan
                        </button>
                    @elseif($order->status == 'processing')
                        <div class="w-full flex flex-col md:flex-row gap-4 items-end">
                            <div class="flex-1 w-full">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Nomor Resi Pengiriman</label>
                                <input type="text" wire:model="tracking_number" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl text-sm font-mono font-bold focus:border-indigo-500 focus:ring-0 transition-colors" placeholder="Masukkan Nomor Resi...">
                            </div>
                            <button wire:click="markAsShipped" class="w-full md:w-auto px-8 py-3.5 bg-white text-slate-900 border-2 border-slate-900 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                Kirim Barang
                            </button>
                        </div>
                    @elseif($order->status == 'shipped')
                        <button wire:click="updateStatus('completed')" wire:confirm="Selesaikan pesanan?" class="px-8 py-3 bg-white text-emerald-700 border-2 border-emerald-500 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-md active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Tandai Selesai
                        </button>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-black text-lg text-slate-900 dark:text-white uppercase tracking-tight">Rincian Barang</h3>
                    <span class="text-xs font-bold text-slate-400">{{ $order->item->count() }} Items</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs text-slate-500 uppercase font-black tracking-wider">
                            <tr>
                                <th class="px-6 py-4 pl-8">Produk</th>
                                <th class="px-6 py-4 text-right">Harga</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-right pr-8">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($order->item as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 pl-8">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $item->product->name }}</div>
                                    <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $item->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-slate-600 dark:text-slate-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center font-bold bg-slate-50/50">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right pr-8 font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">Subtotal</td>
                                <td class="px-6 py-3 text-right font-mono font-bold text-slate-700 dark:text-slate-300 pr-8">Rp {{ number_format($order->item->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">Ongkos Kirim</td>
                                <td class="px-6 py-2 text-right font-mono font-bold text-slate-700 dark:text-slate-300 pr-8">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">Diskon</td>
                                <td class="px-6 py-2 text-right font-mono font-bold text-emerald-600 pr-8">- Rp {{ number_format($order->discount_amount + $order->voucher_discount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-right font-black text-slate-900 dark:text-white uppercase tracking-widest text-sm">Grand Total</td>
                                <td class="px-6 py-6 text-right font-black font-mono text-2xl text-blue-600 dark:text-blue-400 pr-8">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Fraud Detection AI -->
            <div class="bg-slate-900 rounded-2xl shadow-lg p-6 text-white border border-slate-700 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
                
                <h3 class="font-black text-sm mb-4 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    Analisis Keamanan AI
                </h3>

                <div class="space-y-4 relative z-10">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-400 font-bold uppercase">Tingkat Risiko</span>
                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase 
                            {{ $fraudCheck['status'] == 'Aman' ? 'bg-emerald-500 text-white' : ($fraudCheck['status'] == 'Risiko Tinggi' ? 'bg-rose-500 text-white' : 'bg-amber-500 text-black') }}">
                            {{ $fraudCheck['status'] }}
                        </span>
                    </div>
                    
                    <div class="w-full bg-slate-800 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $fraudCheck['skor'] > 50 ? 'bg-rose-500' : ($fraudCheck['skor'] > 20 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $fraudCheck['skor'] }}%"></div>
                    </div>

                    @if(!empty($fraudCheck['indikator']))
                        <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                            <p class="text-[10px] text-slate-400 mb-2 font-bold uppercase">Indikator Terdeteksi:</p>
                            <ul class="list-disc list-inside text-xs text-slate-300 space-y-1">
                                @foreach($fraudCheck['indikator'] as $i)
                                    <li>{{ $i }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-xs text-emerald-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Tidak ada indikasi mencurigakan.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-black text-sm text-slate-900 dark:text-white mb-6 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 pb-4">
                    Informasi Pelanggan
                </h3>
                <div class="space-y-5">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider mb-1">Nama Penerima</p>
                        <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $order->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider mb-1">Kontak</p>
                        <div class="flex items-center gap-2">
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-mono font-bold">{{ $order->guest_whatsapp }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $order->pengguna->email ?? 'Guest Checkout' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider mb-1">Alamat Pengiriman</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-900 p-3 rounded-lg border border-slate-100 dark:border-slate-700">
                            {{ $order->shipping_address }}<br>
                            <span class="font-bold block mt-1 text-slate-800 dark:text-white">{{ $order->shipping_city }}</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider mb-1">Ekspedisi</p>
                        <p class="font-mono font-bold bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-lg inline-block text-xs uppercase border border-slate-200 dark:border-slate-600">
                            {{ strtoupper($order->shipping_courier) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="font-black text-sm text-slate-900 dark:text-white mb-6 uppercase tracking-widest border-b border-slate-100 dark:border-slate-700 pb-4">
                    Status Pembayaran
                </h3>
                
                <div class="mb-6">
                    <div class="flex justify-between items-center p-3 rounded-xl border {{ $order->payment_status == 'paid' ? 'bg-emerald-50 border-emerald-200' : ($order->payment_status == 'pending_approval' ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200') }}">
                        <span class="text-xs font-bold uppercase text-slate-500">Status</span>
                        <span class="font-black text-xs uppercase {{ $order->payment_status == 'paid' ? 'text-emerald-700' : ($order->payment_status == 'pending_approval' ? 'text-amber-700' : 'text-slate-700') }}">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                </div>

                @if($order->payment_proof_path)
                    <div class="mb-6">
                        <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider mb-2 block">Bukti Transfer</span>
                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="block group relative overflow-hidden rounded-xl border-2 border-slate-200 hover:border-slate-400 transition-colors">
                            <img src="{{ asset('storage/' . $order->payment_proof_path) }}" class="w-full h-32 object-cover">
                            <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Lihat Full
                                </span>
                            </div>
                        </a>
                    </div>

                    @if($order->payment_status == 'pending_approval')
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="verifyPayment" wire:confirm="Verifikasi pembayaran dan kurangi stok?" class="py-3 bg-white text-emerald-700 border-2 border-emerald-500 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all active:scale-95">
                                Terima
                            </button>
                            <button wire:click="rejectPayment" wire:confirm="Tolak pembayaran?" class="py-3 bg-white text-rose-700 border-2 border-rose-500 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all active:scale-95">
                                Tolak
                            </button>
                        </div>
                    @endif
                @else
                    <div class="p-6 bg-slate-50 dark:bg-slate-900 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl text-center">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <p class="text-xs text-slate-400 font-bold uppercase">Belum ada bukti</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>