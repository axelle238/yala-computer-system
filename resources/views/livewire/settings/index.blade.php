<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <div>
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
            System <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-600 to-slate-400">Settings</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Konfigurasi global aplikasi toko.</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            <!-- General Settings -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Identitas Toko</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Toko</label>
                        <input wire:model="store_name" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">WhatsApp Admin (Format: 628...)</label>
                        <input wire:model="whatsapp_number" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500 font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">Digunakan untuk fitur checkout & notifikasi.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Fisik</label>
                    <textarea wire:model="address" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-cyan-500"></textarea>
                </div>
            </div>

            <!-- System Toggle -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Kontrol Sistem</h3>
                
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div>
                        <span class="block font-bold text-slate-800 dark:text-white">Maintenance Mode</span>
                        <span class="text-xs text-slate-500">Jika aktif, hanya admin yang bisa mengakses sistem.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="maintenance_mode" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 dark:peer-focus:ring-cyan-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-cyan-600"></div>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button wire:click="save" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>