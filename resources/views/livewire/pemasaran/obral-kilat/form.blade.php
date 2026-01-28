<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 dark:text-white font-tech tracking-tight">Buat Obral Kilat</h1>
        <p class="text-slate-500 text-sm mt-1">Atur promo flash sale untuk meningkatkan penjualan.</p>
    </div>

    <form wire:submit.prevent="simpan" class="space-y-6">
        <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 relative">
            <h2 class="text-lg font-bold mb-6 text-slate-800 dark:text-white flex items-center gap-2">
                <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                Informasi Produk & Harga
            </h2>
            
            <div class="space-y-6">
                <!-- Product Search -->
                <div class="relative z-20">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Cari Produk</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input wire:model.live="cariProduk" type="text" class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Ketik nama produk...">
                        
                        @if(!empty($hasilPencarian))
                            <div class="absolute w-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-60 overflow-y-auto z-50">
                                @foreach($hasilPencarian as $p)
                                    <div wire:click="pilihProduk({{ $p->id }})" class="p-3 hover:bg-indigo-50 dark:hover:bg-slate-700 cursor-pointer border-b last:border-0 border-slate-100 dark:border-slate-700 flex justify-between items-center group transition-colors">
                                        <div>
                                            <div class="font-bold text-sm text-slate-800 dark:text-white group-hover:text-indigo-600">{{ $p->name }}</div>
                                            <div class="text-xs text-slate-500">Stok: {{ $p->stock_quantity }}</div>
                                        </div>
                                        <div class="font-mono text-sm font-bold text-slate-600 dark:text-slate-300">Rp {{ number_format($p->sell_price) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if($namaProdukTerpilih)
                        <div class="mt-3 flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                            <div>
                                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Produk Terpilih</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ $namaProdukTerpilih }}</span>
                            </div>
                            <span class="text-emerald-600 font-mono font-bold">Rp {{ number_format($hargaAsli) }}</span>
                        </div>
                    @endif
                    @error('idProduk') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Harga Diskon (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold">Rp</span>
                            <input wire:model="hargaDiskon" type="number" class="w-full pl-10 rounded-xl border-slate-300 dark:border-slate-600 focus:ring-indigo-500 focus:border-indigo-500 py-3" placeholder="0">
                        </div>
                        @error('hargaDiskon') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kuota Promo</label>
                        <input wire:model="kuota" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 focus:ring-indigo-500 focus:border-indigo-500 py-3" placeholder="Jumlah unit">
                        @error('kuota') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-0">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Waktu Mulai</label>
                        <input wire:model="waktuMulai" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600 focus:ring-indigo-500 focus:border-indigo-500 py-3">
                        @error('waktuMulai') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Waktu Selesai</label>
                        <input wire:model="waktuSelesai" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600 focus:ring-indigo-500 focus:border-indigo-500 py-3">
                        @error('waktuSelesai') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- AI Marketing Content -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between items-center mb-3">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Caption Iklan (Sosmed/WA)</label>
                        <button type="button" wire:click="generateCaption" wire:loading.attr="disabled" class="text-[10px] font-bold text-white bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 px-3 py-1.5 rounded-lg shadow-md flex items-center gap-1 transition-all hover:scale-105 disabled:opacity-50">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            Buat Caption Otomatis
                        </button>
                    </div>
                    <textarea wire:model="captionIklan" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-pink-500 focus:border-pink-500 text-sm" placeholder="Klik tombol di atas untuk generate caption menarik..."></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">*Caption ini bisa Anda copy untuk broadcast WhatsApp atau posting Instagram.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.pemasaran.obral-kilat.indeks') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                Simpan Obral Kilat
            </button>
        </div>
    </form>
</div>
