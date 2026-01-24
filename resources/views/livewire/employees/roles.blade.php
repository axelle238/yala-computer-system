<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Role <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur struktur jabatan dan hak akses sistem.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Jabatan Baru
        </button>
    </div>

    <!-- Role Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-purple-500/10 transition-all"></div>
                
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $role->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $role->users->count() }} Pegawai Aktif</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $role->id }})" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button wire:click="delete({{ $role->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" wire:confirm="Hapus jabatan ini?">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-2 relative z-10">
                    <p class="text-xs font-bold uppercase text-slate-400 tracking-wider">Akses Utama</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($role->permissions->take(5) as $perm)
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold rounded uppercase">
                                {{ explode('.', $perm->name)[0] }}
                            </span>
                        @endforeach
                        @if($role->permissions->count() > 5)
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 text-[10px] font-bold rounded">
                                +{{ $role->permissions->count() - 5 }} lainnya
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-4xl w-full flex flex-col h-[85vh] border border-slate-200 dark:border-slate-700">
                
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-purple-50 dark:bg-purple-900/20 rounded-t-2xl">
                    <h3 class="font-bold text-lg text-purple-800 dark:text-purple-300">
                        {{ $roleIdToEdit ? 'Edit Jabatan' : 'Buat Jabatan Baru' }}
                    </h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Jabatan</label>
                        <input wire:model="name" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-purple-500 focus:border-purple-500 font-bold" placeholder="Contoh: Manager Operasional">
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-6">
                        <h4 class="font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Hak Akses & Izin</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($allPermissions as $group => $permissions)
                                <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                    <h5 class="font-bold text-slate-700 dark:text-slate-300 text-sm mb-3 uppercase tracking-wider">{{ $group }}</h5>
                                    <div class="space-y-2">
                                        @foreach($permissions as $perm)
                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500 transition-all">
                                                <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                                    {{ str_replace('.', ' ', $perm->name) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 rounded-b-2xl flex justify-end gap-3">
                    <button wire:click="$set('isModalOpen', false)" class="px-6 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-500 font-bold hover:bg-white dark:hover:bg-slate-800 transition-colors">Batal</button>
                    <button wire:click="save" class="px-8 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Jabatan</button>
                </div>
            </div>
        </div>
    @endif
</div>