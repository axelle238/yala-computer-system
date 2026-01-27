<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Promo <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">Banners</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Visual pemasaran halaman depan.</p>
        </div>
        <a href="{{ route('admin.spanduk.buat') }}" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Upload Banner
        </a>
    </div>

    <!-- Banner Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($banners as $banner)
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-xl transition-all relative">
                <!-- Image -->
                <div class="aspect-video relative overflow-hidden bg-slate-100">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent opacity-60"></div>
                    
                    <!-- Controls Overlay -->
                    <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                        <div>
                            <h3 class="text-white font-bold text-lg leading-tight line-clamp-1">{{ $banner->title }}</h3>
                            <p class="text-slate-300 text-xs mt-1 line-clamp-1">{{ $banner->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="p-4 flex justify-between items-center bg-white dark:bg-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-slate-400">Status:</span>
                        <button wire:click="toggleActive({{ $banner->id }})" class="relative inline-flex items-center cursor-pointer group focus:outline-none">
                            <span class="{{ $banner->is_active ? 'bg-emerald-500' : 'bg-slate-300' }} w-9 h-5 transition-colors duration-200 ease-in-out rounded-full shadow-inner"></span>
                            <span class="{{ $banner->is_active ? 'translate-x-4' : 'translate-x-0' }} absolute left-0.5 inline-block w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.spanduk.ubah', $banner->id) }}" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        <button wire:click="delete({{ $banner->id }})" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" onclick="return confirm('Hapus banner ini?') || event.stopImmediatePropagation()">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">
                <p>Belum ada banner promo. Upload sekarang untuk mempercantik halaman depan.</p>
            </div>
        @endforelse
    </div>
</div>
