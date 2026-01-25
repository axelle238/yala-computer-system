<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-12 font-sans relative">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Simulasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Rakit PC</span>
            </h1>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto text-lg font-medium">
                Rakit komputer impianmu dengan simulasi harga dan cek kompatibilitas otomatis secara real-time.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Kolom Kiri: Slot Komponen -->
            <div class="lg:w-2/3 space-y-4 animate-fade-in-up delay-100">
                
                <!-- Masalah Kompatibilitas -->
                @if(!empty($masalahKompatibilitas))
                    <div class="bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 p-5 rounded-r-2xl shadow-sm mb-6">
                        <p class="font-black flex items-center gap-2 uppercase tracking-wider text-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Kesalahan Kompatibilitas:
                        </p>
                        <ul class="list-disc list-inside mt-3 text-sm font-medium">
                            @foreach($masalahKompatibilitas as $masalah)
                                <li>{{ $masalah }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Komponen -->
                @foreach($daftarBagian as $slug => $meta)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 md:p-6 shadow-sm border border-slate-200 dark:border-slate-800 hover:border-blue-500/50 transition-all group relative overflow-hidden">
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-5">
                                <!-- Ikon/Gambar Komponen -->
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 flex-shrink-0 shadow-inner border border-slate-100 dark:border-slate-700 overflow-hidden">
                                    @if(isset($pilihanKomponen[$slug]['gambar']) && $pilihanKomponen[$slug]['gambar'])
                                        <img src="{{ asset('storage/' . $pilihanKomponen[$slug]['gambar']) }}" class="w-full h-full object-contain p-2" alt="Bagian">
                                    @else
                                        @if($meta['ikon'] == 'cpu') <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                        @elseif($meta['ikon'] == 'server') <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                                        @elseif($meta['ikon'] == 'memory') <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                                        @else <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                        @endif
                                    @endif
                                </div>
                                
                                <div class="min-w-0">
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">{{ $meta['label'] }}</h3>
                                    @if($pilihanKomponen[$slug])
                                        <div class="font-bold text-slate-900 dark:text-white text-base md:text-lg leading-tight truncate max-w-[250px] md:max-w-md">{{ $pilihanKomponen[$slug]['nama'] }}</div>
                                        <div class="text-blue-600 dark:text-blue-400 font-black font-mono mt-1">Rp {{ number_format($pilihanKomponen[$slug]['harga'], 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-slate-300 dark:text-slate-600 text-sm font-bold italic uppercase tracking-widest">Belum Terpilih</div>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0 ml-4">
                                @if($pilihanKomponen[$slug])
                                    <div class="flex gap-2">
                                        <button wire:click="bukaPemilih('{{ $slug }}')" class="p-3 bg-slate-100 dark:bg-slate-800 hover:bg-indigo-600 hover:text-white text-slate-600 dark:text-slate-300 rounded-xl transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </button>
                                        <button wire:click="hapusBagian('{{ $slug }}')" class="p-3 bg-rose-50 dark:bg-rose-950/30 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                @else
                                    <button wire:click="bukaPemilih('{{ $slug }}')" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-blue-600/20 hover:-translate-y-0.5 transition-all text-[10px] flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Pilih
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Kolom Kanan: Ringkasan (Sticky) -->
            <div class="lg:w-1/3">
                <div class="sticky top-24 space-y-6">
                    <!-- Kartu Harga -->
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl border border-slate-200 dark:border-slate-800 animate-fade-in-up delay-200">
                        <div class="mb-8">
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-3">Nama Rakitan Kamu</label>
                            <input type="text" wire:model.blur="namaRakitan" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl font-black text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 py-4 px-5">
                        </div>

                        <!-- Meteran Daya -->
                        <div class="mb-8 bg-slate-50 dark:bg-slate-800/50 p-5 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-inner">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Estimasi Beban Daya</span>
                                <span class="font-black text-slate-900 dark:text-white font-mono">{{ $estimasiDaya }} Watt</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden shadow-inner">
                                @php
                                    $persen = min(($estimasiDaya / 850) * 100, 100);
                                    $warna = 'bg-emerald-500';
                                    if($estimasiDaya > 500) $warna = 'bg-yellow-500';
                                    if($estimasiDaya > 750) $warna = 'bg-red-500';
                                @endphp
                                <div class="{{ $warna }} h-3 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(0,0,0,0.1)]" style="width: {{ $persen }}%"></div>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-3 text-center uppercase tracking-tighter italic">Disarankan Power Supply (PSU) minimal {{ $estimasiDaya + 100 }}W</p>
                        </div>

                        <!-- Opsi Jasa Rakit -->
                        <div class="mb-8">
                            <label class="flex items-start gap-4 p-4 border-2 {{ $tambahJasaRakit ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-100 dark:border-slate-800' }} rounded-3xl cursor-pointer hover:border-blue-400 transition-all group">
                                <input type="checkbox" wire:model.live="tambahJasaRakit" class="mt-1 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500 w-6 h-6">
                                <div>
                                    <span class="block font-black text-slate-900 dark:text-white text-sm uppercase tracking-tight">Rakitkan PC Ini (+Rp 150rb)</span>
                                    <span class="block text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Termasuk instalasi Windows (Trial), Update BIOS, dan Cable Management Premium.</span>
                                </div>
                            </label>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-800 pt-8 pb-4">
                            <div class="flex justify-between items-center mb-3 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                <span>Subtotal Komponen</span>
                                <span>Rp {{ number_format($tambahJasaRakit ? $totalHarga - 150000 : $totalHarga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">Total Estimasi</p>
                                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter font-mono">
                                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                                </h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-10">
                            <button wire:click="simpanRakitan" class="px-4 py-4 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest rounded-2xl transition-all flex items-center justify-center gap-2 text-[10px]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                Simpan
                            </button>
                            <button class="px-4 py-4 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest rounded-2xl transition-all flex items-center justify-center gap-2 text-[10px]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                Cetak
                            </button>
                            <button wire:click="masukkanKeKeranjang" class="col-span-2 px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black uppercase tracking-[0.2em] rounded-3xl shadow-2xl shadow-blue-600/30 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 text-sm">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Beli Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay Pemilih Komponen Inline (Mobile & Desktop Friendly) -->
    @if($tampilkanPemilih)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-10 bg-slate-950/90 backdrop-blur-md animate-fade-in">
            <div class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-white/10">
                <!-- Header -->
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                            Pilih {{ $daftarBagian[$kategoriSaatIni]['label'] }}
                        </h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Cari dan temukan komponen terbaik untuk performa maksimal.</p>
                    </div>
                    <button wire:click="tutupPemilih" class="p-3 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-rose-500 rounded-2xl transition-all">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Bilah Pencarian -->
                <div class="p-6 bg-slate-50 dark:bg-slate-950/50 border-b border-slate-100 dark:border-slate-800">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input wire:model.live.debounce.300ms="kataKunciCari" type="text" class="w-full bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-[2rem] pl-14 pr-6 py-4 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 dark:text-white text-lg font-bold shadow-inner" placeholder="Ketik nama produk, brand, atau spesifikasi...">
                    </div>
                </div>

                <!-- Daftar Produk -->
                <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-4 custom-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($daftarProduk as $p)
                            <div wire:click="pilihProduk({{ $p->id }})" class="bg-white dark:bg-slate-800 p-5 rounded-3xl border border-slate-100 dark:border-slate-800 cursor-pointer hover:border-blue-500 hover:shadow-xl transition-all group flex gap-5 items-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-950 rounded-2xl flex-shrink-0 flex items-center justify-center border border-slate-100 dark:border-slate-800 shadow-inner overflow-hidden relative z-10">
                                    @if($p->image_path)
                                        <img src="{{ asset('storage/' . $p->image_path) }}" class="w-full h-full object-contain p-2 group-hover:scale-110 transition-transform">
                                    @else
                                        <svg class="w-10 h-10 text-slate-200 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 relative z-10">
                                    <h4 class="font-black text-slate-900 dark:text-white group-hover:text-blue-600 transition-colors uppercase tracking-tight truncate">{{ $p->name }}</h4>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">{{ $p->sku }} • Stok: {{ $p->stock_quantity }}</div>
                                    <div class="mt-2 font-black text-blue-600 dark:text-blue-400 font-mono text-lg">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center text-slate-400 font-bold uppercase tracking-widest opacity-50">
                                <p>Produk tidak ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 bg-slate-50 dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800">
                    {{ $daftarProduk->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    @endif
</div>
