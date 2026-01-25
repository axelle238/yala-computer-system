<div class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-[9000] flex flex-col items-end gap-4" wire:poll.5s>
    
    <!-- Jendela Chat -->
    <div x-data="{ tampil: @entangle('terbuka') }" 
         x-show="tampil" 
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition cubic-bezier(0.16, 1, 0.3, 1) duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-90"
         class="w-[calc(100vw-2rem)] sm:w-[380px] h-[550px] max-h-[calc(100vh-6rem)] bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden origin-bottom-right"
         style="display: none;">
        
        <!-- Header Modern -->
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-violet-600 flex justify-between items-center text-white shrink-0 shadow-md relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
            
            <div class="flex items-center gap-3 relative z-10">
                <div class="relative">
                    <div class="w-11 h-11 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-black text-sm border border-white/10 shadow-inner">
                        YC
                    </div>
                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-400 border-[3px] border-indigo-600 rounded-full"></div>
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight">Yala Support</h3>
                    <p class="text-[11px] text-indigo-100/90 flex items-center gap-1.5 font-medium">
                        @if($modeBot) 
                            <span class="flex items-center gap-1 bg-white/10 px-1.5 py-0.5 rounded text-[9px] uppercase tracking-wide">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> AI Bot
                            </span>
                        @else 
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                            </span>
                            Admin Online 
                        @endif
                    </p>
                </div>
            </div>
            <button wire:click="togleChat" class="p-2 hover:bg-white/20 rounded-full transition-colors relative z-10">
                <svg class="w-6 h-6 text-indigo-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Area Pesan -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50 dark:bg-slate-950/50 scroll-smooth" id="chat-container">
            @if($daftarPesan->isEmpty())
                <div class="h-full flex flex-col items-center justify-center text-center p-4">
                    <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mb-4 animate-bounce-slow">
                        <svg class="w-10 h-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <h4 class="text-slate-800 dark:text-white font-bold text-lg mb-1">Halo, Sobat Yala! 👋</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-[200px]">Butuh bantuan rakit PC atau cek stok produk? Tanyakan saja di sini!</p>
                </div>
            @else
                
                @foreach($daftarPesan as $pesan)
                    <div class="group flex w-full {{ $pesan->is_balasan_admin ? 'justify-start' : 'justify-end' }} animate-fade-in-up">
                        <div class="max-w-[85%] relative">
                            @if($pesan->is_balasan_admin)
                                <div class="absolute -left-2 top-0 w-2 h-2">
                                    <svg viewBox="0 0 10 10" class="w-2 h-2 text-white dark:text-slate-800 fill-current"><path d="M10 0v10H0z"/></svg>
                                </div>
                            @else
                                <div class="absolute -right-2 top-0 w-2 h-2">
                                    <svg viewBox="0 0 10 10" class="w-2 h-2 text-indigo-600 fill-current rotate-90"><path d="M10 0v10H0z"/></svg>
                                </div>
                            @endif

                            <div class="px-4 py-2.5 shadow-sm text-sm leading-relaxed
                                {{ $pesan->is_balasan_admin 
                                    ? 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700' 
                                    : 'bg-indigo-600 text-white rounded-2xl rounded-tr-none' }}">
                                {!! Str::markdown($pesan->isi) !!}
                            </div>
                            <div class="text-[10px] mt-1 opacity-50 px-1 font-medium {{ $pesan->is_balasan_admin ? 'text-left' : 'text-right' }}">
                                {{ $pesan->created_at->format('H:i') }}
                                @if(!$pesan->is_balasan_admin)
                                    <span class="ml-1 text-xs">
                                        @if($pesan->is_dibaca) 
                                            <span class="text-emerald-400" title="Dibaca">✓✓</span> 
                                        @else 
                                            <span class="text-indigo-200" title="Terkirim">✓</span> 
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 shrink-0">
            <form wire:submit.prevent="kirimPesan" class="relative flex items-end gap-2">
                <textarea wire:model="pesanBaru" 
                          rows="1" 
                          class="w-full bg-slate-100 dark:bg-slate-800 border-0 rounded-3xl px-5 py-3.5 pr-12 text-sm focus:ring-2 focus:ring-indigo-500/50 dark:text-white resize-none scrollbar-hide placeholder-slate-400" 
                          placeholder="Ketik pesan..."
                          style="min-height: 48px; max-height: 120px;"
                          x-data="{ resize() { $el.style.height = '48px'; $el.style.height = $el.scrollHeight + 'px' } }"
                          x-init="resize()"
                          @input="resize()"
                          @keydown.enter.prevent="if(!event.shiftKey) { $wire.kirimPesan(); $el.style.height = '48px'; }"></textarea>
                
                <button type="submit" 
                        class="absolute right-2 bottom-2 p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-lg transition-all transform active:scale-90 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="kirimPesan">
                    <svg class="w-5 h-5 translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
            <p class="text-[10px] text-center text-slate-400 mt-2">Ditenagai oleh Yala AI & Tim Support</p>
        </div>
    </div>

    <!-- Tombol Pemicu -->
    <div class="relative group">
        <span class="absolute right-full mr-4 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl shadow-lg border border-slate-100 dark:border-slate-700 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none hidden md:block">
            Chat dengan Kami 👋
            <div class="absolute top-1/2 -right-1.5 -translate-y-1/2 w-3 h-3 bg-white dark:bg-slate-800 rotate-45 border-t border-r border-slate-100 dark:border-slate-700"></div>
        </span>

        <button wire:click="togleChat" class="flex items-center justify-center w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white rounded-full shadow-2xl shadow-indigo-600/40 transition-all hover:scale-110 active:scale-95 ring-4 ring-white/20 dark:ring-slate-900/20">
            <span class="absolute top-0 right-0 w-4 h-4 bg-rose-500 border-2 border-white dark:border-slate-900 rounded-full animate-bounce" 
                  @if(!$terbuka && $daftarPesan && $daftarPesan->isNotEmpty() && !$daftarPesan->last()->is_dibaca && $daftarPesan->last()->is_balasan_admin) style="display:block" @else style="display:none" @endif>
            </span>
            
            <svg x-show="!$wire.terbuka" class="w-7 h-7 md:w-8 md:h-8 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            <svg x-show="$wire.terbuka" class="w-8 h-8" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
        </button>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-container');
            
            const scrollToBottom = () => {
                if(container) {
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 50); // Small delay to ensure DOM update
                }
            };

            Livewire.on('pesan-terkirim', scrollToBottom);
            
            // Auto scroll on open
            Livewire.hook('morph.updated', ({ component, el }) => {
                if (component.el.contains(container) && @this.terbuka) {
                    scrollToBottom();
                }
            });
        });
    </script>
</div>
