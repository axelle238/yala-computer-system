<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h2>
        <button wire:click="save" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 shadow-lg font-bold flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Simpan Perubahan
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Identitas Toko -->
        <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Identitas Toko
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Toko</label>
                    <input type="text" wire:model="form.store_name" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="form.store_address" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" wire:model="form.store_phone" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- Keuangan & POS -->
        <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Keuangan & Kasir
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tarif Pajak PPN (%)</label>
                    <input type="number" wire:model="form.tax_rate" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-slate-500 mt-1">Isi 0 jika harga sudah termasuk pajak.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pesan Footer Struk</label>
                    <input type="text" wire:model="form.receipt_footer" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- Perangkat Keras -->
        <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
            <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Konfigurasi Hardware
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">IP Address Printer Thermal</label>
                    <input type="text" wire:model="form.printer_ip" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="192.168.1.xxx">
                </div>
            </div>
        </div>

    </div>
</div>