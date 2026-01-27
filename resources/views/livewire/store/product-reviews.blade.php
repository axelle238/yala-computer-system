<div class="mt-12" id="reviews">
    <h3 class="font-bold text-xl text-white mb-6 flex items-center gap-3">
        <span class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-500 flex items-center justify-center">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        </span>
        Ulasan Pembeli
    </h3>

    @if($canReview)
        <div class="bg-slate-800/50 rounded-2xl p-6 border border-white/5 mb-8">
            <h4 class="font-bold text-white mb-4">Tulis Ulasan Anda</h4>
            <form wire:submit.prevent="submitReview" class="space-y-4">
                <div class="flex gap-2 mb-2">
                    @for($i=1; $i<=5; $i++)
                        <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none">
                            <svg class="w-8 h-8 {{ $rating >= $i ? 'text-amber-400' : 'text-slate-600' }} hover:text-amber-300 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                    @endfor
                </div>
                <textarea wire:model="comment" rows="3" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-cyan-500 focus:border-cyan-500" placeholder="Bagaimana kualitas produk ini?"></textarea>
                @error('comment') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl transition-all">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    @endif

    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-slate-400">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-white text-sm">{{ $review->user->name }}</div>
                            <div class="flex text-amber-400 text-xs">
                                @for($i=0; $i<$review->rating; $i++) ★ @endfor
                            </div>
                        </div>
                    </div>
                    <span class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-slate-300 text-sm leading-relaxed pl-13">{{ $review->comment }}</p>
            </div>
        @empty
            <div class="text-center py-12 text-slate-500 bg-slate-900/50 rounded-2xl border border-dashed border-slate-800">
                Belum ada ulasan untuk produk ini.
            </div>
        @endforelse
        
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
