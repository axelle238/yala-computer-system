<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Analisis <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Stok</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Intelijen bisnis untuk pergerakan inventaris dan kesehatan persediaan.</p>
        </div>
        <button wire:click="generateReport" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"/></svg>
            Perbarui Data
        </button>
    </div>

    <!-- Konten Laporan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Fast Moving & Stok Menipis -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Produk Terlaris (Fast Moving) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Produk Terlaris (30 Hari)</h3>
                        <p class="text-xs text-slate-500">Berdasarkan kuantitas unit keluar.</p>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($reportData['laku_pesat'] as $item)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <div class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ $item->product->name }}</div>
                            <div class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 rounded-full text-xs font-black">{{ $item->total_terjual }} Unit</div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">Tidak ada data penjualan yang signifikan.</div>
                    @endforelse
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50">
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Peringatan Stok Menipis</h3>
                        <p class="text-xs text-slate-500">Stok saat ini di bawah atau sama dengan batas minimum.</p>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($reportData['stok_menipis'] as $item)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <div class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ $item->name }}</div>
                            <div class="px-3 py-1 bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-300 rounded-full text-xs font-black">Sisa {{ $item->stock_quantity }} Unit</div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">Seluruh stok dalam kondisi aman.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Stok Mati -->
        <div class="lg:col-span-1">
            <div class="bg-rose-600/10 dark:bg-rose-900/20 p-6 rounded-2xl border border-rose-200 dark:border-rose-800 h-full">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-2 bg-rose-100 dark:bg-rose-900 text-rose-600 dark:text-rose-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Stok Mati (90 Hari)</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tidak ada penjualan dalam 3 bulan terakhir.</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($reportData['stok_mati'] as $item)
                        <div class="bg-white dark:bg-slate-800 p-3 rounded-lg flex justify-between items-center shadow-sm">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $item->name }}</span>
                            <span class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded">{{ $item->stock_quantity }} Unit</span>
                        </div>
                    @empty
                        <div class="text-center py-10 text-sm text-slate-500 dark:text-slate-400">Tidak ditemukan stok mati.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>