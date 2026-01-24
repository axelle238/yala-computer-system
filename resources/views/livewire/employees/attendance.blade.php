<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Employee <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-500">Attendance</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Monitor kehadiran dan absensi harian.</p>
        </div>
        <div class="flex items-center gap-4">
            <input type="date" wire:model.live="dateFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 dark:text-white focus:ring-pink-500 shadow-sm">
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-emerald-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Hadir Tepat Waktu</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $presentCount }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-amber-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Terlambat</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $lateCount }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl -mr-6 -mt-6 group-hover:bg-rose-500/20 transition-all"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Belum Absen / Absen</p>
            <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ $absentCount }}</h3>
        </div>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-4 justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-pink-500 text-sm" placeholder="Cari Nama Karyawan...">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Clock In</th>
                        <th class="px-6 py-4 text-center">Clock Out</th>
                        <th class="px-6 py-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($attendances as $attn)
                        <tr class="hover:bg-pink-50/30 dark:hover:bg-pink-900/10 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $attn->user->name }}
                                <div class="text-[10px] text-slate-500 font-normal mt-0.5">{{ $attn->user->role }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'present' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'late' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'absent' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        'leave' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'sick' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$attn->status] ?? 'bg-slate-100' }}">
                                    {{ $attn->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600 dark:text-slate-300">
                                {{ $attn->clock_in ? \Carbon\Carbon::parse($attn->clock_in)->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-slate-600 dark:text-slate-300">
                                {{ $attn->clock_out ? \Carbon\Carbon::parse($attn->clock_out)->format('H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs italic">
                                {{ $attn->notes ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data absensi untuk tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
