<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in-up">
            <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 mb-2 inline-block">&larr; Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Loyalty <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Rewards</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Kumpulkan poin dari setiap transaksi dan tukarkan dengan hadiah eksklusif.</p>
        </div>

        <!-- Stats Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl mb-12 animate-fade-in-up delay-100">
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between gap-8 items-center">
                <div class="text-center md:text-left">
                    <p class="text-amber-400 font-bold uppercase tracking-widest text-sm mb-1">Total Poin Anda</p>
                    <h2 class="text-6xl font-black font-mono mb-2">{{ number_format($user->points) }}</h2>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 rounded-full text-xs font-bold border border-white/10">
                        <span class="w-2 h-2 rounded-full {{ $user->level['color'] == 'text-amber-400' ? 'bg-amber-400' : ($user->level['color'] == 'text-purple-400' ? 'bg-purple-400' : 'bg-slate-400') }}"></span>
                        {{ $user->level['name'] }} Member
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-slate-400">Progress ke Level Berikutnya</span>
                        <span class="text-amber-400">{{ round($nextLevel['percent']) }}%</span>
                    </div>
                    <div class="w-full h-3 bg-slate-700 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-1000" style="width: {{ $nextLevel['percent'] }}%"></div>
                    </div>
                    @if($nextLevel['target'] > 0)
                        <p class="text-xs text-slate-500 text-right">Belanja Rp {{ number_format($nextLevel['remaining'], 0, ',', '.') }} lagi untuk naik level.</p>
                    @else
                        <p class="text-xs text-amber-400 text-right font-bold">Level Maksimal Tercapai!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Rewards Catalog -->
            <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-200">
                <h3 class="font-bold text-xl text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                    Tukarkan Poin
                </h3>

                @if($rewards->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($rewards as $reward)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 relative overflow-hidden group hover:border-amber-500 transition-all">
                                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M20.25 5.5l-2.25 2.25-2.25-2.25-2.25 2.25-2.25-2.25-2.25 2.25-2.25-2.25-2.25 2.25V21h18V7.75l-2.25-2.25z"/></svg>
                                </div>
                                
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white mb-1">{{ $reward->name }}</h4>
                                <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $reward->description }}</p>
                                
                                <div class="flex justify-between items-end">
                                    <div class="text-amber-500 font-mono font-bold text-xl">{{ number_format($reward->points_cost) }} PTS</div>
                                    <button wire:click="redeem({{ $reward->id }})" 
                                            onclick="confirm('Tukarkan {{ number_format($reward->points_cost) }} poin untuk voucher ini?') || event.stopImmediatePropagation()"
                                            class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-lg text-sm hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $user->points < $reward->points_cost ? 'disabled' : '' }}>
                                        Tukar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-slate-100 dark:bg-slate-800 rounded-2xl text-slate-500">
                        Belum ada reward yang tersedia saat ini.
                    </div>
                @endif
            </div>

            <!-- History Log -->
            <div class="animate-fade-in-up delay-300">
                <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Riwayat Poin
                </h3>
                
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($history as $log)
                            <div class="p-4 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div>
                                    <p class="font-bold text-sm text-slate-800 dark:text-white">{{ $log->description }}</p>
                                    <p class="text-xs text-slate-500">{{ $log->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <span class="font-mono font-bold {{ $log->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $log->amount > 0 ? '+' : '' }}{{ number_format($log->amount) }}
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">Belum ada riwayat poin.</div>
                        @endforelse
                    </div>
                    @if($history->hasPages())
                        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
