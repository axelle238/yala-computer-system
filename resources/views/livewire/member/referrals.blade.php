<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Referral <span class="text-indigo-600">Program</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Ajak teman belanja dan dapatkan keuntungan.</p>
            </div>
            <a href="{{ route('member.dashboard') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Dashboard
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <h3 class="text-indigo-100 font-bold uppercase tracking-widest text-xs mb-2">Total Teman Bergabung</h3>
                <div class="text-5xl font-black font-mono">{{ $referrals->total() }}</div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
                <h3 class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Estimasi Pendapatan</h3>
                <div class="text-4xl font-black text-slate-900 dark:text-white font-mono">Rp {{ number_format($totalEarned, 0, ',', '.') }}</div>
                <p class="text-xs text-slate-400 mt-2">*Berupa Poin Belanja</p>
            </div>
            <div class="bg-slate-900 rounded-3xl p-8 border border-slate-800 shadow-sm relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <h3 class="text-slate-400 font-bold uppercase tracking-widest text-xs mb-4">Kode Referral Anda</h3>
                <div class="flex items-center gap-4">
                    <code class="font-mono text-3xl font-bold text-white tracking-wider">{{ $referralCode }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $referralCode }}'); alert('Kode disalin!')" class="p-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Referral List -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Riwayat Referral</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="p-4">Nama Teman</th>
                            <th class="p-4">Tanggal Bergabung</th>
                            <th class="p-4 text-center">Status Belanja</th>
                            <th class="p-4 text-right">Reward</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($referrals as $ref)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 font-bold text-slate-800 dark:text-white">
                                    {{ $ref->name }}
                                    <span class="block text-xs font-normal text-slate-500 font-mono">{{ $ref->email }}</span>
                                </td>
                                <td class="p-4 text-slate-500">{{ $ref->created_at->format('d M Y') }}</td>
                                <td class="p-4 text-center">
                                    @if($ref->orders()->exists())
                                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold uppercase">Belum Belanja</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right font-mono font-bold text-indigo-600">
                                    @if($ref->orders()->exists())
                                        + 50.000 Poin
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400">
                                    Belum ada teman yang bergabung menggunakan kode Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                {{ $referrals->links() }}
            </div>
        </div>

    </div>
</div>