<div class="space-y-8 animate-fade-in-up">
    <!-- Header & Filter -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Analisis Bisnis</h1>
            <p class="text-slate-500 dark:text-slate-400">Analisa performa bisnis berbasis data.</p>
        </div>
        <select wire:model.live="range" class="bg-white dark:bg-slate-800 border-none rounded-xl px-4 py-2 font-bold shadow-sm">
            <option value="7_days">7 Hari Terakhir</option>
            <option value="30_days">30 Hari Terakhir</option>
            <option value="this_month">Bulan Ini</option>
            <option value="last_month">Bulan Lalu</option>
            <option value="this_year">Tahun Ini</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <span class="text-emerald-500">🏆</span> Produk Terlaris (Pendapatan)
            </h3>
            <div class="space-y-4">
                @foreach($topProducts as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                #{{ $loop->iteration }}
                            </div>
                            <div>
                                <div class="font-bold text-sm text-slate-800 dark:text-white">{{ $item->product->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->total_qty }} Terjual</div>
                            </div>
                        </div>
                        <div class="font-mono font-bold text-slate-700 dark:text-slate-200">
                            Rp {{ number_format($item->total_revenue / 1000000, 1, ',', '.') }}jt
                        </div>
                    </div>
                    <!-- Bar -->
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ ($item->total_revenue / $topProducts->first()->total_revenue) * 100 }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Category Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <span class="text-blue-500">📊</span> Performa Kategori
            </h3>
            <div class="space-y-3">
                @foreach($categoryPerformance as $cat)
                    <div class="group">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-slate-600 dark:text-slate-300">{{ $cat->name }}</span>
                            <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($cat->revenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 group-hover:bg-blue-400 transition-all" style="width: {{ ($cat->revenue / $categoryPerformance->max('revenue')) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dead Stock Alert -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-rose-200 dark:border-rose-900/50 p-6">
        <h3 class="font-bold text-lg text-rose-600 dark:text-rose-400 mb-2 flex items-center gap-2">
            <span class="text-rose-500">⚠️</span> Peringatan Stok Mati (Tidak terjual > 90 hari)
        </h3>
        <p class="text-sm text-slate-500 mb-4">Barang-barang ini memiliki stok tapi tidak terjual dalam 3 bulan terakhir. Pertimbangkan diskon/flash sale.</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($deadStocks as $product)
                <div class="p-3 bg-rose-50 dark:bg-rose-900/10 rounded-xl border border-rose-100 dark:border-rose-800/30">
                    <div class="font-bold text-sm text-slate-800 dark:text-white truncate">{{ $product->name }}</div>
                    <div class="flex justify-between mt-2 text-xs">
                        <span class="text-slate-500">Stok: <strong class="text-rose-600">{{ $product->stock_quantity }}</strong></span>
                        <span class="text-slate-500">Nilai: Rp {{ number_format($product->buy_price * $product->stock_quantity / 1000, 0) }}k</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
