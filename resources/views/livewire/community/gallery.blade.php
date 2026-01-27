<div class="min-h-screen pb-20">
    <!-- Header Hero -->
    <div class="relative bg-slate-950 py-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl font-black font-tech text-white mb-4 tracking-tighter uppercase">
                GALERI <span class="text-cyan-400">KOMUNITAS</span>
            </h1>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg font-medium">Inspirasi rakitan PC dari para master dan enthusiast. Lihat spesifikasi, berikan apresiasi, dan bangun impianmu.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-10 relative z-20">
        <!-- Bilah Kontrol -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-white/10 p-4 rounded-3xl shadow-2xl flex flex-col md:flex-row gap-4 items-center justify-between mb-12">
            <div class="relative flex-1 w-full">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-950 border-none rounded-2xl py-4 pl-12 pr-4 text-white focus:ring-2 focus:ring-cyan-500 placeholder-slate-600" placeholder="Cari rakitan impianmu...">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button wire:click="$set('sort', 'latest')" class="flex-1 md:flex-none px-6 py-4 rounded-2xl font-bold uppercase tracking-widest text-xs transition-all {{ $sort === 'latest' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-500/30' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">Terbaru</button>
                <button wire:click="$set('sort', 'popular')" class="flex-1 md:flex-none px-6 py-4 rounded-2xl font-bold uppercase tracking-widest text-xs transition-all {{ $sort === 'popular' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-500/30' : 'bg-slate-800 text-slate-400 hover:bg-slate-700' }}">Terpopuler</button>
            </div>
        </div>

        <!-- Grid Rakitan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($builds as $build)
                <div class="group bg-slate-900 border border-white/5 rounded-[2.5rem] overflow-hidden hover:border-cyan-500/50 transition-all duration-500 shadow-xl hover:shadow-cyan-500/10">
                    <!-- Kartu Header -->
                    <div class="p-8 pb-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-black text-sm border-2 border-slate-900 shadow-lg">
                                {{ substr($build->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-slate-200 font-bold text-sm truncate">{{ $build->user->name ?? 'Anonim' }}</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-black">{{ $build->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-white leading-tight mb-2 group-hover:text-cyan-400 transition-colors uppercase tracking-tight">{{ $build->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-2 italic mb-4">"{{ $build->description }}"</p>
                    </div>

                    <!-- Statistik Cepat -->
                    <div class="px-8 flex items-center gap-6 mb-6">
                        <button wire:click="toggleLike({{ $build->id }})" class="flex items-center gap-2 group/like transition-all">
                            <svg class="w-6 h-6 {{ auth()->check() && $build->isLikedBy(auth()->user()) ? 'text-pink-500' : 'text-slate-600 group-hover/like:text-pink-400' }}" fill="{{ auth()->check() && $build->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            <span class="text-sm font-black font-mono {{ auth()->check() && $build->isLikedBy(auth()->user()) ? 'text-pink-500' : 'text-slate-400 group-hover/like:text-pink-400' }}">{{ number_format($build->likes_count) }}</span>
                        </button>
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <span class="text-sm font-black font-mono">{{ number_format($build->views_count) }}</span>
                        </div>
                    </div>

                    <!-- Footer Kartu -->
                    <div class="p-6 bg-slate-950/50 border-t border-white/5 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Estimasi Biaya</span>
                            <span class="text-lg font-black text-cyan-400 font-mono">Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('toko.produk.detail', ['id' => $build->id]) }}" class="px-5 py-3 bg-white text-slate-950 font-black uppercase tracking-tighter text-xs rounded-xl hover:bg-cyan-500 hover:text-white transition-all transform active:scale-90">Rincian Komponen</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-slate-900/50 border-2 border-dashed border-white/5 rounded-[3rem]">
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-600">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-400 uppercase tracking-widest">Belum Ada Rakitan</h3>
                    <p class="text-slate-600 mt-2">Jadilah yang pertama membagikan mahakarya PC rakitanmu!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $builds->links() }}
        </div>
    </div>
</div>