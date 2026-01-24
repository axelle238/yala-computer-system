<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Community <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">Gallery</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Jelajahi, sukai, dan tiru rakitan PC dari komunitas Yala Computer.</p>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-100">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-purple-500 focus:border-purple-500" placeholder="Cari nama rakitan...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            
            <div class="flex gap-2">
                <button wire:click="$set('sort', 'popular')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $sort === 'popular' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Terpopuler</button>
                <button wire:click="$set('sort', 'latest')" class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $sort === 'latest' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/20 dark:text-purple-400' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700' }}">Terbaru</button>
            </div>
        </div>

        <!-- Gallery Grid -->
        @if($builds->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-200">
                @foreach($builds as $build)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-xl hover:border-purple-500/50 transition-all group relative">
                        <!-- Preview (Mockup of Case) -->
                        <div class="h-48 bg-slate-900 flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-blue-500/20"></div>
                            
                            <!-- Display key components icons -->
                            <div class="flex gap-4 relative z-10 opacity-70 group-hover:opacity-100 transition-opacity">
                                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>

                            <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-slate-900 to-transparent">
                                <span class="font-mono font-bold text-white text-lg">Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-slate-900 dark:text-white text-lg line-clamp-1 group-hover:text-purple-500 transition-colors">{{ $build->name }}</h3>
                                <div class="flex items-center gap-1 text-xs text-slate-500 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-full">
                                    <svg class="w-3 h-3 text-rose-500 fill-current" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" /></svg>
                                    {{ $build->likes_count }}
                                </div>
                            </div>
                            
                            <p class="text-xs text-slate-500 mb-4">Oleh <span class="font-bold text-slate-700 dark:text-slate-300">{{ $build->user->name }}</span> • {{ $build->created_at->diffForHumans() }}</p>

                            <!-- Component List Preview -->
                            <div class="space-y-1 mb-6 text-xs text-slate-600 dark:text-slate-400">
                                @foreach(array_slice($build->components, 0, 3) as $key => $item)
                                    @if($item)
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <span class="truncate">{{ $item['name'] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                                @if(count($build->components) > 3)
                                    <div class="pl-3.5 italic text-slate-400">+ {{ count($build->components) - 3 }} komponen lainnya</div>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <button wire:click="toggleLike({{ $build->id }})" class="flex-1 py-2 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 rounded-lg text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    Suka
                                </button>
                                <button wire:click="cloneBuild({{ $build->id }})" class="flex-1 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-bold shadow-lg shadow-purple-500/30 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    Salin (Clone)
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $builds->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Belum ada rakitan publik</h3>
                <p class="text-slate-500 mb-6">Jadilah yang pertama membagikan rakitan PC Anda!</p>
                <a href="{{ route('pc-builder') }}" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl transition-all">Mulai Rakit PC</a>
            </div>
        @endif
    </div>
</div>