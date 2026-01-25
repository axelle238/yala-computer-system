<div class="h-[calc(100vh-7rem)] flex flex-col md:flex-row gap-6 animate-fade-in-up p-6">
    
    <!-- Message List -->
    <div class="w-full md:w-1/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <h2 class="font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight text-xl">Inbox</h2>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($messages as $msg)
                <button wire:click="selectMessage({{ $msg->id }})" 
                        class="w-full text-left p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ $selectedMessage && $selectedMessage->id == $msg->id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-l-indigo-500' : '' }}">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $msg->name }}</span>
                        <span class="text-[10px] text-slate-400">{{ $msg->created_at->diffForHumans(null, true, true) }}</span>
                    </div>
                    <div class="text-xs font-medium text-slate-600 dark:text-slate-300 mb-1 truncate">{{ $msg->subject }}</div>
                    <p class="text-xs text-slate-400 line-clamp-2">{{ $msg->message }}</p>
                </button>
            @empty
                <div class="p-8 text-center text-slate-400 text-sm">Tidak ada pesan masuk.</div>
            @endforelse
        </div>
        
        <div class="p-2 border-t border-slate-200 dark:border-slate-700">
            {{ $messages->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Message Detail -->
    <div class="w-full md:w-2/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden relative">
        @if($selectedMessage)
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start bg-slate-50 dark:bg-slate-900/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $selectedMessage->subject }}</h3>
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $selectedMessage->name }}</span>
                        <span>&lt;{{ $selectedMessage->email }}&gt;</span>
                    </div>
                </div>
                <button wire:click="delete({{ $selectedMessage->id }})" class="text-slate-400 hover:text-rose-500 p-2" title="Hapus">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-white dark:bg-slate-800">
                <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                    {!! nl2br(e($selectedMessage->message)) !!}
                </div>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <textarea wire:model="replyBody" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-indigo-500 text-sm mb-3" placeholder="Tulis balasan Anda..."></textarea>
                <div class="flex justify-end">
                    <button wire:click="sendReply" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim Balasan
                    </button>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 dark:bg-slate-900/50">
                <svg class="w-20 h-20 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <p class="text-lg font-medium">Pilih pesan untuk membaca</p>
            </div>
        @endif
    </div>
</div>
