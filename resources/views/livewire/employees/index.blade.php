<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Human <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Resources</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen akses, data personalia, dan struktur organisasi.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            Pegawai Baru
        </button>
    </div>

    <!-- Form Modal/Card -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transform transition-all scale-100">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $userId ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru' }}</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <form wire:submit.prevent="save" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Info -->
                    <div class="md:col-span-2">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informasi Dasar</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Email (Login)</label>
                        <input wire:model="email" type="email" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                        @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">No. WhatsApp</label>
                        <input wire:model="phone" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Jabatan / Role</label>
                        <select wire:model="role" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                            <option value="technician">Teknisi</option>
                            <option value="cashier">Kasir</option>
                            <option value="warehouse">Gudang / Logistik</option>
                            <option value="admin">Administrator</option>
                            <option value="hr">HRD</option>
                        </select>
                    </div>

                    <!-- Security & Payroll -->
                    <div class="md:col-span-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Keamanan & Gaji</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Gaji Pokok (Basic Salary)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">Rp</span>
                            <input wire:model="salary" type="number" class="w-full pl-10 rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Password {{ $userId ? '(Kosongkan jika tetap)' : '' }}</label>
                        <input wire:model="password" type="password" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white transition-all">
                        @error('password') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3 mt-4">
                        <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-all">Batal</button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Search Toolbar -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all" placeholder="Cari nama atau email pegawai...">
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
            Menampilkan {{ $employees->total() }} pegawai aktif
        </div>
    </div>

    <!-- Employee Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($employees as $emp)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
                <!-- Status Indicator -->
                <div class="absolute top-4 right-4 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-full border border-emerald-100 dark:border-emerald-800/50">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Aktif</span>
                </div>

                <div class="p-6 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900 dark:to-indigo-900 flex items-center justify-center text-2xl font-black text-blue-600 dark:text-blue-300 mb-4 shadow-inner border border-blue-50 dark:border-blue-800">
                        {{ substr($emp->name, 0, 1) }}
                    </div>
                    
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight">{{ $emp->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $emp->email }}</p>
                    
                    <div class="flex gap-2 mb-6">
                        <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-wide border border-slate-200 dark:border-slate-600">
                            {{ ucfirst($emp->role) }}
                        </span>
                    </div>

                    <div class="w-full grid grid-cols-2 gap-2 border-t border-slate-100 dark:border-slate-700 pt-4 mt-auto">
                        <button wire:click="edit({{ $emp->id }})" class="py-2 px-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-sm font-bold hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                            Edit
                        </button>
                        @if($emp->id !== auth()->id())
                            <button wire:click="delete({{ $emp->id }})" wire:confirm="Yakin ingin menghapus pegawai ini? Akses akan dicabut permanen." class="py-2 px-4 rounded-lg bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-sm font-bold hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors">
                                Hapus
                            </button>
                        @else
                            <button disabled class="py-2 px-4 rounded-lg bg-slate-50 text-slate-400 text-sm font-bold cursor-not-allowed">
                                Saya
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tidak Ada Data</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Belum ada pegawai yang sesuai dengan pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $employees->links() }}
    </div>
</div>