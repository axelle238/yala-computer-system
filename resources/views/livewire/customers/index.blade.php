<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Customer <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-600">Relationship</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen loyalitas, analisis LTV, dan database pelanggan.</p>
        </div>
        <a href="{{ route('admin.pelanggan.buat') }}" class="px-6 py-3 bg-white text-slate-900 border-2 border-slate-900 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pelanggan
        </a>
    </div>

    <!-- Stats Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Pelanggan -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pelanggan</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</h3>
                <p class="text-[10px] text-purple-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Database CRM
                </p>
            </div>
        </div>

        <!-- Pelanggan Baru -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-pink-300 dark:hover:border-pink-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pelanggan Baru (Bln Ini)</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">+{{ number_format($stats['new_this_month']) }}</h3>
                <p class="text-[10px] text-pink-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500 animate-pulse"></span> Pertumbuhan
                </p>
            </div>
        </div>

        <!-- Member Aktif -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Member Aktif</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['active_members']) }}</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pernah Transaksi
                </p>
            </div>
        </div>

        <!-- Top Tier -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Pelanggan VIP</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['top_tier']) }}</h3>
                <p class="text-[10px] text-amber-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> > 1000 Poin
                </p>
            </div>
        </div>
    </div>

    <!-- Main CRM Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
        <!-- Toolbar -->
        <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-purple-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="cari" type="text" class="block w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm transition-all" placeholder="Cari Pelanggan (Nama, HP, Email)...">
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Urutkan:</span>
                <select class="text-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 focus:ring-purple-500 cursor-pointer">
                    <option value="spending">Total Belanja (Tertinggi)</option>
                    <option value="points">Poin Terbanyak</option>
                    <option value="latest">Terdaftar Terbaru</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Profil Pelanggan</th>
                        <th class="px-6 py-4">Kontak & Lokasi</th>
                        <th class="px-6 py-4 text-center">Status Member</th>
                        <th class="px-6 py-4 text-right">Lifetime Value (Total Belanja)</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-purple-50/30 dark:hover:bg-purple-900/10 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-black text-lg text-slate-500 dark:text-slate-300 group-hover:bg-purple-100 group-hover:text-purple-600 transition-colors border border-slate-200 dark:border-slate-600">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white text-base">{{ $customer->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Bergabung: {{ $customer->created_at ? $customer->created_at->format('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300 font-mono text-xs">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $customer->phone ?? '-' }}
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-xs">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $customer->email ?? '-' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $customer->total_spent > 5000000 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600' }}">
                                        @if($customer->total_spent > 5000000)
                                            <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            VIP Member
                                        @else
                                            Regular
                                        @endif
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 mt-1">{{ number_format($customer->points) }} Poin</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="font-black text-slate-800 dark:text-white font-mono text-sm">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-400">Total Akumulasi</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pelanggan.tampil', $customer->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-all" title="Detail Profil">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <a href="{{ route('admin.pelanggan.ubah', $customer->id) }}" class="p-2 text-slate-400 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded-lg transition-all" title="Edit Profil">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-slate-300 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <span>Belum ada data pelanggan yang cocok.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30">
            {{ $customers->links() }}
        </div>
    </div>
</div>