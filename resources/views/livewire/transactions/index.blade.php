<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Transaksi</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Rekapitulasi pergerakan barang masuk dan keluar.</p>
        </div>
        <div class="flex gap-3">
             <button wire:click="exportCsv" wire:loading.attr="disabled" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/40 transition-all font-semibold text-sm disabled:opacity-50">
                <span wire:loading.remove wire:target="exportCsv" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel/CSV
                </span>
                <span wire:loading wire:target="exportCsv">Generating...</span>
            </button>
            <a href="{{ route('transactions.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Catat Baru
            </a>
        </div>
    </div>

    <!-- Smart Filter Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">
        
        <!-- Search -->
        <div class="md:col-span-1">
            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pencarian</label>
            <div class="relative">
                 <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" 
                    placeholder="SKU, Produk, atau No. Ref"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- Date Range -->
        <div class="md:col-span-2 grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <input wire:model.live="dateStart" type="date" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600">
            </div>
             <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input wire:model.live="dateEnd" type="date" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600">
            </div>
        </div>

        <!-- Filter Type -->
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe Transaksi</label>
            <select wire:model.live="type" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-600">
                <option value="">Semua Tipe</option>
                <option value="in">Barang Masuk (In)</option>
                <option value="out">Barang Keluar (Out)</option>
                <option value="adjustment">Penyesuaian (Opname)</option>
                <option value="return">Retur (Pengembalian)</option>
            </select>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden relative">
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Detail Produk</th>
                        <th class="px-6 py-4 text-center">Tipe</th>
                        <th class="px-6 py-4 text-right">Qty</th>
                        <th class="px-6 py-4 text-center">Stok Akhir</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $trx->created_at->format('d M Y') }}</span>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $trx->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $trx->product->name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 font-mono">{{ $trx->product->sku }}</span>
                                    @if($trx->reference_number)
                                        <span class="text-[10px] text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100 font-mono">Ref: {{ $trx->reference_number }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $typeStyles = [
                                        'in' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Masuk'],
                                        'out' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Keluar'],
                                        'adjustment' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Opname'],
                                        'return' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'label' => 'Retur'],
                                    ];
                                    $style = $typeStyles[$trx->type] ?? $typeStyles['in'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $style['bg'] }} {{ $style['text'] }}">
                                    {{ $style['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-base {{ $trx->type === 'out' ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $trx->type === 'out' ? '-' : '+' }}{{ $trx->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-bold text-slate-700">
                                {{ $trx->remaining_stock }}
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate" title="{{ $trx->notes }}">
                                {{ $trx->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        {{ substr($trx->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <span class="text-xs">{{ $trx->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Data transaksi tidak ditemukan sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
