<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter mb-2">
                Tech <span class="text-purple-600">News</span> & Updates
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Informasi terbaru seputar teknologi, tutorial rakit PC, dan promo menarik.</p>
        </div>

        <!-- Featured -->
        @if($featured && !$category && $articles->currentPage() == 1)
            <div class="mb-12 animate-fade-in-up delay-100">
                <a href="{{ route('toko.berita.tampil', $featured->slug) }}" class="group relative block h-[400px] md:h-[500px] rounded-3xl overflow-hidden shadow-2xl">
                    @if($featured->image_path)
                        <img src="{{ asset('storage/' . $featured->image_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-slate-800"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent p-8 md:p-12 flex flex-col justify-end">
                        <span class="inline-block px-3 py-1 bg-purple-600 text-white text-xs font-bold uppercase tracking-widest rounded mb-4 w-fit">
                            {{ $featured->category }}
                        </span>
                        <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 group-hover:text-purple-400 transition-colors">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-slate-300 text-lg line-clamp-2 max-w-2xl">
                            {{ Str::limit(strip_tags($featured->content), 150) }}
                        </p>
                    </div>
                </a>
            </div>
        @endif

        <!-- Filter -->
        <div class="flex justify-center gap-4 mb-12 overflow-x-auto pb-4 scrollbar-hide">
            <button wire:click="filter('')" class="px-6 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap {{ $category == '' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-100' }}">
                Semua
            </button>
            <button wire:click="filter('news')" class="px-6 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap {{ $category == 'news' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-purple-50 hover:text-purple-600' }}">
                Berita IT
            </button>
            <button wire:click="filter('tutorial')" class="px-6 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap {{ $category == 'tutorial' ? 'bg-cyan-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-cyan-50 hover:text-cyan-600' }}">
                Tutorial
            </button>
            <button wire:click="filter('review')" class="px-6 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap {{ $category == 'review' ? 'bg-amber-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-amber-50 hover:text-amber-600' }}">
                Review Produk
            </button>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles as $article)
                @if($featured && $article->id === $featured->id && !$category && $articles->currentPage() == 1) @continue @endif
                
                <a href="{{ route('toko.berita.tampil', $article->slug) }}" class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                    <div class="h-48 overflow-hidden relative">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-1 rounded text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white shadow-sm">
                            {{ $article->category }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs text-slate-400 mb-2 flex items-center gap-2">
                            <span>{{ $article->created_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>{{ $article->user->name ?? 'Admin' }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 leading-tight group-hover:text-purple-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-3 mb-4 flex-1">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        <span class="text-sm font-bold text-purple-600 dark:text-purple-400 flex items-center gap-1 group-hover:gap-2 transition-all">
                            Baca Selengkapnya <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center text-slate-400">
                    <p>Belum ada artikel dalam kategori ini.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>
