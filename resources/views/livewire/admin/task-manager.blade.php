<div class="space-y-8 animate-fade-in-up">
    <!-- Header & Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Task <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kolaborasi tim dan pelacakan pekerjaan.</p>
        </div>
        
        <div class="flex flex-wrap gap-3 items-center">
            <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                <button wire:click="$set('filterStatus', 'all')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filterStatus === 'all' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700' }}">Semua</button>
                <button wire:click="$set('filterStatus', 'pending')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filterStatus === 'pending' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700' }}">Pending</button>
                <button wire:click="$set('filterStatus', 'in_progress')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filterStatus === 'in_progress' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700' }}">Proses</button>
                <button wire:click="$set('filterStatus', 'completed')" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $filterStatus === 'completed' ? 'bg-white dark:bg-slate-700 shadow text-indigo-600 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700' }}">Selesai</button>
            </div>

            <label class="flex items-center gap-2 cursor-pointer bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                <input type="checkbox" wire:model.live="filterMyTasks" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Tugas Saya</span>
            </label>

            <button wire:click="create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Tugas
            </button>
        </div>
    </div>

    <!-- Kanban-ish Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all group relative overflow-hidden flex flex-col h-full">
                <!-- Priority Stripe -->
                <div class="absolute top-0 left-0 w-1 h-full 
                    {{ $task->priority === 'urgent' ? 'bg-rose-500' : '' }}
                    {{ $task->priority === 'high' ? 'bg-orange-500' : '' }}
                    {{ $task->priority === 'medium' ? 'bg-blue-500' : '' }}
                    {{ $task->priority === 'low' ? 'bg-slate-300' : '' }}
                "></div>

                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start mb-3 pl-2">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider
                            {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $task->status === 'pending' ? 'bg-slate-100 text-slate-600' : '' }}
                            {{ $task->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : '' }}
                        ">
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                        
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="edit({{ $task->id }})" class="p-1 text-slate-400 hover:text-indigo-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                            <button wire:click="delete({{ $task->id }})" wire:confirm="Hapus tugas ini?" class="p-1 text-slate-400 hover:text-rose-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                    </div>

                    <h3 class="font-bold text-slate-800 dark:text-white mb-2 pl-2">{{ $task->title }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 pl-2 mb-4">{{ $task->description ?? 'Tidak ada deskripsi.' }}</p>

                    @if($task->related)
                        <div class="ml-2 mb-4 p-2 bg-slate-50 dark:bg-slate-900 rounded border border-slate-100 dark:border-slate-700 text-xs text-slate-600 flex items-center gap-2">
                            <span class="font-bold">Link:</span>
                            <span class="truncate">{{ class_basename($task->related_type) }} #{{ $task->related->id }}</span>
                        </div>
                    @endif
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center pl-6">
                    <div class="flex items-center gap-2" title="Assigned To">
                        <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-[10px] font-bold text-indigo-700">
                            {{ substr($task->assignee->name ?? '?', 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 max-w-[80px] truncate">
                            {{ $task->assignee->name ?? 'Unassigned' }}
                        </span>
                    </div>

                    @if($task->due_date)
                        <div class="text-xs {{ $task->due_date->isPast() && $task->status != 'completed' ? 'text-rose-600 font-bold' : 'text-slate-400' }}">
                            Due: {{ $task->due_date->format('d M') }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <p>Tidak ada tugas ditemukan.</p>
            </div>
        @endforelse
    </div>
    
    {{ $tasks->links() }}

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">{{ $isEditMode ? 'Edit Tugas' : 'Buat Tugas Baru' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Judul Tugas</label>
                        <input wire:model="title" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                        @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Penerima (Assignee)</label>
                            <select wire:model="assigned_to" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Prioritas</label>
                            <select wire:model="priority" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tenggat Waktu</label>
                            <input wire:model="due_date" type="datetime-local" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Status</label>
                            <select wire:model="status" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="in_progress">Dalam Proses</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-slate-500 font-bold text-sm hover:text-slate-800">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all text-sm">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
