<div class="space-y-8 animate-fade-in-up">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Master <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Produk</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen inventaris, kontrol stok, dan pengaturan harga global.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('admin.produk.buat') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-900 border-2 border-slate-900 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-md active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    <!-- Mini Dashboard (Statistik) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total SKU -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-cyan-300 dark:hover:border-cyan-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total SKU</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_sku']) }}</h3>
                <p class="text-[10px] text-cyan-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span> Produk Aktif
                </p>
            </div>
        </div>

        <!-- Nilai Aset -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nilai Aset</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($stats['total_value'] / 1000000, 1) }}Jt</h3>
                <p class="text-[10px] text-blue-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Estimasi Modal
                </p>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-rose-300 dark:hover:border-rose-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Stok Menipis</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['low_stock']) }}</h3>
                <p class="text-[10px] text-rose-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Perlu Restock
                </p>
            </div>
        </div>

        <!-- Kategori Teratas -->
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori Terbanyak</p>
                <h3 class="text-lg font-black text-slate-900 dark:text-white truncate" title="{{ $stats['top_category'] }}">{{ Str::limit($stats['top_category'], 15) }}</h3>
                <p class="text-[10px] text-indigo-600 font-bold mt-2 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Dominasi Katalog
                </p>
            </div>
        </div>
    </div>

    <!-- Toolbar: Cari & Filter -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96 group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-focus-within:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="cari"
                type="text" 
                class="block w-full pl-12 pr-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl leading-5 bg-slate-50 dark:bg-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 dark:text-white sm:text-sm transition-all font-medium" 
                placeholder="Cari SKU, nama, atau barcode..."
            >
        </div>

        <div class="w-full md:w-auto flex items-center gap-3">
            <div class="relative group">
                <select wire:model.live="filterKategori" class="appearance-none block w-full pl-4 pr-12 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-300 font-bold focus:ring-2 focus:ring-cyan-500 sm:text-sm transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($daftarKategori as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 group-hover:text-cyan-500 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden relative">
        <!-- Loading Overlay -->
        <div wire:loading.flex class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-30 items-center justify-center">
            <div class="flex flex-col items-center gap-4">
                <div class="relative w-12 h-12">
                    <div class="absolute inset-0 border-4 border-cyan-500/20 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-cyan-500 rounded-full border-t-transparent animate-spin"></div>
                </div>
                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest animate-pulse">Memuat Data...</span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar min-h-[400px]">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-5 font-black uppercase tracking-wider text-[10px]">Info Produk</th>
                        <th class="px-6 py-5 font-black uppercase tracking-wider text-[10px]">Kategori</th>
                        <th class="px-6 py-5 font-black uppercase tracking-wider text-[10px]">Harga (IDR)</th>
                        <th class="px-6 py-5 font-black uppercase tracking-wider text-[10px] text-center">Persediaan</th>
                        <th class="px-6 py-5 font-black uppercase tracking-wider text-[10px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($daftarProduk as $produk)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative w-14 h-14 flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-slate-100 dark:bg-slate-700 rounded-xl opacity-100"></div>
                                        @if($produk->image_path)
                                            <img src="{{ asset('storage/' . $produk->image_path) }}" class="w-full h-full object-contain rounded-xl p-1 relative z-10">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600 relative z-10">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="font-black text-slate-800 dark:text-white text-sm leading-tight truncate group-hover:text-cyan-600 transition-colors">{{ $produk->name }}</p>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold rounded uppercase tracking-tighter border border-slate-200 dark:border-slate-600 font-mono">{{ $produk->sku }}</span>
                                            @if($produk->barcode)
                                                <span class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                                    {{ $produk->barcode }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border border-slate-200 dark:border-slate-700">
                                    {{ $produk->kategori->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-slate-900 dark:text-white font-black font-mono text-sm">Rp {{ number_format($produk->sell_price, 0, ',', '.') }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase">HPP: Rp {{ number_format($produk->buy_price, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <div class="relative w-10 h-10 flex items-center justify-center">
                                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 36 36">
                                            <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-100 dark:stroke-slate-700" stroke-width="3"></circle>
                                            @php 
                                                $minStock = $produk->min_stock_alert ?? 5;
                                                $stockPct = $produk->stock_quantity > 0 ? min(100, ($produk->stock_quantity / max(1, $minStock * 4)) * 100) : 0;
                                                $stockColor = $produk->stock_quantity <= $minStock ? 'stroke-rose-500' : 'stroke-emerald-500';
                                            @endphp
                                            <circle cx="18" cy="18" r="16" fill="none" class="{{ $stockColor }}" stroke-width="3" stroke-dasharray="{{ $stockPct }}, 100" stroke-linecap="round"></circle>
                                        </svg>
                                        <span class="text-[10px] font-black {{ $produk->stock_quantity <= $minStock ? 'text-rose-600' : 'text-slate-700 dark:text-slate-200' }}">
                                            {{ $produk->stock_quantity }}
                                        </span>
                                    </div>
                                    @if($produk->stock_quantity <= $minStock)
                                        <span class="text-[9px] text-rose-500 font-black uppercase tracking-tighter mt-1 animate-pulse">Menipis</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.cetak.label', $produk->id) }}" target="_blank" class="p-2 text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 rounded-lg transition-all" title="Cetak Barcode">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                    </a>
                                    <a href="{{ route('admin.produk.ubah', $produk->id) }}" class="p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all" title="Ubah Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <button wire:click="hapus({{ $produk->id }})" wire:confirm="Hapus produk ini secara permanen?" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-all" title="Hapus Produk">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    </div>
                                    <p class="font-bold text-slate-500 uppercase tracking-widest text-xs">Tidak Ada Data</p>
                                    <p class="text-xs mt-1 opacity-60">Database tidak mengembalikan hasil pencarian produk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-50/50 dark:bg-slate-900/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $daftarProduk->links() }}
        </div>
    </div>
</div>