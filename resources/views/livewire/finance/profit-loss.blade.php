<div class="space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Laporan Laba Rugi</h1>
            <p class="text-slate-500 dark:text-slate-400">Analisis kinerja keuangan periode {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="month" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 font-bold text-slate-700 dark:text-slate-200">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 font-bold text-slate-700 dark:text-slate-200">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            <button onclick="window.print()" class="bg-slate-800 text-white px-4 py-2 rounded-lg font-bold hover:bg-slate-900">
                Print
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Omset</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-1">Rp {{ number_format($productRevenue + $serviceRevenue, 0, ',', '.') }}</h3>
            <div class="text-xs text-slate-500">
                Barang: Rp {{ number_format($productRevenue, 0, ',', '.') }} <br>
                Jasa: Rp {{ number_format($serviceRevenue, 0, ',', '.') }}
            </div>
        </div>

        <!-- Expense Card -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-900/30 rounded-xl text-rose-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Pengeluaran</span>
            </div>
            <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mb-1">Rp {{ number_format($cogs + $totalExpenses, 0, ',', '.') }}</h3>
             <div class="text-xs text-slate-500">
                HPP Barang: Rp {{ number_format($cogs, 0, ',', '.') }} <br>
                Operasional: Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </div>
        </div>

        <!-- Net Profit Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-xs font-bold text-white/80 uppercase">Laba Bersih (Net Profit)</span>
                </div>
                <h3 class="text-3xl font-black mb-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <p class="text-sm text-emerald-100 font-medium">
                    {{ $netProfit >= 0 ? 'Profitabilitas Positif' : 'Perlu Evaluasi (Rugi)' }}
                </p>
            </div>
            <!-- Decorative Pattern -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-8 -mt-8 pointer-events-none"></div>
        </div>
    </div>

    <!-- Detailed Waterfall Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Rincian Laba Rugi (Waterfall)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <!-- Revenue Section -->
                    <tr class="bg-slate-50/50 dark:bg-slate-700/50">
                        <td class="px-6 py-3 font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-xs" colspan="2">1. Pendapatan (Revenue)</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-slate-600 dark:text-slate-400 pl-10">Penjualan Barang Retail</td>
                        <td class="px-6 py-3 text-right font-mono text-slate-800 dark:text-white font-bold">Rp {{ number_format($productRevenue, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-slate-600 dark:text-slate-400 pl-10">Pendapatan Jasa Service</td>
                        <td class="px-6 py-3 text-right font-mono text-slate-800 dark:text-white font-bold">Rp {{ number_format($serviceRevenue, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-blue-50/50 dark:bg-blue-900/10">
                        <td class="px-6 py-3 font-bold text-blue-700 dark:text-blue-400 pl-10">Total Pendapatan</td>
                        <td class="px-6 py-3 text-right font-mono text-blue-700 dark:text-blue-400 font-bold border-t border-blue-200 dark:border-blue-800">Rp {{ number_format($productRevenue + $serviceRevenue, 0, ',', '.') }}</td>
                    </tr>

                    <!-- COGS Section -->
                    <tr class="bg-slate-50/50 dark:bg-slate-700/50">
                        <td class="px-6 py-3 font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-xs" colspan="2">2. Beban Pokok Penjualan (HPP)</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 text-slate-600 dark:text-slate-400 pl-10">Harga Pokok Barang Terjual</td>
                        <td class="px-6 py-3 text-right font-mono text-rose-600 dark:text-rose-400 font-bold">(Rp {{ number_format($cogs, 0, ',', '.') }})</td>
                    </tr>
                    
                    <!-- Gross Profit -->
                    <tr class="bg-emerald-50/50 dark:bg-emerald-900/10">
                        <td class="px-6 py-4 font-black text-emerald-800 dark:text-emerald-400 uppercase">3. Laba Kotor (Gross Profit)</td>
                        <td class="px-6 py-4 text-right font-mono text-emerald-800 dark:text-emerald-400 font-black text-lg border-t-2 border-emerald-100 dark:border-emerald-800">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
                    </tr>

                    <!-- Expenses Section -->
                    <tr class="bg-slate-50/50 dark:bg-slate-700/50">
                        <td class="px-6 py-3 font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider text-xs" colspan="2">4. Beban Operasional</td>
                    </tr>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="px-6 py-2 text-slate-600 dark:text-slate-400 pl-10 flex justify-between items-center group">
                                <span>{{ $expense->description ?? $expense->category }} <span class="text-xs text-slate-400 ml-2">({{ $expense->created_at->format('d M') }})</span></span>
                            </td>
                            <td class="px-6 py-2 text-right font-mono text-rose-600 dark:text-rose-400">(Rp {{ number_format($expense->amount, 0, ',', '.') }})</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-3 text-slate-400 italic pl-10">Tidak ada pengeluaran operasional tercatat bulan ini.</td>
                            <td class="px-6 py-3 text-right font-mono text-slate-400">-</td>
                        </tr>
                    @endforelse
                    <tr class="bg-rose-50/50 dark:bg-rose-900/10">
                        <td class="px-6 py-3 font-bold text-rose-700 dark:text-rose-400 pl-10">Total Beban Operasional</td>
                        <td class="px-6 py-3 text-right font-mono text-rose-700 dark:text-rose-400 font-bold border-t border-rose-200 dark:border-rose-800">(Rp {{ number_format($totalExpenses, 0, ',', '.') }})</td>
                    </tr>

                    <!-- Net Profit Final -->
                    <tr class="bg-slate-900 text-white dark:bg-black">
                        <td class="px-6 py-6 font-black text-xl uppercase tracking-widest">5. Laba Bersih (Net Profit)</td>
                        <td class="px-6 py-6 text-right font-mono font-black text-2xl {{ $netProfit < 0 ? 'text-rose-400' : 'text-emerald-400' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
