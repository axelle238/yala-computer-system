<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Account <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Receivables</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen piutang penjualan (Invoice Masuk).</p>
        </div>
        
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-4">
            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase text-slate-500">Total Piutang Tertunggak</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Action Panel: Input Pembayaran -->
    @if($activeAction === 'payment')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-emerald-200 dark:border-emerald-800/30 p-6 shadow-lg shadow-emerald-900/5 animate-fade-in-up relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
            
            <div class="flex justify-between items-center mb-6 relative z-10">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </span>
                    Input Pembayaran (Invoice #{{ \App\Models\Order::find($selectedOrderId)->order_number ?? '-' }})
                </h3>
                <button wire:click="closePaymentPanel" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Jumlah Bayar</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                        <input type="number" wire:model="paymentAmount" class="w-full pl-10 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-800 dark:text-white py-2.5">
                    </div>
                    @error('paymentAmount') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Metode Pembayaran</label>
                    <select wire:model="paymentMethod" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5 text-slate-700 dark:text-slate-300">
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Catatan</label>
                    <input type="text" wire:model="paymentNote" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5 text-slate-700 dark:text-slate-300" placeholder="Opsional...">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 dark:border-slate-700/50 pt-4 relative z-10">
                <button wire:click="closePaymentPanel" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors text-sm">Batal</button>
                <button wire:click="savePayment" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Pembayaran
                </button>
            </div>
        </div>
    @endif

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-96 pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 text-sm" placeholder="Cari No. Invoice atau Customer...">
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-4">No. Invoice</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4 text-right">Total Tagihan</th>
                    <th class="px-6 py-4 text-right">Sudah Dibayar</th>
                    <th class="px-6 py-4 text-right text-rose-500">Sisa</th>
                    <th class="px-6 py-4 text-center">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($invoices as $inv)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">{{ $inv->order_number }}</td>
                        <td class="px-6 py-4 font-bold">{{ $inv->guest_name ?? $inv->user->name }}</td>
                        <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono text-emerald-600">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-rose-500">Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($inv->due_date)
                                <span class="{{ $inv->due_date < now() ? 'text-rose-500 font-bold' : 'text-slate-500' }}">
                                    {{ $inv->due_date->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="openPaymentPanel({{ $inv->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm hover:shadow-md">
                                Bayar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Tidak ada piutang tertunggak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $invoices->links() }}
        </div>
    </div>
</div>