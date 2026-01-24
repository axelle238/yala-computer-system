<div class="p-6 h-[calc(100vh-8rem)] flex flex-col">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Task Manager</h1>
            <p class="text-gray-500">Delegasi tugas internal dan pelacakan pekerjaan.</p>
        </div>
        <div class="flex gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tugas..." class="rounded-lg border-gray-300 text-sm">
            <button wire:click="create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm shadow-lg hover:bg-indigo-700 transition">
                + Tugas Baru
            </button>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto pb-4">
        <div class="flex gap-6 h-full min-w-[1000px]">
            
            <!-- TODO Column -->
            <div class="flex-1 flex flex-col bg-slate-100 rounded-xl border border-slate-200 h-full">
                <div class="p-4 border-b border-slate-200 bg-slate-50 rounded-t-xl sticky top-0 z-10 flex justify-between">
                    <h3 class="font-bold text-slate-700 uppercase text-xs tracking-wider">To Do</h3>
                    <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">{{ $todoTasks->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                    @foreach($todoTasks as $task)
                        <div wire:key="task-{{ $task->id }}" class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 cursor-pointer hover:shadow-md transition group relative">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-600' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }}">
                                    {{ $task->priority }}
                                </span>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $task->id }})" class="text-slate-400 hover:text-blue-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                    <button wire:click="delete({{ $task->id }})" wire:confirm="Hapus tugas ini?" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                </div>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $task->description }}</p>
                            
                            <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold" title="Assignee: {{ $task->assignee->name }}">
                                        {{ substr($task->assignee->name, 0, 1) }}
                                    </div>
                                    @if($task->due_date)
                                        <span class="text-[10px] {{ $task->due_date->isPast() ? 'text-red-500 font-bold' : 'text-slate-400' }}">
                                            {{ $task->due_date->format('d M') }}
                                        </span>
                                    @endif
                                </div>
                                <button wire:click="updateStatus({{ $task->id }}, 'in_progress')" class="text-xs font-bold text-blue-600 hover:underline">Start &rarr;</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="flex-1 flex flex-col bg-blue-50 rounded-xl border border-blue-100 h-full">
                <div class="p-4 border-b border-blue-200 bg-blue-50/50 rounded-t-xl sticky top-0 z-10 flex justify-between">
                    <h3 class="font-bold text-blue-700 uppercase text-xs tracking-wider">In Progress</h3>
                    <span class="bg-blue-200 text-blue-700 px-2 py-0.5 rounded text-xs font-bold">{{ $progressTasks->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                    @foreach($progressTasks as $task)
                        <div wire:key="task-{{ $task->id }}" class="bg-white p-4 rounded-lg shadow-sm border border-blue-100 cursor-pointer hover:shadow-md transition group">
                            <!-- Similar Card Structure -->
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $task->priority }}
                                </span>
                                <button wire:click="edit({{ $task->id }})" class="text-slate-400 hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $task->title }}</h4>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-100 mt-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold">
                                        {{ substr($task->assignee->name, 0, 1) }}
                                    </div>
                                </div>
                                <button wire:click="updateStatus({{ $task->id }}, 'completed')" class="text-xs font-bold text-emerald-600 hover:underline">Complete &rarr;</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Completed Column -->
            <div class="flex-1 flex flex-col bg-emerald-50 rounded-xl border border-emerald-100 h-full">
                <div class="p-4 border-b border-emerald-200 bg-emerald-50/50 rounded-t-xl sticky top-0 z-10 flex justify-between">
                    <h3 class="font-bold text-emerald-700 uppercase text-xs tracking-wider">Done</h3>
                    <span class="bg-emerald-200 text-emerald-700 px-2 py-0.5 rounded text-xs font-bold">{{ $completedTasks->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto p-3 space-y-3 custom-scrollbar">
                    @foreach($completedTasks as $task)
                        <div wire:key="task-{{ $task->id }}" class="bg-white/60 p-4 rounded-lg border border-emerald-100 opacity-75 hover:opacity-100 transition">
                            <h4 class="font-bold text-gray-700 text-sm line-through">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-400 mt-1">Selesai: {{ $task->updated_at->format('d M H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
                <h3 class="text-xl font-bold mb-4">{{ $taskId ? 'Edit Tugas' : 'Buat Tugas Baru' }}</h3>
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul Tugas</label>
                        <input wire:model="title" type="text" class="w-full rounded-lg border-gray-300">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Assigned To</label>
                            <select wire:model="assigned_to" class="w-full rounded-lg border-gray-300">
                                <option value="">Pilih Staff</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Prioritas</label>
                            <select wire:model="priority" class="w-full rounded-lg border-gray-300">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Due Date</label>
                            <input wire:model="due_date" type="date" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                            <select wire:model="status" class="w-full rounded-lg border-gray-300">
                                <option value="todo">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
