<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $customer_id ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru' }}
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">Kelola data keanggotaan dan kontak pelanggan.</p>
        </div>
        <a href="{{ route('customers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Identitas & Kontak</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="block w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white" placeholder="Nama Pelanggan">
                        @error('name') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor Telepon (WhatsApp)</label>
                            <input wire:model="phone" type="text" class="block w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white" placeholder="08...">
                            @error('phone') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Email (Opsional)</label>
                            <input wire:model="email" type="email" class="block w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all dark:text-white" placeholder="email@example.com">
                            @error('email') <span class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $customer_id ? 'Simpan Perubahan' : 'Simpan Pelanggan' }}
                </button>
            </div>
        </div>
    </form>
</div>
