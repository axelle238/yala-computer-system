<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Firewall & <span class="text-red-600">Access Control</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Kelola aturan akses jaringan, blokir regional, dan proteksi flood trafik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="flex h-3 w-3 relative">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Firewall Active</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden min-h-[600px] flex flex-col md:flex-row">
        
        <!-- Sidebar Tabs -->
        <div class="w-full md:w-64 bg-slate-50 dark:bg-slate-900/50 border-r border-slate-200 dark:border-slate-700 flex flex-col">
            <div class="p-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Modul Proteksi</h3>
                <nav class="space-y-2">
                    <button wire:click="$set('activeTab', 'ip_rules')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'ip_rules' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800' }}">
                        Aturan IP Address
                    </button>
                    <button wire:click="$set('activeTab', 'geo_block')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'geo_block' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800' }}">
                        Geo-Blocking (Negara)
                    </button>
                    <button wire:click="$set('activeTab', 'ua_block')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'ua_block' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800' }}">
                        User Agent Filter
                    </button>
                    <button wire:click="$set('activeTab', 'rate_limit')" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'rate_limit' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-800' }}">
                        Rate Limiting
                    </button>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            
            <!-- TAB: IP RULES -->
            @if($activeTab === 'ip_rules')
                <div class="animate-fade-in">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Manajemen Blokir IP</h3>
                    </div>

                    <!-- Add IP Form -->
                    <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 mb-8">
                        <form wire:submit.prevent="addIp('blocked')" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">IP Address</label>
                                <input wire:model="newIp" type="text" placeholder="192.168.1.1" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan</label>
                                <input wire:model="ipNote" type="text" placeholder="Alasan blokir..." class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transition-all">
                                    Blokir
                                </button>
                                <button type="button" wire:click="addIp('whitelist')" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all">
                                    Whitelist
                                </button>
                            </div>
                        </form>
                        @error('newIp') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lists -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Blocked List -->
                        <div>
                            <h4 class="text-sm font-black text-red-600 uppercase tracking-widest mb-4 border-b border-red-200 pb-2">Blocked IPs</h4>
                            <div class="space-y-3">
                                @forelse($blockedIps as $index => $item)
                                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/30">
                                        <div>
                                            <div class="font-mono font-bold text-slate-800 dark:text-red-200">{{ $item['ip'] }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $item['note'] ?? '-' }}</div>
                                        </div>
                                        <button wire:click="removeIp('blocked', {{ $index }})" class="text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">Tidak ada IP diblokir.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Whitelist -->
                        <div>
                            <h4 class="text-sm font-black text-emerald-600 uppercase tracking-widest mb-4 border-b border-emerald-200 pb-2">Whitelist IPs</h4>
                            <div class="space-y-3">
                                @forelse($whitelistedIps as $index => $item)
                                    <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-900/30">
                                        <div>
                                            <div class="font-mono font-bold text-slate-800 dark:text-emerald-200">{{ $item['ip'] }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $item['note'] ?? '-' }}</div>
                                        </div>
                                        <button wire:click="removeIp('whitelist', {{ $index }})" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">Tidak ada IP whitelist.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB: GEO BLOCK -->
            @if($activeTab === 'geo_block')
                <div class="animate-fade-in">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Blokir Akses Negara</h3>
                    
                    <div class="flex gap-4 mb-8">
                        <input wire:model="newCountry" type="text" placeholder="Kode Negara (e.g. CN, RU)" class="w-32 rounded-xl border-slate-300 uppercase font-mono text-center">
                        <button wire:click="addCountry" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition">Blokir Negara</button>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @forelse($blockedCountries as $index => $code)
                            <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                <span class="font-mono font-bold text-slate-700 dark:text-white">{{ $code }}</span>
                                <button wire:click="removeCountry({{ $index }})" class="text-slate-400 hover:text-rose-500">&times;</button>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm">Tidak ada negara yang diblokir.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- TAB: UA BLOCK -->
            @if($activeTab === 'ua_block')
                <div class="animate-fade-in">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Filter User Agent (Bot/Scraper)</h3>
                    
                    <div class="flex gap-4 mb-8">
                        <input wire:model="newUa" type="text" placeholder="User Agent String (e.g. curl, bot)" class="flex-1 rounded-xl border-slate-300">
                        <button wire:click="addUa" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition">Tambah Filter</button>
                    </div>

                    <div class="space-y-3">
                        @forelse($blockedUserAgents as $index => $ua)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200">
                                <span class="font-mono text-sm text-slate-600 dark:text-slate-300">{{ $ua }}</span>
                                <button wire:click="removeUa({{ $index }})" class="text-rose-500 hover:text-rose-700 font-bold text-xs uppercase">Hapus</button>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm">Tidak ada filter User Agent.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- TAB: RATE LIMIT -->
            @if($activeTab === 'rate_limit')
                <div class="animate-fade-in">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Rate Limiting & Flood Protection</h3>
                    
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800/50 mb-8">
                        <label class="flex items-center gap-4 cursor-pointer">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" wire:model="rateLimitEnabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </div>
                            <span class="font-bold text-slate-800 dark:text-white">Aktifkan Global Rate Limiting</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Max Requests (per IP)</label>
                            <input wire:model="maxRequests" type="number" class="w-full rounded-xl border-slate-300 text-center font-mono font-bold text-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Decay Time (Menit)</label>
                            <input wire:model="decayMinutes" type="number" class="w-full rounded-xl border-slate-300 text-center font-mono font-bold text-lg">
                        </div>
                    </div>

                    <button wire:click="saveRateLimit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all">
                        Simpan Konfigurasi
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>