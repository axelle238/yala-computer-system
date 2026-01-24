<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Loyalty & <span class="text-blue-600">Referral</span>
            </h1>
            <a href="{{ route('member.dashboard') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Points Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <h3 class="text-blue-100 font-bold uppercase tracking-widest text-sm mb-2">Total Poin Anda</h3>
                <div class="text-6xl font-black font-mono mb-4">{{ number_format($user->points) }}</div>
                <p class="text-blue-100 text-sm mb-6">Tukarkan poin saat checkout untuk mendapatkan diskon langsung.</p>
                <div class="inline-block bg-white/20 backdrop-blur rounded-lg px-4 py-2 text-xs font-bold border border-white/10">
                    1 Poin = Rp 1
                </div>
            </div>

            <!-- Referral Card -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Undang Teman, Dapat Cuan!</h3>
                <p class="text-slate-500 mb-6">Bagikan kode unikmu. Kamu dan temanmu akan mendapatkan <span class="text-blue-600 font-bold">10.000 Poin</span> setelah pesanan pertama mereka selesai.</p>
                
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 bg-slate-100 dark:bg-slate-900 rounded-xl p-4 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                        <span class="font-mono font-black text-2xl text-slate-800 dark:text-white tracking-wider">{{ $user->referral_code }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $user->referral_code }}'); alert('Kode disalin!')" class="text-blue-600 hover:text-blue-800 font-bold text-sm">
                            Salin Kode
                        </button>
                    </div>
                    <div class="flex-1 bg-slate-100 dark:bg-slate-900 rounded-xl p-4 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                        <span class="text-sm text-slate-500 truncate">{{ route('customer.register', ['ref' => $user->referral_code]) }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ route('customer.register', ['ref' => $user->referral_code]) }}'); alert('Link disalin!')" class="text-blue-600 hover:text-blue-800 font-bold text-sm ml-4">
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>

            <!-- History Log -->
            <div class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Riwayat Poin</h3>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase border-b border-slate-100 dark:border-slate-700">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Aktivitas</th>
                            <th class="p-4">Deskripsi</th>
                            <th class="p-4 text-right">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-4 text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold uppercase 
                                        {{ $log->points > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-700 dark:text-slate-300">{{ $log->description }}</td>
                                <td class="p-4 text-right font-mono font-bold {{ $log->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $log->points > 0 ? '+' : '' }}{{ number_format($log->points) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400">Belum ada riwayat poin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</div>