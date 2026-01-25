<div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                {{ $produk ? 'Ubah' : 'Tambah' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-500">Produk</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Manajemen inventaris produk teknologi tinggi.</p>
        </div>
        <a href="{{ route('products.index') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-bold hover:border-blue-500 hover:text-blue-500 transition-all shadow-sm">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="simpan" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Info Utama -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Kartu Informasi Dasar -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-blue-900/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-cyan-500/10 rounded-bl-full pointer-events-none"></div>
                
                <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Informasi Dasar
                </h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Produk</label>
                        <input wire:model.blur="nama" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-bold text-slate-800 dark:text-white" placeholder="Contoh: Laptop Gaming ROG Zephyrus">
                        @error('nama') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                            <div class="relative">
                                <select wire:model="idKategori" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer font-medium text-slate-700 dark:text-slate-300">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($daftarKategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            @error('idKategori') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pemasok (Supplier)</label>
                            <div class="relative">
                                <select wire:model="idPemasok" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer font-medium text-slate-700 dark:text-slate-300">
                                    <option value="">Pilih Pemasok</option>
                                    @foreach($daftarPemasok as $pas)
                                        <option value="{{ $pas->id }}">{{ $pas->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Produk</label>
                        <textarea wire:model="deskripsi" rows="4" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm text-slate-700 dark:text-slate-300" placeholder="Spesifikasi teknis produk secara detail..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Kartu Harga -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-emerald-900/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-teal-500/10 rounded-bl-full pointer-events-none"></div>
                
                <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Harga & Keuntungan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Beli (Modal)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">Rp</span>
                            <input wire:model="hargaBeli" type="number" class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono font-bold text-slate-800 dark:text-white">
                        </div>
                        @error('hargaBeli') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga Jual</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-emerald-500 font-bold">Rp</span>
                            <input wire:model="hargaJual" type="number" class="block w-full pl-10 pr-4 py-3 bg-emerald-50/50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono font-bold text-emerald-700 dark:text-emerald-400 text-lg">
                        </div>
                        @error('hargaJual') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Inventaris & Media -->
        <div class="space-y-8">
            <!-- Kartu Inventaris -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-indigo-900/5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-bl-full pointer-events-none"></div>
                
                <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </span>
                    Data Stok
                </h3>

                <div class="space-y-6 relative z-10">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kode SKU</label>
                        <input wire:model="sku" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-mono text-sm uppercase tracking-wide" placeholder="Otomatis dari Nama">
                        @error('sku') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Barcode (EAN/UPC)</label>
                        <input wire:model="barcode" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-mono text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Stok</label>
                            <input wire:model="jumlahStok" type="number" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-bold">
                            @error('jumlahStok') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-rose-500 uppercase tracking-wider mb-2">Alert Stok Min</label>
                            <input wire:model="peringatanStokMin" type="number" class="block w-full px-4 py-3 bg-rose-50/50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all font-bold text-rose-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Media -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-cyan-900/5 relative overflow-hidden">
                <h3 class="text-lg font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </span>
                    Foto Produk
                </h3>

                <div class="relative z-10">
                    <label class="block w-full aspect-square rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-blue-500 dark:hover:border-blue-400 transition-all cursor-pointer bg-slate-50 dark:bg-slate-900/50 flex flex-col items-center justify-center overflow-hidden group">
                        @if ($gambar)
                            <img src="{{ $gambar->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif ($pathGambar)
                            <img src="{{ asset('storage/' . $pathGambar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex flex-col items-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-12 h-12 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span class="text-sm font-bold text-slate-500">Unggah Foto</span>
                                <span class="text-[10px] text-slate-400 mt-1 uppercase">PNG, JPG MAKS 2MB</span>
                            </div>
                        @endif
                        <input wire:model="gambar" type="file" class="hidden" accept="image/*">
                        
                        <!-- Overlay Ganti -->
                        @if ($gambar || $pathGambar)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white font-bold text-sm uppercase tracking-widest">Ganti Foto</span>
                            </div>
                        @endif
                    </label>
                    <div wire:loading wire:target="gambar" class="mt-2 text-center">
                        <span class="text-xs font-bold text-blue-500 animate-pulse">Sedang mengunggah...</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-blue-500/30 transition-all hover:-translate-y-1 hover:shadow-blue-500/50 flex items-center justify-center gap-2 transform active:scale-95">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ $produk ? 'SIMPAN PERUBAHAN' : 'PUBLIKASIKAN PRODUK' }}
            </button>
        </div>
    </form>
</div>