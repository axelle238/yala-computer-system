<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Marketing <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kelola kode promo, voucher diskon, dan kampanye penjualan.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-xl shadow-lg shadow-pink-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Voucher Baru
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-pink-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Voucher Aktif</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ \App\Models\Voucher::where('is_active', true)->count() }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Penukaran</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ \App\Models\VoucherUsage::count() }}</h3>
        </div>
        <div class="bg-gradient-to-br from-pink-600 to-rose-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-pink-600/20">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-wider text-pink-100">Diskon Diberikan</p>
                <h3 class="text-2xl font-black font-tech mt-2">Rp {{ number_format(\App\Models\VoucherUsage::sum('discount_amount') / 1000, 0) }}K</h3>
                <p class="text-[10px] mt-1 text-pink-200">Total value marketing</p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-96 pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-pink-500 text-sm" placeholder="Cari Kode Voucher...">
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kode Voucher</th>
                        <th class="px-6 py-4">Tipe & Nilai</th>
                        <th class="px-6 py-4">Syarat (Min. Belanja)</th>
                        <th class="px-6 py-4 text-center">Kuota</th>
                        <th class="px-6 py-4">Masa Berlaku</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($vouchers as $v)
                        <tr class="hover:bg-pink-50/30 dark:hover:bg-pink-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-pink-600 bg-pink-50 dark:bg-pink-900/20 px-2 py-1 rounded border border-pink-100 dark:border-pink-800">{{ $v->code }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">
                                @if($v->type == 'fixed')
                                    Rp {{ number_format($v->amount, 0, ',', '.') }}
                                @else
                                    {{ $v->amount }}%
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                Rp {{ number_format($v->min_spend, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded">{{ $v->quota }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $v->start_date ? $v->start_date->format('d/m/y') : '∞' }} - {{ $v->end_date ? $v->end_date->format('d/m/y') : '∞' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $v->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <button wire:click="edit({{ $v->id }})" class="text-blue-500 hover:text-blue-700">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </button>
                                <button wire:click="delete({{ $v->id }})" wire:confirm="Hapus voucher ini?" class="text-rose-500 hover:text-rose-700">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada voucher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $vouchers->links() }}
        </div>
    </div>

    <!-- FORM MODAL -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $voucherId ? 'Edit Voucher' : 'Buat Voucher Baru' }}</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="p-6 overflow-y-auto space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode Voucher</label>
                        <input wire:model="code" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500 font-mono uppercase font-bold" placeholder="SALE2025">
                        @error('code') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tipe Diskon</label>
                            <select wire:model="type" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500">
                                <option value="fixed">Nominal (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nilai Diskon</label>
                            <input wire:model="amount" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500" placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Min. Belanja</label>
                            <input wire:model="min_spend" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kuota Klaim</label>
                            <input wire:model="quota" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Mulai Tanggal</label>
                            <input wire:model="start_date" type="date" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                            <input wire:model="end_date" type="date" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-pink-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input wire:model="is_active" type="checkbox" class="rounded text-pink-600 focus:ring-pink-500 w-5 h-5 border-slate-300">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Aktifkan Voucher Ini</label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="$set('showForm', false)" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-lg shadow-lg transition">Simpan Data</button>
                </div>
            </div>
        </div>
    @endif
</div>