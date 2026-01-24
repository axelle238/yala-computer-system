<div class="flex h-[calc(100vh-8rem)] overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm animate-fade-in-up">
    
    <!-- Sidebar List -->
    <div class="w-1/3 border-r border-slate-200 dark:border-slate-700 flex flex-col bg-slate-50 dark:bg-slate-900/50">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="font-bold text-slate-800 dark:text-white mb-2">Kotak Masuk</h2>
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm" placeholder="Cari pembeli...">
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($conversations as $conv)
                <button wire:click="selectConversation({{ $conv->id }})" class="w-full text-left p-4 border-b border-slate-100 dark:border-slate-800 hover:bg-white dark:hover:bg-slate-800 transition-colors relative {{ $activeConversationId === $conv->id ? 'bg-white dark:bg-slate-800 border-l-4 border-l-indigo-600' : 'border-l-4 border-l-transparent' }}">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-slate-800 dark:text-white text-sm truncate w-2/3">
                            {{ $conv->customer ? $conv->customer->name : 'Guest #' . substr($conv->guest_token, 0, 6) }}
                        </span>
                        <span class="text-[10px] text-slate-400 whitespace-nowrap">
                            {{ $conv->updated_at->diffForHumans(null, true, true) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 truncate pr-6">
                        {{ $conv->messages->last()?->body ?? 'Tidak ada pesan' }}
                    </p>
                    
                    @if($conv->unread_count > 0)
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                            {{ $conv->unread_count }}
                        </span>
                    @endif
                </button>
            @empty
                <div class="p-8 text-center text-slate-400 text-sm">
                    Belum ada percakapan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col bg-white dark:bg-slate-800 relative">
        @if($activeConversationId)
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 z-10 shadow-sm">
                @php $activeConv = $conversations->where('id', $activeConversationId)->first(); @endphp
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white">
                        {{ $activeConv->customer ? $activeConv->customer->name : 'Guest User' }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        {{ $activeConv->customer ? $activeConv->customer->email : 'Token: ' . substr($activeConv->guest_token, 0, 8) }}
                    </p>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50 dark:bg-slate-900 custom-scrollbar" id="admin-chat-box" wire:poll.3s>
                @foreach($activeMessages as $msg)
                    <div class="flex {{ $msg->is_admin_reply ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%]">
                            <div class="p-4 rounded-2xl text-sm shadow-sm {{ $msg->is_admin_reply ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-tl-none' }}">
                                {{ $msg->body }}
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 block {{ $msg->is_admin_reply ? 'text-right' : 'text-left' }}">
                                {{ $msg->created_at->format('d M H:i') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input -->
            <div class="p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input wire:model="replyMessage" type="text" class="flex-1 bg-slate-100 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Tulis balasan..." autofocus>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim
                    </button>
                </form>
            </div>

            <script>
                document.addEventListener('livewire:initialized', () => {
                    const box = document.getElementById('admin-chat-box');
                    const scroll = () => { if(box) box.scrollTop = box.scrollHeight; }
                    Livewire.on('message-sent', scroll);
                    scroll(); // Init
                });
            </script>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <p class="font-bold text-lg text-slate-600 dark:text-slate-300">Pusat Pesan Pembeli</p>
                <p class="text-sm">Pilih percakapan di sebelah kiri untuk mulai membalas.</p>
            </div>
        @endif
    </div>
</div>
