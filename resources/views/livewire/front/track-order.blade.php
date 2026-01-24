<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-16 font-sans relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        
        <div class="max-w-2xl mx-auto text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Lacak <span class="text-blue-600">Pesanan</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Masukkan nomor pesanan dan kontak Anda untuk melihat status terkini.</p>
        </div>

        <div class="max-w-xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-2xl border border-slate-200 dark:border-slate-700 mb-12 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <form wire:submit.prevent="track" class="space-y-4 relative z-10">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nomor Pesanan</label>
                    <input wire:model="order_number" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-mono font-bold focus:ring-blue-500" placeholder="ORD-2024...">
                    @error('order_number') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">No. WhatsApp / Email</label>
                    <input wire:model="contact" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-medium focus:ring-blue-500" placeholder="Email atau No. HP">
                    @error('contact') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                    <svg wire:loading.remove class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <svg wire:loading class="w-5 h-5 animate-spin" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Lacak Status
                </button>
            </form>
        </div>

        @if($order)
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                <!-- Status Bar -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl mb-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-2xl font-black font-mono text-slate-900 dark:text-white">{{ $order->order_number }}</h2>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <p class="text-slate-500 text-sm">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold uppercase text-slate-500 mb-1">Total Pesanan</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Visual Stepper -->
                    <div class="relative">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 dark:bg-slate-700 -translate-y-1/2 z-0 hidden md:block"></div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                            @foreach($timeline as $step)
                                <div class="flex md:flex-col items-center gap-4 md:gap-2">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 transition-all {{ $step['done'] ? 'bg-blue-600 border-blue-200 text-white' : 'bg-slate-200 border-slate-50 text-slate-400 dark:bg-slate-700 dark:border-slate-600' }}">
                                        @if($step['done'])
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <div class="w-3 h-3 rounded-full bg-current opacity-50"></div>
                                        @endif
                                    </div>
                                    <div class="text-left md:text-center">
                                        <p class="font-bold text-sm {{ $step['done'] ? 'text-blue-600 dark:text-blue-400' : 'text-slate-500' }}">{{ $step['label'] }}</p>
                                        @if($step['time'])
                                            <p class="text-xs text-slate-400">{{ $step['time']->format('d M H:i') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6">Rincian Barang</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                                <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center p-2">
                                    @if($item->product->image_path)
                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" class="max-w-full max-h-full object-contain">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-slate-500 mb-1">Qty: {{ $item->quantity }}</p>
                                    <p class="font-mono text-sm font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                        <div>
                            <h4 class="font-bold text-sm text-slate-500 uppercase mb-2">Alamat Pengiriman</h4>
                            <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
                                <strong class="block text-slate-900 dark:text-white">{{ $order->guest_name }}</strong>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}<br>
                                Telp: {{ $order->guest_whatsapp }}
                            </p>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-slate-500 uppercase mb-2">Ekspedisi</h4>
                            <p class="text-slate-700 dark:text-slate-300 text-sm">
                                <span class="uppercase font-bold">{{ $order->shipping_courier }}</span>
                                @if($order->shipping_manifest_id)
                                    <br>Resi: <span class="font-mono bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs">Menunggu Update Kurir</span>
                                @else
                                    <br><span class="text-slate-400 italic">Resi belum tersedia</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>