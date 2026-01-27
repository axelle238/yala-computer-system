<div class="h-[calc(100vh-100px)] flex flex-col space-y-6" wire:poll.10s>
    <!-- Header & Toolbar -->
    <div class="px-6 pt-6 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Pesanan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Pantau dan kelola seluruh transaksi penjualan.</p>
        </div>
        
        <!-- Search Bar -->
        <div class="relative w-full md:w-96 group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl opacity-20 group-hover:opacity-40 transition duration-500 blur"></div>
            <div class="relative flex items-center">
                <input wire:model.live.debounce.300ms="cari" type="text" 
                    class="w-full pl-11 pr-4 py-3 bg-white dark:bg-slate-800 border-0 rounded-xl shadow-sm ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition-all" 
                    placeholder="Cari No. Order, Nama Pelanggan...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Mini Dashboard (Statistik) -->
    <div class="px-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card 1: Pesanan Hari Ini -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pesanan Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['today_count']) }}</h3>
                <p class="text-[10px] text-indigo-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Transaksi Baru
                </p>
            </div>
        </div>

        <!-- Card 2: Pendapatan Hari Ini -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pendapatan Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($stats['today_revenue'] / 1000, 0, ',', '.') }}k</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas (Paid)
                </p>
            </div>
        </div>

        <!-- Card 3: Butuh Tindakan -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Perlu Diproses</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['needs_action']) }}</h3>
                <p class="text-[10px] text-amber-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending / Processing
                </p>
            </div>
        </div>

        <!-- Card 4: Dibatalkan -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-rose-300 dark:hover:border-rose-700 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-16 h-16 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dibatalkan (Bulan Ini)</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['cancelled_month']) }}</h3>
                <p class="text-[10px] text-rose-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal / Cancel
                </p>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-6 px-6">
        <div class="flex gap-6 h-full min-w-[1400px]">
            
            @php
                $statuses = [
                    'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-slate-50 dark:bg-slate-900', 'border' => 'border-slate-200 dark:border-slate-700', 'text' => 'text-slate-600'],
                    'processing' => ['label' => 'Diproses Penjual', 'color' => 'bg-indigo-50 dark:bg-indigo-900/10', 'border' => 'border-indigo-200 dark:border-indigo-800', 'text' => 'text-indigo-600'],
                    'shipped' => ['label' => 'Dalam Pengiriman', 'color' => 'bg-blue-50 dark:bg-blue-900/10', 'border' => 'border-blue-200 dark:border-blue-800', 'text' => 'text-blue-600'],
                    'completed' => ['label' => 'Selesai', 'color' => 'bg-emerald-50 dark:bg-emerald-900/10', 'border' => 'border-emerald-200 dark:border-emerald-800', 'text' => 'text-emerald-600'],
                    'cancelled' => ['label' => 'Dibatalkan', 'color' => 'bg-rose-50 dark:bg-rose-900/10', 'border' => 'border-rose-200 dark:border-rose-800', 'text' => 'text-rose-600'],
                ];
            @endphp

            @foreach($statuses as $key => $meta)
                <div class="w-80 flex-shrink-0 flex flex-col h-full rounded-2xl border {{ $meta['border'] }} {{ $meta['color'] }} backdrop-blur-sm transition-colors">
                    <!-- Column Header -->
                    <div class="p-4 flex justify-between items-center border-b {{ $meta['border'] }} bg-white/50 dark:bg-slate-800/50 rounded-t-2xl">
                        <h3 class="font-black {{ $meta['text'] }} dark:text-white uppercase text-xs tracking-widest">{{ $meta['label'] }}</h3>
                        <span class="bg-white dark:bg-black/20 px-2.5 py-1 rounded-lg text-xs font-black shadow-sm border border-black/5 dark:border-white/10">{{ $columns[$key]?->count() ?? 0 }}</span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar"
                         ondragover="event.preventDefault()"
                         ondrop="drop(event, '{{ $key }}')">
                        
                        @forelse($columns[$key] ?? [] as $order)
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 cursor-move hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-500 transition-all group relative"
                                 draggable="true"
                                 ondragstart="drag(event, {{ $order->id }})">
                                
                                <div class="flex justify-between items-start mb-3">
                                    <span class="font-mono font-black text-xs text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded">{{ $order->order_number }}</span>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $order->created_at->format('d/m H:i') }}</span>
                                </div>
                                
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1 truncate">{{ $order->guest_name ?? $order->pengguna->name }}</h4>
                                <div class="text-xs text-slate-500 mb-4 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    {{ $order->item->count() }} Produk
                                </div>
                                
                                <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-700">
                                    <span class="font-mono font-black text-slate-700 dark:text-slate-300 text-sm">Rp {{ number_format($order->total_amount / 1000, 0) }}k</span>
                                    <a href="{{ route('admin.pesanan.tampil', $order->id) }}" class="p-2 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-900 hover:text-white text-slate-400 transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-50 py-10">
                                <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                <span class="text-xs font-bold uppercase">Kosong</span>
                            </div>
                        @endforelse

                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <script>
        function drag(ev, orderId) {
            ev.dataTransfer.setData("text", orderId);
            ev.dataTransfer.effectAllowed = "move";
        }

        function drop(ev, newStatus) {
            ev.preventDefault();
            var orderId = ev.dataTransfer.getData("text");
            @this.call('updateStatus', orderId, newStatus);
        }
    </script>
</div>