<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                {{ $user ? 'Edit' : 'New' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">Employee</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Manajemen data pegawai dan hak akses sistem.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-bold hover:border-orange-500 hover:text-orange-500 transition-all shadow-sm">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-orange-900/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-500/10 to-amber-500/10 rounded-bl-full pointer-events-none"></div>
        
        <div class="relative z-10 space-y-8">
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </span>
                Profil Pegawai
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all font-bold text-slate-800 dark:text-white placeholder-slate-400" placeholder="Nama Pegawai">
                    @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email (Login)</label>
                    <input wire:model="email" type="email" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="email@yalacomputer.com">
                    @error('email') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                    <input wire:model="password" type="password" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="{{ $user ? 'Isi jika ingin mengubah' : 'Password Login' }}">
                    @error('password') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Role Akses</label>
                    <div class="relative">
                        <select wire:model.live="role" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all appearance-none cursor-pointer font-medium text-slate-700 dark:text-slate-300">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin System</option>
                            <option value="owner">Owner / Pemilik</option>
                            <option value="employee">Employee / Staff</option>
                            @foreach($roles as $r)
                                @if(!in_array($r->name, ['admin', 'owner', 'employee']))
                                    <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    @error('role') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gaji Pokok</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">Rp</span>
                        <input wire:model="base_salary" type="number" class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all font-mono font-bold text-slate-800 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Permissions (Only for Employee) -->
            @if($role === 'employee')
                <div class="pt-6 border-t border-slate-100 dark:border-slate-700 animate-fade-in-up">
                    <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Hak Akses Manual</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($availablePermissions as $key => $label)
                            <label class="flex items-center p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer hover:bg-orange-50 dark:hover:bg-orange-900/20 hover:border-orange-200 transition-all">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $key }}" class="w-5 h-5 rounded text-orange-500 focus:ring-orange-500 border-gray-300">
                                <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-700 hover:to-amber-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-orange-500/30 transition-all hover:-translate-y-1 hover:shadow-orange-500/50 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ $user ? 'SIMPAN PERUBAHAN' : 'TAMBAH PEGAWAI' }}
                </button>
            </div>
        </div>
    </form>
</div>