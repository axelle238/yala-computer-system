<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Data <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-500">Backup</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Amankan data sistem secara berkala untuk pemulihan darurat.</p>
        </div>
        <button wire:click="createBackup" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:-translate-y-0.5 transition-all flex items-center gap-2 border border-white/10" wire:loading.attr="disabled">
            <span wire:loading.remove class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                Buat Backup Baru
            </span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memproses...
            </span>
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/5 rounded-bl-full pointer-events-none"></div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama File</th>
                        <th class="px-6 py-4">Ukuran</th>
                        <th class="px-6 py-4">Tanggal Dibuat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($backups as $index => $backup)
                        <tr class="hover:bg-cyan-50/30 dark:hover:bg-cyan-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                    </div>
                                    <span class="font-mono text-slate-700 dark:text-slate-300 font-medium">{{ $backup['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-600 dark:text-slate-400">{{ $backup['size'] }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-700 text-xs font-medium">
                                    {{ $backup['date']->diffForHumans() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <button wire:click="download('{{ $backup['name'] }}')" class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Unduh">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </button>
                                <button wire:click="delete({{ $index }})" class="p-2 rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors" title="Hapus" onclick="confirm('Hapus file backup ini?') || event.stopImmediatePropagation()">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <span>Belum ada file backup tersedia.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>