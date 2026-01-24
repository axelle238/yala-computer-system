<div class="space-y-8 animate-fade-in-up">
    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Laporan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Keuangan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Analisis profitabilitas dan arus kas perusahaan.</p>
        </div>
        
        <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
            @foreach(['today' => 'Hari Ini', 'this_month' => 'Bulan Ini', 'last_month' => 'Bulan Lalu', 'this_year' => 'Tahun Ini'] as $key => $label)
                <button wire:click="setPeriod('{{ $key }}')" 
                        class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $period === $key ? 'bg-white dark:bg-slate-700 shadow text-emerald-600 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- P&L Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Revenue -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Total Pendapatan (Revenue)</p>
            <h3 class="text-3xl font-black font-mono text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <div class="mt-4 space-y-1 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Penjualan Barang</span>
                    <span class="font-mono text-slate-700 dark:text-slate-300">Rp {{ number_format($salesRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Jasa Service</span>
                    <span class="font-mono text-slate-700 dark:text-slate-300">Rp {{ number_format($serviceRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Gross Profit -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Laba Kotor (Gross Profit)</p>
            <h3 class="text-3xl font-black font-mono text-blue-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h3>
            <div class="mt-4 space-y-1 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>HPP (COGS)</span>
                    <span class="font-mono text-rose-500 font-bold">- Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Margin</span>
                    <span class="font-mono text-blue-500">{{ $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl -mr-10 -mt-10"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2 relative z-10">Laba Bersih (Net Profit)</p>
            <h3 class="text-4xl font-black font-mono relative z-10 {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-slate-400 mt-2 relative z-10">Setelah dikurangi Beban Operasional & Gaji.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Chart & Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Chart Visual -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Tren Pendapatan</h3>
                <div class="h-64 flex items-end justify-between gap-2 px-2">
                    @php $maxVal = collect($chartData)->max('revenue'); @endphp
                    @foreach($chartData as $data)
                        @php 
                            $h = $maxVal > 0 ? ($data['revenue'] / $maxVal) * 100 : 0; 
                        @endphp
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="w-full bg-emerald-100 dark:bg-emerald-900/30 rounded-t-sm relative hover:bg-emerald-500 transition-all duration-300" style="height: {{ $h }}%">
                                <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                    {{ $data['date'] }}: Rp {{ number_format($data['revenue'] / 1000) }}k
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Expense Breakdown -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white">Rincian Pengeluaran</h3>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3 text-right">Tanggal</th>
                            <th class="px-6 py-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr class="bg-slate-50/50">
                            <td class="px-6 py-3 font-bold text-indigo-600">Total Gaji Karyawan (Payroll)</td>
                            <td class="px-6 py-3 text-right text-slate-500">-</td>
                            <td class="px-6 py-3 text-right font-mono font-bold text-slate-800 dark:text-white">Rp {{ number_format($payrollExpense, 0, ',', '.') }}</td>
                        </tr>
                        @foreach($operationalExpenses as $expense)
                            <tr>
                                <td class="px-6 py-3">{{ $expense->description }}</td>
                                <td class="px-6 py-3 text-right text-slate-500">{{ $expense->expense_date->format('d M') }}</td>
                                <td class="px-6 py-3 text-right font-mono text-slate-600 dark:text-slate-300">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-100 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 font-bold">
                        <tr>
                            <td class="px-6 py-4">Total Beban</td>
                            <td></td>
                            <td class="px-6 py-4 text-right text-rose-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Right: Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6">Ringkasan Laporan</h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between pb-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500">Pendapatan</span>
                        <span class="font-mono text-slate-800 dark:text-white font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500">HPP (COGS)</span>
                        <span class="font-mono text-rose-500 font-bold">- Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500">Beban Operasional</span>
                        <span class="font-mono text-rose-500 font-bold">- Rp {{ number_format($totalOpExpense, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-slate-500">Gaji Karyawan</span>
                        <span class="font-mono text-rose-500 font-bold">- Rp {{ number_format($payrollExpense, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="pt-4 mt-2 border-t-2 border-slate-200 dark:border-slate-600">
                        <div class="flex justify-between items-center">
                            <span class="font-black uppercase text-slate-800 dark:text-white">Net Profit</span>
                            <span class="font-black text-xl font-mono {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($netProfit, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button class="w-full py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>