<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Detail <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">Penawaran</span>
            </h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-slate-500 dark:text-slate-400 font-mono font-bold">{{ $quote->quote_number }}</span>
                @php
                    $statusColors = [
                        'draft' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                        'sent' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'accepted' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                        'converted' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                    ];
                @endphp
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$quote->status] }}">
                    {{ $quote->status }}
                </span>
            </div>
        </div>
        <a href="{{ route('quotations.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-4 tracking-wider">Informasi Customer</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Nama Customer</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $quote->customer->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Sales Rep</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $quote->sales->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Berlaku Sampai</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $quote->valid_until->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-4 tracking-wider">Catatan</h3>
            <p class="text-sm text-slate-700 dark:text-slate-300 italic">
                "{{ $quote->notes ?: 'Tidak ada catatan.' }}"
            </p>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white">Item Penawaran</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4 text-center">Qty</th>
                    <th class="px-6 py-4 text-right">Harga Satuan</th>
                    <th class="px-6 py-4 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($quote->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            {{ $item->product->name }}
                        </td>
                        <td class="px-6 py-4 text-center font-mono">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-300">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold font-mono text-slate-900 dark:text-white">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-slate-50 dark:bg-slate-900/50">
                    <td colspan="3" class="px-6 py-4 text-right font-bold uppercase text-slate-500">Total</td>
                    <td class="px-6 py-4 text-right font-black text-xl text-orange-600">
                        Rp {{ number_format($quote->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Actions -->
    <div class="flex flex-col md:flex-row gap-4 justify-end pt-4 border-t border-slate-200 dark:border-slate-700">
        @if($quote->status === 'draft')
            <button wire:click="markAsSent" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all">
                Tandai Terkirim (Sent)
            </button>
        @endif

        @if($quote->status === 'sent')
            <button wire:click="markAsRejected" class="px-6 py-3 bg-rose-100 text-rose-700 hover:bg-rose-200 font-bold rounded-xl transition-all">
                Customer Menolak
            </button>
            <button wire:click="markAsAccepted" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all">
                Customer Setuju (Accepted)
            </button>
        @endif

        @if($quote->status === 'accepted')
            <button wire:click="convertToOrder" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-orange-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Konversi ke Sales Order
            </button>
        @endif
    </div>
</div>
