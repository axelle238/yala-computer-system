<div class="min-h-screen pt-32 pb-24 relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 cyber-grid opacity-30"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Article Header -->
        <div class="mb-12 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <span class="px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold tracking-widest uppercase">{{ $article->category }}</span>
                <span class="text-slate-500 text-xs font-mono">•</span>
                <span class="text-slate-400 text-xs font-mono">{{ $article->created_at->format('d F Y') }}</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black font-tech text-white leading-tight mb-8 tracking-wide drop-shadow-xl">{{ $article->title }}</h1>
            
            <div class="flex items-center justify-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-cyan-500/20">
                        {{ substr($article->author_name ?? 'A', 0, 1) }}
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-white leading-none">{{ $article->author_name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mt-1">Author</p>
                    </div>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div class="text-left">
                    <p class="text-sm font-bold text-white leading-none">{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} Min</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mt-1">Read Time</p>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        @if($article->image_path)
            <div class="rounded-3xl overflow-hidden mb-16 border border-white/10 shadow-2xl shadow-black/50 relative group">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 to-transparent pointer-events-none"></div>
                <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full object-cover max-h-[600px] transition-transform duration-1000 group-hover:scale-105">
            </div>
        @endif

        <!-- Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Main Text -->
            <article class="lg:col-span-8 prose prose-invert prose-lg max-w-none text-slate-300 leading-loose">
                {!! nl2br(e($article->content)) !!}
                
                @if($article->tags)
                    <div class="mt-12 pt-8 border-t border-white/10 flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="text-xs font-mono text-cyan-500 bg-cyan-900/20 px-2 py-1 rounded border border-cyan-900/50">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </article>

            <!-- Sidebar (Share & Related) -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Share -->
                <div class="bg-slate-900 border border-white/10 rounded-2xl p-6 sticky top-32">
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Share Article</h3>
                    <div class="flex gap-2">
                        <button class="flex-1 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.962.925-1.962 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </button>
                        <button class="flex-1 py-2 bg-sky-500 hover:bg-sky-400 text-white rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </button>
                        <button class="flex-1 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Related -->
                @if($related->isNotEmpty())
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4">Related News</h3>
                        <div class="space-y-4">
                            @foreach($related as $item)
                                <a href="{{ route('news.show', $item->slug) }}" class="flex gap-4 group">
                                    <div class="w-20 h-20 rounded-xl bg-slate-800 overflow-hidden flex-shrink-0 border border-white/10">
                                        @if($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-600">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-white line-clamp-2 leading-snug group-hover:text-cyan-400 transition-colors">{{ $item->title }}</h4>
                                        <p class="text-[10px] text-slate-500 mt-1">{{ $item->created_at->format('d M Y') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>