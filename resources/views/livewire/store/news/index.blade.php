<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-6xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Yala <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Newsroom</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Update terbaru seputar teknologi, hardware, dan tips & trik.</p>
        </div>

        <!-- Categories -->
        <div class="flex flex-wrap justify-center gap-3 mb-12 animate-fade-in-up delay-100">
            <button wire:click="filterCategory('')" class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ $category == '' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                Semua
            </button>
            @foreach($categories as $cat)
                <button wire:click="filterCategory('{{ $cat }}')" class="px-6 py-2 rounded-full text-sm font-bold transition-all {{ $category == $cat ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        <!-- Featured (First Article) -->
        @if($articles->currentPage() == 1 && $articles->isNotEmpty())
            @php $featured = $articles->first(); @endphp
            <div class="mb-12 animate-fade-in-up delay-200">
                <a href="{{ route('news.show', $featured->slug) }}" class="group relative block h-[400px] md:h-[500px] rounded-3xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('storage/' . $featured->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full md:w-2/3">
                        <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-bold uppercase rounded mb-4">{{ $featured->category }}</span>
                        <h2 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight group-hover:text-purple-400 transition-colors">{{ $featured->title }}</h2>
                        <p class="text-slate-300 text-lg line-clamp-2 mb-6">{{ $featured->excerpt }}</p>
                        <span class="text-slate-400 text-sm font-mono">Baca Selengkapnya &rarr;</span>
                    </div>
                </a>
            </div>
        @endif

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up delay-300">
            @foreach($articles as $index => $article)
                @if($articles->currentPage() == 1 && $index == 0) @continue @endif
                
                <a href="{{ route('news.show', $article->slug) }}" class="group bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-700 hover:border-purple-500/50 transition-all flex flex-col h-full hover:-translate-y-1">
                    <div class="h-56 overflow-hidden relative">
                        <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur text-white px-3 py-1 text-xs font-bold rounded uppercase">
                            {{ $article->category }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs text-slate-400 mb-2 font-mono">{{ $article->created_at->format('d M Y') }}</div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 line-clamp-2 group-hover:text-purple-500 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-3 mb-4 flex-1">
                            {{ $article->excerpt }}
                        </p>
                        <span class="text-purple-600 font-bold text-sm mt-auto group-hover:underline">Baca Artikel</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>