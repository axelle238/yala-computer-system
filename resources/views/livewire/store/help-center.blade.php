<div class="min-h-screen pb-20">
    <!-- Header Hero -->
    <div class="relative bg-slate-950 py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/10 to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-white mb-6 tracking-tighter uppercase">
                Pusat <span class="text-indigo-400">Bantuan</span>
            </h1>
            <p class="text-slate-400 text-lg font-medium mb-10">Temukan jawaban atas pertanyaan Anda seputar layanan, produk, dan teknis.</p>
            
            <!-- Pencarian Besar -->
            <div class="relative max-w-2xl mx-auto group">
                <input wire:model.live.debounce.300ms="cari" type="text" class="w-full bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-2xl py-5 pl-14 pr-6 text-white focus:ring-2 focus:ring-indigo-500 placeholder-slate-500 text-lg transition-all group-hover:border-indigo-500/50" placeholder="Cari topik bantuan (misal: garansi, pengiriman)...">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-500 group-hover:text-indigo-400 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-12 relative z-20">
        <!-- Kategori -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
            @foreach($daftarKategori as $kat)
                <button wire:click="pilihKategori('{{ $kat['name'] }}')" class="flex flex-col items-center justify-center p-6 rounded-3xl border transition-all duration-300 {{ $kategoriAktif === $kat['name'] ? 'bg-indigo-600 border-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-900 border-white/5 text-slate-400 hover:bg-slate-800 hover:border-white/10 hover:text-white' }}">
                    <div class="w-12 h-12 rounded-2xl {{ $kategoriAktif === $kat['name'] ? 'bg-white/20' : 'bg-slate-800' }} flex items-center justify-center mb-3">
                        <!-- Icon Mapping (Sederhana) -->
                        @if($kat['icon'] == 'truck') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        @elseif($kat['icon'] == 'shield-check') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif($kat['icon'] == 'cpu-chip') <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                        @else <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        @endif
                    </div>
                    <span class="text-sm font-bold uppercase tracking-wide">{{ $kat['name'] }}</span>
                </button>
            @endforeach
        </div>

        <!-- Daftar Artikel (FAQ) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ activeAccordion: null }">
            @forelse($daftarArtikel as $index => $artikel)
                <div class="bg-slate-900 border border-white/5 rounded-2xl overflow-hidden hover:border-indigo-500/30 transition-all duration-300">
                    <button @click="activeAccordion = activeAccordion === {{ $index }} ? null : {{ $index }}" class="w-full px-6 py-5 flex justify-between items-center text-left focus:outline-none">
                        <span class="font-bold text-slate-200 text-lg">{{ $artikel->title }}</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform duration-300" :class="{'rotate-180': activeAccordion === {{ $index }}}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeAccordion === {{ $index }}" x-collapse class="px-6 pb-6 text-slate-400 leading-relaxed border-t border-white/5 pt-4">
                        {!! nl2br(e($artikel->content)) !!}
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-500">
                    <div class="w-16 h-16 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="font-medium">Tidak ada artikel bantuan yang ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>