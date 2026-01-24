<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Reputation <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Moderasi ulasan dan feedback pelanggan.</p>
        </div>
        
        <div class="flex gap-3 items-center">
            <select wire:model.live="filterRating" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm font-bold focus:ring-amber-500">
                <option value="all">Semua Rating</option>
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
            <input wire:model.live.debounce.300ms="search" type="text" class="w-64 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm" placeholder="Cari ulasan...">
        </div>
    </div>

    <!-- Review List -->
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row gap-6 relative overflow-hidden group {{ $review->is_hidden ? 'opacity-50' : '' }}">
                
                @if($review->is_hidden)
                    <div class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-bl-xl uppercase tracking-wider">Hidden</div>
                @endif

                <!-- Product Image -->
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-xl flex-shrink-0 overflow-hidden">
                    @if($review->product->image_path)
                        <img src="{{ asset('storage/' . $review->product->image_path) }}" class="w-full h-full object-cover">
                    @endif
                </div>

                <div class="flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1">{{ $review->product->name }}</h4>
                            <div class="flex items-center gap-2">
                                <div class="flex text-amber-400">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-300 dark:text-slate-600' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-slate-500 font-bold">Oleh {{ $review->user->name }}</span>
                                <span class="text-xs text-slate-400">• {{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="openReply({{ $review->id }})" class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50">Balas</button>
                            <button wire:click="toggleVisibility({{ $review->id }})" class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200">
                                {{ $review->is_hidden ? 'Tampilkan' : 'Sembunyikan' }}
                            </button>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 dark:text-slate-300 italic mb-4">"{{ $review->comment }}"</p>

                    @if($review->admin_reply)
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800 ml-4 relative">
                            <div class="absolute top-3 left-0 w-1 h-8 bg-indigo-500 rounded-r"></div>
                            <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-1">Respon Toko</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400">{{ $review->admin_reply }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <p>Belum ada ulasan yang sesuai filter.</p>
            </div>
        @endforelse
    </div>

    {{ $reviews->links() }}

    <!-- Reply Modal -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Balas Ulasan</h3>
                <textarea wire:model="replyContent" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-amber-500 mb-4" placeholder="Tulis ucapan terima kasih atau solusi..."></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-slate-500 font-bold text-sm hover:text-slate-800">Batal</button>
                    <button wire:click="saveReply" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all text-sm">Kirim Balasan</button>
                </div>
            </div>
        </div>
    @endif
</div>
