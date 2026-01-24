<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Audit Stok (Stock Opname)</h2>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('global'))
        <div class="mb-4 bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded relative">
            {{ $errors->first('global') }}
        </div>
    @endif

    <!-- MODE: LIST (HISTORY) -->
    @if($viewMode == 'list')
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-slate-700">Riwayat Audit</h3>
                <button wire:click="startNewOpname" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center gap-2 font-bold shadow-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Mulai Audit Baru
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">No. Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Dibuat Oleh</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($history as $opname)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                    {{ $opname->opname_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $opname->opname_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $opname->creator->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($opname->status == 'completed')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">Selesai</span>
                                    @elseif($opname->status == 'cancelled')
                                        <span class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-full">Batal</span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full animate-pulse">Berjalan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if(in_array($opname->status, ['counting', 'review']))
                                        <button wire:click="checkActiveOpname" class="text-indigo-600 font-bold hover:underline">Lanjutkan</button>
                                    @else
                                        <span class="text-slate-400">Arsip</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat stock opname.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-3 border-t border-slate-200">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- MODE: COUNTING (INPUT) -->
    @if($viewMode == 'counting')
        <div class="bg-white shadow rounded-xl overflow-hidden border border-slate-200">
            <div class="bg-indigo-600 p-6 flex justify-between items-center text-white">
                <div>
                    <h3 class="text-xl font-bold">Proses Penghitungan Fisik</h3>
                    <p class="text-indigo-100 text-sm">Nomor: {{ $activeOpname->opname_number }}</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="saveCounts" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded text-sm font-bold">Simpan Sementara</button>
                    <button wire:click="finishCounting" class="bg-white text-indigo-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-slate-100">Selesai Hitung &rarr;</button>
                </div>
            </div>
            
            <div class="p-6 bg-yellow-50 border-b border-yellow-100 text-yellow-800 text-sm">
                <span class="font-bold">Instruksi:</span> Masukkan jumlah fisik barang yang ada di gudang. Kosongkan jika belum dihitung. Sistem akan otomatis menghitung selisih.
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Stok Sistem</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase w-48">Stok Fisik (Input)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($activeOpname->items as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-mono text-slate-600">
                                    {{ $item->system_stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" 
                                           wire:model="counts.{{ $item->id }}" 
                                           class="w-full text-center font-bold text-slate-800 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="0">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button wire:click="cancelOpname" class="text-rose-500 text-sm hover:underline">Batalkan Sesi Ini</button>
        </div>
    @endif

    <!-- MODE: REVIEW (ADJUSTMENT) -->
    @if($viewMode == 'review')
        <div class="bg-white shadow rounded-xl overflow-hidden border border-slate-200">
            <div class="bg-slate-800 p-6 flex justify-between items-center text-white">
                <div>
                    <h3 class="text-xl font-bold">Review & Finalisasi</h3>
                    <p class="text-slate-400 text-sm">Periksa selisih sebelum melakukan penyesuaian stok otomatis.</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="$set('viewMode', 'counting')" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded text-sm">Kembali Hitung</button>
                    <button wire:click="finalizeAdjustment" class="bg-emerald-500 text-white px-6 py-2 rounded text-sm font-bold shadow hover:bg-emerald-600" onclick="confirm('Stok sistem akan diubah mengikuti jumlah fisik. Lanjutkan?') || event.stopImmediatePropagation()">
                        Finalisasi & Sesuaikan Stok
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Sistem</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Fisik</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Selisih</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Estimasi Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $totalLoss = 0; @endphp
                        @foreach($activeOpname->items as $item)
                            @if($item->physical_stock === null) @continue @endif
                            
                            @php 
                                $diff = $item->physical_stock - $item->system_stock;
                                $value = $diff * $item->product->buy_price;
                                $totalLoss += $value;
                            @endphp

                            <tr class="{{ $diff < 0 ? 'bg-rose-50' : ($diff > 0 ? 'bg-emerald-50' : '') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">
                                    {{ $item->system_stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-slate-900">
                                    {{ $item->physical_stock }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($diff == 0)
                                        <span class="text-slate-400 font-bold">-</span>
                                    @elseif($diff > 0)
                                        <span class="text-emerald-600 font-bold">+{{ $diff }}</span>
                                    @else
                                        <span class="text-rose-600 font-bold">{{ $diff }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <span class="{{ $value < 0 ? 'text-rose-600' : ($value > 0 ? 'text-emerald-600' : 'text-slate-400') }}">
                                        Rp {{ number_format($value, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right font-bold text-slate-700">Total Selisih Nilai Aset:</td>
                            <td class="px-6 py-4 text-right font-bold {{ $totalLoss < 0 ? 'text-rose-700' : 'text-emerald-700' }}">
                                Rp {{ number_format($totalLoss, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>