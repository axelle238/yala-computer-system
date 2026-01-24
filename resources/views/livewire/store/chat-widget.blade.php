<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end" x-data="{ open: @entangle('isOpen') }">
    
    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="w-80 md:w-96 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-4 flex flex-col h-[500px]">
        
        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-indigo-600 to-violet-600 flex justify-between items-center text-white shrink-0">
            <div>
                <h3 class="font-bold text-sm">Customer Support</h3>
                <p class="text-[10px] text-indigo-100">Biasanya membalas dalam 1 jam</p>
            </div>
            <button @click="open = false" class="text-white hover:bg-white/20 p-1 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-slate-900 custom-scrollbar" 
             id="chat-messages" 
             wire:poll.5s="refreshMessages">
            
            @forelse($messages as $msg)
                <div class="flex {{ $msg->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                    <div class="max-w-[80%] rounded-xl p-3 text-sm {{ $msg->is_admin_reply ? 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 shadow-sm border border-slate-100 dark:border-slate-700 rounded-tl-none' : 'bg-indigo-600 text-white shadow-md rounded-tr-none' }}">
                        <p>{{ $msg->body }}</p>
                        <span class="text-[10px] opacity-70 mt-1 block text-right">
                            {{ $msg->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Halo! Ada yang bisa kami bantu?</p>
                    <p class="text-xs text-slate-500">Tanyakan stok, spesifikasi, atau pesanan.</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700 shrink-0">
            <form wire:submit.prevent="sendMessage" class="flex gap-2">
                <input wire:model="newMessage" type="text" class="flex-1 bg-slate-100 dark:bg-slate-900 border-none rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Ketik pesan..." required>
                <button type="submit" class="p-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors shadow-lg shadow-indigo-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button wire:click="toggleChat" class="relative group">
        <div class="absolute inset-0 bg-indigo-600 rounded-full blur opacity-40 group-hover:opacity-60 transition-opacity animate-pulse"></div>
        <div class="relative w-14 h-14 bg-indigo-600 hover:bg-indigo-500 text-white rounded-full flex items-center justify-center shadow-xl transition-transform hover:scale-110">
            @if($isOpen)
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            @else
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                
                @php 
                    $unread = $conversation ? $conversation->messages()->where('is_admin_reply', true)->where('is_read', false)->count() : 0; 
                @endphp
                @if($unread > 0)
                    <span class="absolute top-0 right-0 w-5 h-5 bg-rose-500 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-slate-900">
                        {{ $unread }}
                    </span>
                @endif
            @endif
        </div>
    </button>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-messages');
            
            const scrollToBottom = () => {
                if(container) container.scrollTop = container.scrollHeight;
            }

            Livewire.on('message-sent', scrollToBottom);
            
            // Initial scroll
            scrollToBottom();
        });
    </script>
</div>
