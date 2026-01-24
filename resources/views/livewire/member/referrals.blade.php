<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in-up">
            <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 mb-2 inline-block">&larr; Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Referral <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-500">Program</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Undang teman dan dapatkan komisi menarik.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Stats & Link -->
            <div class="space-y-6 animate-fade-in-up delay-100">
                <!-- Card Stats -->
                <div class="bg-gradient-to-br from-emerald-500 to-cyan-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none"></div>
                    <p class="text-emerald-100 font-bold uppercase tracking-wider text-xs mb-1">Total Pendapatan</p>
                    <h2 class="text-4xl font-black font-mono">Rp {{ number_format($totalEarned, 0, ',', '.') }}</h2>
                    <div class="mt-4 pt-4 border-t border-white/20 flex justify-between items-center text-sm">
                        <span>Total Teman: {{ $referrals->total() }}</span>
                        <span class="bg-white/20 px-2 py-0.5 rounded text-xs font-bold">Active</span>
                    </div>
                </div>

                <!-- Share Link -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-2">Kode Referral Anda</h3>
                    <div class="flex gap-2">
                        <input type="text" value="{{ $referralCode }}" readonly class="flex-1 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 font-mono font-bold text-lg text-center tracking-widest focus:outline-none">
                        <button onclick="navigator.clipboard.writeText('{{ $referralCode }}'); alert('Kode disalin!')" class="px-4 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 rounded-xl transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Bagikan kode ini ke teman Anda saat mendaftar.</p>
                </div>
            </div>

            <!-- List Invitees -->
            <div class="lg:col-span-2 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Undangan</h3>
                    </div>
                    
                    @if($referrals->count() > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($referrals as $ref)
                                <div class="p-6 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-900/30 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold">
                                            {{ substr($ref->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 dark:text-white">{{ $ref->name }}</p>
                                            <p class="text-xs text-slate-500">Bergabung: {{ $ref->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-emerald-600">+ Rp 50.000</p>
                                        <p class="text-[10px] text-slate-400 uppercase font-bold">Reward</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                            {{ $referrals->links() }}
                        </div>
                    @else
                        <div class="p-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <p>Belum ada teman yang bergabung menggunakan kode Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
