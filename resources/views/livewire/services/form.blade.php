<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-6 overflow-hidden animate-fade-in-up">
    <!-- Left Panel: Ticket Info -->
    <div class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden relative">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/50">
            <h3 class="font-bold font-tech text-lg text-slate-900 dark:text-white">Detail Tiket</h3>
            <p class="text-xs text-slate-500 font-mono">{{ $ticket_number }}</p>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Pelanggan</label>
                    <input wire:model="customer_name" type="text" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm font-bold" placeholder="Nama Pelanggan">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Kontak (WhatsApp)</label>
                    <input wire:model="customer_phone" type="text" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm font-bold" placeholder="08...">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Perangkat</label>
                    <input wire:model="device_name" type="text" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm font-bold" placeholder="Contoh: Laptop Asus ROG">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Keluhan / Masalah</label>
                    <textarea wire:model="problem_description" rows="3" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm"></textarea>
                </div>
                
                <div class="pt-4 border-t border-slate-100 dark:border-slate-700"></div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Status Pengerjaan</label>
                    <select wire:model="status" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm font-bold">
                        <option value="pending">Menunggu Antrian</option>
                        <option value="diagnosing">Sedang Dicek (Diagnosa)</option>
                        <option value="waiting_part">Menunggu Sparepart</option>
                        <option value="repairing">Sedang Diperbaiki</option>
                        <option value="ready">Selesai (Siap Diambil)</option>
                        <option value="picked_up">Sudah Diambil</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Teknisi (PIC)</label>
                    <select wire:model="technician_id" class="w-full mt-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-sm">
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel: Workbench -->
    <div class="flex-1 flex flex-col h-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden relative">
        <!-- Tabs -->
        <div class="flex border-b border-slate-100 dark:border-slate-700">
            <button wire:click="$set('activeTab', 'diagnosis')" class="flex-1 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors {{ $activeTab === 'diagnosis' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">Diagnosa</button>
            <button wire:click="$set('activeTab', 'parts')" class="flex-1 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors {{ $activeTab === 'parts' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">Sparepart</button>
            <button wire:click="$set('activeTab', 'history')" class="flex-1 py-4 text-sm font-bold uppercase tracking-wider border-b-2 transition-colors {{ $activeTab === 'history' ? 'border-cyan-500 text-cyan-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">Riwayat</button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">
            
            <!-- Tab: Diagnosis -->
            <div x-show="$wire.activeTab === 'diagnosis'" class="space-y-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
                    <h4 class="font-bold text-blue-700 dark:text-blue-400 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        Catatan Teknisi (Internal)
                    </h4>
                    <textarea wire:model="technician_notes" rows="8" class="w-full bg-white dark:bg-slate-900 border-none rounded-lg focus:ring-0 text-sm" placeholder="Catat hasil diagnosa, langkah perbaikan, dan progress di sini..."></textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase">Estimasi Biaya Awal</label>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-slate-400 font-bold">Rp</span>
                        <input wire:model="estimated_cost" type="number" class="flex-1 px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 text-lg font-mono font-bold">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">*Disampaikan ke customer sebelum pengerjaan.</p>
                </div>
            </div>

            <!-- Tab: Parts & Billing -->
            <div x-show="$wire.activeTab === 'parts'" class="space-y-6">
                <!-- Add Part Search -->
                <div class="relative">
                    <input wire:model.live.debounce.300ms="productSearch" type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500" placeholder="Cari Sparepart / Produk...">
                    <div class="absolute left-3 top-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>

                    @if(!empty($products))
                        <div class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 z-20 overflow-hidden">
                            @foreach($products as $product)
                                <button wire:click="addPart({{ $product->id }})" class="w-full text-left px-4 py-3 hover:bg-cyan-50 dark:hover:bg-slate-700 flex justify-between items-center group">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $product->name }}</p>
                                        <p class="text-xs text-slate-500 group-hover:text-cyan-600">Stok: {{ $product->stock_quantity }}</p>
                                    </div>
                                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex justify-end">
                    <button wire:click="addCustomItem" class="text-xs font-bold text-cyan-600 hover:underline">+ Tambah Item Manual (Jasa Lain)</button>
                </div>

                <!-- Parts List Table -->
                <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-[10px]">
                            <tr>
                                <th class="px-4 py-3">Item</th>
                                <th class="px-4 py-3 w-20 text-center">Qty</th>
                                <th class="px-4 py-3 w-32 text-right">Harga</th>
                                <th class="px-4 py-3 w-32 text-right">Subtotal</th>
                                <th class="px-4 py-3 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($parts as $index => $part)
                                <tr class="bg-white dark:bg-slate-800">
                                    <td class="px-4 py-3 space-y-2">
                                        <input wire:model="parts.{{ $index }}.name" type="text" class="w-full bg-transparent border-none p-0 font-bold text-slate-700 dark:text-slate-200 focus:ring-0 text-sm" {{ $part['is_inventory'] ? 'readonly' : '' }}>
                                        <div class="flex gap-2">
                                            <input wire:model="parts.{{ $index }}.serial_number" type="text" class="flex-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-[11px]" placeholder="Serial Number (Opsional)">
                                            <div class="flex items-center gap-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1">
                                                <span class="text-[10px] text-slate-400">Garansi:</span>
                                                <input wire:model="parts.{{ $index }}.warranty_duration" type="number" class="w-8 bg-transparent border-none p-0 text-[11px] text-center focus:ring-0">
                                                <span class="text-[10px] text-slate-400">Bln</span>
                                            </div>
                                        </div>
                                        <input wire:model="parts.{{ $index }}.note" type="text" class="w-full bg-transparent border-none p-0 text-xs text-slate-400 focus:ring-0 placeholder-slate-300 italic" placeholder="Catatan item...">
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input wire:model.live="parts.{{ $index }}.qty" type="number" class="w-full text-center bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg py-1 px-1 text-xs font-bold" min="1">
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input wire:model.live="parts.{{ $index }}.price" type="number" class="w-full text-right bg-transparent border-none p-0 font-mono text-slate-600 dark:text-slate-400 text-sm focus:ring-0">
                                    </td>
                                    <td class="px-4 py-3 align-top text-right font-bold font-mono text-slate-800 dark:text-white">
                                        {{ number_format($part['price'] * $part['qty'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-center">
                                        <button wire:click="removePart({{ $index }})" class="text-rose-400 hover:text-rose-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Jasa Service Row (Fixed) -->
                            <tr class="bg-blue-50/50 dark:bg-blue-900/10">
                                <td class="px-4 py-3 font-bold text-blue-800 dark:text-blue-300">Biaya Jasa (Labor Cost)</td>
                                <td class="px-4 py-3 text-center text-slate-400">-</td>
                                <td class="px-4 py-3 text-right">
                                    <input wire:model.live="labor_cost" type="number" class="w-full text-right bg-transparent border-none p-0 font-mono font-bold text-blue-600 dark:text-blue-400 focus:ring-0">
                                </td>
                                <td class="px-4 py-3 text-right font-bold font-mono text-blue-700 dark:text-blue-300">
                                    {{ number_format($labor_cost, 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total -->
                <div class="flex justify-end items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Total Tagihan</span>
                    <span class="text-3xl font-black font-tech text-slate-900 dark:text-white">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tab: History -->
            <div x-show="$wire.activeTab === 'history'" class="space-y-6">
                @if($ticket && $ticket->histories->count() > 0)
                    <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8">
                        @foreach($ticket->histories as $history)
                            <div class="relative pl-6 group">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-2 border-cyan-500 group-hover:bg-cyan-500 transition-colors"></div>
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white text-sm capitalize">{{ $history->status }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $history->notes }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-mono text-slate-400">{{ $history->created_at->format('d M Y H:i') }}</p>
                                        <p class="text-[10px] text-slate-500 font-bold">{{ $history->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-slate-400">
                        <p>Belum ada riwayat status.</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Footer Actions -->
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <a href="{{ route('admin.servis.indeks') }}" class="text-slate-500 font-bold text-sm hover:underline">Kembali</a>
            <div class="flex gap-3">
                @if($ticket)
                    <button wire:click="printInvoice" class="px-6 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Print Invoice
                    </button>
                @endif
                <button wire:click="save" class="px-8 py-2 bg-slate-900 dark:bg-cyan-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
