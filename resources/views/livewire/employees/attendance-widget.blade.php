<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm h-full flex flex-col justify-center items-center text-center relative overflow-hidden" 
    x-data="{ time: new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) }" 
    x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}), 1000)">
    
    <div class="absolute top-0 right-0 p-4 opacity-10">
        <svg class="w-24 h-24 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </div>

    <h3 class="text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-widest mb-2">Presensi Hari Ini</h3>
    <div class="text-4xl font-black font-tech text-slate-800 dark:text-white mb-6" x-text="time">
        {{ now()->format('H:i') }}
    </div>

    @if(!$todayAttendance)
        <button wire:click="clockIn" class="w-full py-3 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white rounded-xl font-bold shadow-lg shadow-cyan-500/30 transition-all transform hover:-translate-y-1">
            CLOCK IN
        </button>
        <p class="text-xs text-slate-400 mt-3">Klik saat mulai bekerja</p>
    @elseif(!$todayAttendance->clock_out)
        <div class="mb-4">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase">
                Masuk: {{ $todayAttendance->clock_in->format('H:i') }}
            </span>
        </div>
        <button wire:click="clockOut" class="w-full py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold shadow-lg shadow-rose-500/30 transition-all transform hover:-translate-y-1">
            CLOCK OUT
        </button>
    @else
        <div class="space-y-2">
            <div class="flex justify-center gap-2">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">
                    IN: {{ $todayAttendance->clock_in->format('H:i') }}
                </span>
                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold">
                    OUT: {{ $todayAttendance->clock_out->format('H:i') }}
                </span>
            </div>
            <p class="text-sm font-bold text-slate-600 dark:text-slate-300 mt-2">Selesai Bekerja</p>
        </div>
    @endif
</div>
