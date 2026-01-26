<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center mb-6">
        <div class="relative w-64">
            <input wire:model.live="pencarian" type="text" class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800" placeholder="Cari Kode Voucher...">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <button wire:click="buat" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition">Buat Voucher</button>
    </div>

    @if($tampilkanForm)
        <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-fade-in-up">
                <h3 class="text-lg font-bold mb-4">{{ $idVoucher ? 'Edit Voucher' : 'Buat Voucher Baru' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Voucher</label>
                        <input wire:model="kode" type="text" class="w-full rounded-lg border-slate-300 font-mono uppercase" placeholder="CONTOH: DISKON50">
                        @error('kode') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tipe</label>
                            <select wire:model="tipe" class="w-full rounded-lg border-slate-300">
                                <option value="fixed">Nominal Tetap (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah Potongan</label>
                            <input wire:model="jumlah" type="number" class="w-full rounded-lg border-slate-300">
                            @error('jumlah') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Min. Belanja</label>
                            <input wire:model="minBelanja" type="number" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kuota</label>
                            <input wire:model="kuota" type="number" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Mulai</label>
                            <input wire:model="tanggalMulai" type="date" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Selesai</label>
                            <input wire:model="tanggalSelesai" type="date" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('tampilkanForm', false)" class="px-4 py-2 text-slate-500 hover:bg-slate-100 rounded-lg">Batal</button>
                    <button wire:click="simpan" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($voucher as $v)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition flex gap-1">
                    <button wire:click="ubah({{ $v->id }})" class="p-1 bg-white text-indigo-500 rounded-lg shadow-sm hover:bg-indigo-50"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                    <button wire:click="hapus({{ $v->id }})" class="p-1 bg-white text-rose-500 rounded-lg shadow-sm hover:bg-rose-50"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                </div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <span class="px-2 py-1 rounded-md bg-indigo-100 text-indigo-700 text-xs font-bold font-mono tracking-wider">{{ $v->code }}</span>
                        <div class="mt-2 text-2xl font-black text-slate-800 dark:text-white">
                            @if($v->type == 'percentage')
                                {{ $v->amount }}% OFF
                            @else
                                Rp {{ number_format($v->amount/1000) }}k OFF
                            @endif
                        </div>
                        <div class="text-sm text-slate-500 mt-1">Min. Belanja: Rp {{ number_format($v->min_spend) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-slate-400 uppercase font-bold">Sisa Kuota</div>
                        <div class="text-xl font-bold text-slate-700 dark:text-slate-300">{{ $v->quota }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
