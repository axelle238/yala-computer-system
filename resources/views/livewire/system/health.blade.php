<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
            System <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-500">Health</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Monitoring server, log aplikasi, dan perawatan sistem.</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 pb-1">
        <button wire:click="$set('activeTab', 'health')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'health' ? 'text-teal-600 border-b-2 border-teal-500' : 'text-slate-500 hover:text-slate-800' }}">
            Status & Info
        </button>
        <button wire:click="$set('activeTab', 'logs')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'logs' ? 'text-teal-600 border-b-2 border-teal-500' : 'text-slate-500 hover:text-slate-800' }}">
            Log Aplikasi
        </button>
        <button wire:click="$set('activeTab', 'cache')" 
            class="px-4 py-2 text-sm font-bold transition-all {{ $activeTab === 'cache' ? 'text-teal-600 border-b-2 border-teal-500' : 'text-slate-500 hover:text-slate-800' }}">
            Cache Manager
        </button>
    </div>

    <!-- Content: Health -->
    @if($activeTab === 'health')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Environment -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Environment</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-slate-500">Laravel Version</span>
                        <span class="font-mono font-bold">{{ $this->systemInfo['laravel'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-slate-500">PHP Version</span>
                        <span class="font-mono font-bold">{{ $this->systemInfo['php'] }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-700 pb-2">
                        <span class="text-slate-500">Database</span>
                        <span class="font-mono font-bold text-emerald-600">{{ $this->systemInfo['db_connection'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Storage -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Penyimpanan (Disk)</h3>
                <div class="flex items-center justify-center py-4">
                    <div class="text-center">
                        <span class="block text-4xl font-black text-slate-800 dark:text-white">{{ $this->systemInfo['disk_free'] }}</span>
                        <span class="text-xs text-slate-500 uppercase tracking-wider">Tersedia dari {{ $this->systemInfo['disk_total'] }}</span>
                    </div>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full overflow-hidden mt-2">
                    <div class="bg-teal-500 h-full w-3/4"></div> <!-- Mock visual, real calc possible -->
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center items-center text-center">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="font-bold text-lg text-emerald-600">Sistem Normal</h3>
                <p class="text-slate-500 text-sm">Semua layanan berjalan dengan baik.</p>
            </div>
        </div>
    @endif

    <!-- Content: Logs -->
    @if($activeTab === 'logs')
        <div class="bg-slate-900 text-slate-300 p-4 rounded-2xl shadow-lg border border-slate-700">
            <div class="flex justify-between items-center mb-4 border-b border-slate-700 pb-2">
                <h3 class="font-bold text-white font-mono">laravel.log (Last 200 Lines)</h3>
                <div class="flex gap-2">
                    <button wire:click="readLogs" class="px-3 py-1 bg-slate-700 hover:bg-slate-600 rounded text-xs font-bold transition-colors">Refresh</button>
                    <button wire:click="clearLogs" wire:confirm="Hapus semua log?" class="px-3 py-1 bg-rose-700 hover:bg-rose-600 text-white rounded text-xs font-bold transition-colors">Clear Logs</button>
                </div>
            </div>
            <div class="h-[500px] overflow-y-auto custom-scrollbar font-mono text-xs space-y-1 p-2">
                @forelse($logContent as $line)
                    @php
                        $color = 'text-slate-300';
                        if(str_contains($line, '.ERROR')) $color = 'text-rose-400 font-bold';
                        if(str_contains($line, '.WARNING')) $color = 'text-amber-400';
                        if(str_contains($line, '.INFO')) $color = 'text-blue-400';
                    @endphp
                    <div class="{{ $color }} border-b border-slate-800/50 pb-0.5">{{ $line }}</div>
                @empty
                    <div class="text-center py-10 text-slate-500 italic">Log file kosong atau tidak terbaca.</div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Content: Cache -->
    @if($activeTab === 'cache')
        <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Maintenance & Optimization</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <button wire:click="runCommand('optimize:clear')" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl hover:border-teal-500 hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-all group">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-300 group-hover:text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-200">Clear All Cache</span>
                    <span class="text-xs text-slate-500 mt-1">Config, Route, & View</span>
                </button>

                <button wire:click="runCommand('view:clear')" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all group">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-300 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-200">Clear View Cache</span>
                    <span class="text-xs text-slate-500 mt-1">Hapus compiled views</span>
                </button>

                <button wire:click="runCommand('config:clear')" class="flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all group">
                    <div class="w-12 h-12 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-300 group-hover:text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <span class="font-bold text-slate-700 dark:text-slate-200">Clear Config Cache</span>
                    <span class="text-xs text-slate-500 mt-1">Refresh konfigurasi .env</span>
                </button>
            </div>
        </div>
    @endif
</div>