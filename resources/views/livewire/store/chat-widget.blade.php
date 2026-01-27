    <style>
        .chat-content p { margin-bottom: 0.5rem; }
        .chat-content p:last-child { margin-bottom: 0; }
        .chat-content ul { list-style-type: disc; margin-left: 1.2rem; margin-bottom: 0.5rem; }
        .chat-content ol { list-style-type: decimal; margin-left: 1.2rem; margin-bottom: 0.5rem; }
        .chat-content li { margin-bottom: 0.25rem; }
        .chat-content strong { font-weight: 800; }
        .chat-content a { text-decoration: underline; }
    </style>

    <!-- Jendela Chat -->
    <div x-data="{ open: @entangle('terbuka') }" 
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="bg-white dark:bg-slate-900 w-[380px] h-[600px] rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden origin-bottom-right font-sans">
        
        <!-- Header -->
        <div class="bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 p-5 flex justify-between items-center relative z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center font-black text-xs border-2 border-slate-100 dark:border-slate-800">
                        YA
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 {{ $isAdminMode ? 'bg-amber-500' : 'bg-emerald-500' }} border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 dark:text-white text-sm tracking-tight">YALA Assistant</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full {{ $isAdminMode ? 'bg-amber-500' : 'bg-emerald-500' }} animate-pulse"></span> 
                        {{ $isAdminMode ? 'Chat dengan Admin' : 'Online' }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @if($isAdminMode)
                    <button wire:click="akhiriChatAdmin" class="text-[10px] font-bold text-red-500 hover:bg-red-50 px-2 py-1 rounded transition-colors" title="Akhiri sesi admin">
                        Akhiri
                    </button>
                @endif
                <button wire:click="togleChat" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <!-- Area Pesan -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-[#F9FAFB] dark:bg-[#0f0f0f] custom-scrollbar" id="chat-messages">
            <!-- Welcome Message -->
            <div class="flex justify-start animate-fade-in-up">
                <div class="max-w-[85%] bg-white dark:bg-slate-800 p-4 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700 shadow-sm text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                    <p class="font-bold mb-1 text-slate-900 dark:text-white">Halo! 👋</p>
                    <p>Selamat datang di Yala Computer. Saya asisten virtual siap membantu cek stok, status pesanan, atau info lainnya.</p>
                </div>
            </div>

            @foreach($daftarPesan as $pesan)
                <div class="flex {{ $pesan->is_balasan_admin ? 'justify-start' : 'justify-end' }} animate-fade-in-up">
                    <div class="max-w-[85%] p-3.5 rounded-2xl text-sm leading-relaxed shadow-sm relative group {{ $pesan->is_balasan_admin ? 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-tl-none border border-slate-100 dark:border-slate-700' : 'bg-black dark:bg-white text-white dark:text-black rounded-tr-none' }}">
                        @if($pesan->is_balasan_admin)
                            <div class="chat-content">
                                {!! Str::markdown($pesan->isi) !!}
                            </div>
                        @else
                            {{ $pesan->isi }}
                        @endif
                        
                        <span class="text-[9px] opacity-0 group-hover:opacity-100 transition-opacity absolute bottom-1 {{ $pesan->is_balasan_admin ? 'right-2 text-slate-400' : 'left-2 text-white/50 dark:text-black/50' }}">
                            {{ $pesan->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick Suggestions (Hanya jika TIDAK dalam mode admin) -->
        @if(!$isAdminMode)
            <div class="px-5 pb-2 bg-[#F9FAFB] dark:bg-[#0f0f0f] flex gap-2 overflow-x-auto no-scrollbar">
                <button wire:click="$set('pesanBaru', 'Cek status pesanan saya')" class="whitespace-nowrap px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-black dark:hover:border-white transition-colors shadow-sm">
                    📦 Cek Pesanan
                </button>
                <button wire:click="$set('pesanBaru', 'Jam buka toko kapan?')" class="whitespace-nowrap px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-black dark:hover:border-white transition-colors shadow-sm">
                    🕒 Jam Buka
                </button>
                <button wire:click="$set('pesanBaru', 'Hubungkan ke Admin')" class="whitespace-nowrap px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-xs font-bold text-slate-600 dark:text-slate-300 hover:border-black dark:hover:border-white transition-colors shadow-sm">
                    👤 CS Admin
                </button>
            </div>
        @endif

        <!-- Input Area -->
        <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            <form wire:submit.prevent="kirimPesan" class="relative">
                <input wire:model.live="pesanBaru" type="text" placeholder="{{ $isAdminMode ? 'Ketik pesan untuk Admin...' : 'Tulis pesan...' }}" 
                    class="w-full pl-4 pr-12 py-3.5 bg-slate-50 dark:bg-slate-800 border-0 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white text-slate-900 dark:text-white placeholder-slate-400 font-medium transition-all">
                
                <button type="submit" class="absolute right-2 top-2 p-1.5 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:scale-105 transition-transform disabled:opacity-50" wire:loading.attr="disabled" wire:target="kirimPesan">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
            @error('pesanBaru') <span class="text-xs text-red-500 block mt-1 ml-1">{{ $message }}</span> @enderror
            <p class="text-[10px] text-center text-slate-400 mt-2 font-medium">
                {{ $isAdminMode ? 'Terhubung dengan Staf Support' : 'Powered by Yala AI • Respon Instan' }}
            </p>
        </div>
    </div>

    <!-- Tombol Floating Modern -->
    <button wire:click="togleChat" 
        class="w-16 h-16 bg-black dark:bg-white text-white dark:text-black rounded-full shadow-2xl hover:scale-110 hover:-rotate-12 transition-all duration-300 flex items-center justify-center group relative overflow-hidden">
        
        <!-- Notifikasi Badge -->
        @if($sesi && $sesi->pesan()->where('is_balasan_admin', true)->where('is_dibaca', false)->count() > 0)
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-bounce"></span>
        @endif

        <!-- Icon Chat Modern (Bubble Dots) -->
        <svg x-show="!open" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <svg x-show="open" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('pesan-terkirim', () => {
                const container = document.getElementById('chat-messages');
                container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
            });
        });
    </script>
</div>
