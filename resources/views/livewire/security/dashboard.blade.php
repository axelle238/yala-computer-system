<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up" wire:poll.3s>
    <!-- GLOBAL SECURITY STATUS BANNER -->
    <div class="relative overflow-hidden rounded-[2.5rem] p-8 shadow-2xl transition-all duration-500
        {{ $lockdownMode ? 'bg-red-950 border-2 border-red-500' : 'bg-slate-900 border border-slate-700' }}">
        
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none animate-pulse-slow
            {{ $lockdownMode ? 'bg-red-600/30' : ($threatScore > 50 ? 'bg-amber-600/20' : 'bg-emerald-600/10') }}"></div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-8">
            <!-- Left: Status & Score -->
            <div class="flex items-center gap-8 w-full lg:w-auto">
                <div class="relative group cursor-pointer">
                    <svg class="w-32 h-32 transform -rotate-90 transition-all duration-1000 ease-out group-hover:scale-105">
                        <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-800" />
                        <circle cx="64" cy="64" r="60" stroke="currentColor" stroke-width="8" fill="transparent" 
                            class="{{ $lockdownMode ? 'text-red-500 animate-pulse' : ($threatScore > 50 ? 'text-amber-500' : 'text-emerald-500') }}" 
                            stroke-dasharray="377" 
                            stroke-dashoffset="{{ 377 - (377 * ($threatScore) / 100) }}" />
                    </svg>
                    <div class="absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
                        <span class="text-4xl font-black text-white font-mono">{{ $threatScore }}%</span>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-widest">Tingkat Risiko</span>
                    </div>
                </div>
                
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-3xl font-black text-white uppercase tracking-tight">Keamanan Sistem</h2>
                        @if($lockdownMode)
                            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase rounded animate-bounce">Lockdown Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase rounded border border-emerald-500/30">Monitoring Aktif</span>
                        @endif
                    </div>
                    <p class="text-slate-400 text-sm max-w-md leading-relaxed font-medium">
                        {{ $activeThreats }} ancaman aktif terdeteksi. Protokol pertahanan otomatis {{ $autoBanEnabled ? 'AKTIF' : 'NON-AKTIF' }}.
                        Integritas sistem saat ini berada pada level {{ $systemIntegrity }}%.
                    </p>
                </div>
            </div>

            <!-- Right: Action Center -->
            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <button wire:click="toggleAutoBan" class="flex-1 px-6 py-4 rounded-xl border border-slate-600 hover:bg-slate-800 transition-all flex flex-col items-center justify-center gap-1 group">
                    <div class="w-2 h-2 rounded-full {{ $autoBanEnabled ? 'bg-emerald-500' : 'bg-slate-500' }} mb-1"></div>
                    <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Blokir Otomatis (IPS)</span>
                    <span class="text-[10px] text-slate-500 font-mono">{{ $autoBanEnabled ? 'AKTIF' : 'NON-AKTIF' }}</span>
                </button>

                <button wire:click="toggleLockdown" wire:confirm="PERINGATAN: Mode Lockdown akan membatasi akses ke sistem secara drastis. Lanjutkan?"
                    class="flex-1 px-8 py-4 rounded-xl font-black uppercase tracking-widest text-xs flex flex-col items-center justify-center gap-2 transition-all shadow-lg hover:scale-105 active:scale-95
                    {{ $lockdownMode ? 'bg-red-600 text-white shadow-red-600/50 animate-pulse' : 'bg-slate-700 text-slate-300 hover:bg-red-900/50 hover:text-red-400' }}">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    {{ $lockdownMode ? 'NON-AKTIFKAN LOCKDOWN' : 'AKTIFKAN LOCKDOWN' }}
                </button>
            </div>
        </div>
    </div>

    <!-- LIVE ATTACK MAP (War Room Style) -->
    <div class="bg-slate-900 rounded-[2.5rem] p-8 border border-slate-700 shadow-2xl relative overflow-hidden h-[400px]">
        <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] bg-cover bg-center opacity-10 pointer-events-none"></div>
        <div class="absolute top-4 left-8 text-xs font-mono text-emerald-500 font-bold uppercase tracking-widest animate-pulse">Umpan Serangan Langsung // Membangun Uplink Aman...</div>
        
        <div class="relative w-full h-full">
            @foreach($attackMapData as $attack)
                <!-- Animated Attack Line -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" style="filter: drop-shadow(0 0 5px {{ $attack['severity'] == 'Critical' ? '#ef4444' : '#f59e0b' }});">
                    <line x1="{{ 50 + $attack['src_lon'] / 3.6 }}%" y1="{{ 50 - $attack['src_lat'] / 1.8 }}%" 
                          x2="80%" y2="60%" 
                          stroke="{{ $attack['severity'] == 'Critical' ? '#ef4444' : '#f59e0b' }}" stroke-width="2" stroke-linecap="round" stroke-dasharray="10,5">
                        <animate attributeName="stroke-dashoffset" from="100" to="0" dur="1s" repeatCount="indefinite" />
                        <animate attributeName="opacity" values="0;1;0" dur="2s" repeatCount="indefinite" />
                    </line>
                    <circle cx="{{ 50 + $attack['src_lon'] / 3.6 }}%" cy="{{ 50 - $attack['src_lat'] / 1.8 }}%" r="4" fill="{{ $attack['severity'] == 'Critical' ? '#ef4444' : '#f59e0b' }}">
                        <animate attributeName="r" values="4;12;4" dur="1.5s" repeatCount="indefinite" />
                        <animate attributeName="opacity" values="1;0;1" dur="1.5s" repeatCount="indefinite" />
                    </circle>
                </svg>
                
                <!-- Attack Info Label -->
                <div class="absolute bg-slate-900/80 backdrop-blur border border-slate-700 text-[10px] text-white px-2 py-1 rounded font-mono"
                     style="left: {{ 50 + $attack['src_lon'] / 3.6 }}%; top: {{ 50 - $attack['src_lat'] / 1.8 }}%;">
                    {{ $attack['type'] }}
                </div>
            @endforeach
            
            <!-- HQ Marker (Jakarta) -->
            <div class="absolute left-[80%] top-[60%] w-4 h-4 bg-emerald-500 rounded-full animate-ping"></div>
            <div class="absolute left-[80%] top-[60%] w-4 h-4 bg-emerald-500 rounded-full border-2 border-white"></div>
        </div>
    </div>

    <!-- LIVE MONITORING GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Column 1: Traffic Chart & Geo -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Traffic Chart Mock -->
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Trafik Jaringan (Live)
                    </h3>
                    <div class="flex gap-2">
                        <span class="flex h-2 w-2 relative mt-1.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-mono text-emerald-500">Aliran Data Masuk</span>
                    </div>
                </div>
                
                <!-- Mock Chart Bars -->
                <div class="flex items-end justify-between h-40 gap-2">
                    @foreach($trafficData['values'] as $index => $val)
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-t-lg relative group">
                            <div class="absolute bottom-0 left-0 w-full rounded-t-lg transition-all duration-500 ease-out
                                {{ $trafficData['anomalies'][$index] > 0 ? 'bg-red-500' : 'bg-blue-500' }}" 
                                style="height: {{ min(100, $val / 15) }}%">
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 transform -translate-x-1/2 bg-black text-white text-[10px] px-2 py-1 rounded pointer-events-none whitespace-nowrap">
                                {{ $val }} Req @ {{ $trafficData['labels'][$index] }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-2 text-[10px] text-slate-400 font-mono uppercase">
                    <span>Sekarang - 12j</span>
                    <span>Sekarang</span>
                </div>
            </div>

            <!-- Geo Distribution -->
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white mb-6">Peta Ancaman Geografis</h3>
                <div class="space-y-3">
                    @foreach($geoDistribution as $geo)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-600">
                            <div class="flex items-center gap-4">
                                <span class="text-2xl">{{ $geo['flag'] }}</span>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $geo['country'] }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wider font-bold">{{ $geo['count'] }} Percobaan Koneksi</div>
                                </div>
                            </div>
                            
                            @if($geo['status'] === 'Aman')
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">Terpercaya</span>
                            @elseif($geo['status'] === 'Kritis')
                                <button class="px-3 py-1 rounded text-[10px] font-black uppercase bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-500/30 transition-all active:scale-95">BLOKIR SEMUA</button>
                            @else
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">{{ $geo['status'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Column 2: Indicators & Logs -->
        <div class="space-y-8">
            <!-- Threat Indicators -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 border border-slate-700 shadow-xl">
                <h4 class="font-bold text-white mb-6 uppercase text-xs tracking-widest border-b border-slate-700 pb-2">Vektor Serangan</h4>
                <div class="space-y-6">
                    <!-- Brute Force -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-400">Brute Force / Isian Kredensial</span>
                            <span class="{{ $failedLogins > 10 ? 'text-red-400' : 'text-emerald-400' }}">{{ $failedLogins }} Kejadian</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full {{ $failedLogins > 10 ? 'bg-red-500' : 'bg-emerald-500' }}" style="width: {{ min(100, $failedLogins * 2) }}%"></div>
                        </div>
                    </div>
                    
                    <!-- Botnet -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-400">Aktivitas Botnet (IP Unik)</span>
                            <span class="text-indigo-400">{{ $distinctIps }} Sumber</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-indigo-500" style="width: {{ min(100, $distinctIps * 5) }}%"></div>
                        </div>
                    </div>

                    <!-- WAF Blocks -->
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-400">Blokir Firewall (WAF)</span>
                            <span class="text-amber-400">12 Kejadian</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-amber-500" style="width: 30%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Security Logs -->
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col h-[500px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm">Umpan Log Keamanan</h3>
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-3">
                    @forelse($securityEvents as $event)
                        <div class="p-3 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-900/30 text-xs relative group">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-black uppercase {{ $event->action == 'login_failed' || $event->action == 'threat_detected' || $event->action == 'security_lockdown_toggle' ? 'text-red-500' : 'text-blue-500' }}">
                                    {{ str_replace('_', ' ', $event->action) }}
                                </span>
                                <span class="text-slate-400 font-mono text-[10px]">{{ $event->created_at->format('H:i:s') }}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300 leading-tight mb-2">{{ Str::limit($event->description, 60) }}</p>
                            
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] font-mono text-slate-400 bg-white dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">
                                    {{ $event->ip_address }}
                                </span>
                                
                                @if($event->action == 'login_failed' || $event->action == 'threat_detected')
                                    <button wire:click="resolveThreat({{ $event->id }})" class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-bold rounded hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors uppercase text-[9px]">
                                        Tangani
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 italic">Hening... Tidak ada insiden.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>