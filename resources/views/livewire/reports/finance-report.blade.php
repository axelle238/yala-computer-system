<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h2 class="text-2xl font-bold text-slate-800">Laporan Laba Rugi (Profit & Loss)</h2>
        
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-sm border border-slate-200">
            <select wire:model.live="month" class="border-0 bg-transparent text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer hover:bg-slate-50 rounded">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="border-0 bg-transparent text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer hover:bg-slate-50 rounded">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 border-l-4 border-l-emerald-500">
            <div class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-1">Total Pendapatan</div>
            <div class="text-2xl font-black text-slate-800">Rp {{ number_format($reportData['revenue']['total'], 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-600 mt-2 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Arus Kas Masuk (Gross)
            </div>
        </div>

        <!-- Expenses -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 border-l-4 border-l-rose-500">
            <div class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-1">Total Beban & HPP</div>
            <div class="text-2xl font-black text-slate-800">Rp {{ number_format($reportData['cogs'] + $reportData['expenses']['total'], 0, ',', '.') }}</div>
            <div class="text-xs text-rose-600 mt-2 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                Termasuk Pembelian Barang & Gaji
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-indigo-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-sm text-indigo-200 font-medium uppercase tracking-wider mb-1">Laba Bersih (Net Profit)</div>
                <div class="text-3xl font-black">Rp {{ number_format($reportData['net_profit'], 0, ',', '.') }}</div>
                <div class="mt-2 inline-block px-2 py-1 bg-white/20 rounded text-xs font-bold">
                    Margin: {{ number_format($reportData['margin_percentage'], 1) }}%
                </div>
            </div>
            <!-- Decorative Circle -->
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>
    </div>

    <!-- Detailed Breakdown (P&L Statement Style) -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-slate-200">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">Rincian Laporan Laba Rugi</h3>
            <p class="text-xs text-slate-500">Periode: {{ $reportData['period'] }}</p>
        </div>
        
        <div class="p-6 space-y-6">
            
            <!-- 1. PENDAPATAN -->
            <div>
                <h4 class="text-sm font-bold text-slate-400 uppercase mb-3 border-b border-slate-100 pb-1">1. Pendapatan Usaha</h4>
                <div class="space-y-2">
                    @foreach($reportData['revenue']['breakdown'] as $cat => $amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $cat) }}</span>
                            <span class="font-medium text-slate-800">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    @if(count($reportData['revenue']['breakdown']) == 0)
                        <div class="text-sm text-slate-400 italic">Tidak ada pendapatan periode ini.</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="font-bold text-slate-800">Total Pendapatan</span>
                    <span class="font-bold text-emerald-600">Rp {{ number_format($reportData['revenue']['total'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 2. HPP -->
            <div>
                <h4 class="text-sm font-bold text-slate-400 uppercase mb-3 border-b border-slate-100 pb-1">2. Harga Pokok Penjualan (COGS)</h4>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Biaya Barang Terjual (Inventory Out)</span>
                    <span class="font-medium text-slate-800">(Rp {{ number_format($reportData['cogs'], 0, ',', '.') }})</span>
                </div>
                <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-100 bg-slate-50/50 p-2 rounded">
                    <span class="font-bold text-slate-800">LABA KOTOR (Gross Profit)</span>
                    <span class="font-bold text-indigo-600">Rp {{ number_format($reportData['gross_profit'], 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 3. BEBAN -->
            <div>
                <h4 class="text-sm font-bold text-slate-400 uppercase mb-3 border-b border-slate-100 pb-1">3. Beban Operasional</h4>
                <div class="space-y-2">
                    @foreach($reportData['expenses']['breakdown'] as $cat => $amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $cat) }}</span>
                            <span class="font-medium text-slate-800">(Rp {{ number_format($amount, 0, ',', '.') }})</span>
                        </div>
                    @endforeach
                    @if(count($reportData['expenses']['breakdown']) == 0)
                        <div class="text-sm text-slate-400 italic">Tidak ada beban tercatat.</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-100">
                    <span class="font-bold text-slate-800">Total Beban</span>
                    <span class="font-bold text-rose-600">(Rp {{ number_format($reportData['expenses']['total'], 0, ',', '.') }})</span>
                </div>
            </div>

            <!-- FINAL -->
            <div class="pt-6 border-t-2 border-slate-800">
                <div class="flex justify-between items-center text-lg">
                    <span class="font-black text-slate-900 uppercase tracking-wide">LABA / (RUGI) BERSIH</span>
                    <span class="font-black {{ $reportData['net_profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        Rp {{ number_format($reportData['net_profit'], 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
