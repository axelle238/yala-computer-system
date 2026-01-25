<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Filter Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div>
            <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Financial <span class="text-indigo-600 dark:text-indigo-400">Report</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Analisis Laba Rugi (Profit & Loss) Bulanan</p>
        </div>
        
        <div class="flex gap-2">
            <select wire:model.live="month" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-indigo-500 py-2 px-4 cursor-pointer transition-colors hover:border-indigo-400">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-indigo-500 py-2 px-4 cursor-pointer transition-colors hover:border-indigo-400">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            <button class="p-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30" onclick="window.print()">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Revenue -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 relative overflow-hidden group hover:border-emerald-500 transition-all">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2">
                    <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pendapatan</span>
                </div>
                <div class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Rp {{ number_format($reportData['revenue']['total'], 0, ',', '.') }}</div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 relative overflow-hidden group hover:border-rose-500 transition-all">
            <div class="absolute right-0 top-0 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-2">
                    <div class="p-1.5 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Beban</span>
                </div>
                <div class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Rp {{ number_format($reportData['cogs'] + $reportData['expenses']['total'], 0, ',', '.') }}</div>
                @php 
                    $totalOut = $reportData['cogs'] + $reportData['expenses']['total'];
                    $ratio = $reportData['revenue']['total'] > 0 ? ($totalOut / $reportData['revenue']['total']) * 100 : 0;
                @endphp
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="bg-rose-500 h-full rounded-full" style="width: {{ min($ratio, 100) }}%"></div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1 text-right">{{ number_format($ratio, 1) }}% dari Pendapatan</p>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl shadow-lg shadow-indigo-600/30 p-6 text-white relative overflow-hidden group">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Laba Bersih</span>
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-bold">Margin {{ number_format($reportData['margin_percentage'], 1) }}%</span>
                </div>
                <div class="text-4xl font-black tracking-tight mb-1">Rp {{ number_format($reportData['net_profit'], 0, ',', '.') }}</div>
                <p class="text-xs text-indigo-200 opacity-80">Periode: {{ $reportData['period'] }}</p>
            </div>
        </div>
    </div>

    <!-- P&L Statement -->
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
        <div class="bg-slate-50 dark:bg-slate-900/50 px-8 py-5 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Rincian Laporan Laba Rugi</h3>
        </div>
        
        <div class="p-8 space-y-8">
            
            <!-- 1. PENDAPATAN -->
            <div>
                <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">1. Pendapatan Usaha</h4>
                <div class="space-y-3 pl-4">
                    @foreach($reportData['revenue']['breakdown'] as $cat => $amount)
                        <div class="flex justify-between text-sm group">
                            <span class="text-slate-600 dark:text-slate-300 capitalize group-hover:text-emerald-600 transition-colors">{{ str_replace('_', ' ', $cat) }}</span>
                            <span class="font-bold text-slate-800 dark:text-white font-mono">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    @if(count($reportData['revenue']['breakdown']) == 0)
                        <div class="text-sm text-slate-400 italic">Tidak ada pendapatan periode ini.</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-dashed border-slate-300 dark:border-slate-600">
                    <span class="font-bold text-slate-800 dark:text-white">Total Pendapatan</span>
                    <span class="font-black text-emerald-600 dark:text-emerald-400 font-mono text-lg">Rp {{ number_format($reportData['revenue']['total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 2. HPP -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">2. Harga Pokok Penjualan (COGS)</h4>
                <div class="space-y-3 pl-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 dark:text-slate-300">Biaya Barang Terjual (Inventory Cost)</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono text-rose-500">(Rp {{ number_format($reportData['cogs'], 0, ',', '.') }})</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30 p-3 rounded-lg -mx-3">
                    <span class="font-bold text-slate-800 dark:text-white uppercase text-xs tracking-wider">LABA KOTOR (Gross Profit)</span>
                    <span class="font-black text-indigo-600 dark:text-indigo-400 font-mono text-lg">Rp {{ number_format($reportData['gross_profit'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 3. BEBAN -->
            <div>
                <h4 class="text-xs font-bold text-rose-500 dark:text-rose-400 uppercase tracking-widest mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">3. Beban Operasional</h4>
                <div class="space-y-3 pl-4">
                    @foreach($reportData['expenses']['breakdown'] as $cat => $amount)
                        <div class="flex justify-between text-sm group">
                            <span class="text-slate-600 dark:text-slate-300 capitalize group-hover:text-rose-500 transition-colors">{{ str_replace('_', ' ', $cat) }}</span>
                            <span class="font-bold text-slate-800 dark:text-white font-mono text-rose-500">(Rp {{ number_format($amount, 0, ',', '.') }})</span>
                        </div>
                    @endforeach
                    @if(count($reportData['expenses']['breakdown']) == 0)
                        <div class="text-sm text-slate-400 italic">Tidak ada beban tercatat.</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-dashed border-slate-300 dark:border-slate-600">
                    <span class="font-bold text-slate-800 dark:text-white">Total Beban</span>
                    <span class="font-black text-rose-600 dark:text-rose-400 font-mono text-lg">(Rp {{ number_format($reportData['expenses']['total'], 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- FINAL -->
            <div class="pt-8 border-t-4 border-slate-800 dark:border-slate-600">
                <div class="flex justify-between items-center">
                    <span class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-xl">LABA / (RUGI) BERSIH</span>
                    <span class="font-black {{ $reportData['net_profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} font-mono text-3xl">
                        Rp {{ number_format($reportData['net_profit'], 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>