<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Status <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-cyan-500">Server</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm border-l-2 border-emerald-500 pl-3 italic">
                Monitor kesehatan infrastruktur, spesifikasi teknis, dan lingkungan operasional sistem.
            </p>
        </div>
        <div class="flex items-center gap-3">
             <span class="px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-500 font-mono">
                IP: {{ $clientIp }}
            </span>
             <button wire:click="$refresh" class="p-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-500/30 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Laravel Version -->
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-red-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Framework Core</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white font-mono">v{{ $laravelVersion }}</h3>
                <p class="text-xs font-bold text-red-500 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2-1-2-1-2 1 2 1zm0-3l2-1-2-1-2 1 2 1zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    Laravel
                </p>
            </div>
        </div>

        <!-- PHP Version -->
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Runtime Engine</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white font-mono">v{{ $phpVersion }}</h3>
                <p class="text-xs font-bold text-indigo-500 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    PHP
                </p>
            </div>
        </div>

        <!-- Database -->
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-500/10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Database Size</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white font-mono">{{ $databaseSize }}</h3>
                <p class="text-xs font-bold text-amber-500 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                    {{ ucfirst($databaseConnection) }}
                </p>
            </div>
        </div>

        <!-- Environment -->
        <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
             <div class="absolute -right-6 -bottom-6 w-24 h-24 {{ $environment === 'production' ? 'bg-emerald-500/10' : 'bg-rose-500/10' }} rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Environment</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase">{{ $environment }}</h3>
                <p class="text-xs font-bold {{ $environment === 'production' ? 'text-emerald-500' : 'text-rose-500' }} mt-2 flex items-center gap-1">
                    @if($debugMode) 
                        <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded-md">Debug: ON</span>
                    @else
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md">Optimized</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Disk Usage Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight">Penyimpanan Server (Disk)</h3>
            <span class="font-mono font-bold text-slate-500 text-sm">{{ $diskUsed }} / {{ $diskTotal }}</span>
        </div>
        <div class="w-full h-4 bg-slate-100 dark:bg-slate-900 rounded-full overflow-hidden">
            <div class="h-full {{ $diskUsagePercent > 80 ? 'bg-rose-500' : ($diskUsagePercent > 50 ? 'bg-amber-500' : 'bg-emerald-500') }} rounded-full transition-all duration-1000 ease-out relative" style="width: {{ $diskUsagePercent }}%">
                <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
            </div>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                <span class="block text-[10px] font-black uppercase text-slate-400">Total</span>
                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $diskTotal }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                <span class="block text-[10px] font-black uppercase text-slate-400">Terpakai</span>
                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $diskUsed }}</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                <span class="block text-[10px] font-black uppercase text-slate-400">Tersedia</span>
                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $diskFree }}</span>
            </div>
        </div>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Configuration Details -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6">Konfigurasi PHP & Server</h3>
            <div class="space-y-4">
                @foreach([
                    'Operating System' => $os,
                    'Server Software' => $serverSoftware,
                    'Timezone' => $timezone,
                    'Memory Limit' => $memoryLimit,
                    'Upload Max Size' => $uploadMaxFilesize,
                    'Post Max Size' => $postMaxSize,
                    'Max Execution Time' => $maxExecutionTime . 's',
                ] as $label => $value)
                    <div class="flex items-center justify-between p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors border-b border-slate-100 dark:border-slate-700/50 last:border-0">
                        <span class="font-bold text-sm text-slate-500 dark:text-slate-400">{{ $label }}</span>
                        <span class="font-mono font-bold text-sm text-slate-800 dark:text-slate-200 text-right truncate max-w-[200px] md:max-w-md">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Driver Status -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-6">Driver Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-500">Cache</span>
                        <span class="px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase">{{ $cacheDriver }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-500">Queue</span>
                        <span class="px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase">{{ $queueDriver }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-500">Session</span>
                        <span class="px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-black uppercase">{{ $sessionDriver }}</span>
                    </div>
                </div>
            </div>

            <!-- Extensions -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 shadow-xl shadow-indigo-500/20 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-lg font-black uppercase tracking-tight mb-4 relative z-10">PHP Extensions</h3>
                <p class="text-indigo-100 text-sm font-medium mb-6 relative z-10">
                    {{ count($extensions) }} module terpasang aktif.
                </p>
                <div class="flex flex-wrap gap-2 relative z-10">
                    @foreach(array_slice($extensions, 0, 10) as $ext)
                        <span class="px-2 py-1 rounded-md bg-white/20 text-[10px] font-bold uppercase backdrop-blur-sm">{{ $ext }}</span>
                    @endforeach
                    @if(count($extensions) > 10)
                        <span class="px-2 py-1 rounded-md bg-white/10 text-[10px] font-bold uppercase">+{{ count($extensions) - 10 }} more</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>