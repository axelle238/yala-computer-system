<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white">
            {{ $banner ? 'Edit Banner' : 'Upload Banner Baru' }}
        </h2>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
        <div class="space-y-6">
            <!-- Image Upload -->
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Gambar Banner (Landscape)</label>
                
                <div class="relative w-full aspect-video bg-slate-100 dark:bg-slate-900 rounded-xl overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-blue-500 transition-colors group cursor-pointer">
                    <input type="file" wire:model="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                    @elseif ($existingImage)
                        <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors">
                            <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-xs font-bold uppercase">Klik untuk Upload</span>
                        </div>
                    @endif
                    
                    <!-- Loading State -->
                    <div wire:loading wire:target="image" class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 flex items-center justify-center z-20">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                @error('image') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Judul Promo</label>
                    <input wire:model="title" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 font-bold" placeholder="Contoh: Super Sale 12.12">
                    @error('title') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea wire:model="description" rows="2" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Teks Tombol (Opsional)</label>
                        <input wire:model="button_text" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500" placeholder="Contoh: Beli Sekarang">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Link URL (Opsional)</label>
                        <input wire:model="link_url" type="url" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500" placeholder="https://...">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Urutan Tampil</label>
                        <input wire:model="order" type="number" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                    <input type="checkbox" wire:model="is_active" id="active" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                    <label for="active" class="font-bold text-slate-700 dark:text-slate-300 text-sm">Aktifkan Banner Ini</label>
                </div>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('banners.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-50 transition-colors">Batal</a>
                <button wire:click="save" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Simpan Banner
                </button>
            </div>
        </div>
    </div>
</div>