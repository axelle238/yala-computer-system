<div class="space-y-6" x-data>

    <!-- Header & Tombol Aksi -->
    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Stok <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-green-600">Opname</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Penyesuaian stok fisik dan sistem secara periodik.</p>
        </div>
        
        @if($activeOpname)
            <div class="flex gap-2">
                <button wire:click="cancelSession" class="px-4 py-2 text-sm font-bold bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg hover:bg-slate-50">Batalkan Sesi</button>
                <button wire:click="finalizeOpname" class="px-6 py-2 text-sm font-bold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 shadow-lg">Selesaikan & Sesuaikan Stok</button>
            </div>
        @else
            <button wire:click="startSession" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Mulai Sesi Opname Baru
            </button>
        @endif
    </div>

    @if($activeOpname)
        <!-- Sesi Aktif -->
        <div class="space-y-4">
            <!-- Filter & Search -->
            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 text-sm" placeholder="Cari Nama atau SKU Produk...">
            </div>

            <!-- Tabel Data Opname -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4 text-center">Stok Sistem</th>
                                <th class="px-6 py-4 text-center w-48">Stok Fisik</th>
                                <th class="px-6 py-4 text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0 hover:bg-slate-50/50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-3">
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $item->product->sku }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-center font-mono font-bold text-slate-600 dark:text-slate-300 text-lg">{{ $item->system_stock }}</td>
                                    <td class="px-6 py-3">
                                        <input type="number"
                                               wire:model.blur="tempStock.{{ $item->product_id }}"
                                               wire:change="updatePhysicalStock({{ $item->id }}, $event.target.value)"
                                               value="{{ $item->physical_stock }}"
                                               class="w-full text-center font-mono font-bold text-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-emerald-500 focus:border-emerald-500"
                                               x-ref="item_{{ $item->id }}">
                                    </td>
                                    <td class="px-6 py-3 text-center font-mono font-bold text-lg {{ $item->variance > 0 ? 'text-emerald-500' : ($item->variance < 0 ? 'text-rose-500' : 'text-slate-400') }}">
                                        {{ $item->variance ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-slate-400">Tidak ada produk yang cocok dengan pencarian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($items)
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $items->links() }}
                </div>
                @endif
            </div>
        </div>
    @else
        <!-- Belum Ada Sesi -->
        <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Sesi Stok Opname Belum Aktif</h3>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Klik tombol "Mulai Sesi Opname Baru" untuk memulai perhitungan stok fisik.</p>
        </div>
    @endif
</div>