<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Payroll & Kinerja</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Laporan gaji dan performa penjualan pegawai.</p>
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

    <!-- Payroll Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4 text-right">Gaji Pokok</th>
                        <th class="px-6 py-4 text-right">Omzet Penjualan</th>
                        <th class="px-6 py-4 text-right">Komisi (1%)</th>
                        <th class="px-6 py-4 text-right bg-blue-50 text-blue-700">Total Gaji</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $emp['name'] }}</div>
                                <div class="text-xs text-slate-400">Join: {{ $emp['join_date'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                Rp {{ number_format($emp['base_salary'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-emerald-600">
                                Rp {{ number_format($emp['sales_total'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-amber-600">
                                Rp {{ number_format($emp['commission'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-blue-700 bg-blue-50/50">
                                Rp {{ number_format($emp['total_salary'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-slate-400 hover:text-blue-600" onclick="window.print()" title="Cetak Slip">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
