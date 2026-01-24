<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <span class="text-cyan-500 font-bold tracking-widest uppercase text-sm mb-2 block">Tech Blog</span>
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Berita & <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Insight</span>
            </h1>
        </div>

        <!-- Featured Article -->
        @if($featured && empty($search))
            <div class="mb-16 animate-fade-in-up delay-100">
                <a href="{{ route('news.show', $featured->slug) }}" class="group relative block w-full h-[500px] rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700">
                    <div class="absolute inset-0 bg-slate-900">
                        @if($featured->image_path)
                            <img src="{{ asset('storage/' . $featured->image_path) }}" class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900"></div>
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/50 to-transparent"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full md:w-2/3">
                        <span class="inline-block px-3 py-1 bg-cyan-500 text-slate-900 text-xs font-bold uppercase rounded mb-4">Featured Story</span>
                        <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 group-hover:text-cyan-400 transition-colors">{{ $featured->title }}</h2>
                        <p class="text-slate-300 text-lg line-clamp-2 mb-6">{{ $featured->excerpt }}</p>
                        <div class="flex items-center gap-4 text-sm text-slate-400 font-mono">
                            <span>By {{ $featured->author->name }}</span>
                            <span>•</span>
                            <span>{{ $featured->published_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <!-- Search -->
        <div class="max-w-xl mx-auto mb-12">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-6 py-4 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-lg text-slate-800 dark:text-white focus:ring-2 focus:ring-cyan-500 transition-all pl-14" placeholder="Cari artikel...">
                <svg class="w-6 h-6 text-slate-400 absolute left-5 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>

        <!-- Article Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <a href="{{ route('news.show', $article->slug) }}" class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-xl hover:border-cyan-500/50 transition-all flex flex-col h-full animate-fade-in-up">
                    <div class="h-56 overflow-hidden relative">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-3 text-xs text-slate-500 uppercase font-bold tracking-wider">
                            <span class="text-cyan-600 dark:text-cyan-400">{{ $article->author->name }}</span>
                            <span>•</span>
                            <span>{{ $article->published_at->format('d M') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 line-clamp-2 group-hover:text-cyan-500 transition-colors">{{ $article->title }}</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm line-clamp-3 mb-6 flex-1">{{ $article->excerpt }}</p>
                        <div class="flex items-center text-sm font-bold text-slate-900 dark:text-white group-hover:text-cyan-500 transition-colors">
                            Baca Selengkapnya <svg class="w-4 h-4 ml-2 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-20 text-slate-500">
                    <p class="text-lg">Belum ada artikel yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>