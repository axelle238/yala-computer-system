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
                                <button wire:click="openDetailPanel({{ $rma->id }})" class="text-rose-600 hover:text-rose-800 font-bold text-xs bg-rose-50 dark:bg-rose-900/20 px-3 py-1.5 rounded-lg border border-rose-100 dark:border-rose-800 transition-all">
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

    <!-- DETAIL PANEL (Inline) -->
    @if($activeAction === 'detail' && $selectedRma)
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            
            <!-- Panel Header -->
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-2xl text-slate-800 dark:text-white tracking-tight">RMA #{{ $selectedRma->rma_number }}</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Status Saat Ini: <span class="uppercase tracking-wider font-bold {{ $selectedRma->status_color }}">{{ $selectedRma->status_label }}</span></p>
                </div>
                <button wire:click="closeDetailPanel" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Panel Body -->
            <div class="p-8 space-y-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Info & Items -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Customer Reason -->
                        <div class="bg-amber-50 dark:bg-amber-900/10 p-6 rounded-2xl border border-amber-100 dark:border-amber-800/30">
                            <h4 class="text-xs font-bold text-amber-800 dark:text-amber-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Keluhan Pelanggan
                            </h4>
                            <p class="text-base text-slate-700 dark:text-slate-300 italic leading-relaxed">"{{ $selectedRma->reason_description }}"</p>
                        </div>

                        <!-- Items List -->
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide mb-4">Produk Diretur</h4>
                            <div class="space-y-3">
                                @foreach($selectedRma->items as $item)
                                    <div class="flex items-center gap-5 p-4 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-900/20">
                                        <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-lg flex items-center justify-center border border-slate-100 dark:border-slate-700 shadow-sm">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white text-lg">{{ $item->product->name }}</div>
                                            <div class="text-sm text-slate-500 mt-1 flex gap-3">
                                                <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-xs font-bold">{{ $item->reason }}</span>
                                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">Kondisi: {{ $item->condition }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right: Actions -->
                    <div class="lg:col-span-1 border-l border-slate-100 dark:border-slate-700 pl-8 space-y-6">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Tindakan Admin</h4>
                        
                        <div class="space-y-4">
                            @if($selectedRma->status == 'requested')
                                <button wire:click="updateStatus('approved')" class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui Permintaan
                                </button>
                                <p class="text-xs text-slate-400 text-center leading-relaxed">Pelanggan akan menerima notifikasi untuk mengirim barang ke toko.</p>
                            @endif

                            @if($selectedRma->status == 'approved')
                                <button wire:click="updateStatus('received')" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    Terima Barang
                                </button>
                                <p class="text-xs text-slate-400 text-center leading-relaxed">Konfirmasi bahwa barang fisik telah diterima di toko.</p>
                            @endif

                            @if(in_array($selectedRma->status, ['received', 'processing', 'vendor_process']))
                                <div class="bg-slate-50 dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Solusi Akhir</label>
                                    <select wire:model.live="resolutionAction" class="w-full mb-4 rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm focus:ring-emerald-500 font-medium">
                                        <option value="">-- Pilih Solusi --</option>
                                        <option value="replace">Ganti Baru</option>
                                        <option value="repair">Servis Selesai</option>
                                        <option value="refund">Refund Dana</option>
                                    </select>

                                    @if($resolutionAction === 'refund')
                                        <div class="mb-4 animate-fade-in">
                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nominal Refund</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-3 text-slate-400 font-bold text-sm">Rp</span>
                                                <input wire:model="refundAmount" type="number" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-300 dark:border-slate-600 dark:bg-slate-800 text-sm focus:ring-emerald-500 font-bold font-mono" placeholder="0">
                                            </div>
                                        </div>
                                    @endif

                                    <button wire:click="resolveRma" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition-all">
                                        Selesaikan RMA
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Notes Input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Internal</label>
                            <textarea wire:model="adminNotes" rows="4" class="w-full rounded-xl border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-rose-500 p-3" placeholder="Catatan hasil inspeksi atau nomor resi pengiriman balik..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>