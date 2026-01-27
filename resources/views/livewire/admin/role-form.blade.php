<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                {{ $isEdit ? 'Edit' : 'Buat' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Peran</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Tentukan nama jabatan dan hak akses sistem.</p>
        </div>
        <a href="{{ route('admin.karyawan.peran.indeks') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="space-y-8">
        <!-- Nama Peran -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Peran / Jabatan</label>
            <input wire:model="nama" type="text" class="w-full md:w-1/2 rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-blue-500 focus:border-blue-500 dark:text-white" placeholder="Contoh: Manager Gudang">
            @error('nama') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Hak Akses -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Pilih Hak Akses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($permissionsList as $group => $perms)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:border-blue-500/50 transition-colors">
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-4 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> {{ $group }}
                        </h4>
                        <div class="space-y-3">
                            @foreach($perms as $key => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input wire:model="hak_akses" value="{{ $key }}" type="checkbox" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-slate-300 dark:border-slate-600 checked:border-blue-500 checked:bg-blue-500 transition-all">
                                        <svg class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none opacity-0 peer-checked:opacity-100 text-white transition-opacity" viewBox="0 0 14 14" fill="none">
                                            <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Peran' }}
            </button>
        </div>
    </form>
</div>
