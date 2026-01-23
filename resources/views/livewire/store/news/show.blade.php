<div class="min-h-screen pt-32 pb-24 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 cyber-grid opacity-30"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header -->
        <div class="mb-8 text-center">
            <span class="inline-block px-3 py-1 rounded bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold tracking-widest uppercase mb-6">Berita Terbaru</span>
            <h1 class="text-3xl md:text-5xl font-black font-tech text-white leading-tight mb-6">{{ $article->title }}</h1>
            <div class="flex items-center justify-center gap-4 text-sm text-slate-400 font-mono">
                <span>{{ $article->created_at->format('d F Y') }}</span>
                <span>•</span>
                <span>Yala Computer Team</span>
            </div>
        </div>

        <!-- Featured Image -->
        @if($article->image_path)
            <div class="rounded-3xl overflow-hidden mb-12 border border-white/10 shadow-2xl">
                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full object-cover max-h-[500px]">
            </div>
        @endif

        <!-- Content -->
        <article class="prose prose-invert prose-lg max-w-none text-slate-300 leading-relaxed mb-16">
            {!! nl2br(e($article->content)) !!}
        </article>

        <!-- Share & Back -->
        <div class="border-t border-white/10 pt-8 flex justify-between items-center">
            <a href="{{ route('news.index') }}" class="text-cyan-500 hover:text-white font-bold flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Berita
            </a>
        </div>

        <!-- Related News -->
        @if($related->isNotEmpty())
            <div class="mt-16 pt-16 border-t border-white/10">
                <h3 class="text-2xl font-bold text-white mb-8">Baca Juga</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="group block bg-slate-900 rounded-xl overflow-hidden border border-white/5 hover:border-cyan-500/30 transition-all">
                            <div class="h-32 bg-slate-800 relative overflow-hidden">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-white text-sm line-clamp-2 group-hover:text-cyan-400 transition-colors">{{ $item->title }}</h4>
                                <span class="text-xs text-slate-500 mt-2 block">{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
