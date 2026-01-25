<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Access <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600">Control</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur jabatan dan batasan akses sistem.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg shadow-violet-600/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Role Baru
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Role List -->
        <div class="lg:col-span-1 space-y-4">
            @foreach($roles as $role)
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 hover:border-violet-500 transition-all cursor-pointer group relative" wire:click="edit({{ $role->id }})">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white group-hover:text-violet-600 transition-colors">{{ $role->name }}</h3>
                        <span class="text-xs bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded font-mono">{{ count($role->permissions ?? []) }} Akses</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                        {{ implode(', ', $role->permissions ?? []) }}
                    </p>
                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                </div>
            @endforeach
            @if($roles->isEmpty())
                <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-dashed">Belum ada role.</div>
            @endif
        </div>

        <!-- Editor Panel -->
        <div class="lg:col-span-2">
            @if($showForm)
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700 animate-slide-in-right">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="font-bold text-xl text-slate-900 dark:text-white">{{ $roleId ? 'Edit Role: ' . $name : 'Role Baru' }}</h3>
                        <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Jabatan</label>
                            <input wire:model="name" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-violet-500 font-bold text-lg">
                            @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-4">Hak Akses (Permissions)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($permissions as $group => $perms)
                                    <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                                        <h4 class="font-bold text-sm text-violet-600 dark:text-violet-400 uppercase mb-3 border-b border-slate-200 pb-1">{{ $group }}</h4>
                                        <div class="space-y-2">
                                            @foreach($perms as $perm)
                                                <label class="flex items-center gap-3 cursor-pointer group hover:bg-white dark:hover:bg-slate-800 p-1 rounded transition">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm }}" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-violet-500 checked:bg-violet-500 hover:shadow-sm">
                                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 14 14" fill="none">
                                                            <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm text-slate-600 dark:text-slate-300 font-medium group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ ucwords(str_replace('_', ' ', $perm)) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedPermissions') <span class="text-rose-500 text-xs block mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                        @if($roleId)
                            <button wire:click="delete({{ $roleId }})" wire:confirm="Hapus role ini? Pastikan tidak ada user yang menggunakannya." class="mr-auto px-4 py-2 text-rose-500 font-bold hover:bg-rose-50 rounded-lg transition text-sm">Hapus Role</button>
                        @endif
                        <button wire:click="$set('showForm', false)" class="px-6 py-2 text-slate-500 font-bold hover:bg-slate-100 rounded-lg transition">Batal</button>
                        <button wire:click="save" class="px-8 py-2 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-lg shadow-lg transition transform hover:-translate-y-0.5">Simpan Perubahan</button>
                    </div>
                </div>
            @else
                <div class="h-full min-h-[400px] flex flex-col items-center justify-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl bg-slate-50/30">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <p class="font-medium text-lg text-slate-500">Pilih role untuk mengedit</p>
                    <p class="text-sm">Atau buat role baru untuk tim Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
