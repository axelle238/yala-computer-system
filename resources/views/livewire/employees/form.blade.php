<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $user ? 'Edit User' : 'Tambah User Baru' }}
            </h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Konfigurasi akun dan hak akses sistem.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Basic Info -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Identitas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('name') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                        <input wire:model="email" type="email" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('email') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password {{ $user ? '(Biarkan kosong jika tidak diganti)' : '' }}</label>
                        <input wire:model="password" type="password" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('password') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Employment Info (New) -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Kepegawaian</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Gaji Pokok</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">Rp</span>
                            <input wire:model="base_salary" type="number" class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        @error('base_salary') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Bergabung</label>
                        <input wire:model="join_date" type="date" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Role & Permissions -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Role & Akses</h3>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tipe User (Role)</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="role" value="employee" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-center hover:bg-slate-50 transition-all">
                                <span class="block font-bold text-slate-800">Pegawai</span>
                                <span class="text-xs text-slate-500">Akses terbatas (Custom)</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="role" value="owner" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 text-center hover:bg-slate-50 transition-all">
                                <span class="block font-bold text-slate-800">Pemilik</span>
                                <span class="text-xs text-slate-500">View Only + Laporan</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="role" value="admin" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-slate-800 peer-checked:bg-slate-100 text-center hover:bg-slate-50 transition-all">
                                <span class="block font-bold text-slate-800">Admin</span>
                                <span class="text-xs text-slate-500">Full Control</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Manual Access Rights -->
                @if($role === 'employee')
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-4">Pilih Hak Akses Manual</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($availablePermissions as $key => $label)
                                <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all">
                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $key }}" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95">
                    Simpan User
                </button>
            </div>
        </div>
    </form>
</div>