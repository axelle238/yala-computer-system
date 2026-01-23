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
            <p class="text-slate-500 mt-1">Supplier: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $po->supplier->name }}</span> | Tanggal: {{ $po->order_date->format('d M Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600 font-bold text-sm hover:bg-slate-50">
                &larr; Kembali
            </a>
            
            @if($po->status === 'draft')
                <button wire:click="markAsOrdered" wire:confirm="Pastikan PO sudah final. Status akan berubah menjadi Ordered?" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/30">
                    Kirim Order (Finalize)
                </button>
            @endif

            @if($po->status === 'ordered')
                <button wire:click="openReceiveModal" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Terima Barang (Receive)
                </button>
            @endif
            
            <button onclick="window.print()" class="px-4 py-2 bg-slate-800 text-white rounded-lg font-bold text-sm hover:bg-slate-900">
                Print PO
            </button>
        </div>
    </div>

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
                    @foreach($po->items as $item)
                        @php
                            $remaining = $item->quantity_ordered - $item->quantity_received;
                            $rowStatus = $item->quantity_received >= $item->quantity_ordered ? 'Selesai' : ($item->quantity_received > 0 ? 'Parsial' : 'Pending');
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

    <!-- Receiving Modal -->
    @if($showReceiveModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Form Penerimaan Barang</h3>
                <button wire:click="$set('showReceiveModal', false)" class="text-slate-400 hover:text-slate-500">&times;</button>
            </div>
            
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <p class="text-sm text-slate-500 mb-4">Masukkan jumlah fisik barang yang diterima saat ini. Stok akan otomatis bertambah.</p>
                
                <div class="space-y-4">
                    @foreach($po->items as $item)
                        @php $remaining = $item->quantity_ordered - $item->quantity_received; @endphp
                        @if($remaining > 0)
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/30 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="flex-1">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</div>
                                <div class="text-xs text-slate-500">SKU: {{ $item->product->sku }} | Sisa Order: <span class="font-bold text-rose-500">{{ $remaining }}</span></div>
                            </div>
                            <div class="w-32">
                                <input type="number" wire:model="receiveData.{{ $item->id }}" min="0" max="{{ $remaining }}" 
                                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-slate-800 text-center font-bold">
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50 flex justify-end gap-3">
                <button wire:click="$set('showReceiveModal', false)" class="px-4 py-2 text-slate-600 font-bold hover:underline">Batal</button>
                <button wire:click="processReceiving" class="px-6 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-500/20">
                    Konfirmasi Penerimaan
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
