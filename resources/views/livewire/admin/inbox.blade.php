<div class="h-[calc(100vh-7rem)] flex flex-col md:flex-row gap-6 animate-fade-in-up p-6">
    
    <!-- Daftar Pesan -->
    <div class="w-full md:w-1/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
            <h2 class="font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight text-xl">Kotak Masuk</h2>
            <select wire:model.live="filter" class="text-[10px] uppercase font-bold rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 dark:text-white">
                <option value="semua">Semua</option>
                <option value="baru">Baru</option>
                <option value="dibaca">Dibaca</option>
                <option value="dibalas">Dibalas</option>
            </select>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($daftarPesan as $msg)
                <button wire:click="pilihPesan({{ $msg->id }})" 
                        class="w-full text-left p-4 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ $pesanTerpilih && $pesanTerpilih->id == $msg->id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-l-4 border-l-indigo-500' : '' }}">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-bold text-slate-800 dark:text-white text-sm truncate">{{ $msg->nama }}</span>
                        <span class="text-[10px] text-slate-400">{{ $msg->created_at->diffForHumans(null, true, true) }}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        @if($msg->status === 'baru')
                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></span>
                        @elseif($msg->status === 'dibalas')
                            <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5"/></svg>
                        @endif
                        <div class="text-xs font-medium text-slate-600 dark:text-slate-300 truncate">{{ $msg->subjek }}</div>
                    </div>
                    <p class="text-xs text-slate-400 line-clamp-2">{{ $msg->isi_pesan }}</p>
                </button>
            @empty
                <div class="p-8 text-center text-slate-400 text-sm italic">Tidak ada pesan masuk.</div>
            @endforelse
        </div>
        
        <div class="p-2 border-t border-slate-200 dark:border-slate-700">
            {{ $daftarPesan->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <!-- Detail Pesan -->
    <div class="w-full md:w-2/3 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col overflow-hidden relative">
        @if($pesanTerpilih)
            <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-start bg-slate-50 dark:bg-slate-900/50">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $pesanTerpilih->subjek }}</h3>
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $pesanTerpilih->nama }}</span>
                        <span>&lt;{{ $pesanTerpilih->surel }}&gt;</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider 
                        {{ $pesanTerpilih->status === 'baru' ? 'bg-indigo-100 text-indigo-600' : 
                           ($pesanTerpilih->status === 'dibalas' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600') }}">
                        {{ $pesanTerpilih->status }}
                    </span>
                    <button wire:click="hapus({{ $pesanTerpilih->id }})" wire:confirm="Yakin ingin menghapus pesan ini?" class="text-slate-400 hover:text-rose-500 p-2 transition-colors" title="Hapus">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-white dark:bg-slate-800">
                <div class="prose prose-sm dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed">
                    {!! nl2br(e($pesanTerpilih->isi_pesan)) !!}
                </div>
            </div>

            <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                <textarea wire:model="isiBalasan" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-indigo-500 text-sm mb-3" placeholder="Tulis balasan Anda ke pelanggan..."></textarea>
                @error('isiBalasan') <span class="text-rose-500 text-xs block mb-2">{{ $message }}</span> @enderror
                <div class="flex justify-end">
                    <button wire:click="kirimBalasan" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim Balasan via Email
                    </button>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <p class="text-lg font-medium tracking-tight">Pilih pesan di samping untuk membaca</p>
                <p class="text-sm opacity-60">Kelola komunikasi dengan pelanggan Anda di sini.</p>
            </div>
        @endif
    </div>
</div>