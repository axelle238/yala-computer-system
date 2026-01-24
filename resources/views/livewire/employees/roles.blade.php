<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Role & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Permissions</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur hak akses pengguna secara granular.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Role Baru
        </button>
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative group hover:border-blue-400 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $role->name }}</h3>
                        <span class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-500">{{ $role->slug }}</span>
                    </div>
                    @if(!in_array($role->slug, ['admin', 'owner']))
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="edit({{ $role->id }})" class="p-2 bg-slate-100 dark:bg-slate-700 text-blue-600 rounded-lg hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                            <button wire:click="delete({{ $role->id }})" onclick="confirm('Hapus role ini?') || event.stopImmediatePropagation()" class="p-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 rounded-lg hover:bg-rose-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    @else
                        <span class="text-xs font-bold text-amber-500 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded">System</span>
                    @endif
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-bold uppercase text-slate-400">Permissions:</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($role->permissions->take(5) as $perm)
                            <span class="text-[10px] bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 px-2 py-1 rounded text-slate-600 dark:text-slate-300">
                                {{ $perm->name }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 italic">Tidak ada izin khusus</span>
                        @endforelse
                        @if($role->permissions->count() > 5)
                            <span class="text-[10px] text-slate-400">+{{ $role->permissions->count() - 5 }} more</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 dark:text-white text-lg">{{ $editingRoleId ? 'Edit Role' : 'Role Baru' }}</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Role</label>
                            <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                            @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Slug (Unik)</label>
                            <input type="text" wire:model="slug" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500" placeholder="e.g. supervisor">
                            @error('slug') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-3">Pilih Hak Akses (Permissions)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($permissions as $perm)
                                <label class="flex items-center gap-2 p-2 rounded-lg border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm->id }}" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300 select-none">{{ $perm->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="$set('showForm', false)" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-bold text-sm">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Role</button>
                </div>
            </div>
        </div>
    @endif
</div>
