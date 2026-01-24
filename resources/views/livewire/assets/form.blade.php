<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white">Registrasi Aset Baru</h2>
        <a href="{{ route('assets.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Kembali</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-8">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Aset</label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold focus:ring-cyan-500" placeholder="Contoh: Laptop Admin">
                    @error('name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tag Aset (Auto)</label>
                    <input wire:model="asset_tag" type="text" class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-mono font-bold text-slate-500" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Harga Beli</label>
                    <input wire:model="purchase_price" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tanggal Beli</label>
                    <input wire:model="purchase_date" type="date" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Masa Manfaat (Tahun)</label>
                    <input wire:model="useful_life_years" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Lokasi</label>
                    <input wire:model="location" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-cyan-500" placeholder="Ruang Staff, Gudang...">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Serial Number (Opsional)</label>
                    <input wire:model="serial_number" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm font-mono focus:ring-cyan-500">
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button wire:click="save" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Aset</button>
            </div>
        </div>
    </div>
</div>
