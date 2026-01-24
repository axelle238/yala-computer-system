<div class="max-w-7xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Goods <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Receive</span>
            </h2>
            <p class="text-slate-500 text-sm">Input penerimaan barang dari Supplier (GRN) & Scan Serial Number.</p>
        </div>
        <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-bold hover:bg-slate-200 transition">
            Kembali ke List PO
        </a>
    </div>

    <!-- Main Form Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Header Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Informasi Dokumen</h3>
                
                <div class="space-y-4">
                    <!-- PO Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pilih Purchase Order</label>
                        <select wire:model.live="purchaseOrderId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm font-bold focus:ring-blue-500">
                            <option value="">-- Pilih PO --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }} - {{ $po->supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($selectedPo)
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Supplier:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $selectedPo->supplier->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tgl PO:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $selectedPo->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- DO Number -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">No. Surat Jalan (DO)</label>
                        <input type="text" wire:model="doNumber" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500" placeholder="Contoh: DO-12345">
                        @error('doNumber') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tanggal Terima</label>
                        <input type="date" wire:model="receivedDate" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500">
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Catatan Penerimaan</label>
                        <textarea wire:model="notes" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-blue-500" placeholder="Kondisi barang, driver, dll..."></textarea>
                    </div>
                </div>
            </div>

            <button wire:click="save" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Finalisasi & Masuk Stok
            </button>
        </div>

        <!-- Right Column: Items List -->
        <div class="lg:col-span-2 space-y-4">
            @if(empty($items))
                <div class="bg-white dark:bg-slate-800 p-12 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h3 class="font-bold text-slate-700 dark:text-white">Belum ada PO dipilih</h3>
                    <p class="text-slate-500 text-sm mt-1">Silakan pilih Purchase Order di panel sebelah kiri untuk memuat item.</p>
                </div>
            @else
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-slate-800 dark:text-white">Daftar Barang ({{ count($items) }})</h3>
                </div>

                @foreach($items as $pid => $item)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:border-blue-300 transition-all group">
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <!-- Product Info -->
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</h4>
                                    <span class="text-xs bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500 font-mono">{{ $item['sku'] }}</span>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-2 w-full max-w-md">
                                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                                        <span>Progress: {{ $item['prev_received'] }} / {{ $item['ordered'] }} diterima</span>
                                        <span>Sisa: {{ $item['ordered'] - $item['prev_received'] }}</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                        @php $pct = ($item['prev_received'] / $item['ordered']) * 100; @endphp
                                        <div class="h-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inputs -->
                            <div class="flex flex-col items-end gap-2 w-full md:w-auto">
                                <div class="flex items-center gap-2">
                                    <label class="text-xs font-bold uppercase text-slate-500">Terima:</label>
                                    <input type="number" 
                                           wire:change="updateQty({{ $pid }}, $event.target.value)" 
                                           value="{{ $item['receiving_now'] }}" 
                                           class="w-24 text-center font-mono font-bold bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-2 py-1 focus:ring-blue-500">
                                </div>

                                <button wire:click="openSerialModal({{ $pid }})" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M12 13h.01M12 21h0" /></svg>
                                    @if(empty($item['serials']))
                                        Input Serial Number
                                    @else
                                        {{ count($item['serials']) }} SN Terinput
                                    @endif
                                </button>
                                
                                @if(count($item['serials']) > 0 && count($item['serials']) != $item['receiving_now'])
                                    <span class="text-[10px] text-rose-500 font-bold animate-pulse">Jumlah SN tidak sesuai!</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Serial Number Modal -->
    @if($showSerialModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white">Input Serial Number</h3>
                    <button wire:click="$set('showSerialModal', false)" class="text-slate-400 hover:text-rose-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-4">
                    <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            Produk: <strong>{{ $items[$currentSerialProductId]['name'] ?? '-' }}</strong><br>
                            Qty Terima: <strong>{{ $items[$currentSerialProductId]['receiving_now'] ?? 0 }}</strong>
                        </p>
                    </div>

                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Scan/Input Serial Number (Satu per baris)</label>
                    <textarea wire:model="serialInput" rows="10" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 font-mono text-sm focus:ring-blue-500" placeholder="Scan barcode di sini...&#10;SN001&#10;SN002"></textarea>
                    
                    <div class="mt-2 text-right text-xs text-slate-400">
                        Jumlah baris: <span class="font-bold text-slate-600 dark:text-slate-300" x-text="$wire.serialInput.split('\n').filter(l => l.trim() !== '').length">0</span>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-2">
                    <button wire:click="saveSerials" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition-all">Simpan SN</button>
                </div>
            </div>
        </div>
    @endif
</div>
