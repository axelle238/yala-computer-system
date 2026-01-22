<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                System <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-indigo-600">Sentinel</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Security audit trail & activity monitoring.</p>
        </div>
        
        <!-- Search -->
        <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                class="block w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm shadow-sm focus:ring-2 focus:ring-violet-500/50 dark:text-white transition-all placeholder-slate-400" 
                placeholder="Search logs by user, action, or object..."
            >
        </div>
    </div>

    <!-- Timeline Container -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden relative">
        <!-- Decorative Grid -->
        <div class="absolute inset-0 grid-pattern opacity-5 pointer-events-none"></div>

        <div class="p-8">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @forelse($logs as $log)
                        <li>
                            <div class="relative pb-8 group">
                                @if(!$loop->last)
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-slate-100 dark:bg-slate-700 group-hover:bg-violet-100 dark:group-hover:bg-violet-900 transition-colors" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-4">
                                    <div class="relative">
                                        <span class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-slate-800 transition-transform group-hover:scale-110 
                                            {{ $log->action == 'created' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : 
                                              ($log->action == 'deleted' ? 'bg-gradient-to-br from-rose-500 to-red-600' : 
                                              ($log->action == 'updated' ? 'bg-gradient-to-br from-blue-500 to-indigo-600' : 'bg-slate-500')) }}">
                                            @if($log->action == 'created')
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                            @elseif($log->action == 'deleted')
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            @elseif($log->action == 'updated')
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            @else
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 py-0">
                                        <div class="text-sm">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-bold text-slate-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                                                <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $log->created_at->format('d M Y H:i:s') }}</span>
                                            </div>
                                            
                                            <!-- Narrative Log Description -->
                                            <p class="text-slate-600 dark:text-slate-300">
                                                @if($log->action == 'created')
                                                    Menambahkan data baru pada <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ class_basename($log->model_type) }}</span>
                                                @elseif($log->action == 'updated')
                                                    Melakukan perubahan pada <span class="font-bold text-blue-600 dark:text-blue-400">{{ class_basename($log->model_type) }}</span>
                                                @elseif($log->action == 'deleted')
                                                    Menghapus data dari <span class="font-bold text-rose-600 dark:text-rose-400">{{ class_basename($log->model_type) }}</span>
                                                @else
                                                    {{ $log->description }}
                                                @endif
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 ml-2 border border-slate-200 dark:border-slate-600 font-mono">
                                                    ID: #{{ $log->model_id }}
                                                </span>
                                            </p>
                                        </div>
                                        
                                        <!-- Visual Diff for Updates -->
                                        @if($log->action == 'updated' && isset($log->properties['old']) && isset($log->properties['new']))
                                            <div class="mt-3 bg-slate-50 dark:bg-slate-900 rounded-xl p-3 border border-slate-100 dark:border-slate-700 text-xs font-mono overflow-x-auto">
                                                <table class="w-full text-left">
                                                    <thead>
                                                        <tr class="text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                                            <th class="pb-2 w-1/3">Field</th>
                                                            <th class="pb-2 w-1/3 text-rose-500">Sebelum</th>
                                                            <th class="pb-2 w-1/3 text-emerald-500">Sesudah</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                        @foreach(array_keys($log->properties['new']) as $key)
                                                            @if(isset($log->properties['old'][$key]) && $log->properties['old'][$key] != $log->properties['new'][$key])
                                                                <tr>
                                                                    <td class="py-2 font-bold text-slate-600 dark:text-slate-400">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                                    <td class="py-2 text-rose-600 dark:text-rose-400 truncate max-w-xs" title="{{ $log->properties['old'][$key] }}">
                                                                        {{ Str::limit($log->properties['old'][$key], 30) }}
                                                                    </td>
                                                                    <td class="py-2 text-emerald-600 dark:text-emerald-400 truncate max-w-xs" title="{{ $log->properties['new'][$key] }}">
                                                                        {{ Str::limit($log->properties['new'][$key], 30) }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                        <div class="mt-2 text-[10px] text-slate-400 flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                            {{ $log->ip_address }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Secure & Quiet</h3>
                            <p class="text-slate-500 dark:text-slate-400 mt-1">Belum ada aktivitas mencurigakan atau tercatat.</p>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
        
        <div class="bg-slate-50 dark:bg-slate-900/50 p-6 border-t border-slate-200 dark:border-slate-700">
            {{ $logs->links() }}
        </div>
    </div>
</div>
