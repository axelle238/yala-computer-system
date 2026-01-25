<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Database <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Monitoring kesehatan database, struktur tabel, dan cadangan data.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="optimize" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-bold rounded-xl transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Optimize
            </button>
            <button wire:click="createBackup" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-600/30 transition flex items-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    Backup Database
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memproses...
                </span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Table Status -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-[500px]">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Struktur & Kapasitas Tabel</h3>
                <span class="text-xs font-bold text-amber-600 bg-amber-100 dark:bg-amber-900/20 px-2 py-1 rounded">Total Size: {{ $totalSize }} MB</span>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3">Nama Tabel</th>
                            <th class="px-6 py-3 text-right">Jumlah Baris</th>
                            <th class="px-6 py-3 text-right">Ukuran (MB)</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($tables as $table)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-3 font-mono text-slate-700 dark:text-slate-300">{{ $table['name'] }}</td>
                                <td class="px-6 py-3 text-right font-bold">{{ number_format($table['rows']) }}</td>
                                <td class="px-6 py-3 text-right text-slate-500">{{ $table['size'] }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> OK
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Tidak dapat membaca info tabel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Backup List -->
        <div class="lg:col-span-1 bg-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Riwayat Backup
            </h3>

            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @forelse($backups as $index => $backup)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 hover:bg-white/10 transition-colors group relative">
                        <div class="flex justify-between items-start mb-2">
                            <div class="font-bold text-sm text-amber-400 truncate w-40" title="{{ $backup['name'] }}">{{ $backup['name'] }}</div>
                            <span class="text-[10px] text-slate-400">{{ $backup['date']->format('d M') }}</span>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-mono text-slate-300">{{ $backup['size'] }}</span>
                            <div class="flex gap-2">
                                <button wire:click="download('{{ $backup['name'] }}')" class="p-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white rounded transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </button>
                                <button wire:click="delete({{ $index }})" class="p-1.5 bg-rose-600/20 hover:bg-rose-600 text-rose-400 hover:text-white rounded transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500 text-sm">Belum ada file backup.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>