<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Shift <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pengaturan jadwal kerja mingguan.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <button wire:click="prevWeek" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <span class="font-bold text-slate-700 dark:text-white text-sm w-32 text-center">
                {{ $dates[0]->format('d M') }} - {{ end($dates)->format('d M') }}
            </span>
            <button wire:click="nextWeek" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    </div>

    <!-- Shift Tools -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-wrap gap-4 items-center">
        <span class="text-xs font-bold uppercase text-slate-500 mr-2">Pilih Shift:</span>
        @foreach($shifts as $shift)
            <button wire:click="$set('selectedShiftId', {{ $shift->id }})" 
                    class="px-4 py-2 rounded-lg text-xs font-bold uppercase transition-all {{ $selectedShiftId === $shift->id ? 'bg-orange-500 text-white shadow-lg ring-2 ring-orange-200' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
            </button>
        @endforeach
    </div>

    <!-- Schedule Grid -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 text-xs uppercase font-bold">
                        <th class="p-4 border-b border-r border-slate-200 dark:border-slate-700 w-48 sticky left-0 bg-slate-50 dark:bg-slate-900 z-10">Karyawan</th>
                        @foreach($dates as $date)
                            <th class="p-4 border-b border-slate-200 dark:border-slate-700 text-center min-w-[100px] {{ $date->isToday() ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-600' : '' }}">
                                {{ $date->format('D') }}<br>
                                <span class="text-lg">{{ $date->format('d') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="p-4 border-r border-slate-100 dark:border-slate-700 sticky left-0 bg-white dark:bg-slate-800 z-10">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-[10px] text-slate-500 uppercase">{{ $user->role }}</div>
                            </td>
                            @foreach($dates as $date)
                                @php
                                    $schedule = $schedules[$user->id] ?? collect();
                                    $shift = $schedule->where('date', $date)->first();
                                @endphp
                                <td class="p-2 border-r border-slate-50 dark:border-slate-800 text-center relative group cursor-pointer {{ $date->isToday() ? 'bg-orange-50/30' : '' }}"
                                    wire:click="assignShift({{ $user->id }}, '{{ $date->format('Y-m-d') }}')">
                                    
                                    @if($shift)
                                        <div class="inline-block px-2 py-1 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 w-full truncate">
                                            {{ $shift->shift->name }}
                                        </div>
                                    @else
                                        <div class="h-6 w-full rounded border-2 border-dashed border-slate-200 dark:border-slate-700 group-hover:border-orange-300 transition-colors"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>