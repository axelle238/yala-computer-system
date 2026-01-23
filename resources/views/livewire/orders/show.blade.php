<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase">Order #{{ $order->order_number }}</h1>
            <p class="text-slate-500">{{ $order->created_at->format('d M Y H:i') }} | {{ $order->guest_name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('orders.index') }}" class="px-4 py-2 border rounded-lg hover:bg-slate-50">Back</a>
            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                <button wire:click="updateStatus('completed')" wire:confirm="Mark as completed?" class="px-4 py-2 bg-slate-900 text-white rounded-lg font-bold">Selesai (Completed)</button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-4">Items</h3>
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-slate-500 uppercase border-b">
                        <th class="py-3">Produk</th>
                        <th class="py-3 text-right">Harga</th>
                        <th class="py-3 text-center">Qty</th>
                        <th class="py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4 font-bold">{{ $item->product->name }}</td>
                        <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-4 text-center">{{ $item->quantity }}</td>
                        <td class="py-4 text-right font-mono">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold text-lg">
                        <td colspan="3" class="py-4 text-right">Grand Total</td>
                        <td class="py-4 text-right text-emerald-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 p-6 h-fit">
            <h3 class="font-bold text-lg mb-4">Informasi Pembayaran</h3>
            
            <div class="mb-4">
                <span class="block text-xs text-slate-500 uppercase font-bold">Status Pembayaran</span>
                <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                    {{ $order->payment_status == 'paid' ? 'bg-emerald-100 text-emerald-700' : 
                      ($order->payment_status == 'pending_approval' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                    {{ $order->payment_status }}
                </span>
            </div>

            @if($order->payment_proof_path)
                <div class="mb-6">
                    <span class="block text-xs text-slate-500 uppercase font-bold mb-2">Bukti Transfer</span>
                    <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof_path) }}" class="w-full rounded-lg border border-slate-200 hover:opacity-90 transition-opacity">
                    </a>
                </div>

                @if($order->payment_status == 'pending_approval')
                    <div class="flex gap-2">
                        <button wire:click="verifyPayment" wire:confirm="Verifikasi pembayaran dan kurangi stok?" class="flex-1 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold shadow-lg shadow-emerald-500/30">
                            Approve
                        </button>
                        <button wire:click="rejectPayment" wire:confirm="Tolak pembayaran?" class="flex-1 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold">
                            Reject
                        </button>
                    </div>
                @endif
            @else
                <div class="p-4 bg-slate-50 text-slate-500 text-sm text-center rounded-lg italic">
                    Belum ada bukti transfer diupload.
                </div>
            @endif
        </div>
    </div>
</div>