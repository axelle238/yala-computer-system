<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Backup Manager</h1>
            <p class="text-slate-500 dark:text-slate-400">Kelola cadangan database sistem.</p>
        </div>
        <button wire:click="createBackup" wire:loading.attr="disabled" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-slate-500/20 transition-all flex items-center gap-2">
            <span wire:loading.remove wire:target="createBackup">BUAT BACKUP BARU</span>
            <span wire:loading wire:target="createBackup">MEMPROSES...</span>
        </button>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-700 text-xs uppercase font-bold text-slate-500">
                <tr>
                    <th class="px-6 py-4">Nama File</th>
                    <th class="px-6 py-4">Ukuran</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($backups as $backup)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">{{ $backup['name'] }}</td>
                    <td class="px-6 py-4">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                    <td class="px-6 py-4 text-slate-500">{{ date('d M Y H:i', $backup['last_modified']) }}</td>
                    <td class="px-6 py-4 text-center flex justify-center gap-2">
                        <button wire:click="download('{{ $backup['path'] }}')" class="text-blue-600 hover:underline font-bold">Download</button>
                        <span class="text-slate-300">|</span>
                        <button wire:click="delete('{{ $backup['path'] }}')" wire:confirm="Hapus backup ini?" class="text-rose-600 hover:underline font-bold">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">Belum ada file backup.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
