<form wire:submit.prevent="simpan" class="space-y-6">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Informasi Obral Kilat</h2>
        
        <div class="space-y-4">
            <!-- Product Search -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Cari Produk</label>
                <div class="relative">
                    <input wire:model.live="cariProduk" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900" placeholder="Ketik nama produk...">
                    @if(!empty($hasilPencarian))
                        <div class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border rounded-xl shadow-lg max-h-40 overflow-y-auto">
                            @foreach($hasilPencarian as $p)
                                <div wire:click="pilihProduk({{ $p->id }})" class="p-3 hover:bg-indigo-50 dark:hover:bg-slate-700 cursor-pointer border-b last:border-0 border-slate-100 dark:border-slate-700">
                                    <div class="font-bold text-sm">{{ $p->name }}</div>
                                    <div class="text-xs text-slate-500">Rp {{ number_format($p->sell_price) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @if($namaProdukTerpilih)
                    <div class="mt-2 text-sm font-bold text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-200">
                        Produk Terpilih: {{ $namaProdukTerpilih }} (Rp {{ number_format($hargaAsli) }})
                    </div>
                @endif
                @error('idProduk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Harga Diskon (Rp)</label>
                    <input wire:model="hargaDiskon" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('hargaDiskon') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kuota</label>
                    <input wire:model="kuota" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('kuota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Waktu Mulai</label>
                    <input wire:model="waktuMulai" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('waktuMulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Waktu Selesai</label>
                    <input wire:model="waktuSelesai" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('waktuSelesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">
        Simpan Obral Kilat
    </button>
</form>
