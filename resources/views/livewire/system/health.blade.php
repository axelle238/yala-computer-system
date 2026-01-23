<div class="space-y-6">
    <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">System Health Monitor</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Database Card -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl {{ $dbStatus === 'OK' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Database</h3>
                    <p class="text-xs text-slate-500">MySQL Connection</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Status</span>
                    <span class="font-bold {{ $dbStatus === 'OK' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $dbStatus }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Est. Size</span>
                    <span class="font-mono">{{ number_format($dbSize, 2) }} MB</span>
                </div>
            </div>
        </div>

        <!-- Storage Card -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Storage & Disk</h3>
                    <p class="text-xs text-slate-500">Local Filesystem</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Permissions</span>
                    <span class="font-bold {{ $storageStatus === 'Writable' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $storageStatus }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Disk Free</span>
                    <span class="font-mono">{{ number_format($diskFree, 2) }} GB / {{ number_format($diskTotal, 2) }} GB</span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                    <div class="h-full bg-blue-500" style="width: {{ (1 - ($diskFree/$diskTotal)) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Server Info -->
        <div class="md:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Environment Details</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="block text-xs font-bold text-slate-500 uppercase">PHP Version</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white">{{ $serverInfo['php_version'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="block text-xs font-bold text-slate-500 uppercase">Laravel</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white">{{ $serverInfo['laravel_version'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="block text-xs font-bold text-slate-500 uppercase">OS</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white truncate" title="{{ $serverInfo['server_os'] }}">{{ $serverInfo['server_os'] }}</span>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="block text-xs font-bold text-slate-500 uppercase">Timezone</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-white">{{ $serverInfo['timezone'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
