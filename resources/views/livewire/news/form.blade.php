<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $article ? 'Edit Berita' : 'Buat Berita Baru' }}</h2>
        <a href="{{ route('news.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm">Kembali</a>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        
        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Judul</label>
            <input wire:model.live.debounce.500ms="title" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-blue-500">
            @error('title') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Slug (URL)</label>
            <input wire:model="slug" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500">
            @error('slug') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Gambar Utama</label>
            @if($image)
                <img src="{{ $image->temporaryUrl() }}" class="h-48 w-full object-cover rounded-lg mb-4">
            @elseif($oldImage)
                <img src="{{ asset('storage/' . $oldImage) }}" class="h-48 w-full object-cover rounded-lg mb-4">
            @endif
            <input wire:model="image" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('image') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Konten</label>
            <textarea wire:model="content" rows="10" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-blue-500"></textarea>
            @error('content') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-3">
            <input wire:model="is_published" type="checkbox" class="w-5 h-5 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500">
            <label class="font-bold text-slate-700 dark:text-slate-300">Publish Sekarang?</label>
        </div>

        <div class="pt-4">
            <button wire:click="save" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition-all">
                Simpan Berita
            </button>
        </div>
    </div>
</div>
