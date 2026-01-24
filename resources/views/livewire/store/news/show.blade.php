<div class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans pb-20">
    
    <!-- Hero Image -->
    <div class="relative h-[400px] md:h-[500px] w-full overflow-hidden">
        <div class="absolute inset-0 bg-slate-900">
            @if($article->image_path)
                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover opacity-60">
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-6 md:p-16">
            <div class="container mx-auto max-w-4xl">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-slate-300 hover:text-white mb-6 transition-colors font-bold text-sm uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Berita
                </a>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-black font-tech text-white leading-tight mb-6 shadow-black drop-shadow-xl">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center gap-6 text-slate-300 font-mono text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-cyan-600 flex items-center justify-center font-bold text-white uppercase text-xs">
                            {{ substr($article->author->name, 0, 1) }}
                        </div>
                        <span>{{ $article->author->name }}</span>
                    </div>
                    <span>•</span>
                    <span>{{ $article->published_at->format('d F Y') }}</span>
                    <span>•</span>
                    <span>{{ $article->views_count }} Views</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 lg:px-8 -mt-10 relative z-10">
        <div class="max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-2xl border border-slate-200 dark:border-slate-700">
            <article class="prose prose-lg dark:prose-invert prose-headings:font-tech prose-headings:font-bold prose-a:text-cyan-600 prose-img:rounded-2xl max-w-none">
                {!! nl2br(e($article->content)) !!}
            </article>

            <!-- Tags -->
            @if($article->tags)
                <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <h4 class="text-sm font-bold uppercase text-slate-500 mb-4">Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $article->tags) as $tag)
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-medium">#{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
