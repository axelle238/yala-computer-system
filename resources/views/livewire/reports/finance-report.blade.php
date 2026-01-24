<div class="space-y-8 animate-fade-in-up">
    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Finance <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Report</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Laporan Laba Rugi & Arus Kas.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            @foreach(['today' => 'Hari Ini', 'this_month' => 'Bulan Ini', 'last_month' => 'Bulan Lalu', 'this_year' => 'Tahun Ini'] as $key => $label)
                <button wire:click="setPeriod('{{ $key }}')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $period === $key ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    {{ $label }}
                </button>
            @endforeach
            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>
            <input type="date" wire:model.live="startDate" class="bg-slate-50 dark:bg-slate-900 border-none rounded-lg text-xs font-bold text-slate-600 focus:ring-emerald-500">
            <span class="text-slate-400 text-xs">-</span>
            <input type="date" wire:model.live="endDate" class="bg-slate-50 dark:bg-slate-900 border-none rounded-lg text-xs font-bold text-slate-600 focus:ring-emerald-500">
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase text-slate-500 tracking-wider">Total Pendapatan (Sales)</p>
            <h3 class="text-2xl font-black font-mono text-slate-800 dark:text-white mt-2">
                Rp {{ number_format($revenue, 0, ',', '.') }}
            </h3>
        </div>

        <!-- COGS -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase text-slate-500 tracking-wider">HPP (Modal Barang)</p>
            <h3 class="text-2xl font-black font-mono text-rose-600 mt-2">
                (Rp {{ number_format($cogs, 0, ',', '.') }})
            </h3>
        </div>

        <!-- Gross Profit -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase text-slate-500 tracking-wider">Laba Kotor</p>
            <h3 class="text-2xl font-black font-mono text-emerald-600 mt-2">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </h3>
            <p class="text-[10px] text-slate-400 mt-1">Margin: {{ $revenue > 0 ? round(($grossProfit / $revenue) * 100, 1) : 0 }}%</p>
        </div>

        <!-- Net Profit -->
        <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 opacity-20"></div>
            <p class="text-xs font-bold uppercase text-slate-300 tracking-wider relative z-10">Laba Bersih (Net)</p>
            <h3 class="text-3xl font-black font-mono text-white mt-2 relative z-10">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Expenses Breakdown -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Biaya Operasional</h3>
                <span class="font-mono font-bold text-rose-600">Total: Rp {{ number_format($totalExpenses + $payroll, 0, ',', '.') }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-3 font-bold text-slate-700 dark:text-slate-300">Gaji Pegawai</td>
                            <td class="px-6 py-3 text-slate-500">Payroll System</td>
                            <td class="px-6 py-3 text-right font-mono text-rose-600">Rp {{ number_format($payroll, 0, ',', '.') }}</td>
                        </tr>
                        @foreach($expenses as $exp)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-[10px] font-bold uppercase text-slate-500">
                                        {{ $exp->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-slate-600 dark:text-slate-400">
                                    {{ $exp->title }}
                                    <span class="block text-[10px] text-slate-400">{{ $exp->expense_date->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-3 text-right font-mono text-rose-600">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Profit Analysis -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Analisis Keuangan</h3>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-4 overflow-hidden">
                        <div class="bg-emerald-500 h-4" style="width: {{ $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 w-12">{{ $revenue > 0 ? round(($grossProfit / $revenue) * 100) : 0 }}%</span>
                    <span class="text-xs text-slate-500 uppercase w-24">Gross Margin</span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-4 overflow-hidden">
                        <div class="bg-indigo-500 h-4" style="width: {{ $revenue > 0 ? ($netProfit / $revenue) * 100 : 0 }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 w-12">{{ $revenue > 0 ? round(($netProfit / $revenue) * 100) : 0 }}%</span>
                    <span class="text-xs text-slate-500 uppercase w-24">Net Margin</span>
                </div>
            </div>

            <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-100 dark:border-yellow-800">
                <p class="text-xs font-bold text-yellow-700 dark:text-yellow-400 uppercase mb-2">Insight AI</p>
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    @if($netProfit > 0)
                        Performa keuangan sehat. Margin bersih {{ $revenue > 0 ? round(($netProfit / $revenue) * 100) : 0 }}% menunjukkan efisiensi operasional yang baik. Pertahankan rasio HPP di bawah {{ $revenue > 0 ? round(($cogs / $revenue) * 100) : 0 }}%.
                    @else
                        Perhatian: Bisnis mengalami kerugian operasional periode ini. Tinjau kembali pengeluaran operasional dan strategi harga jual.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
