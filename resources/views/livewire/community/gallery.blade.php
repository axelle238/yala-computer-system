<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter mb-4">
                Community <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-pink-500">Showcase</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
                Inspirasi rakitan PC terbaik dari komunitas Yala Computer. Pamerkan karyamu dan dapatkan apresiasi!
            </p>
            
            <div class="mt-8">
                <button wire:click="openUploadPanel" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-full hover:scale-105 transition-transform shadow-lg shadow-purple-500/20">
                    + Pamerkan Rakitan Saya
                </button>
            </div>
        </div>

        <!-- Upload Action Panel (Inline) -->
        @if($activeAction === 'upload')
            <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-purple-200 dark:border-purple-900/30 overflow-hidden animate-fade-in-up mb-12">
                <div class="p-8 border-b border-slate-100 dark:border-slate-700 bg-purple-50/50 dark:bg-purple-900/10 flex justify-between items-center">
                    <h3 class="font-black text-2xl text-slate-900 dark:text-white flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        Publikasikan Rakitan
                    </h3>
                    <button wire:click="closePanel" class="text-slate-400 hover:text-purple-600 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">Pilih Rakitan Tersimpan</label>
                        <select wire:model="selectedBuildId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-slate-800 dark:text-white focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-medium">
                            <option value="">-- Pilih Rakitan --</option>
                            @foreach($myBuilds as $b)
                                <option value="{{ $b->id }}">{{ $b->name }} (Rp {{ number_format($b->total_price_estimated) }})</option>
                            @endforeach
                        </select>
                        @if($myBuilds->isEmpty())
                            <div class="mt-3 flex items-center gap-2 text-rose-500 text-sm font-medium bg-rose-50 dark:bg-rose-900/20 p-3 rounded-xl">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Anda belum memiliki rakitan tersimpan. Buat dulu di menu Rakit PC.
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">Judul Postingan</label>
                        <input wire:model="galleryTitle" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-slate-800 dark:text-white focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all font-bold placeholder-slate-400" placeholder="Contoh: The White Beast - RTX 4090 Build">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">Cerita di Balik Rakitan</label>
                        <textarea wire:model="galleryDesc" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-slate-800 dark:text-white focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder-slate-400" placeholder="Ceritakan konsep, tantangan, dan performa PC ini..."></textarea>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-4 border-t border-slate-100 dark:border-slate-700">
                    <button wire:click="closePanel" class="px-8 py-3 text-slate-500 font-bold hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">Batal</button>
                    <button wire:click="publishBuild" class="px-10 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-black rounded-xl shadow-xl shadow-purple-600/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Posting Sekarang
                    </button>
                </div>
            </div>
        @endif

        <!-- Gallery Grid -->
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6 animate-fade-in-up delay-100">
            @forelse($builds as $build)
                <div class="break-inside-avoid bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl transition-all group relative">
                    <!-- Image -->
                    <div class="relative overflow-hidden aspect-[4/3] bg-slate-200 dark:bg-slate-700">
                        <!-- Mock Image based on ID to make it vary -->
                        <img src="https://picsum.photos/seed/{{ $build->id }}/600/400" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-6">
                            <button wire:click="like({{ $build->id }})" class="self-end p-2 bg-white/20 backdrop-blur rounded-full text-white hover:bg-pink-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs">
                                {{ substr($build->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white leading-tight">{{ $build->name }}</h3>
                                <p class="text-xs text-slate-500">by {{ $build->user->name }}</p>
                            </div>
                        </div>
                        
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-4 line-clamp-2">
                            {{ $build->description ?? 'Rakitan gaming performa tinggi dengan budget pelajar. Rata kanan 1080p!' }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-1 text-xs font-bold text-pink-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                {{ rand(10, 500) }}
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-400">Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 text-slate-400">
                    <p>Belum ada rakitan yang dipamerkan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $builds->links() }}
        </div>
    </div>