<div class="flex h-[calc(100vh-8rem)] gap-6 animate-fade-in-up">
    
    <!-- Panel Kiri: Navigasi & Daftar -->
    <div class="w-1/3 flex flex-col bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden {{ $sedangMembaca || $sedangMengedit ? 'hidden md:flex' : 'flex w-full' }}">
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight mb-4">
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Pengetahuan</span>
            </h2>
            <div class="flex gap-2 mb-2">
                <input wire:model.live.debounce.300ms="cari" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm" placeholder="Cari SOP / Panduan...">
                <button wire:click="buat" class="px-3 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl shadow hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </button>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1 custom-scrollbar">
                <button wire:click="$set('filterKategori', '')" class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $filterKategori == '' ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-500' }}">Semua</button>
                @foreach($daftarKategori as $kat)
                    <button wire:click="$set('filterKategori', '{{ $kat }}')" class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $filterKategori == $kat ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-500' }}">{{ $kat }}</button>
                @endforeach
            </div>
        </div>

        <!-- Daftar Artikel -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2">
            @foreach($daftarArtikel as $artikel)
                <button wire:click="baca({{ $artikel->id }})" class="w-full text-left p-4 rounded-xl transition-all border border-transparent hover:border-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 {{ $artikelAktif?->id === $artikel->id ? 'bg-cyan-50 dark:bg-cyan-900/20 border-cyan-200' : '' }}">
                    <h4 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1">{{ $artikel->title }}</h4>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-[10px] bg-slate-200 dark:bg-slate-700 px-2 py-0.5 rounded text-slate-600">{{ $artikel->category }}</span>
                        <span class="text-[10px] text-slate-400">{{ $artikel->created_at->format('d M Y') }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Panel Kanan: Area Konten -->
    <div class="flex-1 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col {{ !$sedangMembaca && !$sedangMengedit ? 'hidden md:flex' : 'flex w-full' }}">
        @if($sedangMengedit)
            <div class="flex-1 flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $idArtikel ? 'Ubah Artikel' : 'Tulis Artikel Baru' }}</h3>
                    <button wire:click="$set('sedangMengedit', false)" class="text-slate-400 hover:text-rose-500">Batal</button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <input wire:model="judul" type="text" class="w-full text-2xl font-black bg-transparent border-b border-slate-200 focus:border-cyan-500 focus:ring-0 px-0 py-2 placeholder-slate-300" placeholder="Judul Artikel...">
                    <input wire:model="kategori" type="text" class="w-full text-sm font-bold bg-slate-50 border-none rounded-lg px-3 py-2 text-slate-600" placeholder="Kategori (misal: SOP, Troubleshooting)...">
                    <textarea wire:model="konten" class="w-full h-96 border-none focus:ring-0 resize-none text-slate-600 dark:text-slate-300 leading-relaxed" placeholder="Mulai menulis konten di sini..."></textarea>
                </div>
                <div class="p-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                    <button wire:click="simpan" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition-all">Terbitkan</button>
                </div>
            </div>
        @elseif($sedangMembaca && $artikelAktif)
            <div class="flex-1 flex flex-col h-full relative">
                <div class="absolute top-4 right-4 flex gap-2">
                    <button wire:click="ubah({{ $artikelAktif->id }})" class="p-2 bg-slate-100 rounded-lg hover:bg-slate-200 text-slate-600"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                    <button wire:click="hapus({{ $artikelAktif->id }})" class="p-2 bg-rose-50 rounded-lg hover:bg-rose-100 text-rose-600" wire:confirm="Hapus artikel ini?"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    <button wire:click="tutupPanel" class="md:hidden p-2 bg-slate-100 rounded-lg text-slate-600">X</button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">{{ $artikelAktif->category }}</span>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-6 leading-tight">{{ $artikelAktif->title }}</h1>
                    
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-500">{{ substr($artikelAktif->penulis->name ?? 'A', 0, 1) }}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $artikelAktif->penulis->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-500">Diterbitkan {{ $artikelAktif->created_at->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-300">
                        {!! nl2br(e($artikelAktif->content)) !!}
                    </div>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 opacity-50">
                <svg class="w-24 h-24 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <h3 class="text-xl font-bold text-slate-400">Pilih Artikel</h3>
                <p class="text-sm text-slate-400 max-w-xs mx-auto mt-2">Pilih dokumen dari daftar di sebelah kiri atau buat artikel baru.</p>
            </div>
        @endif
    </div>
</div>