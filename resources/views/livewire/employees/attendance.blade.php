<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Absensi</h1>

    <!-- Personal Clock Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Halo, {{ auth()->user()->name }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ now()->format('l, d F Y') }}</p>
            @if($activeShift)
                <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                    Shift: {{ $activeShift->start_time }} - {{ $activeShift->end_time }}
                </span>
            @endif
        </div>

        <div class="mt-4 md:mt-0 flex gap-4">
            @if(!$todayAttendance)
                <button wire:click="clockIn" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                    CLOCK IN
                </button>
            @elseif(!$todayAttendance->clock_out)
                <div class="text-center mr-4">
                    <p class="text-xs text-gray-500 uppercase font-bold">Masuk</p>
                    <p class="font-mono font-bold text-emerald-600">{{ $todayAttendance->clock_in->format('H:i') }}</p>
                </div>
                <button wire:click="clockOut" class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-1">
                    CLOCK OUT
                </button>
            @else
                <div class="flex gap-6">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-bold">Masuk</p>
                        <p class="font-mono font-bold text-emerald-600">{{ $todayAttendance->clock_in->format('H:i') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 uppercase font-bold">Keluar</p>
                        <p class="font-mono font-bold text-rose-600">{{ $todayAttendance->clock_out->format('H:i') }}</p>
                    </div>
                </div>
                <button disabled class="px-6 py-3 bg-gray-200 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                    Selesai Hari Ini
                </button>
            @endif
        </div>
    </div>

    <!-- Attendance History (Admin View) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat Absensi Seluruh Pegawai</h3>
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Pegawai</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4 text-center">Masuk</th>
                    <th class="px-6 py-4 text-center">Keluar</th>
                    <th class="px-6 py-4 text-center">Durasi</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($history as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $log->user->name }}</td>
                        <td class="px-6 py-4">{{ $log->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center text-emerald-600 font-mono font-bold">{{ $log->clock_in ? $log->clock_in->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-center text-rose-600 font-mono font-bold">{{ $log->clock_out ? $log->clock_out->format('H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($log->clock_in && $log->clock_out)
                                {{ $log->clock_in->diff($log->clock_out)->format('%Hj %Im') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if(!$log->clock_out)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold uppercase">Working</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold uppercase">Done</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $history->links() }}
        </div>
    </div>
</div>