<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up" wire:poll.1s="loadMore">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Traffic <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Inspector</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Deep Packet Inspection & Live Request Log.</p>
        </div>
        <button wire:click="togglePause" class="px-6 py-3 rounded-xl font-bold shadow-lg transition-all {{ $isPaused ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
            {{ $isPaused ? 'RESUME STREAM' : 'PAUSE STREAM' }}
        </button>
    </div>

    <!-- Terminal View -->
    <div class="bg-slate-900 rounded-[2rem] p-6 shadow-2xl border border-slate-700 font-mono text-xs md:text-sm overflow-hidden h-[600px] flex flex-col relative">
        <div class="absolute top-4 right-6 flex gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-1 pb-4" id="terminal-log">
            @foreach($requests as $req)
                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 p-2 hover:bg-white/5 rounded transition-colors border-l-2 {{ $req['status'] >= 400 ? 'border-red-500' : 'border-emerald-500' }}">
                    <span class="text-slate-500 shrink-0 w-24">{{ $req['time'] }}</span>
                    <span class="font-bold {{ $req['method'] == 'GET' ? 'text-blue-400' : ($req['method'] == 'POST' ? 'text-amber-400' : 'text-red-400') }} shrink-0 w-16">{{ $req['method'] }}</span>
                    <span class="text-slate-300 shrink-0 w-32">{{ $req['ip'] }}</span>
                    <span class="text-slate-100 flex-1 truncate">{{ $req['path'] }}</span>
                    <span class="font-bold {{ $req['status'] >= 400 ? 'text-red-500' : 'text-emerald-500' }} shrink-0 w-12">{{ $req['status'] }}</span>
                    <span class="text-slate-500 shrink-0 w-16 text-right">{{ $req['size'] }}</span>
                </div>
            @endforeach
        </div>
        
        <div class="pt-4 border-t border-slate-700 text-slate-500 flex justify-between items-center">
            <span>Status: {{ $isPaused ? 'PAUSED' : 'LISTENING...' }}</span>
            <span>Packet Capture Interface: eth0</span>
        </div>
    </div>
</div>
