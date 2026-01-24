<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Latest <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">News</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Update terbaru seputar teknologi, tutorial rakit PC, dan promo menarik.</p>
        </div>

        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-12 animate-fade-in-up delay-100">
            <button wire:click="filterCategory('')" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $category === '' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-500/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:border-cyan-500' }}">
                Semua
            </button>
            @foreach($categories as $cat)
                <button wire:click="filterCategory('{{ $cat }}')" class="px-5 py-2 rounded-full text-sm font-bold transition-all {{ $category === $cat ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-500/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:border-cyan-500' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        <!-- Articles Grid -->
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up delay-200">
                @foreach($articles as $article)
                    <article class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-200 dark:border-slate-700 group flex flex-col h-full">
                        <a href="{{ route('news.show', $article->slug) }}" class="block relative aspect-video overflow-hidden">
                            @if($article->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4 bg-slate-900/80 backdrop-blur text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-white/10">
                                {{ $article->category }}
                            </div>
                        </a>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                <span>•</span>
                                <span>{{ $article->author->name ?? 'Admin' }}</span>
                            </div>

                            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-3 leading-tight group-hover:text-cyan-500 transition-colors">
                                <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                            </h2>
                            
                            <p class="text-slate-600 dark:text-slate-400 text-sm line-clamp-3 mb-6 flex-1">
                                {{ Str::limit(strip_tags($article->content), 120) }}
                            </p>

                            <a href="{{ route('news.show', $article->slug) }}" class="inline-flex items-center gap-2 text-cyan-600 dark:text-cyan-400 font-bold text-sm uppercase tracking-wider group/link mt-auto">
                                Baca Selengkapnya 
                                <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Belum ada artikel</h3>
                <p class="text-slate-500 text-sm mt-1">Coba kategori lain atau kembali lagi nanti.</p>
            </div>
        @endif
    </div>
</div>
