<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Sales <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">Quotations</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen penawaran harga B2B/Corporate.</p>
        </div>
        <a href="{{ route('quotations.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-orange-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Penawaran
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-orange-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Draft / Baru</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $draftCount }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-blue-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Terkirim ke Customer</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $sentCount }}</h3>
        </div>
        <div class="bg-gradient-to-br from-orange-600 to-amber-600 rounded-2xl p-6 shadow-lg shadow-orange-600/20 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-wider text-white/80">Deal / Converted</p>
                <h3 class="text-3xl font-black font-tech mt-2">{{ $convertedCount }}</h3>
                <p class="text-xs text-white/80 mt-1">Penawaran sukses menjadi order.</p>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-orange-500 text-sm" placeholder="Cari No. Quotation atau Customer...">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No. Quote</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Total Nilai</th>
                        <th class="px-6 py-4 text-right">Berlaku Sampai</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-orange-50/30 dark:hover:bg-orange-900/10 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $quote->quote_number }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $quote->customer->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                                        'sent' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'accepted' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        'converted' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$quote->status] ?? 'bg-slate-100' }}">
                                    {{ $quote->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-slate-600 dark:text-slate-300">
                                Rp {{ number_format($quote->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-slate-500">
                                {{ $quote->valid_until->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('quotations.show', $quote->id) }}" class="text-orange-600 font-bold hover:underline text-xs">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada Quotation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $quotes->links() }}
        </div>
    </div>
</div>
