<div class="fixed bottom-6 right-6 z-[9000] flex flex-col items-end gap-4" wire:poll.5s>
    
    <!-- Jendela Chat -->
    <div x-data="{ tampil: @entangle('terbuka') }" 
         x-show="tampil" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="w-full max-w-sm sm:w-[400px] h-[500px] bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 flex flex-col overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 bg-indigo-600 flex justify-between items-center text-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center font-black text-sm">
                        YC
                    </div>
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-indigo-600 rounded-full"></div>
                </div>
                <div>
                    <h3 class="font-bold text-sm">Layanan Pelanggan</h3>
                    <p class="text-[10px] text-indigo-200 flex items-center gap-1">
                        @if($modeBot) 
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> Bot AI Aktif 
                        @else 
                            <span class="animate-pulse">●</span> Online 
                        @endif
                    </p>
                </div>
            </div>
            <button wire:click="togleChat" class="p-2 hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Area Pesan -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-slate-950 scroll-smooth" id="chat-container">
            @if($daftarPesan->isEmpty())
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-3 text-indigo-600 dark:text-indigo-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Halo! Ada yang bisa kami bantu?</p>
                    <p class="text-xs text-slate-400 mt-1">Tanya stok produk atau status pesanan.</p>
                </div>
            @else
                @foreach($daftarPesan as $pesan)
                    <div class="flex {{ $pesan->is_balasan_admin ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[85%] rounded-2xl p-3 text-sm leading-relaxed shadow-sm
                            {{ $pesan->is_balasan_admin 
                                ? 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-tl-none border border-slate-100 dark:border-slate-700' 
                                : 'bg-indigo-600 text-white rounded-tr-none' }}">
                            {!! Str::markdown($pesan->isi) !!}
                            <div class="text-[9px] mt-1 opacity-60 text-right">
                                {{ $pesan->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 shrink-0">
            <form wire:submit.prevent="kirimPesan" class="flex gap-2 items-end">
                <textarea wire:model="pesanBaru" 
                          rows="1" 
                          class="flex-1 bg-slate-50 dark:bg-slate-800 border-0 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white resize-none" 
                          placeholder="Ketik pesan..."
                          @keydown.enter.prevent="$wire.kirimPesan()"></textarea>
                <button type="submit" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg transition-all transform active:scale-95 flex-shrink-0">
                    <svg class="w-5 h-5 translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Tombol Pemicu -->
    <button wire:click="togleChat" class="group relative flex items-center justify-center w-14 h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full shadow-2xl shadow-indigo-600/40 transition-all hover:scale-110 active:scale-95">
        <span class="absolute top-0 right-0 w-3 h-3 bg-rose-500 border-2 border-white rounded-full animate-pulse" 
              @if(!$terbuka && $daftarPesan && $daftarPesan->isNotEmpty() && !$daftarPesan->last()->is_dibaca && $daftarPesan->last()->is_balasan_admin) style="display:block" @else style="display:none" @endif>
        </span>
        
        <svg x-show="!$wire.terbuka" class="w-7 h-7 transition-transform duration-300 group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
        <svg x-show="$wire.terbuka" class="w-7 h-7" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const container = document.getElementById('chat-container');
            
            const scrollToBottom = () => {
                if(container) container.scrollTop = container.scrollHeight;
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