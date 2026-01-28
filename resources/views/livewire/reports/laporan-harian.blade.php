<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                Rekapitulasi <span class="text-indigo-600">Harian</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Ringkasan operasional harian sistem Yala Computer.</p>
        </div>
        <div class="flex items-center gap-2 bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <input type="date" wire:model.live="tanggal" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Penjualan Selesai -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-2">Penjualan Selesai</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white">Rp {{ number_format($ringkasan['penjualan'], 0, ',', '.') }}</h3>
        </div>

        <!-- Kas Masuk -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-2">Total Kas Masuk</p>
            <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($ringkasan['kas_masuk'], 0, ',', '.') }}</h3>
        </div>

        <!-- Kas Keluar -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-2">Total Kas Keluar</p>
            <h3 class="text-2xl font-black text-rose-600">Rp {{ number_format($ringkasan['kas_keluar'], 0, ',', '.') }}</h3>
        </div>

        <!-- Stok Keluar -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase mb-2">Item Stok Keluar</p>
            <h3 class="text-2xl font-black text-indigo-600">{{ $ringkasan['stok_keluar'] }} <span class="text-sm font-medium text-slate-400">Unit</span></h3>
        </div>
    </div>

    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 rounded-xl text-blue-700 dark:text-blue-300 text-sm">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <p>Data yang ditampilkan di atas adalah rekapitulasi real-time berdasarkan tanggal yang dipilih.</p>
        </div>
    </div>
</div>
