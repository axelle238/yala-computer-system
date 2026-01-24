<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-500">Transfer</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Mutasi stok antar gudang (Inter-Warehouse).</p>
        </div>
        
        <button wire:click="toggleMode" class="px-6 py-3 {{ $viewMode === 'list' ? 'bg-cyan-600 hover:bg-cyan-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }} text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            @if($viewMode === 'list')
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Pengajuan Transfer
            @else
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Lihat Daftar Transfer
            @endif
        </button>
    </div>

    @if($viewMode === 'create')
        <!-- Form Create -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm animate-fade-in">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6 border-b border-slate-100 pb-4">Form Pengajuan Transfer</h3>
            
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Gudang Asal (Source)</label>
                        <select wire:model="source_warehouse_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500 text-sm">
                            <option value="">-- Pilih Gudang Asal --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->location }})</option>
                            @endforeach
                        </select>
                        @error('source_warehouse_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Gudang Tujuan (Destination)</label>
                        <select wire:model="destination_warehouse_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500 text-sm">
                            <option value="">-- Pilih Gudang Tujuan --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->location }})</option>
                            @endforeach
                        </select>
                        @error('destination_warehouse_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Catatan Mutasi</label>
                        <textarea wire:model="notes" rows="2" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500 text-sm" placeholder="Alasan transfer..."></textarea>
                    </div>
                </div>

                <!-- Items -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-700 dark:text-slate-300 text-sm">Daftar Barang</h4>
                        <button type="button" wire:click="addItem" class="text-xs font-bold text-cyan-600 hover:bg-cyan-50 px-3 py-2 rounded-lg transition-colors border border-cyan-200">
                            + Tambah Baris
                        </button>
                    </div>

                    @foreach($transferItems as $index => $item)
                        <div class="flex flex-col md:flex-row gap-4 items-start bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 relative group">
                            <div class="flex-1 w-full">
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Produk</label>
                                <select wire:model="transferItems.{{ $index }}.product_id" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-cyan-500">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Sisa Stok Total: {{ $product->stock_quantity }})</option>
                                    @endforeach
                                </select>
                                @error('transferItems.'.$index.'.product_id') <span class="text-rose-500 text-[10px] block mt-1">Wajib dipilih</span> @enderror
                            </div>

                            <div class="w-32">
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Qty Transfer</label>
                                <input type="number" wire:model="transferItems.{{ $index }}.qty" min="1" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-cyan-500 text-center">
                                @error('transferItems.'.$index.'.qty') <span class="text-rose-500 text-[10px] block mt-1">Min 1</span> @enderror
                            </div>

                            @if(count($transferItems) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="mt-6 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Baris">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-700">
                    <button type="submit" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-cyan-600/30 hover:-translate-y-0.5 transition-all">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- List Mode -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden animate-fade-in">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">No. Transfer</th>
                            <th class="px-6 py-4">Dari (Source)</th>
                            <th class="px-6 py-4">Ke (Destination)</th>
                            <th class="px-6 py-4">Requester</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi (Admin)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($transfers as $trf)
                            <tr class="hover:bg-cyan-50/30 dark:hover:bg-cyan-900/10 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $trf->transfer_number }}</span>
                                    <div class="text-[10px] text-slate-400 mt-1">{{ $trf->created_at->format('d M Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                    {{ $trf->sourceWarehouse->name }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                    {{ $trf->destinationWarehouse->name }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                    {{ $trf->requester->name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-rose-100 text-rose-700',
                                            'completed' => 'bg-blue-100 text-blue-700',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$trf->status] ?? 'bg-slate-100' }}">
                                        {{ $trf->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trf->status === 'pending')
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="approve({{ $trf->id }})" wire:confirm="Setujui transfer ini? Stok akan berpindah." class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors">
                                                Approve
                                            </button>
                                            <button wire:click="reject({{ $trf->id }})" wire:confirm="Tolak transfer ini?" class="px-3 py-1.5 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg hover:bg-rose-200 transition-colors">
                                                Reject
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat transfer stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $transfers->links() }}
            </div>
        </div>
    @endif
</div>
