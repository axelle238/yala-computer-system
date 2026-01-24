<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Komunitas <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">Rakit PC</span>
            </h1>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Temukan inspirasi rakitan PC dari komunitas Yala Computer. Bagikan hasil karyamu dan dapatkan apresiasi!
            </p>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-4 animate-fade-in-up delay-100">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full px-6 py-3 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-purple-500 pl-12" placeholder="Cari build...">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            
            <a href="{{ route('pc-builder') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold rounded-full shadow-lg hover:shadow-purple-500/30 transition-all transform hover:-translate-y-1">
                + Buat Rakitan Baru
            </a>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($builds as $build)
                <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-2xl hover:border-purple-500/50 transition-all group flex flex-col h-full animate-fade-in-up">
                    <div class="h-48 bg-slate-900 relative overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/50 to-slate-900"></div>
                        <!-- Placeholder visual if no image uploaded for build -->
                        <div class="text-center z-10 p-6">
                            <h3 class="text-2xl font-black font-tech text-white mb-1 group-hover:scale-110 transition-transform">{{ $build->name }}</h3>
                            <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur rounded-full text-xs font-bold text-white uppercase tracking-wider">
                                Rp {{ number_format($build->total_price_estimated / 1000000, 1) }} Jt
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 font-bold uppercase">
                                {{ substr($build->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $build->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $build->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-6 line-clamp-2 flex-1">
                            {{ $build->description ?? 'Tidak ada deskripsi.' }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex gap-4 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                <span class="flex items-center gap-1 hover:text-pink-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    {{ $build->likes_count }}
                                </span>
                                <span class="flex items-center gap-1 hover:text-blue-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{ $build->views_count }}
                                </span>
                            </div>
                            <!-- Future: Link to detail -->
                            <button class="text-purple-600 hover:text-purple-500 font-bold text-sm">Lihat Detail &rarr;</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-500">
                    <p class="text-lg">Belum ada rakitan publik.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $builds->links() }}
        </div>
    </div>
</div>
