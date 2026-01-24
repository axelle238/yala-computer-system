<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-16">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Track <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Your Order</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Pantau perjalanan paket belanja Anda tanpa perlu login.</p>
        </div>

        <!-- Form -->
        <div class="max-w-xl mx-auto bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 mb-12 relative overflow-hidden animate-fade-in-up delay-100">
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <form wire:submit.prevent="track" class="space-y-4 relative z-10">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nomor Pesanan (Order ID)</label>
                    <input wire:model="order_number" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 font-mono font-bold text-lg focus:ring-cyan-500 placeholder-slate-400" placeholder="ORD-2026...">
                    @error('order_number') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Email / No. WhatsApp</label>
                    <input wire:model="contact" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-lg focus:ring-cyan-500 placeholder-slate-400" placeholder="Masukkan data saat checkout">
                </div>
                <button type="submit" class="w-full py-4 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all flex items-center justify-center gap-2">
                    <span wire:loading.remove>Lacak Pesanan</span>
                    <span wire:loading><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                </button>
            </form>
        </div>

        <!-- Result -->
        @if($order)
            <div class="max-w-3xl mx-auto animate-fade-in-up">
                <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700">
                    <!-- Order Header -->
                    <div class="bg-slate-900 p-6 md:p-8 flex flex-col md:flex-row justify-between gap-4 border-b border-white/10">
                        <div>
                            <span class="inline-block px-3 py-1 bg-cyan-900/50 text-cyan-400 rounded-full text-xs font-bold uppercase tracking-wider mb-2 border border-cyan-500/20">Verified Order</span>
                            <h2 class="text-3xl font-black font-mono text-white">{{ $order->order_number }}</h2>
                            <p class="text-slate-400 text-sm mt-1">Dipesan: {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 uppercase font-bold mb-1">Total</p>
                            <p class="text-2xl font-black text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="p-8 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <div class="relative flex justify-between">
                            <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 dark:bg-slate-700 -translate-y-1/2 z-0"></div>
                            @foreach($timeline as $step)
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 {{ $step['done'] ? 'bg-cyan-500 border-cyan-100 dark:border-cyan-900 shadow-lg shadow-cyan-500/50' : 'bg-slate-200 dark:bg-slate-800 border-white dark:border-slate-700' }} transition-all">
                                        @if($step['done'])
                                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-[10px] md:text-xs font-bold uppercase tracking-wider {{ $step['done'] ? 'text-cyan-600 dark:text-cyan-400' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                                    @if($step['time'])
                                        <p class="text-[10px] text-slate-400 font-mono mt-1">{{ \Carbon\Carbon::parse($step['time'])->format('d/m') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items & Shipping -->
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-4 uppercase text-xs tracking-wider">Item Barang</h3>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                    <div class="flex gap-4 items-center">
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                                            @if($item->product->image_path)
                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $item->product->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-4 uppercase text-xs tracking-wider">Info Pengiriman</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase font-bold">Penerima</p>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $order->guest_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase font-bold">Kurir</p>
                                    <p class="font-mono">{{ strtoupper($order->shipping_courier) }}</p>
                                    @if($order->shipping_tracking_number)
                                        <div class="mt-1 p-2 bg-slate-100 dark:bg-slate-700 rounded-lg flex justify-between items-center">
                                            <span class="font-mono font-bold select-all text-slate-800 dark:text-white">{{ $order->shipping_tracking_number }}</span>
                                            <span class="text-[10px] text-emerald-500 font-bold uppercase">Resi</span>
                                        </div>
                                    @else
                                        <p class="text-xs text-amber-500 italic">Resi belum tersedia</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase font-bold">Alamat</p>
                                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
