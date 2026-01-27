<div class="max-w-7xl mx-auto px-4 py-12 animate-fade-in-up">
    
    <!-- Header Poin -->
    <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-3xl p-8 md:p-12 text-white shadow-2xl mb-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-3xl font-black font-tech uppercase tracking-tight mb-2">Member <span class="text-amber-300">Rewards</span></h2>
                <p class="text-indigo-100 max-w-lg">Tukarkan poin loyalitas Anda dengan voucher belanja eksklusif. Semakin sering belanja, semakin banyak untungnya!</p>
            </div>
            <div class="text-center md:text-right bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20">
                <span class="block text-sm font-bold uppercase tracking-widest text-indigo-200 mb-1">Saldo Poin Anda</span>
                <span class="text-5xl font-black font-mono text-white">{{ number_format($poinSaatIni) }}</span>
                <span class="text-xl text-amber-300 ml-1">pts</span>
            </div>
        </div>
    </div>

    <!-- Katalog Penukaran -->
    <div class="mb-16">
        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-3">
            <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
            Katalog Penukaran
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($katalogHadiah as $voucher)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-lg transition-all group relative">
                    <div class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl z-10">
                        {{ $voucher->type == 'fixed' ? 'Potongan Rp' : 'Diskon %' }}
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-lg">{{ $voucher->code }}</h4>
                                <p class="text-xs text-slate-500">Berakhir: {{ $voucher->end_date->format('d M Y') }}</p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
                            Dapatkan potongan harga sebesar 
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $voucher->type == 'fixed' ? 'Rp '.number_format($voucher->discount_value) : $voucher->discount_value.'%' }}
                            </span>
                            untuk pembelian berikutnya.
                        </p>

                        <button wire:click="tukarVoucher({{ $voucher->id }})" wire:confirm="Tukarkan {{ number_format($voucher->points_cost) }} poin dengan voucher ini?" class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl flex items-center justify-center gap-2 group-hover:bg-indigo-600 group-hover:text-white dark:group-hover:bg-indigo-500 transition-colors {{ $poinSaatIni < $voucher->points_cost ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $poinSaatIni < $voucher->points_cost ? 'disabled' : '' }}>
                            <span>Tukar {{ number_format($voucher->points_cost) }} Poin</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-slate-50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <p class="text-slate-500 font-medium">Belum ada voucher yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Riwayat Poin -->
    <div class="bg-white dark:bg-slate-800 rounded-[2rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Riwayat Mutasi Poin</h3>
            
            <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                <button wire:click="$set('filterRiwayat', 'semua')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $filterRiwayat == 'semua' ? 'bg-white dark:bg-slate-700 shadow-sm text-indigo-600' : 'text-slate-500' }}">Semua</button>
                <button wire:click="$set('filterRiwayat', 'masuk')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $filterRiwayat == 'masuk' ? 'bg-white dark:bg-slate-700 shadow-sm text-emerald-600' : 'text-slate-500' }}">Masuk (+)</button>
                <button wire:click="$set('filterRiwayat', 'keluar')" class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $filterRiwayat == 'keluar' ? 'bg-white dark:bg-slate-700 shadow-sm text-rose-600' : 'text-slate-500' }}">Keluar (-)</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs tracking-wider">
                    <tr>
                        <th class="px-8 py-4">Tanggal</th>
                        <th class="px-8 py-4">Keterangan</th>
                        <th class="px-8 py-4 text-right">Jumlah Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($riwayatPoin as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-8 py-4 text-slate-500 font-mono">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td class="px-8 py-4 font-bold text-slate-700 dark:text-slate-200">{{ $log->description }}</td>
                            <td class="px-8 py-4 text-right font-mono font-black {{ $log->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $log->amount > 0 ? '+' : '' }}{{ number_format($log->amount) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-8 py-12 text-center text-slate-400">Belum ada riwayat poin.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
            {{ $riwayatPoin->links() }}
        </div>
    </div>
</div>