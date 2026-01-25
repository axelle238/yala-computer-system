<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Activity <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-fuchsia-500">Logs</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Audit trail aktivitas pengguna untuk keamanan sistem.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-violet-500 text-sm" placeholder="Cari Log (User, Deskripsi)...">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select wire:model.live="filterAction" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 cursor-pointer">
            <option value="">Semua Aksi</option>
            <option value="create">Create</option>
            <option value="update">Update</option>
            <option value="delete">Delete</option>
            <option value="login">Login</option>
        </select>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Aksi</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-violet-50/30 dark:hover:bg-violet-900/10 transition-colors">
                            <td class="px-6 py-4 font-mono text-slate-500 text-xs">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300">
                                        {{ substr($log->user->name ?? 'System', 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $color = match($log->action) {
                                        'create' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'update' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'delete' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        'login' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400',
                                        default => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase {{ $color }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 truncate max-w-xs" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $logs->links() }}
        </div>
    </div>
</div>
