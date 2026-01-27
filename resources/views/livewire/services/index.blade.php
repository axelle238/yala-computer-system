<div class="h-[calc(100vh-8rem)] flex flex-col animate-fade-in-up space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Service <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Kanban</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Visual workflow tracking for repair center.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.servis.buat') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-900 border-2 border-slate-900 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tiket Baru
            </a>
        </div>
    </div>

    <!-- Mini Dashboard (Statistik) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-1">
        <!-- Antrian Masuk -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Antrian Masuk</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['queue']) }}</h3>
                <p class="text-[10px] text-blue-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Pending / Cek
                </p>
            </div>
        </div>

        <!-- Sedang Dikerjakan -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-amber-300 dark:hover:border-amber-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sedang Dikerjakan</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['working']) }}</h3>
                <p class="text-[10px] text-amber-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Perbaikan / Sparepart
                </p>
            </div>
        </div>

        <!-- Siap Diambil -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-emerald-300 dark:hover:border-emerald-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Siap Diambil</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['ready']) }}</h3>
                <p class="text-[10px] text-emerald-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                </p>
            </div>
        </div>

        <!-- Pendapatan Servis -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Omzet Servis (Bulan Ini)</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($stats['revenue_month'] / 1000, 0, ',', '.') }}k</h3>
                <p class="text-[10px] text-indigo-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Total Jasa & Part
                </p>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-4 custom-scrollbar">
        <div class="inline-flex h-full gap-6 min-w-full px-1">
            @foreach($statuses as $statusKey => $statusLabel)
                <div class="w-80 flex flex-col flex-shrink-0 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors hover:border-slate-300 dark:hover:border-slate-600">
                    <!-- Column Header -->
                    <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center sticky top-0 bg-white/95 dark:bg-slate-800/95 backdrop-blur z-10 rounded-t-2xl">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                            {{ $statusLabel }}
                        </span>
                        <span class="bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg text-[10px] font-bold text-slate-700 dark:text-slate-200 shadow-sm border border-slate-200 dark:border-slate-600">
                            {{ isset($tickets[$statusKey]) ? $tickets[$statusKey]->count() : 0 }}
                        </span>
                    </div>

                    <!-- Drop Zone -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar bg-slate-50/50 dark:bg-slate-900/20"
                         x-data
                         x-on:drop.prevent="$wire.updateStatus($event.dataTransfer.getData('text/plain'), '{{ $statusKey }}'); $el.classList.remove('bg-cyan-50', 'dark:bg-cyan-900/10')"
                         x-on:dragover.prevent="$el.classList.add('bg-cyan-50', 'dark:bg-cyan-900/10')"
                         x-on:dragleave.prevent="$el.classList.remove('bg-cyan-50', 'dark:bg-cyan-900/10')"
                    >
                        @if(isset($tickets[$statusKey]))
                            @foreach($tickets[$statusKey] as $ticket)
                                <div draggable="true" 
                                     x-on:dragstart="$event.dataTransfer.setData('text/plain', {{ $ticket->id }}); $el.classList.add('opacity-50')"
                                     x-on:dragend="$el.classList.remove('opacity-50')"
                                     class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 cursor-move hover:shadow-md hover:border-cyan-400 dark:hover:border-cyan-500 transition-all group relative overflow-hidden"
                                >
                                    <!-- Technician Indicator -->
                                    @if($ticket->technician)
                                        <div class="absolute top-0 right-0 p-1.5 bg-slate-50 dark:bg-slate-700 rounded-bl-lg border-l border-b border-slate-100 dark:border-slate-600">
                                            <div class="w-6 h-6 rounded-full bg-cyan-100 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-[9px] font-bold shadow-sm" title="{{ $ticket->technician->name }}">
                                                {{ substr($ticket->technician->name, 0, 2) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded select-none border border-slate-200 dark:border-slate-600">
                                            {{ $ticket->ticket_number }}
                                        </span>
                                    </div>
                                    
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white leading-tight mb-1 line-clamp-1">{{ $ticket->device_name }}</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-4 leading-relaxed">{{ $ticket->customer_name }} • {{ $ticket->problem_description }}</p>

                                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            {{ $ticket->created_at->format('d M') }}
                                        </span>
                                        
                                        <a href="{{ route('admin.servis.ubah', $ticket->id) }}" class="p-2 text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>