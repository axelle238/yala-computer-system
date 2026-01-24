<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Executive Dashboard</h1>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32">
            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</div>
            <div class="text-2xl font-black text-indigo-700">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        </div>

        <!-- Profit -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32">
            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider">Laba Bersih (Est)</div>
            <div class="text-2xl font-black {{ $stats['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}
            </div>
        </div>

        <!-- Active Service -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32 border-l-4 border-l-amber-400">
            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider">Servis Aktif</div>
            <div class="flex justify-between items-end">
                <div class="text-3xl font-black text-slate-800">{{ $stats['active_tickets'] }}</div>
                <a href="{{ route('services.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Antrian &rarr;</a>
            </div>
        </div>

        <!-- Orders Today -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32 border-l-4 border-l-blue-400">
            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider">Order Hari Ini</div>
            <div class="flex justify-between items-end">
                <div class="text-3xl font-black text-slate-800">{{ $stats['orders_today'] }}</div>
                <a href="{{ route('orders.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Order &rarr;</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Peringatan Stok Rendah</h3>
                <span class="text-xs font-bold bg-rose-100 text-rose-600 px-2 py-1 rounded-full">{{ $analysis['low_stock']->count() }} Item</span>
            </div>
            <div class="p-0">
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($analysis['low_stock'] as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $item->name }}</td>
                                <td class="px-6 py-3 text-sm font-bold text-rose-600 text-right">{{ $item->stock_quantity }} Unit</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-4 text-center text-slate-400 italic">Stok aman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('purchase-requisitions.create') }}" class="text-xs font-bold text-indigo-600 hover:underline">Buat Permintaan Pembelian &rarr;</a>
            </div>
        </div>

        <!-- Fast Moving -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Produk Terlaris (30 Hari)</h3>
            </div>
            <div class="p-0">
                <table class="w-full">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($analysis['fast_moving'] as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $item->product->name }}</td>
                                <td class="px-6 py-3 text-sm font-bold text-emerald-600 text-right">{{ $item->total_sold }} Terjual</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="px-6 py-4 text-center text-slate-400 italic">Belum ada data penjualan cukup.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
