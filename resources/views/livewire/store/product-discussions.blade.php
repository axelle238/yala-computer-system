<div class="mt-16 pt-16 border-t border-white/5" id="discussions">
    <h3 class="font-bold text-xl text-white mb-6 flex items-center gap-3">
        <span class="w-8 h-8 rounded-full bg-cyan-500/20 text-cyan-500 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
        </span>
        Diskusi Produk
    </h3>

    <!-- Input Form -->
    <div class="mb-8">
        @auth
            <form wire:submit.prevent="sendMessage" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" wire:model="message" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-cyan-500 focus:border-cyan-500" placeholder="Ada pertanyaan tentang produk ini?">
                    @error('message') <span class="text-rose-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition-colors border border-slate-700">
                    Kirim
                </button>
            </form>
        @else
            <div class="bg-slate-900/50 p-4 rounded-xl text-center border border-white/5">
                <p class="text-slate-400 text-sm">Silakan <a href="{{ route('pelanggan.masuk') }}" class="text-cyan-400 hover:underline font-bold">Login</a> untuk bertanya.</p>
            </div>
        @endauth
    </div>

    <!-- List -->
    <div class="space-y-6">
        @forelse($discussions as $chat)
            <div class="flex gap-4 group">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-slate-400 flex-shrink-0 border border-slate-700">
                    {{ substr($chat->user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-white text-sm">{{ $chat->user->name }}</span>
                        <span class="text-xs text-slate-500">{{ $chat->created_at->diffForHumans() }}</span>
                        @if($chat->user->role == 'admin') 
                            <span class="bg-cyan-500/20 text-cyan-400 text-[10px] px-1.5 rounded font-bold uppercase">Admin</span> 
                        @endif
                    </div>
                    <p class="text-slate-300 text-sm mb-2">{{ $chat->message }}</p>
                    
                    <!-- Replies -->
                    @foreach($chat->replies as $reply)
                        <div class="flex gap-3 mt-3 pl-4 border-l-2 border-slate-800">
                            <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-400 flex-shrink-0">
                                {{ substr($reply->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="font-bold text-slate-200 text-xs">{{ $reply->user->name }}</span>
                                    <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                    @if($reply->user->role == 'admin') 
                                        <span class="bg-cyan-500/20 text-cyan-400 text-[10px] px-1.5 rounded font-bold uppercase">Admin</span> 
                                    @endif
                                </div>
                                <p class="text-slate-400 text-xs">{{ $reply->message }}</p>
                            </div>
                        </div>
                    @endforeach

                    @auth
                        <button wire:click="setReply({{ $chat->id }})" class="text-xs text-slate-500 hover:text-cyan-400 font-bold mt-2">Balas</button>
                        @if($replyToId === $chat->id)
                            <form wire:submit.prevent="sendMessage" class="mt-2 flex gap-2">
                                <input type="text" wire:model="message" class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-xs text-white" placeholder="Tulis balasan...">
                                <button type="button" wire:click="cancelReply" class="px-3 py-2 text-xs text-slate-400">Batal</button>
                                <button type="submit" class="px-3 py-2 bg-cyan-600 text-white rounded-lg text-xs font-bold">Kirim</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <p class="text-slate-500 text-sm text-center">Belum ada diskusi.</p>
        @endforelse
        
        <div class="mt-4">
            {{ $discussions->links() }}
        </div>
    </div>
</div>
