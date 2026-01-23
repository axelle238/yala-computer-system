<div class="min-h-screen pt-32 pb-24 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 cyber-grid opacity-30"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black font-tech text-white mb-4 uppercase tracking-tight">
                News <span class="text-cyan-500">&</span> Updates
            </h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Informasi terbaru seputar teknologi, promo, dan tips merakit PC terbaik.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <a href="{{ route('news.show', $article->slug) }}" class="group block bg-slate-900 border border-white/10 rounded-3xl overflow-hidden hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.2)] transition-all duration-300 tech-card">
                    <div class="h-48 overflow-hidden relative">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-80"></div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3 text-xs font-mono text-cyan-400">
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span class="w-1 h-1 bg-slate-500 rounded-full"></span>
                            <span>Berita</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-cyan-400 transition-colors">{{ $article->title }}</h3>
                        <p class="text-slate-400 text-sm line-clamp-3 leading-relaxed">{{ $article->excerpt }}</p>
                        <div class="mt-4 flex items-center text-cyan-500 font-bold text-sm uppercase tracking-wider group-hover:underline">
                            Baca Selengkapnya
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-slate-500 italic">Belum ada berita terbaru.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>
