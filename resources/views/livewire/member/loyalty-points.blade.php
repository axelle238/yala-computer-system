<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header & Stats -->
        <div class="flex flex-col md:flex-row gap-8 items-center mb-12 animate-fade-in-up">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter mb-2">
                    Yala <span class="text-amber-500">Rewards</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400">Tukar poin belanjamu dengan hadiah eksklusif.</p>
            </div>
            
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl blur opacity-40 group-hover:opacity-60 transition-opacity"></div>
                <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 border border-amber-200 dark:border-amber-900/50 shadow-xl flex items-center gap-6">
                    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Poin Saya</p>
                        <h2 class="text-4xl font-black text-slate-900 dark:text-white">{{ number_format($userPoints) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Rewards Catalog -->
            <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                    Katalog Hadiah
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($rewards as $reward)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-lg transition-all group flex flex-col">
                            <div class="h-32 bg-slate-100 dark:bg-slate-700 flex items-center justify-center relative overflow-hidden">
                                <!-- Mock Image Placeholder -->
                                <div class="absolute inset-0 bg-gradient-to-br {{ $reward['type'] == 'voucher' ? 'from-pink-500 to-rose-600' : 'from-indigo-500 to-purple-600' }} opacity-10 group-hover:opacity-20 transition-opacity"></div>
                                <svg class="w-12 h-12 text-slate-400 {{ $reward['type'] == 'voucher' ? 'text-rose-400' : 'text-indigo-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($reward['type'] == 'voucher')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    @endif
                                </svg>
                            </div>
                            
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="mb-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded {{ $reward['type'] == 'voucher' ? 'bg-rose-100 text-rose-600' : 'bg-indigo-100 text-indigo-600' }}">
                                        {{ $reward['type'] == 'voucher' ? 'Digital' : 'Fisik' }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 dark:text-white mb-1">{{ $reward['name'] }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 line-clamp-2">{{ $reward['desc'] }}</p>
                                
                                <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                                    <span class="font-black text-amber-500 text-lg">{{ number_format($reward['points']) }} <span class="text-xs font-medium text-slate-400">Poin</span></span>
                                    
                                    @if($userPoints >= $reward['points'])
                                        <button wire:click="redeem({{ $reward['id'] }})" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-lg text-xs hover:bg-slate-700 transition-colors" onclick="confirm('Tukar {{ number_format($reward['points']) }} poin untuk item ini?') || event.stopImmediatePropagation()">
                                            Tukar
                                        </button>
                                    @else
                                        <button disabled class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-400 font-bold rounded-lg text-xs cursor-not-allowed">
                                            Kurang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- History -->
            <div class="lg:col-span-1 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Riwayat Penukaran</h3>
                    
                    <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($redemptionHistory as $history)
                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($history['date'])->format('d M Y') }}</span>
                                    <span class="text-xs font-bold {{ $history['status'] == 'Aktif' ? 'text-emerald-500' : 'text-amber-500' }}">{{ $history['status'] }}</span>
                                </div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1">{{ $history['name'] }}</h4>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-rose-500 font-bold">- {{ number_format($history['points']) }} Poin</span>
                                    @if($history['code'])
                                        <span class="font-mono text-xs bg-white dark:bg-slate-800 px-2 py-1 rounded border border-slate-200 dark:border-slate-600 select-all">{{ $history['code'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-sm">Belum ada riwayat.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
