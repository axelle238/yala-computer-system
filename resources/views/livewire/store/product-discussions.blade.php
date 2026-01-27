<div class="space-y-8">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Diskusi & Tanya Jawab</h3>
        <span class="text-xs font-bold bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full text-slate-500">{{ $diskusi->total() }} Komentar</span>
    </div>

    @auth
        <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <form wire:submit.prevent="kirimPesan">
                        @if($idBalasan)
                            <div class="flex items-center justify-between mb-2 text-xs text-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1 rounded-lg">
                                <span>Membalas komentar...</span>
                                <button type="button" wire:click="batalBalas" class="hover:underline font-bold">Batal</button>
                            </div>
                        @endif
                        <textarea wire:model="pesanBaru" rows="2" class="w-full bg-white dark:bg-slate-900 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 placeholder-slate-400 p-3" placeholder="Tanyakan sesuatu tentang produk ini..."></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-indigo-500/30">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-6 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
            <p class="text-sm text-slate-500 mb-3">Ingin bertanya atau berdiskusi?</p>
            <a href="{{ route('pelanggan.masuk') }}" class="inline-block px-6 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                Masuk untuk Menulis
            </a>
        </div>
    @endauth

    <div class="space-y-6">
        @forelse($diskusi as $d)
            <div class="animate-fade-in-up">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 font-bold text-sm shrink-0">
                        {{ substr($d->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="bg-white dark:bg-slate-800/50 p-4 rounded-2xl rounded-tl-none border border-slate-100 dark:border-slate-700/50">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $d->user->name }}</span>
                                <span class="text-[10px] text-slate-400">{{ $d->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $d->message }}</p>
                            
                            <div class="mt-3 flex gap-4">
                                <button wire:click="setBalas({{ $d->id }})" class="text-xs font-bold text-indigo-500 hover:underline">Balas</button>
                            </div>
                        </div>

                        <!-- Replies -->
                        @if($d->replies->count() > 0)
                            <div class="mt-4 space-y-4 pl-4 border-l-2 border-slate-100 dark:border-slate-800 ml-4">
                                @foreach($d->replies as $reply)
                                    <div class="flex gap-3">
                                        <div class="w-8 h-8 rounded-full {{ $reply->is_admin_reply ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($reply->is_admin_reply ? 'Admin' : $reply->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="bg-slate-50 dark:bg-slate-800 p-3 rounded-xl rounded-tl-none {{ $reply->is_admin_reply ? 'border border-indigo-100 dark:border-indigo-900/30' : '' }}">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-bold text-sm {{ $reply->is_admin_reply ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-900 dark:text-white' }}">
                                                        {{ $reply->is_admin_reply ? 'Admin Yala' : $reply->user->name }}
                                                        @if($reply->is_admin_reply) 
                                                            <svg class="w-3 h-3 inline ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                        @endif
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $reply->message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10 opacity-50">
                <p class="text-sm text-slate-400">Belum ada diskusi untuk produk ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $diskusi->links() }}
    </div>
</div>