<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Promo Banner</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Kelola slide gambar di halaman depan toko.</p>
        </div>
        <a href="{{ route('banners.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Banner
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($banners as $banner)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden group hover:shadow-md transition-all">
                <div class="relative h-48 bg-slate-100">
                    <img src="{{ asset('storage/' . $banner->image_path) }}" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2">
                        <button wire:click="toggleActive({{ $banner->id }})" class="px-2 py-1 rounded text-xs font-bold {{ $banner->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-500 text-white' }}">
                            {{ $banner->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-slate-900">{{ $banner->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $banner->link_url ?? 'Tanpa Link' }}</p>
                    
                    <div class="mt-4 flex items-center justify-end gap-2">
                        <a href="{{ route('banners.edit', $banner->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </a>
                        <button wire:click="delete({{ $banner->id }})" wire:confirm="Hapus banner ini?" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
