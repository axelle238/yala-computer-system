<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                {{ $supplier_id ? 'Edit' : 'New' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Supplier</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Database vendor dan pemasok barang.</p>
        </div>
        <a href="{{ route('master.suppliers') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-bold hover:border-emerald-500 hover:text-emerald-500 transition-all shadow-sm">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-emerald-900/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-bl-full pointer-events-none"></div>
        
        <div class="relative z-10 space-y-8">
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
                Profil Supplier
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Perusahaan / Supplier</label>
                    <input wire:model="name" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-bold text-slate-800 dark:text-white placeholder-slate-400" placeholder="Nama Supplier">
                    @error('name') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon / WA</label>
                        <input wire:model="phone" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono text-slate-700 dark:text-slate-300" placeholder="08...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                        <input wire:model="email" type="email" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="contact@supplier.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                    <textarea wire:model="address" rows="3" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="Alamat kantor/gudang..."></textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-2xl font-black text-lg shadow-xl shadow-emerald-500/30 transition-all hover:-translate-y-1 hover:shadow-emerald-500/50 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ $supplier_id ? 'SIMPAN PERUBAHAN' : 'SIMPAN SUPPLIER' }}
                </button>
            </div>
        </div>
    </form>
</div>