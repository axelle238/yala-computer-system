<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Audit Log System</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Rekaman jejak digital aktivitas pengguna.</p>
        </div>
    </div>

    <!-- Timeline View -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
        <div class="mb-6">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-64 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Cari aktivitas...">
        </div>

        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($logs as $log)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white 
                                        {{ $log->action == 'created' ? 'bg-emerald-500' : ($log->action == 'deleted' ? 'bg-rose-500' : 'bg-blue-500') }}">
                                        @if($log->action == 'created')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        @elseif($log->action == 'deleted')
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        @else
                                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-slate-500">
                                            <span class="font-bold text-slate-900">{{ $log->user->name ?? 'System' }}</span>
                                            {{ $log->description }}
                                            <span class="font-mono text-xs bg-slate-100 px-1 rounded">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</span>
                                        </p>
                                        @if($log->action == 'updated' && isset($log->properties['old']))
                                            <div class="mt-2 text-xs bg-slate-50 p-2 rounded border border-slate-100 font-mono text-slate-600">
                                                Perubahan: 
                                                @foreach(array_keys($log->properties['new']) as $key)
                                                    @if(isset($log->properties['old'][$key]) && $log->properties['old'][$key] != $log->properties['new'][$key])
                                                        <br><span class="text-rose-500">{{ $key }}: {{ $log->properties['old'][$key] }}</span> 
                                                        &rarr; <span class="text-emerald-500">{{ $log->properties['new'][$key] }}</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-slate-500">
                                        <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                        <div class="text-[10px] text-slate-300 mt-1">{{ $log->ip_address }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
