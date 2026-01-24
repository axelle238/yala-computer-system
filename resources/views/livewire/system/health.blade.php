<div class="p-6 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">System <span class="text-indigo-500">Health</span></h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Monitoring status server, database, dan log aplikasi.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="runCommand('optimize:clear')" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 transition-colors">
                Clear Cache
            </button>
            <button wire:click="readLogs" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/30">
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Status Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- PHP Version -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">PHP Version</p>
            <h3 class="text-2xl font-black font-mono text-indigo-600">{{ $this->systemInfo['php'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Laravel {{ $this->systemInfo['laravel'] }}</p>
        </div>

        <!-- Database -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Database Status</p>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white truncate max-w-[150px]" title="{{ $this->systemInfo['db_connection'] }}">Connected</h3>
            </div>
            <p class="text-xs text-slate-400 mt-1 truncate">{{ $this->systemInfo['db_connection'] }}</p>
        </div>

        <!-- Storage -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-cyan-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Disk Space</p>
            <h3 class="text-2xl font-black font-mono text-cyan-600">{{ $this->systemInfo['disk_free'] }}</h3>
            <p class="text-xs text-slate-400 mt-1">Free of {{ $this->systemInfo['disk_total'] }}</p>
        </div>

        <!-- Server -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-20 h-20 bg-violet-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Server Software</p>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white line-clamp-2">{{ $this->systemInfo['server'] }}</h3>
        </div>
    </div>

    <!-- Terminal / Log Viewer -->
    <div class="bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 shadow-2xl font-mono text-sm">
        <div class="flex items-center justify-between px-4 py-3 bg-slate-950 border-b border-slate-800">
            <div class="flex gap-2">
                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            </div>
            <span class="text-slate-500 text-xs font-bold">laravel.log (Last 200 lines)</span>
            <button wire:click="clearLogs" class="text-slate-500 hover:text-rose-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>
        <div class="p-4 h-96 overflow-y-auto custom-scrollbar space-y-1 bg-slate-900 text-slate-300">
            @forelse($logContent as $line)
                <div class="hover:bg-slate-800/50 px-2 rounded cursor-default break-words">
                    @if(str_contains($line, '.ERROR'))
                        <span class="text-rose-500 font-bold">[ERROR]</span>
                    @elseif(str_contains($line, '.WARNING'))
                        <span class="text-amber-500 font-bold">[WARNING]</span>
                    @elseif(str_contains($line, '.INFO'))
                        <span class="text-blue-400 font-bold">[INFO]</span>
                    @else
                        <span class="text-slate-600">></span>
                    @endif
                    <span class="ml-2">{{ $line }}</span>
                </div>
            @empty
                <div class="text-center text-slate-600 py-12">Log file is empty or not readable.</div>
            @endforelse
        </div>
    </div>
</div>
