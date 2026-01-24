<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Payroll <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-indigo-500">Automation</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Generate slip gaji otomatis berdasarkan absensi & kinerja.</p>
        </div>
        
        @if($viewMode === 'list')
            <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <select wire:model="month" class="bg-slate-50 dark:bg-slate-900 border-none rounded-lg text-sm font-bold focus:ring-violet-500">
                    @foreach($months as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
                <select wire:model="year" class="bg-slate-50 dark:bg-slate-900 border-none rounded-lg text-sm font-bold focus:ring-violet-500">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <button wire:click="startGeneration" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-lg shadow-lg shadow-violet-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    Generate Payroll
                </button>
            </div>
        @endif
    </div>

    <!-- PREVIEW MODE -->
    @if($viewMode === 'generate_preview')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-violet-50 dark:bg-violet-900/20">
                <div>
                    <h3 class="font-bold text-violet-800 dark:text-violet-300 text-lg">Preview Payroll: {{ $months[$month] }} {{ $year }}</h3>
                    <p class="text-sm text-slate-500">Periksa kalkulasi sebelum menyimpan. Data komisi akan ditandai 'Paid' setelah ini.</p>
                </div>
                <button wire:click="$set('viewMode', 'list')" class="text-slate-400 hover:text-rose-500 font-bold text-sm">Batal</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-4">Pegawai</th>
                            <th class="px-6 py-4 text-center">Kehadiran</th>
                            <th class="px-6 py-4 text-right">Gaji Pokok</th>
                            <th class="px-6 py-4 text-right">Tunjangan</th>
                            <th class="px-6 py-4 text-right">Lembur</th>
                            <th class="px-6 py-4 text-right">Komisi</th>
                            <th class="px-6 py-4 text-right">Net Salary</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($previewData as $data)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $data['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $data['role'] }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-xs">
                                        {{ $data['days_present'] }} Hari
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-slate-600">
                                    {{ number_format($data['base_salary']) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-emerald-600">
                                    +{{ number_format($data['meal_allowance']) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-emerald-600">
                                    +{{ number_format($data['overtime_pay']) }}
                                    <span class="block text-[10px] text-slate-400">({{ $data['overtime_hours'] }} Jam)</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-blue-600">
                                    +{{ number_format($data['commission']) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-lg text-slate-800 dark:text-white">
                                    Rp {{ number_format($data['net_salary']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-4">
                <div class="text-right mr-4">
                    <p class="text-xs font-bold uppercase text-slate-500">Total Pengeluaran Gaji</p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white">Rp {{ number_format(collect($previewData)->sum('net_salary')) }}</p>
                </div>
                <button wire:click="storePayroll" wire:loading.attr="disabled" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                    <span wire:loading.remove>Konfirmasi & Bayar</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    @endif

    <!-- LIST MODE -->
    @if($viewMode === 'list')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4">Tgl Bayar</th>
                        <th class="px-6 py-4 text-right">Gaji Pokok</th>
                        <th class="px-6 py-4 text-right">Insentif</th>
                        <th class="px-6 py-4 text-right">Total Net</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $payroll->period_month }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-xs">
                                        {{ substr($payroll->user->name, 0, 2) }}
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $payroll->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $payroll->pay_date ? \Carbon\Carbon::parse($payroll->pay_date)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 text-right font-mono text-slate-500">{{ number_format($payroll->base_salary) }}</td>
                            <td class="px-6 py-4 text-right font-mono text-emerald-600">
                                {{ number_format($payroll->total_allowance + $payroll->overtime_pay + $payroll->total_commission) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-800 dark:text-white">
                                Rp {{ number_format($payroll->net_salary) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="showDetail({{ $payroll->id }})" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs">Slip Gaji</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat penggajian. Silakan generate payroll baru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $payrolls->links() }}
            </div>
        </div>
    @endif

    <!-- DETAIL / SLIP GAJI -->
    @if($viewMode === 'detail' && $selectedPayroll)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full flex flex-col border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 bg-slate-900 text-white flex justify-between items-start">
                    <div>
                        <h2 class="font-black font-tech text-2xl uppercase tracking-widest">Payslip</h2>
                        <p class="text-slate-400 text-sm">Periode: {{ $selectedPayroll->period_month }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="font-bold text-xl">{{ config('app.name') }}</h3>
                        <p class="text-xs text-slate-400">Official Computer Store</p>
                    </div>
                </div>

                <div class="p-8 space-y-6 bg-white relative">
                    <!-- Watermark -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-5">
                        <h1 class="text-9xl font-black uppercase -rotate-12">PAID</h1>
                    </div>

                    <!-- Employee Info -->
                    <div class="flex justify-between border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold">Penerima</p>
                            <p class="font-bold text-lg text-slate-800">{{ $selectedPayroll->user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $selectedPayroll->user->role }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase font-bold">Tanggal Pembayaran</p>
                            <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($selectedPayroll->pay_date)->format('d F Y') }}</p>
                            <p class="text-xs font-mono text-slate-400">REF: PAY-{{ $selectedPayroll->id }}</p>
                        </div>
                    </div>

                    <!-- Earnings -->
                    <div>
                        <h4 class="font-bold text-slate-800 mb-3 border-b border-slate-200 pb-1">Penghasilan (Earnings)</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Gaji Pokok (Base Salary)</span>
                                <span class="font-mono font-bold text-slate-800">Rp {{ number_format($selectedPayroll->base_salary) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Tunjangan Makan & Transport</span>
                                <span class="font-mono text-slate-800">Rp {{ number_format($selectedPayroll->total_allowance) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Lembur (Overtime)</span>
                                <span class="font-mono text-slate-800">Rp {{ number_format($selectedPayroll->overtime_pay) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Komisi / Bonus (Commissions)</span>
                                <span class="font-mono text-slate-800">Rp {{ number_format($selectedPayroll->total_commission) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions -->
                    <div>
                        <h4 class="font-bold text-rose-700 mb-3 border-b border-rose-100 pb-1">Potongan (Deductions)</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Potongan Lainnya / Kasbon</span>
                                <span class="font-mono text-rose-600">- Rp {{ number_format($selectedPayroll->deductions) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="bg-slate-50 p-4 rounded-xl flex justify-between items-center border border-slate-100">
                        <span class="font-bold text-slate-600 uppercase">Gaji Bersih (Take Home Pay)</span>
                        <span class="font-black text-2xl text-indigo-600">Rp {{ number_format($selectedPayroll->net_salary) }}</span>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end gap-3">
                    <button wire:click="$set('viewMode', 'list')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50">Tutup</button>
                    <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-lg hover:bg-indigo-700">Print / PDF</button>
                </div>
            </div>
        </div>
    @endif
</div>
