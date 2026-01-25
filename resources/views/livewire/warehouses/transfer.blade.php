<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Mutasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Stok</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">Pindahkan stok antar lokasi gudang atau toko secara tercatat.</p>
        </div>
        @if(!$tampilkanForm)
            <button wire:click="buat" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Buat Mutasi Baru
            </button>
        @endif
    </div>

    <!-- Form Inline (Pengganti Modal) -->
    @if($tampilkanForm)
        <div class="bg-white dark:bg-slate-800 w-full rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Formulir Mutasi Stok</h3>
                <button wire:click="$set('tampilkanForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Pilihan Gudang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 dark:bg-slate-900/30 rounded-xl border border-slate-100 dark:border-slate-700">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Dari Gudang (Asal)</label>
                        <select wire:model="idGudangAsal" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-blue-500 dark:text-white">
                            @foreach($daftarGudang as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                        </select>
                        @error('idGudangAsal') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ke Gudang (Tujuan)</label>
                        <select wire:model="idGudangTujuan" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-blue-500 dark:text-white">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($daftarGudang as $g) <option value="{{ $g->id }}">{{ $g->name }}</option> @endforeach
                        </select>
                        @error('idGudangTujuan') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Tabel Item -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Daftar Item Mutasi</label>
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 uppercase text-xs font-bold">
                                <tr>
                                    <th class="p-3 text-left pl-4">Produk</th>
                                    <th class="p-3 w-32 text-center">Jumlah</th>
                                    <th class="p-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($daftarItem as $indeks => $item)
                                    <tr class="bg-white dark:bg-slate-800">
                                        <td class="p-2 pl-4">
                                            <select wire:model="daftarItem.{{ $indeks }}.id_produk" class="w-full text-sm border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-900 focus:ring-blue-500 dark:text-white">
                                                <option value="">Pilih Produk</option>
                                                @foreach($daftarProduk as $p)
                                                    <option value="{{ $p->id }}">{{ $p->name }} (Stok: {{ $p->stock_quantity }})</option>
                                                @endforeach
                                            </select>
                                            @error('daftarItem.'.$indeks.'.id_produk') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="p-2">
                                            <input type="number" wire:model="daftarItem.{{ $indeks }}.qty" class="w-full text-sm border-slate-300 dark:border-slate-600 rounded-lg text-center bg-white dark:bg-slate-900 focus:ring-blue-500 dark:text-white" min="1">
                                            @error('daftarItem.'.$indeks.'.qty') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="p-2 text-center">
                                            <button wire:click="hapusItem({{ $indeks }})" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Hapus Baris">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button wire:click="tambahItem" class="mt-3 text-xs font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris Produk
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Catatan Tambahan</label>
                    <textarea wire:model="catatan" class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 dark:text-white" rows="2" placeholder="Alasan mutasi, nomor referensi surat jalan, dll..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                <button wire:click="$set('tampilkanForm', false)" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition">Batal</button>
                <button wire:click="simpan" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition transform active:scale-95">Proses Mutasi Stok</button>
            </div>
        </div>
    @endif

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No. Transfer</th>
                        <th class="px-6 py-4">Dari Gudang</th>
                        <th class="px-6 py-4">Ke Gudang</th>
                        <th class="px-6 py-4 text-center">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($riwayatMutasi as $mutasi)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-blue-600 dark:text-blue-400">{{ $mutasi->transfer_number }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-300">{{ $mutasi->source->name ?? 'Gudang Utama' }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-300">{{ $mutasi->destination->name ?? 'Toko Cabang' }}</td>
                            <td class="px-6 py-4 text-center text-slate-500">{{ $mutasi->transfer_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-[10px] font-bold uppercase tracking-wide border border-emerald-200 dark:border-emerald-800">Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat mutasi stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $riwayatMutasi->links() }}</div>
    </div>
</div>
