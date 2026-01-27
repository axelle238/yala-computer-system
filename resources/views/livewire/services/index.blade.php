<div class="h-[calc(100vh-8rem)] flex flex-col animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Service <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Kanban</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Visual workflow tracking for repair center.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.servis.buat') }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl shadow-lg shadow-cyan-500/20 hover:shadow-cyan-500/40 transition-all font-bold text-sm hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tiket Baru
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden pb-4 custom-scrollbar">
        <div class="inline-flex h-full gap-4 min-w-full">
            @foreach($statuses as $statusKey => $statusLabel)
                <div class="w-80 flex flex-col flex-shrink-0 bg-slate-100 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700/50">
                    <!-- Column Header -->
                    <div class="p-3 border-b border-slate-200 dark:border-slate-700/50 flex justify-between items-center sticky top-0 bg-slate-100/90 dark:bg-slate-800/90 backdrop-blur z-10 rounded-t-2xl">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                            {{ $statusLabel }}
                        </span>
                        <span class="bg-white dark:bg-slate-700 px-2 py-0.5 rounded-md text-[10px] font-bold text-slate-700 dark:text-slate-200 shadow-sm">
                            {{ isset($tickets[$statusKey]) ? $tickets[$statusKey]->count() : 0 }}
                        </span>
                    </div>

                    <!-- Drop Zone -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar"
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
                                            <div class="w-5 h-5 rounded-full bg-cyan-100 dark:bg-cyan-900 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-[8px] font-bold" title="{{ $ticket->technician->name }}">
                                                {{ substr($ticket->technician->name, 0, 2) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-2">
                                        <span class="text-[10px] font-mono font-bold text-slate-400 bg-slate-50 dark:bg-slate-700/50 px-1.5 py-0.5 rounded select-none">
                                            {{ $ticket->ticket_number }}
                                        </span>
                                    </div>
                                    
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white leading-tight mb-1">{{ $ticket->device_name }}</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ $ticket->customer_name }} • {{ $ticket->problem_description }}</p>

                                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                        <span class="text-[10px] font-semibold text-slate-400">
                                            {{ $ticket->created_at->format('d M') }}
                                        </span>
                                        
                                        <a href="{{ route('admin.servis.ubah', $ticket->id) }}" class="p-1.5 text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 bg-slate-50 dark:bg-slate-700 rounded-lg transition-colors">
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
