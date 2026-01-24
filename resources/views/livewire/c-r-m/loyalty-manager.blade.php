<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Loyalty <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Program</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur tingkatan membership dan keuntungan pelanggan.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Level Baru
        </button>
    </div>

    <!-- Tier Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($groups as $group)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-100 dark:border-slate-700 p-6 relative overflow-hidden group hover:border-amber-400 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $group->color }}-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-wider">{{ $group->name }}</h3>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $group->id }})" class="p-1.5 text-slate-400 hover:text-amber-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                            <button wire:click="delete({{ $group->id }})" class="p-1.5 text-slate-400 hover:text-rose-500" wire:confirm="Hapus level ini?"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Min. Belanja</span>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($group->min_spend/1000000) }} Juta</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Diskon Otomatis</span>
                            <span class="font-mono font-bold text-emerald-500">{{ $group->discount_percent }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kode</span>
                            <span class="font-mono font-bold text-slate-400">{{ $group->code }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">{{ $groupIdToEdit ? 'Edit Level' : 'Buat Level Baru' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Level</label>
                        <input wire:model="name" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode</label>
                            <input wire:model="code" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Warna UI</label>
                            <select wire:model="color" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm">
                                <option value="gray">Gray</option>
                                <option value="amber">Gold</option>
                                <option value="slate">Platinum</option>
                                <option value="emerald">Emerald</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Min. Total Belanja (Lifetime)</label>
                        <input wire:model="min_spend" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Diskon (%)</label>
                        <input wire:model="discount_percent" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2">
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-500 font-bold text-sm">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-amber-500 text-white font-bold rounded-xl shadow-lg text-sm">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
