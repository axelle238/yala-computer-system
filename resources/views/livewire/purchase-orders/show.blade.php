<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech">{{ $po->po_number }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide 
                    {{ $po->status == 'received' ? 'bg-emerald-100 text-emerald-700' : 
                      ($po->status == 'ordered' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                    {{ $po->status }}
                </span>
            </div>
            <p class="text-slate-500 mt-1">Supplier: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $po->pemasok->name }}</span> | Tanggal: {{ $po->order_date->format('d M Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pesanan-pembelian.indeks') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600 font-bold text-sm hover:bg-slate-50">
                &larr; Kembali
            </a>
            
            @if($po->status === 'draft')
                <button wire:click="markAsOrdered" wire:confirm="Pastikan PO sudah final. Status akan berubah menjadi Ordered?" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30">
                    Kirim Order (Finalize)
                </button>
            @endif

            @if($po->status === 'ordered')
                <button wire:click="openReceivePanel" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Terima Barang (Receive)
                </button>
            @endif
            
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-bold text-sm hover:bg-slate-900">
                Print PO
            </button>
        </div>
    </div>

    <!-- Receiving Action Panel -->
    @if($activeAction === 'receive')
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-emerald-200 dark:border-emerald-800/30 overflow-hidden animate-fade-in-up mb-6">
            <div class="p-6 border-b border-emerald-100 dark:border-emerald-800/20 bg-emerald-50/50 dark:bg-emerald-900/10 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </span>
                        Form Penerimaan Barang
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 ml-10">Masukkan jumlah fisik barang yang diterima saat ini. Stok akan otomatis bertambah.</p>
                </div>
                <button wire:click="closeReceivePanel" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($po->item as $item)
                        @php $remaining = $item->quantity_ordered - $item->quantity_received; @endphp
                        @if($remaining > 0)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500/30 transition-all">
                            <div class="flex-1 mr-4">
                                <div class="font-bold text-slate-800 dark:text-white line-clamp-1" title="{{ $item->product->name }}">{{ $item->product->name }}</div>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                    <span class="font-mono bg-slate-200 dark:bg-slate-600 px-1.5 py-0.5 rounded text-[10px]">{{ $item->product->sku }}</span>
                                    <span>Sisa: <span class="font-bold text-rose-500">{{ $remaining }}</span></span>
                                </div>
                            </div>
                            <div class="w-24">
                                <input type="number" wire:model="receiveData.{{ $item->id }}" min="0" max="{{ $remaining }}" 
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-slate-800 text-center font-bold text-lg">
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/20 flex justify-end gap-3">
                <button wire:click="closeReceivePanel" class="px-5 py-2.5 text-slate-600 font-bold hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg transition-colors text-sm">Batal</button>
                <button wire:click="processReceiving" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all transform active:scale-95 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Konfirmasi Penerimaan
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/50">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Daftar Barang Pesanan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-700 text-xs uppercase font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-right">Harga Beli</th>
                        <th class="px-6 py-4 text-center">Qty Pesan</th>
                        <th class="px-6 py-4 text-center">Qty Diterima</th>
                        <th class="px-6 py-4 text-center">Sisa (Backorder)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($po->item as $item)
                        @php
                            $remaining = $item->quantity_ordered - $item->quantity_received;
                            $rowStatus = $item->quantity_received >= $item->quantity_ordered ? 'Selesai' : ($item->quantity_received > 0 ? 'Parsial' : 'Menunggu');
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</span>
                                <div class="text-xs text-slate-400">{{ $item->product->sku }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($item->buy_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center font-bold">{{ $item->quantity_ordered }}</td>
                            <td class="px-6 py-4 text-center font-bold text-emerald-600">{{ $item->quantity_received }}</td>
                            <td class="px-6 py-4 text-center font-bold {{ $remaining > 0 ? 'text-rose-500' : 'text-slate-300' }}">{{ $remaining }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $rowStatus == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $rowStatus }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-700 font-bold">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-right text-slate-500 uppercase tracking-wider">Total Estimasi</td>
                        <td class="px-6 py-4 text-right text-lg text-slate-800 dark:text-white">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Notes & Footer -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
            <h4 class="font-bold text-sm text-slate-500 uppercase mb-2">Catatan</h4>
            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $po->notes ?? '-' }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700">
            <h4 class="font-bold text-sm text-slate-500 uppercase mb-2">Informasi Internal</h4>
            <ul class="text-sm space-y-2">
                <li class="flex justify-between">
                    <span class="text-slate-500">Dibuat Oleh:</span>
                    <span class="font-medium">{{ $po->creator->name ?? 'System' }}</span>
                </li>
                <li class="flex justify-between">
                    <span class="text-slate-500">Dibuat Tanggal:</span>
                    <span class="font-medium">{{ $po->created_at->format('d M Y H:i') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
