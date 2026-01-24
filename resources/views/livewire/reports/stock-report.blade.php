<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
            Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Card</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Audit jejak pergerakan barang (Mutasi Stok).</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row gap-6 items-end">
        <div class="flex-1 w-full relative">
            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Cari Produk</label>
            <input type="text" wire:model.live.debounce.300ms="searchProduct" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 font-bold" placeholder="Ketik Nama / SKU...">
            
            @if(!empty($searchResultProducts) && count($searchResultProducts) > 0)
                <div class="absolute z-10 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden">
                    @foreach($searchResultProducts as $p)
                        <button wire:click="selectProduct({{ $p->id }})" class="w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-100 last:border-0 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-sm text-slate-800 dark:text-white">{{ $p->name }}</span>
                                <span class="text-xs text-slate-500 block">{{ $p->sku }}</span>
                            </div>
                            <span class="text-xs font-mono bg-slate-100 dark:bg-slate-900 px-2 py-1 rounded">Stok: {{ $p->stock_quantity }}</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex gap-2 items-center">
            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Sampai</label>
                <input type="date" wire:model.live="endDate" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm font-bold">
            </div>
        </div>
    </div>

    <!-- Data Table -->
    @if($selectedProductId)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Transaksi: {{ $searchProduct }}</h3>
            </div>
            
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Tipe Mutasi</th>
                        <th class="px-6 py-4">Ref. Dokumen</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-center">Saldo Akhir</th>
                        <th class="px-6 py-4">User</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4 font-mono text-slate-600 dark:text-slate-400">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badges = [
                                        'in' => 'bg-emerald-100 text-emerald-700',
                                        'out' => 'bg-rose-100 text-rose-700',
                                        'adjustment' => 'bg-amber-100 text-amber-700',
                                        'return' => 'bg-blue-100 text-blue-700',
                                    ];
                                    $labels = [
                                        'in' => 'Masuk (Beli)',
                                        'out' => 'Keluar (Jual)',
                                        'adjustment' => 'Penyesuaian',
                                        'return' => 'Retur',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $badges[$trx->type] ?? 'bg-slate-100' }}">
                                    {{ $labels[$trx->type] ?? $trx->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">
                                {{ $trx->reference_number }}
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $trx->notes }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-bold {{ in_array($trx->type, ['out']) ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ in_array($trx->type, ['out']) ? '-' : '+' }}{{ abs($trx->quantity) }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-slate-500">
                                {{ $trx->remaining_stock }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $trx->user->name ?? 'System' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
        </div>
    @else
        <div class="py-20 text-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <h3 class="text-lg font-bold text-slate-500">Pilih Produk Terlebih Dahulu</h3>
            <p class="text-sm text-slate-400">Gunakan kolom pencarian di atas untuk melihat kartu stok.</p>
        </div>
    @endif
</div>
