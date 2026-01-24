<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Sales <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Report</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Analisis detail penjualan per item.</p>
        </div>
        
        <div class="flex gap-2 items-center">
            <input type="date" wire:model.live="startDate" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-bold">
            <span class="text-slate-400">-</span>
            <input type="date" wire:model.live="endDate" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-bold">
            
            <button wire:click="exportCsv" class="ml-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export CSV
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4 text-right">Qty</th>
                    <th class="px-6 py-4 text-right">Harga Satuan</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4">Pembeli</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($sales as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $item->order->order_number }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $item->order->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</span>
                            <span class="text-xs text-slate-500 block">{{ $item->product->sku }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-mono text-slate-500">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-indigo-600">
                            {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->order->guest_name ?? $item->order->user->name ?? 'Guest' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada data penjualan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $sales->links() }}
        </div>
    </div>
</div>
