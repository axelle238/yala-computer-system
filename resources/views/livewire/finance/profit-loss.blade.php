<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Profit & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Loss</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Laporan laba rugi bulanan (Income Statement).</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <select wire:model.live="month" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-white focus:ring-0 cursor-pointer">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endfor
            </select>
            <span class="text-slate-300">|</span>
            <select wire:model.live="year" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-white focus:ring-0 cursor-pointer">
                @for($y = 2024; $y <= 2030; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- P&L Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="bg-slate-50 dark:bg-slate-900/50 p-8 border-b border-slate-100 dark:border-slate-700 text-center">
            <p class="text-xs font-bold uppercase text-slate-500 mb-2 tracking-widest">Net Profit (Laba Bersih)</p>
            <h1 class="text-5xl font-black font-mono {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </h1>
        </div>

        <!-- Details -->
        <div class="p-8 space-y-8">
            
            <!-- Revenue -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-700 dark:text-slate-200 text-lg">Pendapatan (Revenue)</h3>
                    <span class="font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                </div>
                <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 w-full"></div>
                </div>
            </div>

            <!-- COGS -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-slate-700 dark:text-slate-200">Harga Pokok Penjualan (HPP)</h3>
                    <span class="font-mono font-bold text-rose-500">- Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                </div>
                <div class="pl-4 text-xs text-slate-500 italic mb-2">Biaya modal barang yang terjual.</div>
                
                <div class="flex justify-between items-center pt-2 border-t border-dashed border-slate-200 dark:border-slate-700">
                    <span class="font-bold text-sm text-slate-600 dark:text-slate-400 uppercase tracking-wider">Gross Profit (Laba Kotor)</span>
                    <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-slate-50 dark:bg-slate-900/30 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-700 dark:text-slate-200 mb-4">Biaya Operasional (Expenses)</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Gaji Karyawan (Payroll)</span>
                        <span class="font-mono text-slate-800 dark:text-white">Rp {{ number_format($expenses['payroll'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Biaya Lain-lain (Listrik, Air, dll)</span>
                        <span class="font-mono text-slate-800 dark:text-white">Rp {{ number_format($expenses['operational'], 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="border-t border-slate-200 dark:border-slate-600 pt-3 mt-3 flex justify-between font-bold">
                        <span class="text-slate-700 dark:text-slate-300">Total Expenses</span>
                        <span class="font-mono text-rose-500">- Rp {{ number_format($expenses['total'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
