<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                RMA <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-600">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pusat pengelolaan klaim garansi dan retur produk.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500 text-sm" placeholder="Cari No. RMA...">
            <div class="absolute left-3 top-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
        
        <select wire:model.live="statusFilter" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300">
            <option value="">Semua Status</option>
            <option value="requested">Permintaan Baru</option>
            <option value="approved">Disetujui (Menunggu Barang)</option>
            <option value="received">Diterima (Sedang Cek)</option>
            <option value="resolved">Selesai</option>
        </select>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No. RMA</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Tanggal Request</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($rmas as $rma)
                        <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">{{ $rma->rma_number }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $rma->customer_name }}</div>
                                <div class="text-[10px] text-slate-400">Order Ref: #{{ $rma->order_id }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $rma->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $rma->status_color }}">
                                    {{ $rma->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="openDetail({{ $rma->id }})" class="text-rose-600 hover:text-rose-800 font-bold text-xs bg-rose-50 dark:bg-rose-900/20 px-3 py-1.5 rounded-lg border border-rose-100 dark:border-rose-800 transition-all">
                                    Kelola
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data RMA.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $rmas->links() }}
        </div>
    </div>

    <!-- DETAIL MODAL -->
    @if($showDetailModal && $selectedRma)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-3xl rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">RMA #{{ $selectedRma->rma_number }}</h3>
                        <p class="text-xs text-slate-500">Status Saat Ini: {{ $selectedRma->status_label }}</p>
                    </div>
                    <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto custom-scrollbar space-y-6">
                    
                    <!-- Customer Reason -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-100 dark:border-amber-800">
                        <h4 class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase tracking-wider mb-2">Keluhan Pelanggan</h4>
                        <p class="text-sm text-slate-700 dark:text-slate-300 italic">"{{ $selectedRma->reason_description }}"</p>
                    </div>

                    <!-- Items List -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Produk Diretur</h4>
                        <div class="space-y-3">
                            @foreach($selectedRma->items as $item)
                                <div class="flex items-center gap-4 p-3 border border-slate-200 dark:border-slate-600 rounded-lg">
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</div>
                                        <div class="text-xs text-slate-500">Masalah: {{ $item->reason }} | Kondisi: {{ $item->condition }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Admin Actions Panel -->
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">Proses & Tindakan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Flow Control -->
                            <div class="space-y-3">
                                @if($selectedRma->status == 'requested')
                                    <button wire:click="updateStatus('approved')" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg transition-all">
                                        Setujui Permintaan (Approve)
                                    </button>
                                    <p class="text-[10px] text-slate-400 text-center">Pelanggan akan diminta mengirim barang.</p>
                                @endif

                                @if($selectedRma->status == 'approved')
                                    <button wire:click="updateStatus('received')" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg transition-all">
                                        Tandai Barang Diterima
                                    </button>
                                    <p class="text-[10px] text-slate-400 text-center">Konfirmasi barang fisik sudah di toko.</p>
                                @endif

                                @if(in_array($selectedRma->status, ['received', 'processing', 'vendor_process']))
                                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Solusi Akhir</label>
                                        <select wire:model.live="resolutionAction" class="w-full mb-3 rounded-lg border-slate-300 text-sm focus:ring-emerald-500">
                                            <option value="">-- Pilih Solusi --</option>
                                            <option value="replace">Ganti Baru (Stok Berkurang)</option>
                                            <option value="repair">Servis Selesai</option>
                                            <option value="refund">Refund Dana</option>
                                        </select>

                                        @if($resolutionAction === 'refund')
                                            <div class="mb-3 animate-fade-in">
                                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal Refund (IDR)</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-xs">Rp</span>
                                                    <input wire:model="refundAmount" type="number" class="w-full pl-10 pr-4 py-2 rounded-lg border-slate-300 text-sm focus:ring-emerald-500 font-mono" placeholder="0">
                                                </div>
                                                <p class="text-[10px] text-slate-400 mt-1">Pastikan saldo kasir mencukupi.</p>
                                            </div>
                                        @endif

                                        <button wire:click="resolveRma" class="w-full py-2 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-all">
                                            Selesaikan RMA (Resolve)
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Internal / Teknisi</label>
                                <textarea wire:model="adminNotes" rows="6" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm focus:ring-rose-500" placeholder="Catatan inspeksi atau nomor resi balik..."></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>