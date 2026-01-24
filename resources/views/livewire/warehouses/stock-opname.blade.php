<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-500">Opname</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Audit dan penyesuaian stok fisik berkala.</p>
        </div>
        
        <button wire:click="toggleMode('{{ $viewMode === 'list' ? 'create' : 'list' }}')" class="px-6 py-3 {{ $viewMode === 'list' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }} text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            @if($viewMode === 'list')
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Mulai Opname Baru
            @else
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Lihat Riwayat
            @endif
        </button>
    </div>

    @if($viewMode === 'create')
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tanggal Opname</label>
                    <input type="date" wire:model="opnameDate" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Cari Produk (Scan Barcode/Nama)</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchProduct" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl pl-10" placeholder="Ketik nama atau SKU...">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        
                        @if(!empty($searchProducts))
                            <div class="absolute z-10 w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden">
                                @foreach($searchProducts as $p)
                                    <button wire:click="addProduct({{ $p->id }})" class="w-full text-left px-4 py-3 hover:bg-amber-50 dark:hover:bg-slate-700 border-b border-slate-100 last:border-0 flex justify-between items-center">
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
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700 mb-6">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 text-center w-32">Stok Sistem</th>
                            <th class="px-4 py-3 text-center w-32">Stok Fisik</th>
                            <th class="px-4 py-3 text-center w-32">Selisih</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($items as $pid => $item)
                            <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</span>
                                    <span class="text-xs text-slate-500 block">{{ $item['sku'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-mono">{{ $item['system'] }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" wire:change="updatePhysical({{ $pid }}, $event.target.value)" value="{{ $item['physical'] }}" class="w-full text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 font-bold">
                                </td>
                                <td class="px-4 py-3 text-center font-bold {{ $item['diff'] < 0 ? 'text-rose-500' : ($item['diff'] > 0 ? 'text-emerald-500' : 'text-slate-400') }}">
                                    {{ $item['diff'] > 0 ? '+' : '' }}{{ $item['diff'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" wire:model="items.{{ $pid }}.notes" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 text-xs" placeholder="Keterangan...">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-400 italic">Belum ada item yang ditambahkan. Scan atau cari produk di atas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button wire:click="save" class="px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Laporan Opname</button>
            </div>
        </div>
    @else
        <!-- List Mode -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden animate-fade-in">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4">No. Opname</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pembuat</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($opnames as $op)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">{{ $op->opname_number }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $op->opname_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold">{{ $op->creator->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $op->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $op->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($op->status === 'pending_approval' && (auth()->user()->isAdmin() || auth()->user()->isOwner()))
                                    <button wire:click="approve({{ $op->id }})" wire:confirm="Setujui Opname? Stok akan diupdate permanen." class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700">Approve</button>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat stock opname.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $opnames->links() }}
            </div>
        </div>
    @endif
</div>