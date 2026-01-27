<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $articleId ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h2>
        <a href="{{ route('admin.berita.indeks') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm">Kembali</a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <form wire:submit.prevent="save" class="p-6 md:p-8 space-y-6">
            
            <!-- Title -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Judul Artikel</label>
                <input wire:model.live.debounce.500ms="title" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-lg font-bold p-4 focus:ring-purple-500" placeholder="Contoh: Tips Merakit PC Gaming Hemat Budget">
                @error('title') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Slug (Auto) -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Permalink (Slug)</label>
                <input wire:model="slug" type="text" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 text-sm font-mono" readonly>
            </div>

            <!-- Category & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kategori</label>
                    <select wire:model="category" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 p-3">
                        <option value="news">Berita & Info</option>
                        <option value="tutorial">Tutorial & Tips</option>
                        <option value="promo">Promo & Event</option>
                        <option value="review">Review Produk</option>
                    </select>
                </div>
                <div class="flex items-center pt-8">
                    <label class="flex items-center cursor-pointer gap-3">
                        <div class="relative">
                            <input wire:model="is_published" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Publikasikan Langsung</span>
                    </label>
                </div>
            </div>

            <!-- Image -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Gambar Utama (Cover)</label>
                <div class="flex items-center gap-6">
                    @if ($image)
                        <div class="w-32 h-24 rounded-lg overflow-hidden border border-slate-200">
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @elseif ($existingImage)
                        <div class="w-32 h-24 rounded-lg overflow-hidden border border-slate-200">
                            <img src="{{ asset('storage/' . $existingImage) }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <input wire:model="image" type="file" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-purple-50 file:text-purple-700
                            hover:file:bg-purple-100
                        "/>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, WEBP (Max. 2MB)</p>
                        @error('image') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Content (Textarea for now, WYSIWYG later if needed) -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Isi Konten</label>
                <textarea wire:model="content" rows="15" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 p-4 focus:ring-purple-500 font-serif leading-relaxed" placeholder="Mulai menulis cerita Anda..."></textarea>
                @error('content') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                <a href="{{ route('admin.berita.indeks') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5">Simpan Artikel</button>
            </div>

        </form>
    </div>
</div>
