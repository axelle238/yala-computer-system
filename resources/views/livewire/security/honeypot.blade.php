<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Sistem <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Deception (Honeypot)</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Pasang jebakan untuk mendeteksi dan menjebak penyerang sebelum mereka mencapai sistem inti.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Trap -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6">Pasang Jebakan Baru</h3>
                <form wire:submit.prevent="addTrap" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Path URL Palsu</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">/</span>
                            <input wire:model="newTrapPath" type="text" placeholder="admin/login_old" class="w-full pl-6 rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tipe Jebakan</label>
                        <select wire:model="newTrapType" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900">
                            <option value="login">Fake Login Page</option>
                            <option value="api">Fake API Endpoint</option>
                            <option value="file">Fake Sensitive File</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all">
                        Deploy Honeypot
                    </button>
                </form>
            </div>
        </div>

        <!-- Trap List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6">Jebakan Aktif</h3>
                
                @if(empty($traps))
                    <div class="text-center py-12 text-slate-400">Belum ada honeypot yang dipasang.</div>
                @else
                    <div class="space-y-4">
                        @foreach($traps as $index => $trap)
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm
                                        {{ $trap['type'] == 'login' ? 'bg-blue-100 text-blue-600' : ($trap['type'] == 'api' ? 'bg-purple-100 text-purple-600' : 'bg-slate-200 text-slate-600') }}">
                                        @if($trap['type'] == 'login') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                        @elseif($trap['type'] == 'api') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        @else <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-mono font-bold text-slate-800 dark:text-white">{{ $trap['path'] }}</div>
                                        <div class="text-xs text-slate-500 uppercase font-bold tracking-wider">{{ $trap['type'] }} &bull; {{ $trap['hits'] }} Hits</div>
                                    </div>
                                </div>
                                <button wire:click="deleteTrap({{ $index }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
