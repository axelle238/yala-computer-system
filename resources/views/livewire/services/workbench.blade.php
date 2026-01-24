<div class="min-h-screen bg-slate-50 p-6">
    <!-- Header: Ticket Summary & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-slate-800">Tiket #{{ $ticket->ticket_number }}</h1>
                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $ticket->status_color }}">
                    {{ $ticket->status_label }}
                </span>
            </div>
            <p class="text-slate-500">Dibuat pada {{ $ticket->created_at->format('d M Y, H:i') }} oleh {{ $ticket->technician->name ?? 'Admin' }}</p>
        </div>
        
        <!-- Global Actions -->
        <div class="flex gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Tanda Terima
            </button>
            <a href="{{ route('services.index') }}" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition shadow-sm">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- LEFT COLUMN: DEVICE & CUSTOMER INFO (3 cols) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Customer Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Informasi Pelanggan</h3>
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                        {{ substr($ticket->customer_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-slate-800">{{ $ticket->customer_name }}</div>
                        <div class="text-sm text-slate-500">{{ $ticket->customer_phone }}</div>
                        @if($ticket->user_id)
                            <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Member
                            </span>
                        @endif
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4 mt-4 space-y-2">
                    <button class="w-full py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        Hubungi via WhatsApp
                    </button>
                    <button class="w-full py-2 text-sm font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 rounded-lg transition">
                        Lihat Riwayat Servis
                    </button>
                </div>
            </div>

            <!-- Device Info -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Detail Perangkat</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-slate-500">Model / Tipe</label>
                        <div class="font-medium text-slate-800">{{ $ticket->device_name }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-500">Serial Number</label>
                            <div class="font-mono text-sm text-slate-700 bg-slate-50 px-2 py-1 rounded border border-slate-100 mt-1">
                                {{ $ticket->serial_number_in ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500">Passcode/PIN</label>
                            <div class="font-mono text-sm text-rose-600 bg-rose-50 px-2 py-1 rounded border border-rose-100 mt-1">
                                {{ $ticket->passcode ?? 'NONE' }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Kelengkapan</label>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @if($ticket->completeness)
                                @foreach($ticket->completeness as $item)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ $item }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-sm text-slate-400 italic">Tidak ada data</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500">Keluhan Pelanggan</label>
                        <div class="text-sm text-slate-700 bg-amber-50 p-3 rounded-lg border border-amber-100 mt-1 italic">
                            "{{ $ticket->problem_description }}"
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CENTER COLUMN: WORKBENCH (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Quick Actions / Status Changer -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Update Status Pengerjaan</h2>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button wire:click="updateStatus('diagnosing')" class="p-3 rounded-lg border {{ $currentStatus === 'diagnosing' ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'border-slate-200 hover:border-blue-300' }} text-left transition relative">
                        <div class="font-semibold text-blue-700">1. Diagnosa</div>
                        <div class="text-xs text-slate-500">Pengecekan awal</div>
                    </button>
                    <button wire:click="updateStatus('waiting_part')" class="p-3 rounded-lg border {{ $currentStatus === 'waiting_part' ? 'bg-amber-50 border-amber-500 ring-1 ring-amber-500' : 'border-slate-200 hover:border-amber-300' }} text-left transition relative">
                        <div class="font-semibold text-amber-700">2. Tunggu Part</div>
                        <div class="text-xs text-slate-500">Menunggu konfirmasi/stok</div>
                    </button>
                    <button wire:click="updateStatus('repairing')" class="p-3 rounded-lg border {{ $currentStatus === 'repairing' ? 'bg-indigo-50 border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-200 hover:border-indigo-300' }} text-left transition relative">
                        <div class="font-semibold text-indigo-700">3. Perbaikan</div>
                        <div class="text-xs text-slate-500">Sedang dikerjakan</div>
                    </button>
                    <button wire:click="updateStatus('ready')" class="p-3 rounded-lg border {{ $currentStatus === 'ready' ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' : 'border-slate-200 hover:border-emerald-300' }} text-left transition relative">
                        <div class="font-semibold text-emerald-700">4. Selesai</div>
                        <div class="text-xs text-slate-500">Siap diambil</div>
                    </button>
                </div>

                <div class="space-y-3">
                    <textarea wire:model="noteInput" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Tulis catatan pengerjaan atau hasil diagnosa di sini..."></textarea>
                    <div class="flex justify-between items-center">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" wire:model="isPublicNote" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Tampilkan ke customer (Tracking Page)
                        </label>
                        <button wire:click="saveProgress" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                            Simpan Update
                        </button>
                    </div>
                </div>
            </div>

            <!-- Parts Manager -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Penggunaan Sparepart & Jasa</h2>
                    <span class="text-sm font-medium text-slate-500">Total: <span class="text-slate-900 font-bold">Rp {{ number_format($ticket->parts->sum('subtotal'), 0, ',', '.') }}</span></span>
                </div>

                <!-- Add Part Form -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 mb-6">
                    <div class="mb-3 relative">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Cari Produk / Jasa</label>
                        <input type="text" wire:model.live="partSearch" class="w-full rounded-md border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ketik nama sparepart atau kode SKU...">
                        
                        @if(!empty($searchResults))
                            <div class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                @foreach($searchResults as $product)
                                    <button wire:click="selectProduct({{ $product->id }})" class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition flex justify-between items-center group">
                                        <div>
                                            <div class="font-medium text-slate-800 group-hover:text-indigo-600">{{ $product->name }}</div>
                                            <div class="text-xs text-slate-500">SKU: {{ $product->sku }} | Stok: {{ $product->stock }}</div>
                                        </div>
                                        <div class="font-semibold text-slate-700">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($selectedProduct)
                        <div class="p-3 bg-white border border-indigo-100 rounded-md mb-3">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-medium text-indigo-900">{{ $selectedProduct->name }}</div>
                                <button wire:click="$set('selectedProduct', null)" class="text-slate-400 hover:text-rose-500">&times;</button>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-slate-500 block mb-1">Harga Satuan</label>
                                    <input type="number" wire:model="customPrice" class="w-full text-sm rounded border-slate-200 bg-slate-50">
                                </div>
                                <div>
                                    <label class="text-xs text-slate-500 block mb-1">Jumlah</label>
                                    <input type="number" wire:model="quantity" min="1" class="w-full text-sm rounded border-slate-200">
                                </div>
                            </div>
                            <button wire:click="addPart" class="w-full mt-3 py-2 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700 transition">
                                Tambahkan ke Tiket
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Parts List -->
                <div class="space-y-3">
                    @forelse($ticket->parts as $part)
                        <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-lg shadow-sm group hover:border-indigo-200 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                    {{ $part->quantity }}x
                                </div>
                                <div>
                                    <div class="font-medium text-slate-800">{{ $part->product->name ?? 'Unknown Item' }}</div>
                                    <div class="text-xs text-slate-500">
                                        @ {{ number_format($part->price_per_unit, 0, ',', '.') }}
                                        @if($part->serial_number) 
                                            <span class="ml-1 px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[10px]">SN: {{ $part->serial_number }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="font-semibold text-slate-700">Rp {{ number_format($part->subtotal, 0, ',', '.') }}</div>
                                <button wire:click="removePart({{ $part->id }})" class="text-slate-300 hover:text-rose-500 transition" onclick="confirm('Hapus item ini?') || event.stopImmediatePropagation()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-sm bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            Belum ada sparepart atau jasa yang ditambahkan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ACTIVITY TIMELINE (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full max-h-[calc(100vh-100px)] overflow-y-auto">
                <h2 class="text-lg font-bold text-slate-800 mb-6 sticky top-0 bg-white z-10 pb-2 border-b border-slate-100">Riwayat Pengerjaan</h2>
                
                <div class="relative pl-6 border-l-2 border-slate-100 space-y-8">
                    @foreach($ticket->progressLogs as $log)
                        <div class="relative">
                            <!-- Bullet -->
                            <div class="absolute -left-[29px] top-1 w-4 h-4 rounded-full border-2 border-white shadow-sm 
                                {{ match($log->status_label) {
                                    'ready' => 'bg-emerald-500',
                                    'repairing' => 'bg-indigo-500',
                                    'diagnosing' => 'bg-blue-500',
                                    'waiting_part' => 'bg-amber-500',
                                    'cancelled' => 'bg-rose-500',
                                    default => 'bg-slate-400'
                                } }}">
                            </div>

                            <!-- Content -->
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 uppercase tracking-wide">
                                        {{ str_replace('_', ' ', $log->status_label) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-sm text-slate-700">
                                    {{ $log->description }}
                                </div>

                                <div class="mt-2 flex items-center gap-2 text-xs text-slate-400">
                                    <div class="flex items-center gap-1">
                                        <div class="w-4 h-4 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-bold text-slate-600">
                                            {{ substr($log->technician_id, 0, 1) }}
                                        </div>
                                        <span>Teknisi ID: {{ $log->technician_id }}</span>
                                    </div>
                                    @if($log->is_public)
                                        <span class="flex items-center gap-1 text-emerald-500 ml-auto" title="Terlihat oleh Customer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Public
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 text-slate-400 ml-auto" title="Internal Only">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                            Internal
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>