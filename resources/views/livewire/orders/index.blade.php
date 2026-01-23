<div class="space-y-6 animate-fade-in-up">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Online <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-600">Orders</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Monitor dan proses pesanan masuk dari toko online.</p>
        </div>
    </div>

    <!-- Toolbar: Search & Filter -->
    <div class="bg-white/50 dark:bg-slate-800/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 dark:border-slate-700 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search"
                type="text" 
                class="block w-full pl-12 pr-4 py-3 border-none rounded-xl leading-5 bg-white dark:bg-slate-900 shadow-inner placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-purple-500/50 dark:text-white sm:text-sm transition-all" 
                placeholder="Cari No Order, Customer..."
            >
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <div class="relative group">
                <select wire:model.live="status" class="appearance-none block w-full pl-4 pr-12 py-3 border-none rounded-xl bg-white dark:bg-slate-900 shadow-inner text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-purple-500/50 sm:text-sm transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 group-hover:text-purple-500 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700 rounded-3xl shadow-xl overflow-hidden relative tech-border">
        <!-- Loading Overlay -->
        <div wire:loading.flex class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-30 items-center justify-center">
            <div class="flex flex-col items-center gap-4">
                <div class="relative w-12 h-12">
                    <div class="absolute inset-0 border-4 border-purple-500/20 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-purple-500 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest animate-pulse">Loading Orders...</span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar min-h-[400px]">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px]">Order Info</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px]">Customer</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px]">Total</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px] text-center">Status</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px] text-center">Payment</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-wider text-[10px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-purple-50/30 dark:hover:bg-purple-900/10 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-black text-slate-800 dark:text-white text-base leading-tight group-hover:text-purple-600 transition-colors">
                                        {{ $order->order_number }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium mt-1">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr($order->guest_name ?? $order->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 dark:text-slate-200 text-sm">
                                            {{ $order->guest_name ?? $order->user->name ?? 'Guest' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $order->guest_whatsapp ?? $order->user->email ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-slate-900 dark:text-white font-black font-tech text-base">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusColor = match($order->status) {
                                        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'processing' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-amber-100 text-amber-700 border-amber-200'
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border {{ $statusColor }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                 @php
                                    $payColor = match($order->payment_status) {
                                        'paid' => 'text-emerald-600',
                                        'refunded' => 'text-rose-600',
                                        default => 'text-slate-400'
                                    };
                                @endphp
                                <span class="text-xs font-bold uppercase {{ $payColor }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-sm text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-purple-500 hover:text-purple-600 transition-all">
                                    Detail
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    </div>
                                    <p class="font-bold text-slate-500 uppercase tracking-widest text-xs">No Orders Found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-50/50 dark:bg-slate-900/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $orders->links() }}
        </div>
    </div>
</div>
