<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Executive <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Dashboard</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Business Intelligence & Operational Overview.</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold uppercase text-slate-400">Last Updated</p>
            <p class="font-mono font-bold text-slate-700 dark:text-slate-200">{{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-emerald-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Monthly Revenue</p>
            <h3 class="text-2xl font-black font-tech text-emerald-600 mt-2">Rp {{ number_format($stats['monthlyRevenue'] / 1000000, 1, ',', '.') }}M</h3>
            <p class="text-xs text-slate-400 mt-1">Omzet bulan ini</p>
        </div>

        <!-- Inventory Value -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-blue-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Inventory Value</p>
            <h3 class="text-2xl font-black font-tech text-slate-900 dark:text-white mt-2">Rp {{ number_format($stats['totalValue'] / 1000000, 1, ',', '.') }}M</h3>
            <p class="text-xs text-slate-400 mt-1">Total aset stok</p>
        </div>

        <!-- Active Tickets -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-orange-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Active Service</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $stats['activeTickets'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Tiket servis berjalan</p>
        </div>

        <!-- Low Stock -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-rose-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Critical Stock</p>
            <h3 class="text-3xl font-black font-tech text-rose-500 mt-2">{{ $stats['lowStock'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Produk perlu restock</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Sales Trend & Forecast -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Sales Trend Chart (Visual Representation) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Tren Penjualan (12 Bulan Terakhir)</h3>
                <div class="h-64 flex items-end gap-2 justify-between px-2">
                    @foreach($analytics['salesTrend'] as $data)
                        @php 
                            $height = $data->revenue > 0 ? ($data->revenue / $analytics['salesTrend']->max('revenue')) * 100 : 0;
                        @endphp
                        <div class="flex flex-col items-center w-full group">
                            <div class="w-full bg-indigo-100 dark:bg-indigo-900/30 rounded-t-lg relative transition-all duration-500 group-hover:bg-indigo-500" style="height: {{ $height }}%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    Rp {{ number_format($data->revenue, 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-2 rotate-45 md:rotate-0">{{ $data->month_year }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Inventory Forecast Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        AI Stock Forecast
                    </h3>
                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-lg font-bold">Risk: High</span>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center">Stok Kini</th>
                            <th class="px-6 py-4 text-center">Avg. Sales/Day</th>
                            <th class="px-6 py-4 text-center">Estimasi Habis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($analytics['forecast'] as $item)
                            <tr>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $item['product']->name }}</td>
                                <td class="px-6 py-4 text-center font-mono">{{ $item['product']->stock_quantity }}</td>
                                <td class="px-6 py-4 text-center font-mono">{{ $item['daily_usage'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-rose-500 font-black">{{ $item['days_left'] }} Hari</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Right: Top Products & Staff -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Top Products -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Produk Terlaris</h3>
                <div class="space-y-4">
                    @foreach($analytics['topProducts'] as $index => $item)
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 text-xs">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white line-clamp-1">{{ $item->product->name }}</h4>
                                <div class="text-xs text-slate-500">{{ $item->total_qty }} Terjual</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Employee Performance -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg">
                <h3 class="font-bold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    Top Technician (Bulan Ini)
                </h3>
                <div class="space-y-4">
                    @foreach($analytics['technicians'] as $tech)
                        <div class="flex justify-between items-center border-b border-white/10 pb-2 last:border-0">
                            <div>
                                <p class="font-bold text-sm">{{ $tech['name'] }}</p>
                                <p class="text-xs text-slate-400">{{ ucfirst($tech['role']) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-mono font-bold text-yellow-400">{{ $tech['value'] }}</p>
                                <p class="text-[10px] text-slate-500 uppercase">Tiket</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>