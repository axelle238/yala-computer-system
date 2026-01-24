<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Riwayat <span class="text-blue-600">Pesanan</span>
            </h1>
            <a href="{{ route('member.dashboard') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            @if($orders->isEmpty())
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada pesanan</h3>
                    <p class="text-slate-500 mb-6">Mulai belanja produk impianmu sekarang.</p>
                    <a href="{{ route('store.catalog') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors">
                        Ke Katalog Produk
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="p-6">No. Order</th>
                                <th class="p-6">Tanggal</th>
                                <th class="p-6">Status</th>
                                <th class="p-6 text-right">Total</th>
                                <th class="p-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                                    <td class="p-6 font-mono font-bold text-slate-800 dark:text-white">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="p-6 text-slate-500">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="p-6">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                                'completed' => 'bg-emerald-100 text-emerald-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusColors[$order->status] ?? 'bg-slate-100' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="p-6 text-right font-bold text-slate-800 dark:text-white">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-6 text-center">
                                        <a href="{{ route('member.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
