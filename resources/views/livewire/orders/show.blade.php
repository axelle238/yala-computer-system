<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('orders.index') }}" class="text-slate-400 hover:text-purple-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                    Order <span class="text-purple-600">{{ $order->order_number }}</span>
                </h2>
            </div>
            <p class="text-slate-500 text-sm ml-7">{{ $order->created_at->format('l, d F Y H:i') }}</p>
        </div>
        <div class="flex gap-3">
            @if($order->status === 'pending')
                <button wire:click="updateStatus('processing')" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-500/20 font-bold text-sm transition-all">
                    Process Order
                </button>
            @endif
            @if($order->status === 'processing')
                <button wire:click="updateStatus('completed')" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/20 font-bold text-sm transition-all">
                    Mark Completed
                </button>
            @endif
            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                <button wire:click="updateStatus('cancelled')" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl shadow-lg shadow-rose-500/20 font-bold text-sm transition-all">
                    Cancel Order
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700 rounded-3xl shadow-lg p-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Items Purchased
                </h3>
                <div class="overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Product</th>
                                <th class="px-4 py-3 text-right">Price</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20">
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-slate-700 dark:text-slate-200">{{ $item->product->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $item->product->sku }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-bold text-slate-800 dark:text-white">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-slate-200 dark:border-slate-600">
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-right font-bold text-slate-500">Total Amount</td>
                                <td class="px-4 py-4 text-right font-black text-xl text-purple-600 font-tech">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-2xl p-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-2">Customer Notes</h4>
                <p class="text-amber-800 dark:text-amber-100 italic">{{ $order->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700 rounded-3xl shadow-lg p-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    Customer Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Name</span>
                        <span class="text-slate-800 dark:text-white font-medium">{{ $order->guest_name ?? $order->user->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Contact</span>
                        <span class="text-slate-800 dark:text-white font-medium">{{ $order->guest_whatsapp ?? $order->user->email ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Customer Type</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $order->user_id ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $order->user_id ? 'Registered Member' : 'Guest Checkout' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-white/20 dark:border-slate-700 rounded-3xl shadow-lg p-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Order Status
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block mb-1">Current Status</span>
                         @php
                            $statusColor = match($order->status) {
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'processing' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-rose-100 text-rose-700',
                                default => 'bg-amber-100 text-amber-700'
                            };
                        @endphp
                        <span class="inline-block w-full text-center py-2 rounded-xl font-bold uppercase text-sm {{ $statusColor }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block mb-2">Payment Status</span>
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="updatePaymentStatus('unpaid')" class="px-2 py-1 text-xs font-bold rounded border {{ $order->payment_status === 'unpaid' ? 'bg-slate-800 text-white border-slate-800' : 'border-slate-200 text-slate-500 hover:border-slate-400' }}">
                                Unpaid
                            </button>
                            <button wire:click="updatePaymentStatus('paid')" class="px-2 py-1 text-xs font-bold rounded border {{ $order->payment_status === 'paid' ? 'bg-emerald-600 text-white border-emerald-600' : 'border-slate-200 text-slate-500 hover:border-emerald-400' }}">
                                Paid
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
