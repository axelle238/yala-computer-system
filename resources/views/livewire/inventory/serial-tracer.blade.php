<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <div class="text-center">
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
            Asset <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Traceability</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Lacak riwayat lengkap unit barang dari pembelian hingga purna jual.</p>
    </div>

    <!-- Search Box -->
    <div class="max-w-xl mx-auto">
        <form wire:submit.prevent="searchSerial" class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
            <div class="relative flex">
                <input wire:model="search" type="text" class="block w-full p-4 pl-12 text-sm text-slate-900 border border-slate-300 rounded-l-xl bg-white focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-800 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white dark:focus:ring-blue-500 font-mono uppercase tracking-widest" placeholder="SERIAL NUMBER..." required>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-r-xl text-sm px-6 py-4 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 uppercase tracking-wider">Lacak</button>
            </div>
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>
    </div>

    @if($serial)
        <!-- Result Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700 animate-slide-in-up">
            
            <!-- Header Info -->
            <div class="bg-slate-50 dark:bg-slate-900/50 p-8 text-center border-b border-slate-200 dark:border-slate-700">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $serial->status == 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }} mb-4">
                    Status: {{ $serial->status_label }}
                </span>
                <h1 class="text-4xl font-black font-mono text-slate-800 dark:text-white tracking-widest mb-2">{{ $serial->serial_number }}</h1>
                <p class="text-lg text-slate-600 dark:text-slate-300 font-bold">{{ $serial->product->name }}</p>
                <p class="text-sm text-slate-400 font-mono mt-1">SKU: {{ $serial->product->sku }}</p>
            </div>

            <div class="p-8">
                <!-- Timeline -->
                <div class="relative border-l-4 border-slate-100 dark:border-slate-700 space-y-12 ml-4 md:ml-12 pl-8 md:pl-12 py-4">
                    
                    <!-- 1. Inbound (Purchase) -->
                    @if($serial->purchaseOrder)
                        <div class="relative group">
                            <div class="absolute -left-[42px] md:-left-[58px] top-0 w-6 h-6 rounded-full bg-blue-500 border-4 border-white dark:border-slate-800 shadow-lg group-hover:scale-125 transition-transform"></div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-12">
                                <div class="w-32 flex-shrink-0 text-xs font-bold text-slate-400 uppercase tracking-wider pt-1">
                                    {{ $serial->created_at->format('d M Y') }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800 dark:text-white">Barang Masuk (Inbound)</h4>
                                    <p class="text-slate-600 dark:text-slate-300 text-sm mt-1">
                                        Diterima dari Supplier <b>{{ $serial->purchaseOrder->supplier->name }}</b> via PO #{{ $serial->purchaseOrder->po_number }}.
                                    </p>
                                    <p class="text-xs text-slate-400 mt-2 font-mono">HPP: Rp {{ number_format($serial->buy_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2. Sales (Outbound) -->
                    @if($serial->order)
                        <div class="relative group">
                            <div class="absolute -left-[42px] md:-left-[58px] top-0 w-6 h-6 rounded-full bg-emerald-500 border-4 border-white dark:border-slate-800 shadow-lg group-hover:scale-125 transition-transform"></div>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-12">
                                <div class="w-32 flex-shrink-0 text-xs font-bold text-slate-400 uppercase tracking-wider pt-1">
                                    {{ $serial->order->created_at->format('d M Y') }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800 dark:text-white">Barang Terjual (Outbound)</h4>
                                    <p class="text-slate-600 dark:text-slate-300 text-sm mt-1">
                                        Dibeli oleh <b>{{ $serial->order->guest_name ?? $serial->order->user->name }}</b> via Order #{{ $serial->order->order_number }}.
                                    </p>
                                    <p class="text-xs text-slate-400 mt-2">Masa Garansi Aktif s/d {{ $serial->order->created_at->addMonths($serial->product->warranty_duration ?? 12)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 3. Current State / Warranty Status -->
                    <div class="relative group">
                        <div class="absolute -left-[42px] md:-left-[58px] top-0 w-6 h-6 rounded-full bg-slate-300 dark:bg-slate-600 border-4 border-white dark:border-slate-800 shadow-lg"></div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-12">
                            <div class="w-32 flex-shrink-0 text-xs font-bold text-slate-400 uppercase tracking-wider pt-1">
                                Saat Ini
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-slate-800 dark:text-white">Status Akhir</h4>
                                <p class="text-slate-600 dark:text-slate-300 text-sm mt-1">
                                    Posisi unit: 
                                    @if($serial->status == 'available') 
                                        <span class="font-bold text-blue-600">Gudang (Belum Terjual)</span>
                                    @elseif($serial->status == 'sold')
                                        <span class="font-bold text-emerald-600">Di Tangan Pelanggan</span>
                                    @elseif($serial->status == 'rma')
                                        <span class="font-bold text-rose-600">Dalam Proses Servis/RMA</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
