<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Manajemen Penggajian</h1>
            <p class="text-slate-500 dark:text-slate-400">Periode: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        </div>
        
        <div class="flex gap-2">
            <select wire:model.live="month" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 font-bold text-slate-700 dark:text-slate-200">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select wire:model.live="year" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-4 py-2 font-bold text-slate-700 dark:text-slate-200">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    @if(!$hasGenerated)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-8 text-center">
            <h3 class="text-lg font-bold text-blue-800 dark:text-blue-300 mb-2">Payroll Belum Dibuat</h3>
            <p class="text-blue-600 dark:text-blue-400 mb-6">Klik tombol di bawah untuk menghitung gaji pokok dan komisi secara otomatis untuk periode ini.</p>
            <button wire:click="generatePayroll" wire:confirm="Yakin ingin generate payroll? Komisi yang pending akan dihitung." class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-1">
                Generate Payroll Otomatis
            </button>
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-700 text-xs uppercase font-bold text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4 text-right">Gaji Pokok</th>
                            <th class="px-6 py-4 text-right">Komisi</th>
                            <th class="px-6 py-4 text-right">Potongan</th>
                            <th class="px-6 py-4 text-right">Gaji Bersih (Net)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($payrolls as $payroll)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $payroll->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $payroll->user->job_title ?? 'Staff' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($payroll->base_salary, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono text-emerald-600 font-bold">+ Rp {{ number_format($payroll->total_commission, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono text-rose-500">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono font-black text-lg text-slate-800 dark:text-white">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $payroll->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($payroll->status == 'draft')
                                    <button wire:click="markAsPaid({{ $payroll->id }})" class="text-xs bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 font-bold">
                                        Bayar
                                    </button>
                                @else
                                    <button class="text-xs bg-slate-200 text-slate-500 px-3 py-1 rounded cursor-not-allowed font-bold" disabled>
                                        Lunas
                                    </button>
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