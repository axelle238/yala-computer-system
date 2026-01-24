<div class="h-[calc(100vh-8rem)] flex flex-col">
    <!-- Header Controls -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black font-tech text-slate-800 dark:text-white uppercase tracking-tight">Service <span class="text-indigo-600">Kanban</span></h2>
            <p class="text-slate-500 text-sm">Drag and drop tickets to update status.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('services.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-bold hover:bg-slate-200 transition">
                List View
            </a>
            <a href="{{ route('services.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition">
                + New Ticket
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto pb-4">
        <div class="flex gap-6 h-full min-w-max">
            @foreach($statuses as $statusKey => $statusLabel)
                <div class="w-80 flex flex-col bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-800 h-full"
                     x-data
                     @dragover.prevent="$el.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20', 'border-indigo-300')"
                     @dragleave="$el.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20', 'border-indigo-300')"
                     @drop="
                        $el.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20', 'border-indigo-300');
                        let ticketId = event.dataTransfer.getData('ticketId');
                        $wire.updateStatus(ticketId, '{{ $statusKey }}');
                     ">
                    
                    <!-- Column Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center sticky top-0 bg-inherit rounded-t-xl z-10 backdrop-blur-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full 
                                {{ $statusKey == 'pending' ? 'bg-slate-400' : '' }}
                                {{ $statusKey == 'diagnosing' ? 'bg-blue-500' : '' }}
                                {{ $statusKey == 'waiting_part' ? 'bg-amber-500' : '' }}
                                {{ $statusKey == 'repairing' ? 'bg-indigo-500' : '' }}
                                {{ $statusKey == 'ready' ? 'bg-emerald-500' : '' }}
                            "></div>
                            <h3 class="font-bold text-slate-700 dark:text-slate-200 text-sm uppercase">{{ $statusLabel }}</h3>
                        </div>
                        <span class="px-2 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded text-xs font-bold">
                            {{ $tickets->get($statusKey)?->count() ?? 0 }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                        @forelse($tickets->get($statusKey) ?? [] as $ticket)
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('ticketId', '{{ $ticket->id }}')"
                                 class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow-sm border border-slate-100 dark:border-slate-700 cursor-grab active:cursor-grabbing hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/50 transition-all group relative">
                                
                                <!-- Ticket ID & Date -->
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-mono text-xs font-bold text-slate-400">#{{ $ticket->ticket_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>

                                <!-- Customer Info -->
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1">{{ $ticket->customer_name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ $ticket->device_name }}</p>

                                <!-- Problem Preview -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-2 rounded text-xs text-slate-600 dark:text-slate-300 mb-3 line-clamp-2">
                                    {{ $ticket->problem_description }}
                                </div>

                                <!-- Footer -->
                                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-1.5" title="Technician">
                                        <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold">
                                            {{ substr($ticket->technician->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] text-slate-500">{{ $ticket->technician->name ?? 'Unassigned' }}</span>
                                    </div>
                                    
                                    <a href="{{ route('services.edit', $ticket->id) }}" class="text-slate-300 hover:text-indigo-500 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 opacity-50 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-lg">
                                <p class="text-xs text-slate-400">No tickets</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
