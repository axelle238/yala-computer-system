<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Payroll <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">System</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen penggajian karyawan otomatis.</p>
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

    @if(!$hasGenerated)
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="relative z-10 max-w-2xl">
                <h3 class="text-2xl font-black mb-2">Generate Payroll Bulan Ini</h3>
                <p class="text-white/80 mb-6 leading-relaxed">Sistem akan otomatis menghitung gaji pokok, komisi penjualan, dan potongan berdasarkan absensi (keterlambatan/alpha) untuk periode yang dipilih.</p>
                <button wire:click="generatePayroll" wire:confirm="Pastikan semua data absensi dan komisi sudah final. Lanjutkan?" class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-indigo-50 hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    Proses Hitung Gaji (Generate)
                </button>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pembayaran</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2">
                    Rp {{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}
                </h3>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Karyawan Tergaji</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2">
                    {{ $payrolls->count() }} <span class="text-base font-normal text-slate-400">Orang</span>
                </h3>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4 text-right">Gaji Pokok</th>
                            <th class="px-6 py-4 text-right">Komisi</th>
                            <th class="px-6 py-4 text-right text-rose-500">Potongan</th>
                            <th class="px-6 py-4 text-right">Take Home Pay</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($payrolls as $payroll)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $payroll->user->name }}
                                    <div class="text-[10px] text-slate-500 font-normal mt-0.5">{{ $payroll->user->role }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-300">
                                    Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-emerald-600 font-bold">
                                    + {{ number_format($payroll->total_commission, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-rose-500 font-bold">
                                    - {{ number_format($payroll->deductions, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-black font-mono text-slate-900 dark:text-white text-lg">
                                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $payroll->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $payroll->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center flex justify-center gap-2">
                                    @if($payroll->status === 'draft')
                                        <button wire:click="markAsPaid({{ $payroll->id }})" class="p-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg transition-colors" title="Tandai Sudah Dibayar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                        <button wire:click="deletePayroll({{ $payroll->id }})" class="p-2 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-lg transition-colors" title="Hapus Draft">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Paid on {{ $payroll->pay_date ? \Carbon\Carbon::parse($payroll->pay_date)->format('d/m') : '-' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
