<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Kontrol <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">Akses</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur jabatan dan batasan hak akses sistem secara detail.</p>
        </div>
        <button wire:click="buat" class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg shadow-violet-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Peran Baru
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Daftar Peran -->
        <div class="lg:col-span-1 space-y-4">
            @foreach($daftarPeran as $peran)
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:border-violet-500 transition-all cursor-pointer group relative {{ $idPeran === $peran->id ? 'ring-2 ring-violet-500 border-transparent' : '' }}" wire:click="ubah({{ $peran->id }})">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white group-hover:text-violet-600 transition-colors">{{ $peran->nama }}</h3>
                        <span class="text-[10px] bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded font-bold uppercase">{{ count($peran->hak_akses ?? []) }} Akses</span>
                    </div>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 uppercase tracking-tight font-medium">
                        {{ implode(' • ', array_map(fn($h) => str_replace('_', ' ', $h), $peran->hak_akses ?? [])) }}
                    </p>
                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                </div>
            @endforeach
            @if($daftarPeran->isEmpty())
                <div class="p-10 text-center text-slate-400 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <p class="font-bold uppercase text-xs tracking-widest">Belum Ada Peran</p>
                </div>
            @endif
        </div>

        <!-- Panel Editor -->
        <div class="lg:col-span-2">
            @if($tampilkanForm)
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white">{{ $idPeran ? 'Ubah Peran: ' . $nama : 'Peran Baru' }}</h3>
                        <button wire:click="$set('tampilkanForm', false)" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Nama Jabatan / Peran</label>
                            <input wire:model="nama" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-violet-500 font-bold text-lg dark:text-white transition-all" placeholder="Contoh: Manajer Toko">
                            @error('nama') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-4 tracking-wider">Hak Akses Sistem</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($petaHakAkses as $grup => $daftarHak)
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700">
                                        <h4 class="font-bold text-xs text-violet-600 dark:text-violet-400 uppercase mb-4 border-b border-slate-200 dark:border-slate-700 pb-2 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04 obstruction" /></svg>
                                            {{ $grup }}
                                        </h4>
                                        <div class="space-y-3">
                                            @foreach($daftarHak as $hak)
                                                <label class="flex items-center gap-3 cursor-pointer group p-1.5 rounded-lg transition hover:bg-white dark:hover:bg-slate-800">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" wire:model="hakAksesTerpilih" value="{{ $hak }}" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 dark:border-slate-600 transition-all checked:border-violet-500 checked:bg-violet-500">
                                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 14 14" fill="none">
                                                            <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm text-slate-600 dark:text-slate-300 font-bold group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ str_replace('_', ' ', $hak) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('hakAksesTerpilih') <span class="text-rose-500 text-xs block mt-4 font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                        @if($idPeran)
                            <button wire:click="hapus({{ $idPeran }})" wire:confirm="Hapus peran ini? Pastikan tidak ada karyawan yang menggunakannya." class="sm:mr-auto px-4 py-2.5 text-rose-500 font-bold hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition text-sm">Hapus Peran</button>
                        @endif
                        <button wire:click="$set('tampilkanForm', false)" class="px-6 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                        <button wire:click="simpan" class="px-8 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg shadow-violet-600/30 transition transform active:scale-95">Simpan Perubahan</button>
                    </div>
                </div>
            @else
                <div class="h-full min-h-[500px] flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl bg-slate-50/30 dark:bg-slate-900/20">
                    <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <p class="font-bold text-xl text-slate-500 dark:text-slate-400">Pilih Peran Untuk Dikelola</p>
                    <p class="text-sm opacity-60">Atau gunakan tombol "Buat Peran Baru" untuk menambah jabatan baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>