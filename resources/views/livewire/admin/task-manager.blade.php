<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Team <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Tasks</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Papan kerja kolaboratif untuk tim internal.</p>
        </div>
        <button wire:click="createTask" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tugas Baru
        </button>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-full min-h-[500px]">
        
        <!-- TODO Column -->
        <div class="bg-slate-100 dark:bg-slate-800/50 rounded-2xl p-4 flex flex-col gap-4">
            <div class="flex justify-between items-center px-2">
                <h3 class="font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider text-xs">Akan Dikerjakan</h3>
                <span class="bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded text-xs font-bold">{{ $todoTasks->count() }}</span>
            </div>
            
            <div class="space-y-3">
                @foreach($todoTasks as $task)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all cursor-pointer group relative">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $task->priority == 'high' ? 'bg-rose-100 text-rose-600' : ($task->priority == 'medium' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600') }}">
                                {{ $task->priority }}
                            </span>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="updateStatus({{ $task->id }}, 'in_progress')" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-blue-500" title="Move to Progress">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1">{{ $task->title }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $task->description }}</p>
                        
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                {{ substr($task->assignee->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-xs text-slate-400">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- IN PROGRESS Column -->
        <div class="bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-4 flex flex-col gap-4 border border-blue-100 dark:border-blue-900/20">
            <div class="flex justify-between items-center px-2">
                <h3 class="font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider text-xs">Sedang Dikerjakan</h3>
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 px-2 py-0.5 rounded text-xs font-bold">{{ $progressTasks->count() }}</span>
            </div>

            <div class="space-y-3">
                @foreach($progressTasks as $task)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-blue-200 dark:border-blue-800 hover:shadow-md transition-all cursor-pointer group">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $task->priority == 'high' ? 'bg-rose-100 text-rose-600' : ($task->priority == 'medium' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600') }}">
                                {{ $task->priority }}
                            </span>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="updateStatus({{ $task->id }}, 'pending')" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-slate-600" title="Back to Todo">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                                </button>
                                <button wire:click="updateStatus({{ $task->id }}, 'completed')" class="p-1 hover:bg-emerald-50 rounded text-emerald-400 hover:text-emerald-600" title="Mark Done">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1">{{ $task->title }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $task->description }}</p>
                        
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                                {{ substr($task->assignee->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-xs text-slate-400">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- DONE Column -->
        <div class="bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl p-4 flex flex-col gap-4 border border-emerald-100 dark:border-emerald-900/20">
            <div class="flex justify-between items-center px-2">
                <h3 class="font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider text-xs">Selesai</h3>
                <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-300 px-2 py-0.5 rounded text-xs font-bold">{{ $doneTasks->count() }}</span>
            </div>

            <div class="space-y-3">
                @foreach($doneTasks as $task)
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-emerald-200 dark:border-emerald-800 opacity-75 hover:opacity-100 transition-all cursor-pointer group">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-600 line-through">
                                Selesai
                            </span>
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="updateStatus({{ $task->id }}, 'in_progress')" class="p-1 hover:bg-slate-100 rounded text-slate-400 hover:text-blue-500" title="Reopen">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                </button>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1 line-through decoration-slate-400">{{ $task->title }}</h4>
                        
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="text-xs text-slate-400">Selesai</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Buat Tugas Baru</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Judul Tugas</label>
                        <input wire:model="title" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Prioritas</label>
                            <select wire:model="priority" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                                <option value="low">Low (Santai)</option>
                                <option value="medium">Medium (Standar)</option>
                                <option value="high">High (Penting)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tugaskan Ke</label>
                            <select wire:model="assignee_id" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                                <option value="">-- Diri Sendiri --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>