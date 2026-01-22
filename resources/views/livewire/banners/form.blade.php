<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $banner ? 'Edit Banner' : 'Upload Banner' }}
            </h2>
        </div>
        <a href="{{ route('banners.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Promo</label>
                <input wire:model="title" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('title') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Link Tujuan (Opsional)</label>
                <input wire:model="link_url" type="url" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="https://...">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Gambar Banner</label>
                @if ($image)
                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-xl mb-4 border border-slate-200">
                @elseif ($image_path)
                    <img src="{{ asset('storage/' . $image_path) }}" class="w-full h-48 object-cover rounded-xl mb-4 border border-slate-200">
                @endif
                
                <input wire:model="image" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4">
                <div class="w-24">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Urutan</label>
                    <input wire:model="order" type="number" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-center">
                </div>
                <div class="flex-1 pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-700">Tampilkan Banner</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95">
                    Simpan Banner
                </button>
            </div>
        </div>
    </form>
</div>
