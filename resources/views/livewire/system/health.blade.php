<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                System <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500">Health</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Monitoring performa server dan aplikasi.</p>
        </div>
        <button wire:click="clearCache" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            Bersihkan Cache
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- DB Status -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Database</p>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white {{ $metrics['database']['color'] }}">{{ $metrics['database']['status'] }}</h3>
                    <p class="text-xs text-slate-400">Latency: {{ $metrics['database']['latency'] }}</p>
                </div>
            </div>
        </div>

        <!-- Disk Status -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Disk Usage</p>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $metrics['disk']['percent'] }}%</h3>
                    <p class="text-xs text-slate-400">Used: {{ $metrics['disk']['used'] }} / Free: {{ $metrics['disk']['free'] }}</p>
                </div>
            </div>
        </div>

        <!-- App Info -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Environment</p>
                <div class="mt-2 space-y-1 text-sm font-mono">
                    <div class="flex justify-between"><span>Laravel</span> <span class="font-bold text-emerald-400">{{ $metrics['server']['laravel'] }}</span></div>
                    <div class="flex justify-between"><span>PHP</span> <span class="font-bold text-blue-400">{{ $metrics['server']['php'] }}</span></div>
                    <div class="flex justify-between"><span>Server</span> <span class="font-bold text-amber-400">{{ $metrics['server']['server'] }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>