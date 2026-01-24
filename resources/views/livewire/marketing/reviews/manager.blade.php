<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Review <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-500">Moderation</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kelola ulasan dan testimoni pelanggan.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex gap-2">
        <button wire:click="$set('filter', 'pending')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $filter === 'pending' ? 'bg-white dark:bg-slate-700 shadow text-slate-800 dark:text-white' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            Pending / Hidden
        </button>
        <button wire:click="$set('filter', 'approved')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $filter === 'approved' ? 'bg-white dark:bg-slate-700 shadow text-emerald-600 dark:text-emerald-400' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            Approved
        </button>
    </div>

    <!-- Review List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($reviews as $review)
                <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-900/30 transition-colors">
                    <div class="flex justify-between items-start gap-4">
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-slate-800 dark:text-white">{{ $review->reviewer_name }}</span>
                                <span class="text-xs text-slate-400">• {{ $review->created_at->diffForHumans() }}</span>
                                <div class="flex text-amber-400">
                                    @for($i=0; $i<$review->rating; $i++)
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            
                            <p class="text-sm text-slate-600 dark:text-slate-300 mb-2 italic">"{{ $review->comment }}"</p>
                            
                            <!-- Product Badge -->
                            <a href="{{ route('product.detail', $review->product_id) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded hover:underline">
                                {{ $review->product->name }}
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>

                            <!-- Images -->
                            @if($review->images)
                                <div class="flex gap-2 mt-3">
                                    @foreach($review->images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200 dark:border-slate-600">
                                    @endforeach
                                </div>
                            @endif

                            <!-- Reply Section -->
                            @if($review->reply)
                                <div class="mt-4 pl-4 border-l-2 border-indigo-500">
                                    <p class="text-xs font-bold text-indigo-600 mb-1">Balasan Admin</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $review->reply }}</p>
                                </div>
                            @endif

                            @if($replyingTo === $review->id)
                                <div class="mt-4 space-y-2 animate-fade-in">
                                    <textarea wire:model="replyMessage" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm focus:ring-indigo-500" placeholder="Tulis balasan..."></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="cancelReply" class="px-3 py-1.5 text-xs font-bold text-slate-500 hover:text-slate-700">Batal</button>
                                        <button wire:click="saveReply" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold">Kirim Balasan</button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2">
                            @if(!$review->is_approved)
                                <button wire:click="approve({{ $review->id }})" class="p-2 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200" title="Setujui">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </button>
                            @else
                                <button wire:click="reject({{ $review->id }})" class="p-2 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200" title="Sembunyikan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                </button>
                            @endif
                            
                            <button wire:click="startReply({{ $review->id }})" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200" title="Balas">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                            </button>

                            <button wire:click="delete({{ $review->id }})" wire:confirm="Hapus ulasan ini permanen?" class="p-2 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-200" title="Hapus">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <p>Tidak ada ulasan dalam kategori ini.</p>
                </div>
            @endforelse
        </div>
        
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $reviews->links() }}
        </div>
    </div>
</div>