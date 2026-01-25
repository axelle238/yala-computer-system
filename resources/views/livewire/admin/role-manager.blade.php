<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Peran & Akses</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur struktur jabatan dan hak akses sistem.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Peran Baru
        </button>
    </div>

    <!-- Role List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($peranList as $peran)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full pointer-events-none"></div>
                
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $peran->id }})" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button wire:click="delete({{ $peran->id }})" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" onclick="confirm('Hapus peran ini?') || event.stopImmediatePropagation()">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $peran->nama }}</h3>
                <p class="text-sm text-slate-500 mb-4">{{ $peran->pengguna_count }} Pengguna Aktif</p>

                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach(array_slice($peran->hak_akses ?? [], 0, 3) as $akses)
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-[10px] uppercase font-bold rounded">
                            {{ str_replace('_', ' ', $akses) }}
                        </span>
                    @endforeach
                    @if(count($peran->hak_akses ?? []) > 3)
                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-500 text-[10px] font-bold rounded">
                            +{{ count($peran->hak_akses) - 3 }} Lainnya
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form (No Alpine x-modal, just simple conditional rendering) -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
                    <div class="px-6 py-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
                            {{ $isEdit ? 'Edit Peran' : 'Buat Peran Baru' }}
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Peran / Jabatan</label>
                                <input wire:model="nama" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white" placeholder="Contoh: Manager Gudang">
                                @error('nama') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">Pilih Hak Akses</label>
                                <div class="space-y-4 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                                    @foreach($permissionsList as $group => $perms)
                                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 border border-slate-100 dark:border-slate-700">
                                            <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-3 border-b border-slate-200 dark:border-slate-700 pb-2">{{ $group }}</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                @foreach($perms as $key => $label)
                                                    <label class="flex items-center gap-3 cursor-pointer group">
                                                        <input wire:model="hak_akses" value="{{ $key }}" type="checkbox" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all">
                                                        <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-blue-600 transition-colors">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                        <button wire:click="$set('showModal', false)" type="button" class="px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700 font-bold text-sm transition-colors">Batal</button>
                        <button wire:click="store" type="button" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow-lg transition-all transform active:scale-95">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
