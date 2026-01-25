<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pt-24 pb-12">
    <!-- Progress Bar -->
    <div class="fixed top-0 left-0 w-full h-1 z-50 bg-slate-200 dark:bg-slate-800">
        <div class="h-full bg-purple-600" style="width: 0%" id="scrollProgress"></div>
    </div>

    <article class="container mx-auto px-4 lg:px-8 max-w-4xl">
        
        <!-- Header -->
        <header class="text-center mb-12 animate-fade-in-up">
            <div class="flex items-center justify-center gap-4 text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-4">
                <span class="text-purple-600">{{ $article->category }}</span>
                <span>•</span>
                <span>{{ $article->created_at->format('d M Y') }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-black font-tech text-slate-900 dark:text-white leading-tight mb-8">
                {{ $article->title }}
            </h1>
            
            @if($article->image_path)
                <div class="rounded-3xl overflow-hidden shadow-2xl mb-8">
                    <img src="{{ asset('storage/' . $article->image_path) }}" class="w-full max-h-[500px] object-cover">
                </div>
            @endif
        </header>

        <!-- Content -->
        <div class="prose prose-lg prose-slate dark:prose-invert max-w-none mb-16 animate-fade-in-up delay-100 font-serif leading-loose">
            {!! nl2br(e($article->content)) !!}
        </div>

        <!-- Share -->
        <div class="border-t border-b border-slate-200 dark:border-slate-700 py-8 mb-12 text-center">
            <p class="text-xs font-bold uppercase text-slate-500 mb-4 tracking-widest">Bagikan Artikel Ini</p>
            <div class="flex justify-center gap-4">
                <a href="#" class="p-3 rounded-full bg-blue-600 text-white hover:-translate-y-1 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                <a href="#" class="p-3 rounded-full bg-blue-800 text-white hover:-translate-y-1 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                <a href="#" class="p-3 rounded-full bg-green-500 text-white hover:-translate-y-1 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.506-.669-.51-.172-.008-.372-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.015-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.084 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg></a>
            </div>
        </div>

        <!-- Related -->
        @if($related->count() > 0)
            <div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Artikel Terkait</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <a href="{{ route('news.show', $rel->slug) }}" class="group bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700 hover:-translate-y-1 transition-all">
                            <div class="h-32 overflow-hidden bg-slate-200">
                                @if($rel->image_path)
                                    <img src="{{ asset('storage/' . $rel->image_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-purple-500 transition-colors">{{ $rel->title }}</h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </article>

    <script>
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("scrollProgress").style.width = scrolled + "%";
        };
    </script>
</div>