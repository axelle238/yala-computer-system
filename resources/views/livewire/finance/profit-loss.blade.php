<div class="space-y-8 animate-fade-in-up">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Financial <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-cyan-600">Intelligence</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Analisis profitabilitas dan arus kas real-time.</p>
        </div>
        
        <div class="flex gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <select wire:model.live="month" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 cursor-pointer">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                @endfor
            </select>
            <div class="w-px bg-slate-200 dark:bg-slate-700 my-1"></div>
            <select wire:model.live="year" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 cursor-pointer">
                @for($i=2024; $i<=date('Y'); $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Main KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Revenue Card -->
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-blue-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Revenue</span>
            </div>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 mt-2 font-medium">Total Omset Penjualan</p>
        </div>

        <!-- Expense Card -->
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-rose-500/20 transition-all"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-900/30 rounded-2xl text-rose-600 dark:text-rose-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Expenses + COGS</span>
            </div>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white">Rp {{ number_format($totalExpenses + $cogs, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 mt-2 font-medium">HPP: {{ number_format($cogs, 0, ',', '.') }} | Ops: {{ number_format($totalExpenses, 0, ',', '.') }}</p>
        </div>

        <!-- Net Profit Card -->
        <div class="relative bg-slate-900 dark:bg-white rounded-3xl p-6 border border-slate-800 dark:border-slate-200 overflow-hidden group shadow-xl">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="p-3 bg-white/10 dark:bg-slate-100 rounded-2xl text-white dark:text-slate-900">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-wider text-white/60 dark:text-slate-500">Net Profit</span>
            </div>
            <h3 class="text-4xl font-black font-tech text-white dark:text-slate-900 relative z-10">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h3>
            <div class="mt-4 w-full bg-white/10 dark:bg-slate-200 rounded-full h-1.5 overflow-hidden">
                @php $margin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0; @endphp
                <div class="bg-gradient-to-r from-emerald-400 to-cyan-400 h-full rounded-full transition-all duration-1000" style="width: {{ max(0, min(100, $margin)) }}%"></div>
            </div>
            <p class="text-xs text-white/60 dark:text-slate-500 mt-2 font-medium relative z-10">Net Margin: {{ number_format($margin, 1) }}%</p>
        </div>
    </div>

    <!-- Profit Trend Chart & Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Visualization -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6 font-tech">Daily Profit Trend</h3>
            
            <div class="flex items-end justify-between gap-1 h-64 w-full">
                @php $maxProfit = max(array_column($trendData, 'profit') ?: [1]); @endphp
                @foreach($trendData as $data)
                    <div class="group flex-1 flex flex-col items-center justify-end h-full gap-2 relative">
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-t-sm relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/20" 
                             style="height: {{ $maxProfit > 0 ? max(5, ($data['profit'] / $maxProfit) * 100) : 5 }}%">
                             <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-emerald-500 to-cyan-400 opacity-80 group-hover:opacity-100 transition-opacity" style="height: 100%"></div>
                        </div>
                        <!-- Tooltip -->
                        <div class="absolute -top-8 bg-slate-900 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                            Rp {{ number_format($data['profit'] / 1000, 0) }}k
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-2 text-[10px] text-slate-400 font-mono">
                <span>Day 1</span>
                <span>Day {{ count($trendData) }}</span>
            </div>
        </div>

        <!-- Expenses Breakdown -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white font-tech">Beban Operasional</h3>
                <a href="{{ route('expenses.index') }}" class="text-xs font-bold text-cyan-600 hover:underline">Kelola</a>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar space-y-4">
                @forelse($expenses as $expense)
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border border-transparent hover:border-slate-100 dark:hover:border-slate-600 group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center text-rose-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $expense->title }}</p>
                                <p class="text-[10px] text-slate-500">{{ $expense->expense_date->format('d M') }} • {{ ucfirst($expense->category) }}</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-rose-600 dark:text-rose-400 text-sm">-{{ number_format($expense->amount / 1000, 0) }}k</span>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400 text-sm">Tidak ada pengeluaran bulan ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>