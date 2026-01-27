<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Purchase <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Requisitions</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pengajuan permintaan pembelian barang/stok.</p>
        </div>
        <a href="{{ route('purchase-requisitions.create') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Buat Pengajuan (PR)
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-purple-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Menunggu Persetujuan</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $pendingCount }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-emerald-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Siap Proses PO</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $approvedCount }}</h3>
        </div>
        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl p-6 shadow-lg shadow-purple-600/20 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-wider text-white/80">Pengajuan Saya</p>
                <h3 class="text-3xl font-black font-tech mt-2">{{ $myRequestsCount }}</h3>
                <p class="text-xs text-white/80 mt-1">Total PR yang saya buat.</p>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-purple-500 text-sm" placeholder="Cari No. PR atau Requester...">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">No. PR</th>
                        <th class="px-6 py-4">Requester</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Tanggal Butuh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($requisitions as $pr)
                        <tr class="hover:bg-purple-50/30 dark:hover:bg-purple-900/10 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $pr->pr_number }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $pr->requester->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        'converted_to_po' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$pr->status] ?? 'bg-slate-100' }}">
                                    {{ str_replace('_', ' ', $pr->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($pr->required_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('purchase-requisitions.show', $pr->id) }}" class="text-purple-600 font-bold hover:underline text-xs">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada pengajuan (PR).</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $requisitions->links() }}
        </div>
    </div>
</div>
