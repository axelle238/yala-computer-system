<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Deteksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Intrusi (IDS)</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Log aktivitas mencurigakan, analisis anomali, dan respon insiden otomatis.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
            </span>
            <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Scanning Network</span>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border-l-4 border-amber-500 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Incidents (24h)</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $threats->total() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border-l-4 border-red-500 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">High Risk IP Sources</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">{{ $threats->unique('ip_address')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border-l-4 border-blue-500 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Automated Blocks</h4>
            <p class="text-3xl font-black text-slate-900 dark:text-white mt-2">0</p>
        </div>
    </div>

    <!-- Threat Table -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs tracking-wider border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4">Waktu & IP</th>
                        <th class="px-6 py-4">Identitas User</th>
                        <th class="px-6 py-4">Vektor Serangan</th>
                        <th class="px-6 py-4">Risk Score</th>
                        <th class="px-6 py-4 text-center">Tindakan Respon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($threats as $threat)
                        <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-mono text-slate-500 mb-1">{{ $threat->created_at->format('Y-m-d H:i:s') }}</div>
                                <div class="font-mono font-bold text-slate-800 dark:text-white bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded w-fit text-xs border border-slate-200 dark:border-slate-700">
                                    {{ $threat->ip_address }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $threat->user->name ?? 'Unauthenticated' }}</div>
                                <div class="text-xs text-slate-400">{{ $threat->user->email ?? 'Guest Session' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-black text-slate-700 dark:text-slate-300 text-xs uppercase mb-1">{{ $threat->action }}</span>
                                <span class="text-xs text-slate-500 block max-w-xs leading-tight">{{ $threat->description }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                    Critical
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="blockIp('{{ $threat->ip_address }}')" class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-md transition-all active:scale-95" title="Blokir IP Permanen">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                    </button>
                                    
                                    @if($threat->user_id)
                                        <button wire:click="killSession({{ $threat->user_id }})" class="p-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg shadow-md transition-all active:scale-95" title="Putus Sesi User">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        </button>
                                    @endif

                                    <button wire:click="analyzeThreat('{{ $threat->ip_address }}')" class="p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition-all active:scale-95" title="Analisis Mendalam">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-slate-200 dark:text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-lg font-bold">Sistem Bersih</p>
                                    <p class="text-sm">Tidak ada ancaman aktif yang terdeteksi saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
            {{ $threats->links() }}
        </div>
    </div>

    <!-- Analysis Modal -->
    @if($analysisResult)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('analysisResult', null)"></div>

                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-700">
                    <div class="bg-slate-900 px-4 py-3 border-b border-slate-700 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-black text-white uppercase tracking-wider" id="modal-title">
                            Laporan Analisis Forensik
                        </h3>
                        <button wire:click="$set('analysisResult', null)" class="text-slate-400 hover:text-white">&times;</button>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-xs text-slate-500 uppercase font-bold">Target IP</p>
                                <p class="text-xl font-mono font-black text-white">{{ $analysisResult['ip'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-500 uppercase font-bold">Threat Score</p>
                                <p class="text-2xl font-black {{ $analysisResult['score'] > 50 ? 'text-red-500' : 'text-amber-500' }}">{{ $analysisResult['score'] }}/100</p>
                            </div>
                        </div>

                        <div class="bg-slate-900/50 rounded-xl p-4 border border-slate-700 mb-6">
                            <p class="text-xs text-slate-400 uppercase font-bold mb-2">Verdict</p>
                            <p class="font-bold text-lg text-white">{{ $analysisResult['verdict'] }}</p>
                        </div>

                        <div class="space-y-2">
                            @foreach($analysisResult['details'] as $key => $val)
                                <div class="flex justify-between text-sm border-b border-slate-700 pb-2">
                                    <span class="text-slate-400">{{ $key }}</span>
                                    <span class="text-white font-mono">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-slate-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-700">
                        <button wire:click="blockIp('{{ $analysisResult['ip'] }}')" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-wider font-bold">
                            Blokir IP Ini
                        </button>
                        <button wire:click="$set('analysisResult', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-600 shadow-sm px-4 py-2 bg-transparent text-base font-medium text-slate-300 hover:bg-slate-800 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>