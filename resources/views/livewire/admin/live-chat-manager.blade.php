<div class="h-[calc(100vh-7rem)] flex flex-col md:flex-row gap-6 animate-fade-in-up p-6" wire:poll.5s>
    
    <!-- Sidebar List Sesi -->
    <div class="w-full md:w-1/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <h2 class="font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight text-xl mb-3">Layanan Pelanggan</h2>
            <div class="relative">
                <input wire:model.live.debounce.300ms="cariPelanggan" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari Pelanggan atau Topik...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($daftarSesi as $sesi)
                <button wire:click="pilihSesi({{ $sesi->id }})" 
                        class="w-full text-left p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ $sesiTerpilihId == $sesi->id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-l-indigo-500' : '' }}">
                    <div class="flex justify-between items-start mb-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800 dark:text-white text-sm truncate max-w-[120px]">
                                {{ $sesi->pelanggan->name ?? 'Tamu (' . substr($sesi->token_tamu, 0, 6) . ')' }}
                            </span>
                            @if(isset($sesi->pelanggan) && in_array($sesi->pelanggan->loyalty_tier, ['Gold', 'Platinum']))
                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[8px] font-black uppercase rounded border border-amber-200">Prioritas</span>
                            @endif
                        </div>
                        <span class="text-[10px] text-slate-400">{{ $sesi->updated_at->format('H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[80%]">
                            {{ $sesi->pesanTerakhir->isi ?? 'Belum ada pesan' }}
                        </p>
                        @php
                            $unread = $sesi->pesan()->where('is_balasan_admin', false)->where('is_dibaca', false)->count();
                        @endphp
                        @if($unread > 0)
                            <span class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $unread }}</span>
                        @endif
                    </div>
                </button>
            @empty
                <div class="p-8 text-center text-slate-400 text-sm italic">Belum ada sesi obrolan aktif.</div>
            @endforelse
        </div>
    </div>

    <!-- Area Chat -->
    <div class="w-full md:w-2/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden relative">
        @if($sesiAktif)
            <!-- Header Chat -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($sesiAktif->pelanggan->name ?? 'T', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $sesiAktif->pelanggan->name ?? 'Tamu' }}
                        </h3>
                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            @if(isset($sesiAktif->pelanggan))
                                <span class="font-bold text-indigo-500">{{ $sesiAktif->pelanggan->loyalty_tier ?? 'Basic' }} Member</span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span> Pengunjung Situs
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="pilihSesi({{ $sesiAktif->id }})" class="p-2 text-slate-400 hover:text-indigo-500 transition-colors" title="Segarkan Chat">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </button>
                </div>
            </div>

            <!-- Pesan Chat -->
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-slate-50/50 dark:bg-slate-900/20 space-y-4" id="chat-messages">
                @foreach($daftarPesan as $msg)
                    <div class="flex w-full {{ $msg->is_balasan_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%]">
                            <div class="px-4 py-3 text-sm shadow-sm
                                {{ $msg->is_balasan_admin 
                                    ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-none' 
                                    : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-2xl rounded-tl-none border border-slate-200 dark:border-slate-600' }}">
                                {!! nl2br(e($msg->isi)) !!}
                            </div>
                            <div class="text-[10px] mt-1 px-1 text-slate-400 {{ $msg->is_balasan_admin ? 'text-right' : 'text-left' }}">
                                {{ $msg->created_at->format('H:i') }}
                                @if($msg->is_balasan_admin)
                                    <span class="ml-1">
                                        @if($msg->is_dibaca) 
                                            <span class="text-emerald-500">✓✓</span> 
                                        @else 
                                            ✓ 
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- AI Assistant Panel (NEW) -->
            @if($aiAnalisis)
                <div class="px-6 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border-t border-indigo-100 dark:border-indigo-800/30 flex items-start gap-4">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-8 h-8 rounded-lg bg-white/50 dark:bg-white/10 flex items-center justify-center animate-pulse">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Yala AI Insight</h4>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border 
                                {{ $aiAnalisis['sentimen'] == 'negatif' ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200' }}">
                                Sentimen: {{ ucfirst($aiAnalisis['sentimen']) }} ({{ $aiAnalisis['skor_kepuasan'] }}%)
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 italic mb-2">
                            "{{ $aiAnalisis['saran_balasan'] }}"
                        </p>
                        <button wire:click="gunakanSaranAi" class="text-[10px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                            Gunakan Saran Ini
                        </button>
                    </div>
                </div>
            @endif

            <!-- Input Balasan -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                <form wire:submit.prevent="kirimBalasan" class="flex gap-3 items-end">
                    
                    <!-- Tombol Respon Cepat -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="p-3 bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-indigo-600 rounded-xl transition-colors" title="Balasan Cepat">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute bottom-full left-0 mb-2 w-64 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-20">
                            <div class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-700 text-xs font-bold uppercase text-slate-500">Template Balasan</div>
                            <div class="max-h-60 overflow-y-auto">
                                @foreach($daftarResponCepat as $judul => $isi)
                                    <button type="button" 
                                            wire:click="gunakanResponCepat('{{ $judul }}'); open = false;" 
                                            class="w-full text-left px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 text-sm border-b border-slate-50 dark:border-slate-700/50 last:border-0 transition-colors">
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $judul }}</div>
                                        <div class="text-xs text-slate-500 truncate">{{ $isi }}</div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <textarea wire:model="isiPesan" rows="1" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none py-3" placeholder="Ketik pesan balasan..."></textarea>
                    </div>
                    
                    <button type="submit" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg transition-all transform active:scale-95 disabled:opacity-50" wire:loading.attr="disabled">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400">
                <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <p class="font-medium">Pilih sesi obrolan untuk memulai.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const wadahPesan = document.getElementById('chat-messages');
            
            const gulirKeBawah = () => {
                if(wadahPesan) {
                    setTimeout(() => {
                        wadahPesan.scrollTop = wadahPesan.scrollHeight;
                    }, 100);
                }
            };

            Livewire.on('gulir-ke-bawah', gulirKeBawah);
            
            // Auto scroll on load/update if chat active
            Livewire.hook('morph.updated', ({ component, el }) => {
                if (component.el.contains(wadahPesan)) {
                    gulirKeBawah();
                }
            });
        });
    </script>
</div>