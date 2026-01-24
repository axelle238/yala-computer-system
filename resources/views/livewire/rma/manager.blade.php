<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Warranty <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-rose-600">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen Klaim Garansi & Retur Barang.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Klaim Baru (Scan SN)
        </button>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 pb-1">
        @foreach(['pending' => 'Menunggu Proses', 'processing' => 'Sedang Dicek', 'completed' => 'Selesai'] as $key => $label)
            <button wire:click="$set('activeTab', '{{ $key }}')" 
                class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === $key ? 'text-orange-600 border-b-2 border-orange-500' : 'text-slate-500 hover:text-slate-800' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-4">RMA ID</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Barang</th>
                    <th class="px-6 py-4">Masalah</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($rmas as $rma)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4 font-mono font-bold text-orange-600">{{ $rma->rma_number }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold block text-slate-800 dark:text-white">{{ $rma->user->name ?? $rma->guest_name }}</span>
                            <span class="text-xs text-slate-500">Order: #{{ $rma->order_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($rma->items as $item)
                                <div class="mb-1">
                                    <span class="font-bold text-xs">{{ $item->product->name }}</span>
                                    <span class="block text-[10px] text-slate-400 font-mono">SN: {{ $item->serial_number }}</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-600 italic max-w-xs truncate">
                            "{{ $rma->reason }}"
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $rma->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ str_replace('_', ' ', $rma->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openProcess({{ $rma->id }})" class="text-indigo-600 font-bold hover:underline text-xs">
                                Kelola
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Tidak ada data RMA.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $rmas->links() }}
        </div>
    </div>

    <!-- Create Modal (Scan SN) -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Cek Serial Number</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex gap-2">
                        <input wire:model="lookupSn" type="text" class="flex-1 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl" placeholder="Scan Barcode / Ketik SN...">
                        <button wire:click="lookupWarranty" class="px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold">Cek</button>
                    </div>
                    @error('lookupSn') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror

                    @if($lookupResult)
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800">
                            <p class="font-bold text-emerald-800 dark:text-emerald-300">{{ $lookupResult->product->name }}</p>
                            <p class="text-xs text-emerald-600 mt-1">Status: {{ strtoupper($lookupResult->status) }}</p>
                            
                            <div class="mt-4 space-y-2">
                                <input wire:model="newRmaContact" type="text" class="w-full text-xs px-3 py-2 rounded-lg border border-emerald-200" placeholder="Nama Pelanggan / Kontak">
                                <textarea wire:model="newRmaReason" class="w-full text-xs px-3 py-2 rounded-lg border border-emerald-200" placeholder="Deskripsi Kerusakan..."></textarea>
                                <button wire:click="createRma" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs">Buat Tiket RMA</button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-900 text-right">
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-500 font-bold text-xs">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Process Modal -->
    @if($showProcessModal && $selectedRma)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full flex flex-col h-[80vh] border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-orange-50 dark:bg-orange-900/20">
                    <h3 class="font-bold text-lg text-orange-800 dark:text-orange-300">Proses RMA: {{ $selectedRma->rma_number }}</h3>
                    <button wire:click="$set('showProcessModal', false)" class="text-slate-400 hover:text-rose-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Resolution Type -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tindakan Penyelesaian</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button wire:click="$set('actionType', 'replacement')" class="px-4 py-3 rounded-xl border-2 font-bold text-sm {{ $actionType === 'replacement' ? 'border-orange-500 text-orange-600 bg-orange-50' : 'border-slate-200 text-slate-500' }}">Tukar Unit</button>
                            <button wire:click="$set('actionType', 'refund')" class="px-4 py-3 rounded-xl border-2 font-bold text-sm {{ $actionType === 'refund' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-slate-200 text-slate-500' }}">Refund Dana</button>
                            <button wire:click="$set('actionType', 'reject')" class="px-4 py-3 rounded-xl border-2 font-bold text-sm {{ $actionType === 'reject' ? 'border-rose-500 text-rose-600 bg-rose-50' : 'border-slate-200 text-slate-500' }}">Tolak Klaim</button>
                        </div>
                    </div>

                    @if($actionType === 'replacement')
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-600">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white mb-2">Input Serial Number Pengganti</h4>
                            @foreach($selectedRma->items as $item)
                                <div class="mb-3">
                                    <p class="text-xs text-slate-500 mb-1">Barang Rusak: {{ $item->product->name }} ({{ $item->serial_number }})</p>
                                    <input type="text" wire:model="replacementItems.{{ $item->id }}.sn" class="w-full px-3 py-2 border rounded-lg text-sm font-mono font-bold" placeholder="Scan SN Baru di sini...">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($actionType === 'refund')
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nominal Refund</label>
                            <input type="number" wire:model="refundAmount" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl font-bold">
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Catatan Admin</label>
                        <textarea wire:model="adminNotes" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm" placeholder="Internal notes..."></textarea>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                    <button wire:click="processRma" class="px-8 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan & Proses</button>
                </div>
            </div>
        </div>
    @endif
</div>
