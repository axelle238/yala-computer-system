<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h1>
        <div class="flex gap-2">
            <select wire:model.live="period" class="rounded-lg border-gray-300 text-sm">
                <option value="today">Hari Ini</option>
                <option value="this_week">Minggu Ini</option>
                <option value="this_month">Bulan Ini</option>
                <option value="custom">Custom</option>
            </select>
            @if($period === 'custom')
                <input type="date" wire:model.live="startDate" class="rounded-lg border-gray-300 text-sm">
                <input type="date" wire:model.live="endDate" class="rounded-lg border-gray-300 text-sm">
            @endif
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Pendapatan</p>
            <h3 class="text-3xl font-black text-indigo-600 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-500 uppercase">Total Transaksi</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs font-bold text-gray-500 uppercase">Rata-rata Order</p>
            <h3 class="text-3xl font-black text-emerald-600 mt-2">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Placeholder (Visual Representation using CSS bars) -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mb-8">
        <h3 class="font-bold text-gray-800 mb-6">Tren Penjualan</h3>
        <div class="h-64 flex items-end justify-between gap-2 px-4 border-b border-gray-200 pb-2">
            @foreach($chartLabels as $index => $label)
                @php 
                    $value = $chartValues[$index];
                    $max = $chartValues->max() > 0 ? $chartValues->max() : 1;
                    $height = ($value / $max) * 100;
                @endphp
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-sm relative transition-all group-hover:bg-indigo-500" style="height: {{ $height }}%">
                        <span class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-10">
                            Rp {{ number_format($value) }}
                        </span>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-2 rotate-45 md:rotate-0">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Transaksi Terakhir (Periode Ini)</h3>
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">No. Order</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono font-bold">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->guest_name ?? ($order->user->name ?? 'Guest') }}</td>
                        <td class="px-6 py-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase bg-slate-100">{{ $order->status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>