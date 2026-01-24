<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Community <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">Builds</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto text-lg">
                Jelajahi, beri apresiasi, dan modifikasi rakitan PC terbaik dari komunitas Yala Computer.
            </p>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-100">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-purple-500 text-sm" placeholder="Cari nama rakitan...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-slate-500">Urutkan:</span>
                <select wire:model.live="sort" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm py-2 px-4 focus:ring-purple-500 font-bold">
                    <option value="popular">Terpopuler</option>
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </select>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-200">
            @forelse($builds as $build)
                <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:border-purple-500/50 transition-all group flex flex-col h-full">
                    <!-- Preview Image (Mockup or Component Collage) -->
                    <div class="h-48 bg-slate-100 dark:bg-slate-700 relative overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent z-10"></div>
                        <!-- Icons Collage -->
                        <div class="grid grid-cols-3 gap-4 opacity-30 transform group-hover:scale-110 transition-transform duration-700">
                            <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                            <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                        </div>
                        
                        <div class="absolute bottom-4 left-4 z-20">
                            <h3 class="font-bold text-white text-lg line-clamp-1">{{ $build->name }}</h3>
                            <p class="text-xs text-slate-300">by {{ $build->user->name }}</p>
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs text-slate-500 mb-4 line-clamp-2 min-h-[2.5em]">
                            {{ $build->description ?: 'Tidak ada deskripsi.' }}
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between mb-6 text-sm">
                            <div class="font-mono font-black text-purple-600 dark:text-purple-400 text-lg">
                                Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}
                            </div>
                            <div class="flex items-center gap-3 text-slate-400">
                                <button wire:click="toggleLike({{ $build->id }})" class="flex items-center gap-1 hover:text-rose-500 transition-colors {{ auth()->check() && $build->isLikedBy(auth()->user()) ? 'text-rose-500' : '' }}">
                                    <svg class="w-5 h-5 {{ auth()->check() && $build->isLikedBy(auth()->user()) ? 'fill-current' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    <span>{{ $build->likes_count }}</span>
                                </button>
                                <div class="flex items-center gap-1">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <span>{{ $build->views_count }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-auto grid grid-cols-2 gap-3">
                            {{-- View Detail (Coming Soon or Modal) --}}
                            {{-- <button class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Detail
                            </button> --}}
                            <button wire:click="cloneBuild({{ $build->id }})" class="col-span-2 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-600/30 transition-all text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                                Clone / Edit
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-400">
                    <p class="text-xl font-bold">Belum ada rakitan komunitas.</p>
                    <p class="mb-6">Jadilah yang pertama membagikan rakitanmu!</p>
                    <a href="{{ route('pc-builder') }}" class="text-purple-500 hover:underline font-bold">Buat Rakitan &rarr;</a>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $builds->links() }}
        </div>
    </div>
</div>
