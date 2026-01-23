<div class="min-h-screen pt-32 pb-24 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 cyber-grid opacity-30"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12">
            <h1 class="text-5xl font-black font-tech text-white mb-4 uppercase tracking-tight drop-shadow-lg">
                News <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">&</span> Insights
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Update teknologi terbaru, review hardware, dan promo eksklusif.</p>
        </div>

        <!-- Category Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-16">
            <button wire:click="filterCategory('')" 
                    class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider border transition-all duration-300 {{ $category === '' ? 'bg-cyan-500 text-slate-900 border-cyan-500 shadow-lg shadow-cyan-500/30' : 'bg-transparent text-slate-400 border-white/10 hover:border-white/30 hover:text-white' }}">
                All Stories
            </button>
            @foreach($categories as $cat)
                <button wire:click="filterCategory('{{ $cat }}')"
                        class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider border transition-all duration-300 {{ $category === $cat ? 'bg-cyan-500 text-slate-900 border-cyan-500 shadow-lg shadow-cyan-500/30' : 'bg-transparent text-slate-400 border-white/10 hover:border-white/30 hover:text-white' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        @if($articles->isEmpty())
            <div class="col-span-full text-center py-20">
                <div class="inline-flex p-4 bg-slate-800 rounded-full mb-4">
                    <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                <p class="text-slate-500 italic">Belum ada berita dalam kategori ini.</p>
                @if($category)
                    <button wire:click="filterCategory('')" class="mt-4 text-cyan-500 hover:text-cyan-400 font-bold text-sm">Lihat Semua Berita</button>
                @endif
            </div>
        @else
            <!-- Featured Article (First One on Page 1) -->
            @if($articles->onFirstPage() && !$category)
                @php $featured = $articles->shift(); @endphp
                <div class="mb-16">
                    <a href="{{ route('news.show', $featured->slug) }}" class="group relative block bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-white/10 hover:border-cyan-500/50 transition-all duration-500">
                        <div class="grid md:grid-cols-2 gap-0 h-full">
                            <div class="relative h-64 md:h-auto overflow-hidden">
                                @if($featured->image_path)
                                    <img src="{{ asset('storage/' . $featured->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                                        <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60 md:hidden"></div>
                            </div>
                            <div class="p-8 md:p-12 flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-cyan-500 text-slate-900 text-xs font-bold uppercase tracking-widest rounded-full">Featured</span>
                                    <span class="text-slate-400 text-xs font-mono">{{ $featured->created_at->format('d M Y') }}</span>
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight group-hover:text-cyan-400 transition-colors">{{ $featured->title }}</h2>
                                <p class="text-slate-400 text-lg mb-6 line-clamp-3">{{ $featured->excerpt }}</p>
                                <span class="text-cyan-500 font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                                    Baca Artikel <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                    <a href="{{ route('news.show', $article->slug) }}" class="group block bg-slate-900 border border-white/10 rounded-3xl overflow-hidden hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.15)] transition-all duration-300 flex flex-col h-full tech-card">
                        <div class="h-48 overflow-hidden relative flex-shrink-0">
                            @if($article->image_path)
                                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-2 py-1 bg-slate-900/80 backdrop-blur text-white text-[10px] font-bold uppercase tracking-widest rounded border border-white/10">{{ $article->category }}</span>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-3 text-xs font-mono text-slate-500">
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                                <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                                <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-cyan-400 transition-colors">{{ $article->title }}</h3>
                            <p class="text-slate-400 text-sm line-clamp-3 leading-relaxed mb-4 flex-1">{{ $article->excerpt }}</p>
                            <div class="mt-auto pt-4 border-t border-white/5 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-[10px] font-bold text-white">
                                        {{ substr($article->author_name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-xs text-slate-400 font-bold">{{ $article->author_name ?? 'Admin' }}</span>
                                </div>
                                <span class="text-cyan-500 text-xs font-bold uppercase tracking-widest group-hover:underline">Read</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
