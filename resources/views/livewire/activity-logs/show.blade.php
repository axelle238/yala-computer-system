<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Detail <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-500 to-fuchsia-500">Aktivitas</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm font-medium flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-xs font-mono font-bold">#{{ $log->id }}</span>
                <span>{{ $log->created_at->isoFormat('dddd, D MMMM Y, HH:mm:ss') }}</span>
            </p>
        </div>
        <a href="{{ route('admin.log-aktivitas.indeks') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden relative group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/5 rounded-bl-full pointer-events-none group-hover:bg-violet-500/10 transition-colors"></div>
        
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 dark:border-slate-700/50">
            <!-- User Info -->
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center text-xl font-bold text-slate-600 dark:text-slate-300 shadow-inner">
                    {{ substr($log->user->name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Pelaku (User)</label>
                    <p class="text-lg font-bold text-slate-900 dark:text-white leading-tight">{{ $log->user->name ?? 'System' }}</p>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $log->user->email ?? '-' }}</p>
                </div>
            </div>

            <!-- Action Info -->
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                    @if($log->action == 'create') <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    @elseif($log->action == 'update') <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    @elseif($log->action == 'delete') <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    @else <svg class="w-6 h-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Jenis Aksi</label>
                    @php
                        $badgeColor = match($log->action) {
                            'create' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                            'update' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                            'delete' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                            'login' => 'bg-violet-100 text-violet-700 border-violet-200 dark:bg-violet-900/30 dark:text-violet-400 dark:border-violet-800',
                            default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600'
                        };
                        $actionLabel = match($log->action) {
                            'create' => 'Pembuatan Data',
                            'update' => 'Pembaruan Data',
                            'delete' => 'Penghapusan Data',
                            'login' => 'Login Masuk',
                            default => $log->action
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold uppercase border {{ $badgeColor }}">
                        {{ $actionLabel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Narrative Story (Kompleks & Naratif) -->
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl p-6 border border-indigo-100 dark:border-indigo-800 relative">
                <div class="absolute top-4 right-4">
                    <span class="text-xs font-mono text-indigo-400">{{ $log->ip_address }}</span>
                </div>
                <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-200 uppercase tracking-wider mb-3">Kronologi Kejadian</h4>
                <div class="prose prose-sm dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                    <p>
                        Pada hari <strong>{{ $log->created_at->isoFormat('dddd') }}</strong>, tanggal <strong>{{ $log->created_at->isoFormat('D MMMM Y') }}</strong> pukul <strong>{{ $log->created_at->format('H:i') }}</strong>, 
                        pengguna <strong>{{ $log->user->name ?? 'System' }}</strong> melakukan aktivitas <strong>{{ strtolower($actionLabel) }}</strong> 
                        pada modul <strong>{{ class_basename($log->model_type) }}</strong>.
                    </p>
                    <p class="mt-2">{{ $log->description }}</p>

                    <!-- Auto-Generated Narrative from Changes -->
                    @if(isset($log->properties['old']) || isset($log->properties['new']))
                        <div class="mt-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-indigo-100 dark:border-slate-700 shadow-sm">
                            <strong class="block mb-2 text-indigo-600 dark:text-indigo-400">Rincian Perubahan:</strong>
                            <ul class="list-disc list-inside space-y-1">
                                @php 
                                    $old = $log->properties['old'] ?? [];
                                    $new = $log->properties['new'] ?? [];
                                    $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
                                @endphp
                                @foreach($keys as $key)
                                    @php
                                        $label = $this->readableKey($key);
                                        $valOld = isset($old[$key]) ? $this->formatValue($key, $old[$key]) : null;
                                        $valNew = isset($new[$key]) ? $this->formatValue($key, $new[$key]) : null;
                                    @endphp

                                    @if(isset($old[$key]) && isset($new[$key]) && $old[$key] != $new[$key])
                                        <li>
                                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span> 
                                            telah diubah dari <span class="font-bold text-rose-500">"{{ Str::limit($valOld, 50) }}"</span> 
                                            menjadi <span class="font-bold text-emerald-500">"{{ Str::limit($valNew, 50) }}"</span>.
                                        </li>
                                    @elseif(isset($new[$key]) && !isset($old[$key]))
                                        <li>
                                            Menambahkan <span class="font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span> 
                                            baru dengan nilai <span class="font-bold text-emerald-500">"{{ Str::limit($valNew, 50) }}"</span>.
                                        </li>
                                    @elseif(isset($old[$key]) && !isset($new[$key]))
                                        <li>
                                            Menghapus data <span class="font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span> 
                                            yang sebelumnya bernilai <span class="font-bold text-rose-500">"{{ Str::limit($valOld, 50) }}"</span>.
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Changes Table (Technical View) -->
            @if(isset($log->properties['old']) || isset($log->properties['new']))
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                        Data Teknis (Raw Data)
                    </h4>
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                        <table class="w-full text-left text-sm font-mono">
                            <thead class="bg-slate-100 dark:bg-slate-900/80">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-slate-500 uppercase text-xs w-1/4">Atribut</th>
                                    <th class="px-6 py-4 font-bold text-rose-600 uppercase text-xs w-[37.5%] bg-rose-50/50 dark:bg-rose-900/10">Sebelum</th>
                                    <th class="px-6 py-4 font-bold text-emerald-600 uppercase text-xs w-[37.5%] bg-emerald-50/50 dark:bg-emerald-900/10">Sesudah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-800">
                                @foreach($keys as $key)
                                    @php
                                        $label = $this->readableKey($key);
                                        $valOld = isset($old[$key]) ? $this->formatValue($key, $old[$key]) : null;
                                        $valNew = isset($new[$key]) ? $this->formatValue($key, $new[$key]) : null;
                                    @endphp
                                    @if(isset($old[$key]) || isset($new[$key]))
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors {{ (isset($old[$key]) && isset($new[$key]) && $old[$key] != $new[$key]) ? 'bg-amber-50/30 dark:bg-amber-900/5' : '' }}">
                                            <td class="px-6 py-4 border-r border-slate-100 dark:border-slate-700">
                                                <span class="font-bold text-slate-700 dark:text-slate-300 block">{{ $label }}</span>
                                                <span class="text-xs text-slate-400 font-normal">{{ $key }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-rose-600 dark:text-rose-400 break-all border-r border-slate-100 dark:border-slate-700 leading-relaxed">
                                                {{ $valOld ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 break-all leading-relaxed">
                                                {{ $valNew ?? '-' }}
                                            </td>
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
</div>