<div class="h-[calc(100vh-100px)] flex flex-col">
    <!-- Header & Toolbar -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6 px-4 pt-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Order <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Kanban</span>
            </h2>
            <p class="text-slate-500 text-sm">Geser kartu untuk mengubah status pesanan.</p>
        </div>
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm focus:ring-blue-500 text-sm" placeholder="Cari No. Order atau Pelanggan...">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-4 px-4">
        <div class="flex gap-6 h-full min-w-[1200px]">
            
            @php
                $statuses = [
                                'pending' => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-slate-100 dark:bg-slate-800', 'border' => 'border-slate-300 dark:border-slate-600'],
                                'processing' => ['label' => 'Diproses', 'color' => 'bg-blue-100 dark:bg-blue-900/30', 'border' => 'border-blue-300 dark:border-blue-700'],
                                'shipped' => ['label' => 'Dalam Pengiriman', 'color' => 'bg-indigo-100 dark:bg-indigo-900/30', 'border' => 'border-indigo-300 dark:border-indigo-700'],
                                'completed' => ['label' => 'Selesai', 'color' => 'bg-emerald-100 dark:bg-emerald-900/30', 'border' => 'border-emerald-300 dark:border-emerald-700'],
                                'cancelled' => ['label' => 'Dibatalkan', 'color' => 'bg-rose-100 dark:bg-rose-900/30', 'border' => 'border-rose-300 dark:border-rose-700'],                ];
            @endphp

            @foreach($statuses as $key => $meta)
                <div class="w-80 flex-shrink-0 flex flex-col h-full rounded-2xl border {{ $meta['border'] }} {{ $meta['color'] }} backdrop-blur-sm">
                    <!-- Column Header -->
                    <div class="p-4 flex justify-between items-center border-b {{ $meta['border'] }}">
                        <h3 class="font-bold text-slate-700 dark:text-slate-200 uppercase text-xs tracking-wider">{{ $meta['label'] }}</h3>
                        <span class="bg-white/50 dark:bg-black/20 px-2 py-1 rounded text-xs font-bold">{{ $columns[$key]->count() }}</span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar"
                         ondragover="event.preventDefault()"
                         ondrop="drop(event, '{{ $key }}')">
                        
                        @foreach($columns[$key] as $order)
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 cursor-move hover:shadow-md transition-all group relative"
                                 draggable="true"
                                 ondragstart="drag(event, {{ $order->id }})">
                                
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-mono font-bold text-xs text-slate-500">{{ $order->order_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $order->created_at->format('d/m H:i') }}</span>
                                </div>
                                
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1 truncate">{{ $order->guest_name ?? $order->pengguna->name }}</h4>
                                <div class="text-xs text-slate-500 mb-3">{{ $order->item->count() }} Items</div>
                                
                                <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-700">
                                    <span class="font-mono font-bold text-slate-700 dark:text-slate-300 text-sm">Rp {{ number_format($order->total_amount / 1000, 0) }}k</span>
                                    <a href="{{ route('orders.show', $order->id) }}" class="p-1.5 bg-slate-100 dark:bg-slate-700 rounded-lg hover:bg-blue-100 text-slate-500 hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach

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
