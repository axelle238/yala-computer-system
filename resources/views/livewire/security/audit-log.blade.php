<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Audit <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Keamanan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Rekam jejak digital lengkap aktivitas sistem.</p>
        </div>
        <div class="relative w-64">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari log..." class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-blue-500">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Aksi</th>
                        <th class="px-6 py-4">Detail</th>
                        <th class="px-6 py-4">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-slate-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold uppercase bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $log->description }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Tidak ada data log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
            {{ $logs->links() }}
        </div>
    </div>
</div>
