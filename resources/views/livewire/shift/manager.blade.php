<div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Shift <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Roster</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pengaturan jadwal kerja tim operasional.</p>
        </div>
        
        <!-- Week Navigator -->
        <div class="flex items-center bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-1">
            <button wire:click="prevWeek" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <div class="px-4 font-bold text-slate-700 dark:text-slate-200 text-sm whitespace-nowrap min-w-[200px] text-center">
                {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($startDate)->addDays(6)->format('d M Y') }}
            </div>
            <button wire:click="nextWeek" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    </div>

    <!-- Roster Grid -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs border-b border-slate-200 dark:border-slate-700">
                    <th class="px-4 py-4 w-48 sticky left-0 bg-slate-50 dark:bg-slate-900 z-10 border-r border-slate-200 dark:border-slate-700">Pegawai</th>
                    @foreach($weekDates as $date)
                        <th class="px-2 py-4 text-center {{ $date->isToday() ? 'bg-blue-50/50 dark:bg-blue-900/10 text-blue-600 dark:text-blue-400' : '' }}">
                            <div class="flex flex-col">
                                <span>{{ $date->translatedFormat('D') }}</span>
                                <span class="text-lg">{{ $date->format('d') }}</span>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($employees as $emp)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                        <!-- Employee Info -->
                        <td class="px-4 py-3 sticky left-0 bg-white dark:bg-slate-800 z-10 border-r border-slate-200 dark:border-slate-700 group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 text-xs">
                                    {{ substr($emp->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $emp->name }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wide">{{ $emp->role }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Shift Cells -->
                        @foreach($weekDates as $date)
                            @php 
                                $dateStr = $date->format('Y-m-d');
                                $shiftId = $emp->roster[$dateStr] ?? 4;
                                $shiftData = $shifts->firstWhere('id', $shiftId);
                            @endphp
                            <td class="px-1 py-2 text-center h-16 relative group cursor-pointer" wire:click="openShiftPanel({{ $emp->id }}, '{{ $dateStr }}')">
                                <div class="w-full h-full rounded-lg border flex flex-col items-center justify-center p-1 transition-all hover:scale-95 shadow-sm {{ $shiftData['color'] }}">
                                    <span class="font-bold text-xs">{{ $shiftData['name'] }}</span>
                                    <span class="text-[9px] opacity-75">{{ $shiftData['time'] }}</span>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Shift Action Panel -->
    @if($activeAction === 'edit')
        <div class="fixed inset-x-0 bottom-0 z-50 p-4 pointer-events-none flex justify-center">
            <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 pointer-events-auto animate-slide-up">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Ubah Jadwal Shift</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pilih shift untuk pegawai pada tanggal terpilih.</p>
                        </div>
                        <button wire:click="closePanel" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($shifts as $shift)
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model="selectedShiftId" value="{{ $shift['id'] }}" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 transition-all text-center
                                    {{ $selectedShiftId == $shift['id'] 
                                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 ring-2 ring-blue-500/20' 
                                        : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 bg-slate-50 dark:bg-slate-900' 
                                    }}">
                                    <div class="w-3 h-3 rounded-full {{ explode(' ', $shift['color'])[0] }} border mx-auto mb-2"></div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $shift['name'] }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $shift['time'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button wire:click="closePanel" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                        <button wire:click="saveShift" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all transform active:scale-95">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
