<div class="space-y-8 animate-fade-in-up" x-data="{ tab: 'executive' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                <span x-text="tab === 'executive' ? 'Executive' : 'Operational'"></span> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Dashboard</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">
                <span x-show="tab === 'executive'">Business Intelligence & Strategic Overview.</span>
                <span x-show="tab === 'operational'">Day-to-day Management & Action Items.</span>
            </p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Tab Switcher -->
            <div class="bg-slate-100 dark:bg-slate-800 p-1 rounded-lg flex items-center shadow-inner">
                <button @click="tab = 'executive'" 
                    :class="tab === 'executive' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-4 py-2 rounded-md text-sm font-bold transition-all">
                    Executive
                </button>
                <button @click="tab = 'operational'" 
                    :class="tab === 'operational' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                    class="px-4 py-2 rounded-md text-sm font-bold transition-all flex items-center gap-2">
                    Operational
                    @if(($operational['unassignedServices'] + $operational['pendingOpnames'] + $operational['pendingPO']) > 0)
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    @endif
                </button>
            </div>
            
            <div class="text-right hidden md:block">
                <p class="text-xs font-bold uppercase text-slate-400">Last Updated</p>
                <p class="font-mono font-bold text-slate-700 dark:text-slate-200">{{ now()->format('d M H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- EXECUTIVE TAB -->
    <div x-show="tab === 'executive'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

    <!-- OPERATIONAL TAB -->
    <div x-show="tab === 'operational'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        
        <!-- Action Items / Alerts -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Unassigned Service -->
            <a href="{{ route('services.index') }}" class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:border-rose-300 dark:hover:border-rose-500 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    @if($operational['unassignedServices'] > 0)
                        <span class="bg-rose-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $operational['unassignedServices'] }} Pending</span>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Unassigned Tickets</h3>
                <p class="text-sm text-slate-500 mt-1">Service tickets needing technician assignment.</p>
            </a>

            <!-- Pending PO -->
            <a href="{{ route('purchase-orders.index') }}" class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:border-amber-300 dark:hover:border-amber-500 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    @if($operational['pendingPO'] > 0)
                        <span class="bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $operational['pendingPO'] }} Approval</span>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Pending Purchase Orders</h3>
                <p class="text-sm text-slate-500 mt-1">Procurement orders waiting for approval.</p>
            </a>

            <!-- Pending Opname -->
            <a href="{{ route('warehouses.stock-opname') }}" class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:border-indigo-300 dark:hover:border-indigo-500 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    @if($operational['pendingOpnames'] > 0)
                        <span class="bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $operational['pendingOpnames'] }} Review</span>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Stock Adjustment</h3>
                <p class="text-sm text-slate-500 mt-1">Inventory counts needing verification.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Critical Stock List -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Critical Low Stock
                    </h3>
                    <a href="{{ route('products.index') }}" class="text-xs text-indigo-600 font-bold hover:underline">View All</a>
                </div>
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($operational['criticalStockList'] as $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">SKU: {{ $product->sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200">
                                        {{ $product->stock_quantity }} Remaining
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-xs font-bold text-indigo-600 border border-indigo-200 rounded px-2 py-1 hover:bg-indigo-50">Restock</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400">All stock levels are healthy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Quick Access / Shortcuts -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('services.kanban') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:bg-indigo-900/20 dark:hover:border-indigo-500/50 transition text-center group">
                        <span class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Service Board</span>
                    </a>
                    
                    <a href="{{ route('transactions.create') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 hover:bg-emerald-50 hover:border-emerald-200 dark:hover:bg-emerald-900/20 dark:hover:border-emerald-500/50 transition text-center group">
                        <span class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">New Sale</span>
                    </a>

                    <a href="{{ route('employees.attendance') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 hover:bg-blue-50 hover:border-blue-200 dark:hover:bg-blue-900/20 dark:hover:border-blue-500/50 transition text-center group">
                        <span class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Attendance</span>
                    </a>

                    <a href="{{ route('purchase-requisitions.create') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 hover:bg-orange-50 hover:border-orange-200 dark:hover:bg-orange-900/20 dark:hover:border-orange-500/50 transition text-center group">
                        <span class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Request Item</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
