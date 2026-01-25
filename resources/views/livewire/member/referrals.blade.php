<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Undang Teman <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Dapat Cuan</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Dapatkan 5.000 Poin untuk setiap teman yang mendaftar dan belanja pertama kali.</p>
        </div>

        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Code Card -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden flex flex-col justify-center animate-fade-in-up delay-100">
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
                <div class="relative z-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-100 mb-4">Kode Referral Anda</p>
                    <div class="bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl p-4 mb-6">
                        <span class="text-4xl font-black font-mono tracking-wider select-all">{{ $referralCode }}</span>
                    </div>
                    <button wire:click="copyLink" onclick="navigator.clipboard.writeText('{{ $referralLink }}')" class="w-full py-3 bg-white text-orange-600 font-bold rounded-xl hover:bg-orange-50 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        Salin Link Pendaftaran
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="space-y-6 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-6">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalEarnings) }} <span class="text-sm font-medium text-slate-400">Poin</span></h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Teman Bergabung</p>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalReferrals }} <span class="text-sm font-medium text-slate-400">Orang</span></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- History -->
        <div class="max-w-4xl mx-auto mt-12 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden animate-fade-in-up delay-300">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Ajakan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Nama Teman</th>
                            <th class="px-6 py-4">Tanggal Gabung</th>
                            <th class="px-6 py-4 text-center">Status Bonus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($referrals as $ref)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $ref->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $ref->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Diterima (+5000)</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-slate-400">Belum ada teman yang bergabung menggunakan kode Anda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $referrals->links() }}
            </div>
        </div>

    </div>
</div>
