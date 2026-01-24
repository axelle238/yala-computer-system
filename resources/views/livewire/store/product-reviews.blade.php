<div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 dark:border-slate-700 mt-8">
    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-2">
        <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
        Ulasan Pelanggan
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left: Summary Stats -->
        <div class="lg:col-span-1 space-y-6">
            <div class="text-center bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="text-5xl font-black text-slate-900 dark:text-white mb-2">{{ number_format($stats['avg'], 1) }}</div>
                <div class="flex justify-center gap-1 mb-2">
                    @for($i=1; $i<=5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($stats['avg']) ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @endfor
                </div>
                <div class="text-sm text-slate-500 font-bold">{{ $stats['count'] }} Ulasan</div>
            </div>

            <!-- Distribution Bars -->
            <div class="space-y-2">
                @foreach(range(5, 1) as $star)
                    @php
                        $count = $stats[$star . '_star'];
                        $percent = $stats['count'] > 0 ? ($count / $stats['count']) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-sm">
                        <span class="font-bold w-3 text-slate-600 dark:text-slate-400">{{ $star }}</span>
                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="w-8 text-right text-slate-500">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Reviews List & Form -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Review Form -->
            @if($canReview)
                <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-2xl border border-blue-100 dark:border-blue-800">
                    <h4 class="font-bold text-lg text-blue-900 dark:text-blue-100 mb-4">Tulis Ulasan Anda</h4>
                    <form wire:submit.prevent="submitReview" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Rating</label>
                            <div class="flex gap-2">
                                @for($i=1; $i<=5; $i++)
                                    <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-8 h-8 {{ $rating >= $i ? 'text-amber-500' : 'text-slate-300 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </button>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Komentar</label>
                            <textarea wire:model="comment" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 text-sm p-3" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..."></textarea>
                            @error('comment') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all">
                                Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($hasReviewed)
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 rounded-xl text-center border border-emerald-100 dark:border-emerald-800 font-medium">
                    Anda sudah memberikan ulasan untuk produk ini. Terima kasih!
                </div>
            @elseif(!auth()->check())
                <div class="p-4 bg-slate-50 dark:bg-slate-800 text-slate-500 rounded-xl text-center border border-slate-200 dark:border-slate-700">
                    Silakan <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login</a> untuk memberikan ulasan.
                </div>
            @endif

            <!-- List Reviews -->
            <div class="space-y-6">
                @forelse($reviews as $review)
                    <div class="border-b border-slate-100 dark:border-slate-700 pb-6 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h5 class="font-bold text-slate-900 dark:text-white">{{ $review->reviewer_name }}</h5>
                                <div class="flex gap-1 mt-1">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-amber-500' : 'text-slate-200 dark:text-slate-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                            {{ $review->comment }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        <p>Belum ada ulasan. Jadilah yang pertama!</p>
                    </div>
                @endforelse
            </div>
            
            {{ $reviews->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
