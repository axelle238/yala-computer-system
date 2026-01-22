<div class="space-y-6 animate-fade-in-up">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase">
            Access <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-600">Roles</span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- List Roles -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
            <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Daftar Role</h3>
            <div class="space-y-3">
                @foreach($roles as $role)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-600">
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $role->name }}</p>
                            <p class="text-xs text-slate-500">{{ $role->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <button wire:click="delete({{ $role->id }})" class="text-rose-500 hover:text-rose-700 p-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Add Role -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 h-fit">
            <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Tambah Role Baru</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Role</label>
                    <input wire:model="name" type="text" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500">
                    @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500"></textarea>
                </div>
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all">
                    Simpan Role
                </button>
            </form>
        </div>
    </div>
</div>
