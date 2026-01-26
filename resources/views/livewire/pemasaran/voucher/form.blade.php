<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white">Buat Voucher Baru</h2>
        <a href="{{ route('marketing.vouchers.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Kembali</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-8">
        <div class="space-y-6">
            <!-- Identity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Voucher</label>
                    <input wire:model="code" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-mono font-bold text-lg uppercase tracking-wider focus:ring-pink-500" placeholder="SALE1212">
                    @error('code') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Promo</label>
                    <input wire:model="name" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500" placeholder="Diskon Akhir Tahun">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Deskripsi</label>
                <textarea wire:model="description" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500" rows="2"></textarea>
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-700"></div>

            <!-- Value Logic -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tipe Potongan</label>
                    <select wire:model.live="type" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm font-bold focus:ring-pink-500">
                        <option value="fixed">Nominal (Rp)</option>
                        <option value="percent">Persentase (%)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Besar Potongan</label>
                    <input wire:model="amount" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-bold focus:ring-pink-500">
                </div>
                @if($type === 'percent')
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Max Diskon (Rp)</label>
                    <input wire:model="max_discount" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500" placeholder="0 = Unlimited">
                </div>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Minimum Belanja</label>
                <input wire:model="min_spend" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500">
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-700"></div>

            <!-- Limits & Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kuota Global (Total Pakai)</label>
                    <input wire:model="usage_limit" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500" placeholder="Kosong = Unlimited">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kuota Per User</label>
                    <input wire:model="usage_per_user" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Mulai Tanggal</label>
                    <input wire:model="start_date" type="date" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Sampai Tanggal</label>
                    <input wire:model="end_date" type="date" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-pink-500">
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button wire:click="save" class="px-8 py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Voucher</button>
            </div>
        </div>
    </div>
</div>
