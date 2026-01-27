<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <!-- Header dan Kontrol Utama -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">{{ $po->po_number }}</h1>
                @php
                    $statusWarna = [
                        'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                        'ordered' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'received' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                    ];
                    $statusLabel = [
                        'draft' => 'DRAF',
                        'ordered' => 'DIPESAN',
                        'received' => 'DITERIMA',
                        'cancelled' => 'DIBATALKAN',
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest {{ $statusWarna[$po->status] ?? 'bg-slate-100' }}">
                    {{ $statusLabel[$po->status] ?? $po->status }}
                </span>
            </div>
            <p class="text-slate-500 mt-1 text-sm font-medium uppercase tracking-wide">
                <span class="text-indigo-600 font-bold">{{ $po->pemasok->name }}</span> 
                <span class="mx-2 text-slate-300">|</span> 
                {{ $po->order_date->translatedFormat('d F Y') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pesanan-pembelian.indeks') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-xs uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                &larr; Kembali
            </a>
            
            @if($po->status === 'draft')
                <button wire:click="tandaiDipesan" wire:confirm="Pastikan data pesanan sudah final. Lanjutkan ke status DIPESAN?" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95">
                    Finalisasi Pesanan
                </button>
            @endif

            @if($po->status === 'ordered')
                <button wire:click="bukaPanelTerima" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 flex items-center gap-2 transition-all transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Terima Barang
                </button>
            @endif
            
            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-black text-xs uppercase tracking-widest hover:shadow-xl transition-all shadow-md">
                Cetak Dokumen PO
            </button>
        </div>
    </div>

    <!-- Panel Aksi Penerimaan Barang -->
    @if($aksiAktif === 'terima')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border-2 border-emerald-500/20 overflow-hidden animate-fade-in-up mb-6 relative">
            <div class="p-6 border-b border-emerald-100 dark:border-emerald-800/20 bg-emerald-50/50 dark:bg-emerald-900/10 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2 uppercase tracking-tight">
                        <span class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </span>
                        Konfirmasi Penerimaan Fisik
                    </h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase mt-1 ml-10 tracking-widest">Input stok fisik yang masuk ke gudang saat ini.</p>
                </div>
                <button wire:click="tutupPanelTerima" class="text-slate-400 hover:text-rose-500 transition-colors p-2 bg-white dark:bg-slate-900 rounded-full shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-slate-50/30 dark:bg-slate-900/20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($po->item as $item)
                        @php $sisa = $item->quantity_ordered - $item->quantity_received; @endphp
                        @if($sisa > 0)
                        <div class="bg-white dark:bg-slate-800 p-5 rounded-[1.5rem] border border-slate-200 dark:border-slate-700 hover:border-emerald-500/50 transition-all shadow-sm">
                            <div class="font-black text-slate-800 dark:text-white text-sm line-clamp-1 mb-2 uppercase tracking-tight">{{ $item->product->name }}</div>
                            <div class="flex justify-between items-end">
                                <div class="space-y-1">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Sisa Pesanan</span>
                                    <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-lg text-sm font-black">{{ $sisa }} Unit</span>
                                </div>
                                <div class="w-24">
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Terima Baru</label>
                                    <input type="number" wire:model="dataPenerimaan.{{ $item->id }}" min="0" max="{{ $sisa }}" 
                                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 text-center font-black text-xl">
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex justify-end gap-3">
                <button wire:click="tutupPanelTerima" class="px-6 py-3 text-slate-500 font-black uppercase tracking-widest text-[10px] hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-all">Batalkan</button>
                <button wire:click="prosesPenerimaan" class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-emerald-700 shadow-xl shadow-emerald-500/30 transition-all transform active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Proses Masuk Stok
                </button>
            </div>
        </div>
    @endif

    <!-- Konten Utama: Tabel Item -->
    <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden relative border-b-8 border-b-indigo-600">
        <div class="p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="font-black text-lg text-slate-800 dark:text-white uppercase tracking-tight">Rincian Daftar Barang</h3>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Data Real-time</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 uppercase font-black text-[10px] tracking-widest border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-5 text-left">Info Produk</th>
                        <th class="px-8 py-5 text-right">Harga Beli (Satuan)</th>
                        <th class="px-8 py-5 text-center">Jumlah Pesan</th>
                        <th class="px-8 py-5 text-center">Jumlah Diterima</th>
                        <th class="px-8 py-5 text-center">Sisa Unit</th>
                        <th class="px-8 py-5 text-center">Status Item</th>
                        <th class="px-8 py-5 text-right">Subtotal Modal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($po->item as $item)
                        @php
                            $sisa = $item->quantity_ordered - $item->quantity_received;
                            $labelStatus = $item->quantity_received >= $item->quantity_ordered ? 'Lengkap' : ($item->quantity_received > 0 ? 'Parsial' : 'Menunggu');
                            $warnaStatus = $item->quantity_received >= $item->quantity_ordered ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200';
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $item->product->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">{{ $item->product->sku }}</div>
                            </td>
                            <td class="px-8 py-5 text-right font-mono font-bold text-slate-600 dark:text-slate-400 italic">Rp {{ number_format($item->buy_price, 0, ',', '.') }}</td>
                            <td class="px-8 py-5 text-center font-black text-slate-800 dark:text-white text-base">{{ $item->quantity_ordered }}</td>
                            <td class="px-8 py-5 text-center font-black text-emerald-600 dark:text-emerald-400 text-base">{{ $item->quantity_received }}</td>
                            <td class="px-8 py-5 text-center font-black {{ $sisa > 0 ? 'text-rose-500' : 'text-slate-200' }} text-base">{{ $sisa }}</td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase tracking-wider {{ $warnaStatus }}">
                                    {{ $labelStatus }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right font-mono font-black text-slate-900 dark:text-white text-base">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-900/80 font-black">
                    <tr>
                        <td colspan="6" class="px-8 py-6 text-right text-slate-500 uppercase tracking-[0.2em] text-xs">Total Estimasi Nilai Pesanan</td>
                        <td class="px-8 py-6 text-right text-2xl text-indigo-600 dark:text-indigo-400 font-mono tracking-tighter">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Catatan dan Informasi Internal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-32 h-32 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h4 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Catatan Pesanan</h4>
            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line text-sm leading-relaxed italic">"{{ $po->notes ?? 'Tidak ada catatan tambahan.' }}"</p>
        </div>
        
        <div class="bg-white dark:bg-slate-800 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-32 h-32 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h4 class="font-black text-xs text-slate-400 uppercase tracking-[0.2em] mb-4">Jejak Audit Internal</h4>
            <ul class="space-y-4">
                <li class="flex items-center justify-between border-b border-slate-50 dark:border-slate-700 pb-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Otoritas Pembuat</span>
                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">{{ $po->pembuat->name ?? 'SISTEM' }}</span>
                </li>
                <li class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu Terbit Sistem</span>
                    <span class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">{{ $po->created_at->translatedFormat('d M Y H:i:s') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>