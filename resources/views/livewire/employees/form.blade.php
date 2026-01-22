<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white">
            {{ $employee ? 'Edit Pegawai' : 'Tambah Pegawai Baru' }}
        </h2>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                <input wire:model="name" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 font-bold">
                @error('name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email Login</label>
                <input wire:model="email" type="email" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 font-mono">
                @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Role Akses</label>
                    <select wire:model="role" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500 font-bold">
                        <option value="staff">Staff (Kasir/Gudang)</option>
                        <option value="technician">Technician (Teknisi)</option>
                        <option value="admin">Administrator (Full Akses)</option>
                    </select>
                    @error('role') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Password {{ $employee ? '(Kosongkan jika tidak ubah)' : '' }}
                    </label>
                    <input wire:model="password" type="password" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-cyan-500">
                    @error('password') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('employees.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Batal</a>
                <button wire:click="save" class="px-8 py-3 bg-slate-900 dark:bg-cyan-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>
</div>
