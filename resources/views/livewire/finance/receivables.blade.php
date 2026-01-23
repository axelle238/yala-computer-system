<div class="space-y-8">
    <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase">Kartu Piutang</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Debtors -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-4 text-rose-600">Pelanggan Berhutang</h3>
            <div class="space-y-4">
                @forelse($debtors as $debtor)
                    <div class="flex justify-between items-center pb-3 border-b last:border-0">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-white">{{ $debtor->name }}</div>
                            <div class="text-xs text-slate-500">{{ $debtor->phone }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono font-bold text-rose-600">Rp {{ number_format($debtor->current_debt, 0, ',', '.') }}</div>
                            <span class="text-[10px] text-slate-400">Limit: {{ number_format($debtor->credit_limit / 1000000, 1) }}jt</span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 italic">Tidak ada piutang aktif.</p>
                @endforelse
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-4 text-slate-800">Faktur Jatuh Tempo</h3>
            <!-- Placeholder for logic linkage (since Order direct link to Customer needs proper setup) -->
            <p class="text-sm text-slate-500">Fitur ini memantau invoice B2B yang melewati TOP (Term of Payment).</p>
        </div>
    </div>
</div>
