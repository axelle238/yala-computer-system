<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Informasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-indigo-500">Sistem</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Detail teknis lingkungan server dan aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Kartu Utama -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-bl-full pointer-events-none"></div>
            
            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </span>
                Environment Inti
            </h3>

            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Framework</span>
                    <span class="font-bold text-slate-800 dark:text-white">Laravel v{{ $laravelVersion }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">PHP Version</span>
                    <span class="font-bold text-slate-800 dark:text-white">v{{ $phpVersion }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Web Server</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate max-w-[200px]" title="{{ $serverSoftware }}">{{ $serverSoftware }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Sistem Operasi</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate max-w-[200px]">{{ $os }}</span>
                </div>
            </div>
        </div>

        <!-- Konfigurasi -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden">
             <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-bl-full pointer-events-none"></div>

            <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                </span>
                Konfigurasi Aplikasi
            </h3>

            <div class="space-y-4 relative z-10">
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Database Driver</span>
                    <span class="font-bold text-slate-800 dark:text-white uppercase">{{ $databaseConnection }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Zona Waktu</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $timezone }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Locale (Bahasa)</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $locale }}</span>
                </div>
                 <div class="flex justify-between items-center py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 font-medium">Debug Mode</span>
                    <span class="font-bold {{ config('app.debug') ? 'text-rose-500' : 'text-emerald-500' }}">
                        {{ config('app.debug') ? 'AKTIF (Tidak Aman)' : 'NON-AKTIF (Aman)' }}
                    </span>
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
                <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-600">
                    {{ $ext }}
                </span>
            @endforeach
        </div>
    </div>
</div>
