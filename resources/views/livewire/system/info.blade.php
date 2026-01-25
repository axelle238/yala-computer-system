<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Informasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-500">Sistem</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Detail teknis lingkungan server, database, dan aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Environment Inti -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-blue-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-bl-full pointer-events-none"></div>
            
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                </span>
                Server & Core
            </h3>

            <div class="space-y-3 relative z-10 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Laravel</span>
                    <span class="font-bold text-slate-800 dark:text-white">v{{ $laravelVersion }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">PHP</span>
                    <span class="font-bold text-slate-800 dark:text-white">v{{ $phpVersion }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">OS</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate max-w-[150px]" title="{{ $os }}">{{ $os }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Software</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate max-w-[150px]" title="{{ $serverSoftware }}">{{ $serverSoftware }}</span>
                </div>
            </div>
        </div>

        <!-- Database & Config -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-emerald-500/50 transition-colors">
             <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-bl-full pointer-events-none"></div>

            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                </span>
                Database & App
            </h3>

            <div class="space-y-3 relative z-10 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Driver</span>
                    <span class="font-bold text-slate-800 dark:text-white uppercase">{{ $databaseConnection }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Versi DB</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate max-w-[150px]">{{ $databaseVersion }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Zona Waktu</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $timezone }}</span>
                </div>
                 <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Debug Mode</span>
                    <span class="font-bold {{ config('app.debug') ? 'text-rose-500' : 'text-emerald-500' }}">
                        {{ config('app.debug') ? 'AKTIF' : 'NON-AKTIF' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Limitasi PHP -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-purple-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-bl-full pointer-events-none"></div>
            
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
                Limitasi PHP
            </h3>

            <div class="space-y-3 relative z-10 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Memory Limit</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $memoryLimit }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Upload Max</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $uploadMaxFilesize }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Post Max</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $postMaxSize }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400">Max Exec Time</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $maxExecutionTime }}s</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Info -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-6 shadow-xl text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-lg">Client Information</h4>
                    <p class="text-slate-400 text-sm">Informasi sesi Anda saat ini.</p>
                </div>
            </div>
            <div class="flex flex-col md:items-end text-sm">
                <div class="flex gap-2">
                    <span class="text-slate-400">IP Address:</span>
                    <span class="font-mono font-bold text-cyan-400">{{ $clientIp }}</span>
                </div>
                <div class="flex gap-2">
                    <span class="text-slate-400">User Agent:</span>
                    <span class="font-medium truncate max-w-[300px]" title="{{ $userAgent }}">{{ $userAgent }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ekstensi PHP -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden">
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
            </span>
            Ekstensi PHP Terinstall
        </h3>
        
        <div class="flex flex-wrap gap-2">
            @foreach($extensions as $ext)
                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors cursor-default">
                    {{ $ext }}
                </span>
            @endforeach
        </div>
    </div>
</div>
