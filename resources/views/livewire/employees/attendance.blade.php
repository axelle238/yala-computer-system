<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Kartu Absensi Utama -->
        <div class="md:col-span-1 bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-200">
            <div class="bg-indigo-600 p-6 text-center text-white">
                <h2 class="text-lg font-medium opacity-90">Halo, {{ Auth::user()->name }}</h2>
                <div class="text-4xl font-bold mt-2 font-mono">{{ now()->format('H:i') }}</div>
                <div class="text-sm opacity-75 mt-1">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            
            <div class="p-8">
                @if(!$todayAttendance)
                    <!-- Belum Absen -->
                    <div class="text-center">
                        <div class="mb-6">
                            <span class="inline-block p-4 rounded-full bg-slate-100 text-slate-400">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </span>
                        </div>
                        <button wire:click="clockIn" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-1">
                            ABSEN MASUK
                        </button>
                        <p class="text-xs text-slate-400 mt-3">Jadwal Masuk: 09:00 WIB</p>
                    </div>

                @elseif(!$todayAttendance->clock_out)
                    <!-- Sudah Masuk, Belum Pulang -->
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="text-sm text-slate-500 mb-1">Anda masuk pada:</div>
                            <div class="text-2xl font-bold text-slate-800">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</div>
                            @if($todayAttendance->status == 'late')
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-600">Terlambat {{ $todayAttendance->late_minutes }} menit</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-600">Tepat Waktu</span>
                            @endif
                        </div>
                        
                        <button wire:click="clockOut" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition transform hover:-translate-y-1">
                            ABSEN PULANG
                        </button>
                    </div>

                @else
                    <!-- Sudah Selesai Hari Ini -->
                    <div class="text-center">
                        <div class="mb-6 text-emerald-500">
                            <svg class="w-16 h-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Absensi Selesai</h3>
                        <p class="text-slate-500 mt-1">Terima kasih atas kerja keras Anda hari ini!</p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <div class="text-xs text-slate-400">Masuk</div>
                                <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</div>
                            </div>
                            <div class="bg-slate-50 p-2 rounded-lg">
                                <div class="text-xs text-slate-400">Pulang</div>
                                <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Absensi -->
        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-800">Riwayat Kehadiran Saya</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Masuk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Pulang</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($history as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">
                                    {{ $log->clock_in ? \Carbon\Carbon::parse($log->clock_in)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">
                                    {{ $log->clock_out ? \Carbon\Carbon::parse($log->clock_out)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($log->status == 'present')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Hadir</span>
                                    @elseif($log->status == 'late')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Terlambat</span>
                                    @elseif($log->status == 'sick')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Sakit</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">{{ ucfirst($log->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $log->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-200">
                {{ $history->links() }}
            </div>
        </div>

    </div>
</div>
