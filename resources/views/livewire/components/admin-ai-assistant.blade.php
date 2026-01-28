<div class="fixed bottom-6 right-6 z-50 font-sans" x-data="{ open: @entangle('isOpen') }">
    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="bg-white dark:bg-slate-900 w-[360px] h-[500px] rounded-2xl shadow-2xl border border-indigo-100 dark:border-slate-700 flex flex-col overflow-hidden mb-4">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">Yala Brain AI</h3>
                    <p class="text-[10px] text-indigo-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online & Siap
                    </p>
                </div>
            </div>
            <button wire:click="toggle" class="text-white/80 hover:text-white"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-slate-950/50 custom-scrollbar" id="ai-chat-box">
            @foreach($chatHistory as $chat)
                <div class="flex {{ $chat['role'] == 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] p-3 rounded-2xl text-xs leading-relaxed shadow-sm 
                        {{ $chat['role'] == 'user' ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-bl-none border border-slate-200 dark:border-slate-700' }}">
                        {!! Str::markdown($chat['message']) !!}
                        <div class="mt-1 text-[9px] opacity-70 text-right">{{ $chat['time'] }}</div>
                    </div>
                </div>
            @endforeach

            @if($isThinking)
                <div class="flex justify-start" wire:init="processAnswer">
                    <div class="bg-white dark:bg-slate-800 p-3 rounded-2xl rounded-bl-none border border-slate-200 dark:border-slate-700 shadow-sm">
                        <div class="flex gap-1">
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-75"></span>
                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce delay-150"></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <form wire:submit.prevent="ask" class="relative">
                <input wire:model="inputQuery" type="text" placeholder="Tanya tentang data toko..." 
                    class="w-full pl-4 pr-10 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-xs font-medium focus:ring-2 focus:ring-indigo-500 text-slate-800 dark:text-white transition-all">
                <button type="submit" class="absolute right-2 top-2 p-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Button -->
    <button wire:click="toggle" 
        class="w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-xl shadow-indigo-500/30 flex items-center justify-center transition-all duration-300 hover:scale-105 group relative">
        <span class="absolute -top-1 -right-1 flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
        </span>
        <svg x-show="!open" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
        <svg x-show="open" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Auto scroll ke bawah saat ada pesan baru
            const chatBox = document.getElementById('ai-chat-box');
            const observer = new MutationObserver(() => {
                chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
            });
            observer.observe(chatBox, { childList: true, subtree: true });
        });
    </script>
</div>
