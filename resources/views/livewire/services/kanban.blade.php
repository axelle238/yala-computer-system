<div class="h-[calc(100vh-8rem)] flex flex-col">
    <!-- Header Controls -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                Papan <span class="text-indigo-600">Kanban Servis</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola alur kerja servis dengan metode drag & drop yang efisien.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('services.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Mode Daftar
            </a>
            <a href="{{ route('services.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-500/30 transition flex items-center gap-2 transform active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Tiket Baru
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto pb-6 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-slate-700">
        <div class="flex gap-6 h-full min-w-max px-1">
            @php
                // Mapping status ke Bahasa Indonesia & Warna
                $statusMap = [
                    'pending' => ['label' => 'Menunggu Antrian', 'color' => 'bg-slate-500', 'border' => 'border-slate-500'],
                    'diagnosing' => ['label' => 'Sedang Diagnosa', 'color' => 'bg-blue-500', 'border' => 'border-blue-500'],
                    'waiting_part' => ['label' => 'Menunggu Sparepart', 'color' => 'bg-amber-500', 'border' => 'border-amber-500'],
                    'repairing' => ['label' => 'Sedang Dikerjakan', 'color' => 'bg-indigo-600', 'border' => 'border-indigo-600'],
                    'ready' => ['label' => 'Selesai / Siap Ambil', 'color' => 'bg-emerald-500', 'border' => 'border-emerald-500'],
                    'picked_up' => ['label' => 'Sudah Diambil', 'color' => 'bg-slate-800', 'border' => 'border-slate-800'],
                ];
            @endphp

            @foreach($statuses as $statusKey => $statusLabelOriginal)
                @php $statusInfo = $statusMap[$statusKey] ?? ['label' => $statusLabelOriginal, 'color' => 'bg-slate-400', 'border' => 'border-slate-400']; @endphp
                
                <div class="w-80 flex flex-col bg-slate-100 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-800 h-full shadow-inner"
                     x-data
                     @dragover.prevent="$el.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/10')"
                     @dragleave="$el.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/10')"
                     @drop="
                        $el.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/10');
                        let ticketId = event.dataTransfer.getData('ticketId');
                        $wire.updateStatus(ticketId, '{{ $statusKey }}');
                     ">
                    
                    <!-- Column Header -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700/50 flex justify-between items-center sticky top-0 bg-slate-100/90 dark:bg-slate-900/90 backdrop-blur-md rounded-t-2xl z-10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full {{ $statusInfo['color'] }} shadow-sm"></div>
                            <h3 class="font-extrabold text-slate-700 dark:text-slate-200 text-xs uppercase tracking-wider">{{ $statusInfo['label'] }}</h3>
                        </div>
                        <span class="px-2.5 py-0.5 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold border border-slate-200 dark:border-slate-700 shadow-sm">
                            {{ $tickets->get($statusKey)?->count() ?? 0 }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                        @forelse($tickets->get($statusKey) ?? [] as $ticket)
                            <div draggable="true"
                                 @dragstart="event.dataTransfer.setData('ticketId', '{{ $ticket->id }}')"
                                 class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border-l-4 {{ $statusInfo['border'] }} cursor-grab active:cursor-grabbing hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group relative">
                                
                                <!-- Ticket Header -->
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-mono text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-1.5 py-0.5 rounded border border-indigo-100 dark:border-indigo-800">#{{ $ticket->ticket_number }}</span>
                                    
                                    <!-- Priority Badge (Optional Logic) -->
                                    @if($ticket->priority == 'high')
                                        <span class="text-[10px] font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/20 px-1.5 py-0.5 rounded flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                            Prioritas
                                        </span>
                                    @else
                                        <span class="text-[10px] text-slate-400">{{ $ticket->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>

                                <!-- Customer Info -->
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-0.5 truncate">{{ $ticket->customer_name }}</h4>
                                <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 mb-3">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    <span class="truncate max-w-[180px]">{{ $ticket->device_name }}</span>
                                </div>

                                <!-- Problem Preview -->
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-2.5 rounded-lg text-xs text-slate-600 dark:text-slate-300 mb-3 line-clamp-2 border border-slate-100 dark:border-slate-700/50 italic leading-relaxed">
                                    "{{ $ticket->problem_description }}"
                                </div>

                                <!-- Footer -->
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                    <div class="flex items-center gap-2" title="Teknisi Penanggung Jawab">
                                        @if($ticket->technician)
                                            <div class="flex items-center gap-1.5 bg-slate-50 dark:bg-slate-700/50 pr-2 rounded-full border border-slate-100 dark:border-slate-600">
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-[10px] font-bold">
                                                    {{ substr($ticket->technician->name, 0, 1) }}
                                                </div>
                                                <span class="text-[10px] font-semibold text-slate-600 dark:text-slate-300">{{ explode(' ', $ticket->technician->name)[0] }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 opacity-50">
                                                <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-400 flex items-center justify-center text-[10px]">?</div>
                                                <span class="text-[10px] italic text-slate-400">Belum Ada</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('services.workbench', $ticket->id) }}" class="p-1.5 text-slate-400 hover:text-white hover:bg-indigo-600 rounded-lg transition-colors group-hover:bg-indigo-50 dark:group-hover:bg-slate-700 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" title="Buka Workbench (Meja Kerja)">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 opacity-40 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kosong</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
