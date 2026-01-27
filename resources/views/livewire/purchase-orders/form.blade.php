<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                PO <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Workbench</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Buat dan kelola pesanan stok ke pemasok.</p>
        </div>
        
        <!-- Bilah Status & Aksi -->
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider 
                {{ $status == 'draft' ? 'bg-slate-100 text-slate-600' : '' }}
                {{ $status == 'ordered' ? 'bg-blue-100 text-blue-700' : '' }}
                {{ $status == 'received' ? 'bg-emerald-100 text-emerald-700' : '' }}
                {{ $status == 'cancelled' ? 'bg-rose-100 text-rose-700' : '' }}">
                Status: {{ $status }}
            </span>

            @if($po)
                @if($status === 'draft')
                    <button wire:click="$set('status', 'ordered'); $wire.simpan()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md text-sm transition-all">
                        Finalisasi & Pesan
                    </button>
                @elseif($status === 'ordered')
                    <button wire:click="terimaStok" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md text-sm transition-all flex items-center gap-2" onclick="return confirm('Terima barang dan update stok?') || event.stopImmediatePropagation()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Terima Barang
                    </button>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel Kiri: Informasi -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-100 pb-2">Informasi Dasar</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nomor PO</label>
                        <input wire:model="nomor_po" type="text" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg font-mono text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pemasok</label>
                        <select wire:model.live="id_pemasok" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-sm font-bold" {{ $status !== 'draft' ? 'disabled' : '' }}>
                            <option value="">-- Pilih Pemasok --</option>
                            @foreach($daftarPemasok as $pemasok)
                                <option value="{{ $pemasok->id }}">{{ $pemasok->name }}</option>
                            @endforeach
                        </select>
                        @error('id_pemasok') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Pesan</label>
                        <input wire:model="tanggal_pesanan" type="date" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-sm" {{ $status !== 'draft' ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan</label>
                        <textarea wire:model="catatan" rows="3" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-blue-500 text-sm"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Item Pesanan -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-900 dark:text-white">Item Pesanan</h3>
                    @if($status === 'draft')
                        <button wire:click="tambahItem" class="text-xs font-bold text-blue-600 hover:underline">+ Tambah Baris</button>
                    @endif
                </div>

                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-[10px]">
                            <tr>
                                <th class="px-4 py-3 min-w-[200px]">Produk</th>
                                <th class="px-4 py-3 w-24 text-center">Qty</th>
                                <th class="px-4 py-3 w-32 text-right">Harga Beli</th>
                                <th class="px-4 py-3 w-32 text-right">Subtotal</th>
                                @if($status === 'draft') <th class="px-4 py-3 w-10"></th> @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($item_pesanan as $index => $item)
                                <tr class="bg-white dark:bg-slate-800 group">
                                    <td class="px-4 py-2">
                                        <select wire:model.live="item_pesanan.{{ $index }}.id_produk" wire:change="perbaruiHarga({{ $index }})" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 cursor-pointer" {{ $status !== 'draft' ? 'disabled' : '' }}>
                                            <option value="">Pilih Produk...</option>
                                            @foreach($daftarProduk as $produk)
                                                <option value="{{ $produk->id }}">{{ $produk->name }} (Stok: {{ $produk->stock_quantity }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input wire:model.live="item_pesanan.{{ $index }}.qty" type="number" class="w-full text-center bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg py-1 text-xs font-bold" min="1" {{ $status !== 'draft' ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input wire:model.live="item_pesanan.{{ $index }}.harga" type="number" class="w-full text-right bg-transparent border-none p-0 font-mono text-xs" {{ $status !== 'draft' ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-4 py-2 text-right font-bold font-mono text-slate-800 dark:text-white">
                                        {{ number_format((int)$item['qty'] * (int)$item['harga'], 0, ',', '.') }}
                                    </td>
                                    @if($status === 'draft')
                                        <td class="px-4 py-2 text-center">
                                            <button wire:click="hapusItem({{ $index }})" class="text-slate-300 hover:text-rose-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 flex justify-end items-center gap-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Estimasi</span>
                    <span class="text-2xl font-black font-tech text-slate-900 dark:text-white">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Footer Aksi -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.pesanan-pembelian.indeks') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-100 transition-colors">Kembali</a>
                <button wire:click="simpan" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                    Simpan Draft
                </button>
            </div>
        </div>
    </div>
</div>