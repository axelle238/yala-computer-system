<form wire:submit.prevent="simpan" class="space-y-6">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-bold mb-4 text-slate-800 dark:text-white">Informasi Voucher</h2>
        
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Voucher</label>
                    <input wire:model="kode" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 uppercase font-mono" placeholder="DISKON50">
                    @error('kode') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Promo</label>
                    <input wire:model="nama" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600" placeholder="Promo Akhir Tahun">
                    @error('nama') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                <textarea wire:model="deskripsi" rows="2" class="w-full rounded-xl border-slate-300 dark:border-slate-600"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Potongan</label>
                    <select wire:model="tipe" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                        <option value="fixed">Nominal (Rp)</option>
                        <option value="percentage">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah</label>
                    <input wire:model="jumlah" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('jumlah') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Min. Belanja</label>
                    <input wire:model="minBelanja" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                    @error('minBelanja') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Mulai Berlaku</label>
                    <input wire:model="tanggalMulai" type="date" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Berakhir</label>
                    <input wire:model="tanggalSelesai" type="date" class="w-full rounded-xl border-slate-300 dark:border-slate-600">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">
        Simpan Voucher
    </button>
</form>