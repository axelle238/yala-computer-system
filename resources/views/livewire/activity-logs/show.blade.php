<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Detail <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-indigo-600">Aktivitas</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium">Log ID: #{{ $log->id }}</p>
        </div>
        <a href="{{ route('activity-logs.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden p-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 border-b border-slate-100 dark:border-slate-700 pb-8">
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">User</label>
                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $log->user->name ?? 'System' }}</p>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</label>
                    <p class="text-lg font-mono text-slate-700 dark:text-slate-300">{{ $log->created_at->format('d F Y, H:i:s') }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe Aksi</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 capitalize border border-slate-200 dark:border-slate-600">
                            {{ $log->action }}
                        </span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">IP Address</label>
                    <p class="text-lg font-mono text-slate-700 dark:text-slate-300">{{ $log->ip_address }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-2 mb-8">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</label>
            <p class="text-base text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                {{ $log->description }}
            </p>
        </div>

        @if(isset($log->properties['old']) || isset($log->properties['new']))
            <div>
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 block">Perubahan Data</label>
                <div class="bg-slate-50 dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <table class="w-full text-left text-sm font-mono">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-800 text-slate-500 font-bold border-b border-slate-200 dark:border-slate-700">
                                <th class="px-6 py-3 w-1/3">Field</th>
                                <th class="px-6 py-3 w-1/3 text-rose-600">Sebelum</th>
                                <th class="px-6 py-3 w-1/3 text-emerald-600">Sesudah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @php 
                                $old = $log->properties['old'] ?? [];
                                $new = $log->properties['new'] ?? [];
                                $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
                            @endphp
                            @foreach($keys as $key)
                                @if(isset($old[$key]) || isset($new[$key]))
                                    <tr class="{{ (isset($old[$key]) && isset($new[$key]) && $old[$key] != $new[$key]) ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
                                        <td class="px-6 py-3 font-bold text-slate-700 dark:text-slate-300">{{ $key }}</td>
                                        <td class="px-6 py-3 text-rose-600 dark:text-rose-400 break-all">{{ is_array($old[$key] ?? null) ? json_encode($old[$key]) : ($old[$key] ?? '-') }}</td>
                                        <td class="px-6 py-3 text-emerald-600 dark:text-emerald-400 break-all">{{ is_array($new[$key] ?? null) ? json_encode($new[$key]) : ($new[$key] ?? '-') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
