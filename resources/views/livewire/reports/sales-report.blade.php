<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Sales <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Intelligence</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Analisis performa penjualan, tren, dan produk unggulan.</p>
        </div>
        
        <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
            <button wire:click="setPeriod('today')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'today' ? 'bg-white dark:bg-slate-700 shadow text-blue-600 dark:text-blue-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Hari Ini</button>
            <button wire:click="setPeriod('this_week')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'this_week' ? 'bg-white dark:bg-slate-700 shadow text-blue-600 dark:text-blue-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Minggu Ini</button>
            <button wire:click="setPeriod('this_month')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $period == 'this_month' ? 'bg-white dark:bg-slate-700 shadow text-blue-600 dark:text-blue-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Bulan Ini</button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:scale-110 transition-transform"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Total Omset</p>
            <h3 class="text-3xl font-black font-tech mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-blue-200 mt-1">Periode terpilih</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Jumlah Transaksi</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ number_format($totalOrders) }}</h3>
            <p class="text-xs text-slate-400 mt-1">Order selesai & diproses</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Rata-rata Keranjang</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1">Per transaksi</p>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Daily Trend (CSS Chart) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Tren Penjualan</h3>
            
            <div class="h-64 flex items-end gap-2">
                @php $maxVal = $dailySales->max('total') ?: 1; @endphp
                @foreach($dailySales as $day)
                    <div class="flex-1 flex flex-col items-center group relative">
                        <div class="w-full bg-blue-500 rounded-t-sm transition-all duration-500 group-hover:bg-indigo-500 relative" 
                             style="height: {{ ($day->total / $maxVal) * 100 }}%">
                             
                             <!-- Tooltip -->
                             <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap z-10">
                                Rp {{ number_format($day->total, 0, ',', '.') }}
                             </div>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-2 rotate-45 md:rotate-0 truncate w-full text-center">
                            {{ \Carbon\Carbon::parse($day->date)->format('d/m') }}
                        </span>
                    </div>
                @endforeach
                @if($dailySales->isEmpty())
                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">Belum ada data penjualan.</div>
                @endif
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Produk Terlaris</h3>
            
            <div class="space-y-4">
                @foreach($topProducts as $index => $item)
                    <div class="flex items-center gap-4 group">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 text-xs">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between mb-1">
                                <span class="font-bold text-slate-700 dark:text-slate-200 truncate">{{ $item->product->name }}</span>
                                <span class="text-xs font-bold text-slate-500">{{ $item->total_qty }} Unit</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                                @php $pct = ($item->total_sales / ($totalRevenue ?: 1)) * 100; @endphp
                                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="text-[10px] text-slate-400 mt-1 text-right">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
                
                @if($topProducts->isEmpty())
                    <div class="text-center py-10 text-slate-400 text-sm">Belum ada produk terjual.</div>
                @endif
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 lg:col-span-2">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Metode Pembayaran</h3>
            <div class="flex flex-wrap gap-4">
                @foreach($paymentStats as $stat)
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-100 dark:border-slate-700 flex-1 min-w-[150px]">
                        <p class="text-xs font-bold uppercase text-slate-500 mb-1">{{ ucfirst($stat->payment_method ?: 'Manual') }}</p>
                        <p class="text-xl font-black text-slate-800 dark:text-white">{{ $stat->total }} <span class="text-sm font-medium text-slate-400">Transaksi</span></p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
