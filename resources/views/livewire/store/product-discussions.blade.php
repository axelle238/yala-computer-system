<div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 dark:border-slate-700 mt-8">
    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
        Diskusi & Pertanyaan
    </h3>

    <!-- Ask Form -->
    <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 mb-8">
        @auth
            <form wire:submit.prevent="ask">
                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Punya pertanyaan tentang produk ini?</label>
                <textarea wire:model="message" rows="2" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 text-sm p-3" placeholder="Tulis pertanyaan Anda disini..."></textarea>
                @error('message') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                <div class="flex justify-end mt-3">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all text-sm">
                        Kirim Pertanyaan
                    </button>
                </div>
            </form>
        @else
            <p class="text-center text-slate-500 text-sm">Silakan <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login</a> untuk bertanya.</p>
        @endauth
    </div>

    <!-- Discussion List -->
    <div class="space-y-8">
        @forelse($discussions as $item)
            <div class="flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 uppercase">
                    {{ substr($item->user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $item->user->name }}</span>
                            <span class="text-xs text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 text-sm">{{ $item->message }}</p>
                        
                        <div class="flex gap-3 mt-3">
                            <button wire:click="setReplyTo({{ $item->id }})" class="text-xs font-bold text-blue-500 hover:underline">Balas</button>
                            @if(auth()->id() === $item->user_id || (auth()->user() && auth()->user()->isAdmin()))
                                <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus diskusi ini?" class="text-xs font-bold text-rose-500 hover:underline">Hapus</button>
                            @endif
                        </div>

                        <!-- Reply Form -->
                        @if($replyToId === $item->id)
                            <div class="mt-4 pl-4 border-l-2 border-slate-200 dark:border-slate-700 animate-fade-in">
                                <form wire:submit.prevent="reply({{ $item->id }})">
                                    <textarea wire:model="replyMessage" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm p-2" placeholder="Tulis balasan..."></textarea>
                                    <div class="flex justify-end gap-2 mt-2">
                                        <button type="button" wire:click="cancelReply" class="text-xs font-bold text-slate-500">Batal</button>
                                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-bold">Kirim</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Replies -->
                    @if($item->replies->count() > 0)
                        <div class="mt-4 space-y-4 pl-8 border-l-2 border-slate-100 dark:border-slate-700">
                            @foreach($item->replies as $reply)
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $reply->is_admin_reply ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold uppercase text-xs">
                                        {{ $reply->is_admin_reply ? 'A' : substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-3 border border-slate-100 dark:border-slate-700">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="font-bold text-xs {{ $reply->is_admin_reply ? 'text-blue-600' : 'text-slate-800 dark:text-slate-200' }}">
                                                    {{ $reply->is_admin_reply ? 'Admin Yala' : $reply->user->name }}
                                                    @if($reply->is_admin_reply) <span class="ml-1 bg-blue-600 text-white px-1.5 py-0.5 rounded text-[10px]">OFFICIAL</span> @endif
                                                </span>
                                                <span class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-600 dark:text-slate-400 text-xs">{{ $reply->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-400">
                <p>Belum ada diskusi. Jadilah yang pertama bertanya!</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $discussions->links() }}
    </div>
</div>
