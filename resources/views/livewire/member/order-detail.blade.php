<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in-up">
            <a href="{{ route('member.orders') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 mb-2 inline-block">&larr; Kembali ke Riwayat Pesanan</a>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                        Pesanan <span class="text-blue-600">#{{ $pesanan->order_number }}</span>
                    </h1>
                    <p class="text-slate-500 text-sm">Dibuat pada {{ $pesanan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex gap-2">
                    @if($pesanan->status == 'pending' && $pesanan->payment_status == 'unpaid')
                        <button wire:click="batalkanPesanan" class="px-4 py-2 border border-rose-200 text-rose-600 rounded-lg text-sm font-bold hover:bg-rose-50 transition-colors">
                            Batalkan
                        </button>
                        <button wire:click="bayarSekarang" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-lg shadow-blue-500/30 transition-all animate-pulse">
                            Bayar Sekarang
                        </button>
                    @endif
                    <button wire:click="cetakFaktur" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg text-sm font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Faktur
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in-up delay-100">
            <!-- Left Column: Tracking & Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Timeline -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 uppercase text-xs tracking-wider">Status Pesanan</h3>
                    <div class="relative flex justify-between">
                        <!-- Progress Bar Background -->
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 dark:bg-slate-700 -translate-y-1/2 z-0"></div>
                        
                        @foreach($linimasa as $langkah)
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 {{ $langkah['selesai'] ? 'bg-emerald-500 border-emerald-100 dark:border-emerald-900' : 'bg-slate-200 dark:bg-slate-700 border-white dark:border-slate-800' }}">
                                    @if($langkah['selesai'])
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    @endif
                                </div>
                                <p class="mt-2 text-[10px] font-bold uppercase tracking-wider {{ $langkah['selesai'] ? 'text-emerald-600' : 'text-slate-400' }}">{{ $langkah['label'] }}</p>
                                @if($langkah['waktu'])
                                    <p class="text-[10px] text-slate-400">{{ $langkah['waktu']->format('d/m H:i') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Items -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <h3 class="font-bold text-slate-800 dark:text-white uppercase text-xs tracking-wider">Rincian Barang</h3>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($pesanan->items as $item)
                            <div class="p-6 flex gap-4 items-center">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    @if($pesanan->status == 'completed')
                                        <a href="{{ route('toko.produk.detail', $item->product_id) }}" class="text-[10px] text-blue-600 hover:underline mt-1 block">Beri Ulasan</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Info -->
            <div class="space-y-6">
                <!-- Shipping Info -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4 uppercase text-xs tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Info Pengiriman</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold">Penerima</p>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $pesanan->guest_name }}</p>
                            <p class="text-slate-500">{{ $pesanan->guest_whatsapp }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold">Alamat</p>
                            <p class="text-slate-600 dark:text-slate-400">{{ $pesanan->shipping_address }}, {{ $pesanan->shipping_city }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold">Kurir</p>
                            <p class="font-mono bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded inline-block text-xs uppercase">{{ $pesanan->shipping_courier }}</p>
                            @if($pesanan->shipping_tracking_number)
                                <p class="text-xs mt-1">Resi: <span class="font-mono font-bold text-blue-600 select-all">{{ $pesanan->shipping_tracking_number }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4 uppercase text-xs tracking-wider border-b border-slate-100 dark:border-slate-700 pb-2">Rincian Pembayaran</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal Barang</span>
                            <span class="font-mono text-slate-700 dark:text-slate-300">Rp {{ number_format($pesanan->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Ongkos Kirim</span>
                            <span class="font-mono text-slate-700 dark:text-slate-300">Rp {{ number_format($pesanan->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($pesanan->discount_amount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Diskon</span>
                                <span class="font-mono">- Rp {{ number_format($pesanan->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($pesanan->voucher_discount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Voucher ({{ $pesanan->voucher_code }})</span>
                                <span class="font-mono">- Rp {{ number_format($pesanan->voucher_discount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-700">
                            <span class="font-bold text-slate-800 dark:text-white uppercase">Total Bayar</span>
                            <span class="text-xl font-black font-mono text-blue-600">Rp {{ number_format($pesanan->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>