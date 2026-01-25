<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Program <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Loyalitas</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur tingkatan membership dan keuntungan pelanggan.</p>
        </div>
        <button wire:click="bukaPanelFormulir" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Level Baru
        </button>
    </div>

    <!-- Panel Aksi -->
    @if($aksiAktif === 'formulir')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-amber-100 dark:border-amber-900/30 p-6 shadow-lg animate-fade-in-up">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    {{ $idGrupDiubah ? 'Edit Level Membership' : 'Buat Level Baru' }}
                </h3>
                <button wire:click="tutupPanel" class="text-slate-400 hover:text-amber-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Level</label>
                        <input wire:model="nama" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-amber-500 focus:border-amber-500 font-bold text-slate-800 dark:text-white" placeholder="Contoh: Gold Member">
                        @error('nama') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Unik</label>
                        <input wire:model="kode" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-amber-500 focus:border-amber-500 uppercase font-mono tracking-wider" placeholder="GOLD">
                        @error('kode') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Warna UI</label>
                        <select wire:model="warna" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500">
                            <option value="gray">Abu-abu (Dasar)</option>
                            <option value="amber">Emas (Premium)</option>
                            <option value="slate">Platinum (Elite)</option>
                            <option value="emerald">Zamrud (Eksklusif)</option>
                        </select>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Min. Total Belanja (Lifetime)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 font-bold text-sm">Rp</span>
                            <input wire:model="minBelanja" type="number" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-amber-500 focus:border-amber-500 font-mono font-bold" placeholder="0">
                        </div>
                        @error('minBelanja') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Diskon Otomatis (%)</label>
                        <input wire:model="persenDiskon" type="number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-amber-500 focus:border-amber-500 font-bold text-amber-600" placeholder="0">
                        @error('persenDiskon') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button wire:click="tutupPanel" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">Batal</button>
                <button wire:click="simpan" class="px-8 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform active:scale-95">Simpan Level</button>
            </div>
        </div>
    @endif

    <!-- Kartu Tier -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($daftarGrup as $grup)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-slate-100 dark:border-slate-700 p-6 relative overflow-hidden group hover:border-amber-400 transition-all">
                <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $grup->color }}-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-wider">{{ $grup->name }}</h3>
                        <div class="flex gap-2">
                            <button wire:click="bukaPanelFormulir({{ $grup->id }})" class="p-1.5 text-slate-400 hover:text-amber-500 transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                            <button wire:click="hapus({{ $grup->id }})" class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors" wire:confirm="Hapus level ini?"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Min. Belanja</span>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($grup->min_spend/1000000) }} Juta</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Diskon Otomatis</span>
                            <span class="font-mono font-bold text-emerald-500">{{ $grup->discount_percent }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kode</span>
                            <span class="font-mono font-bold text-slate-400">{{ $grup->code }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
