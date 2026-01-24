<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Payroll Manager</h1>
        <div class="flex gap-4">
            <input type="month" wire:model.live="period" class="rounded-lg border-gray-300">
            <button wire:click="generatePayroll" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg">
                Generate Payroll {{ \Carbon\Carbon::parse($period)->format('F Y') }}
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Pegawai</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4 text-right">Gaji Pokok</th>
                    <th class="px-6 py-4 text-right">Komisi</th>
                    <th class="px-6 py-4 text-right">Take Home Pay</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payrolls as $payroll)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $payroll->user->name }}</td>
                        <td class="px-6 py-4">{{ $payroll->period_month }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-emerald-600 font-bold">+ Rp {{ number_format($payroll->total_commission, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-black text-indigo-700 text-lg">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($payroll->status === 'draft')
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs font-bold uppercase">Draft</span>
                            @elseif($payroll->status === 'approved')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase">Approved</span>
                            @elseif($payroll->status === 'paid')
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-bold uppercase">PAID</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            @if($payroll->status === 'draft')
                                <button wire:click="approve({{ $payroll->id }})" class="text-blue-600 hover:underline font-bold text-xs">Approve</button>
                            @elseif($payroll->status === 'approved')
                                <button wire:click="markPaid({{ $payroll->id }})" class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 text-xs font-bold">Pay Now</button>
                            @endif
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada data payroll untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $payrolls->links() }}</div>
    </div>
</div>