<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Keuangan</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Analisa Laba Rugi (Profit & Loss) Bulanan.</p>
        </div>
        
        <div class="flex gap-2">
            <select wire:model.live="month" class="px-4 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="px-4 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                @for($y=date('Y'); $y>=2024; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan (Omzet)</p>
            <h3 class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
        </div>
        <!-- Total Expense Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Pengeluaran</p>
            <h3 class="text-3xl font-extrabold text-rose-600">Rp {{ number_format($totalExpenses + $cogs, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1">Termasuk HPP + Biaya Ops + Gaji</p>
        </div>
        <!-- Net Profit Card -->
        <div class="p-6 rounded-2xl border shadow-sm {{ $netProfit >= 0 ? 'bg-emerald-600 border-emerald-500' : 'bg-rose-600 border-rose-500' }}">
            <p class="text-xs font-bold text-white/80 uppercase tracking-wider mb-2">Laba Bersih (Net Profit)</p>
            <h3 class="text-3xl font-extrabold text-white">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Detailed Report -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden p-8">
        <h3 class="text-lg font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Rincian Laba Rugi</h3>
        
        <div class="space-y-4 text-sm">
            <!-- Income Section -->
            <div>
                <div class="flex justify-between items-center py-2">
                    <span class="font-bold text-slate-700">Pendapatan Penjualan</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 text-slate-500 pl-4 border-l-2 border-slate-200">
                    <span>(-) Harga Pokok Penjualan (HPP)</span>
                    <span>(Rp {{ number_format($cogs, 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between items-center py-3 border-t border-slate-100 bg-slate-50 px-4 -mx-4 mt-2">
                    <span class="font-bold text-slate-800">Laba Kotor (Gross Profit)</span>
                    <span class="font-bold text-emerald-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Expenses Section -->
            <div class="mt-6">
                <h4 class="font-bold text-slate-700 mb-2">Biaya Operasional</h4>
                <div class="space-y-2 pl-4 border-l-2 border-slate-200">
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Biaya Operasional (Listrik, Sewa, dll)</span>
                        <span>(Rp {{ number_format($operationalExpenses, 0, ',', '.') }})</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-500">
                        <span>Beban Gaji & Komisi Pegawai</span>
                        <span>(Rp {{ number_format($payrollExpenses, 0, ',', '.') }})</span>
                    </div>
                </div>
                <div class="flex justify-between items-center py-3 border-t border-slate-100 bg-slate-50 px-4 -mx-4 mt-2">
                    <span class="font-bold text-slate-800">Total Beban Operasional</span>
                    <span class="font-bold text-rose-600">(Rp {{ number_format($totalExpenses, 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- Final Result -->
            <div class="mt-8 pt-6 border-t-2 border-slate-900">
                <div class="flex justify-between items-center text-lg">
                    <span class="font-extrabold text-slate-900">LABA BERSIH USAHA</span>
                    <span class="font-extrabold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
