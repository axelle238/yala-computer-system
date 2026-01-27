<div class="h-screen flex flex-col bg-slate-100 dark:bg-slate-900 overflow-hidden" x-data="{ showShortcuts: false }">
    <!-- Navbar Khusus POS -->
    <header class="bg-indigo-700 px-6 py-3 flex justify-between items-center shadow-lg z-30 shrink-0">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.beranda') }}" class="text-indigo-200 hover:text-white transition-colors p-1 rounded-lg hover:bg-indigo-600" title="Kembali ke Dashboard">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-white tracking-wide flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    POS Yala <span class="font-light opacity-70 text-sm mt-1">| Point of Sale</span>
                </h1>
            </div>
        </div>
        
        <div class="flex items-center gap-8">
             <button @click="showShortcuts = !showShortcuts" class="text-indigo-200 hover:text-white text-xs font-semibold flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Shortcut (F1)
            </button>
            <div class="text-right hidden md:block border-r border-indigo-600 pr-6">
                <div class="text-[10px] text-indigo-300 uppercase tracking-wider font-bold">Kasir Bertugas</div>
                <div class="font-bold text-white text-sm">{{ Auth::user()->name }}</div>
            </div>
            <div class="text-right hidden md:block">
                <div class="text-[10px] text-indigo-300 uppercase tracking-wider font-bold">Jam Operasional</div>
                <div class="font-bold text-white font-mono text-lg leading-none" x-data x-init="setInterval(() => $el.innerText = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}), 1000)">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </header>

    <!-- Shortcut Overlay -->
    <div x-show="showShortcuts" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center" x-cloak @click.self="showShortcuts = false">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 w-96 transform transition-all scale-100">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">Keyboard Shortcuts</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center text-sm"><span class="text-slate-500 dark:text-slate-400">Cari Produk</span> <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded font-mono font-bold text-slate-700 dark:text-slate-300">F2</kbd></div>
                <div class="flex justify-between items-center text-sm"><span class="text-slate-500 dark:text-slate-400">Bayar / Checkout</span> <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded font-mono font-bold text-slate-700 dark:text-slate-300">F9</kbd></div>
                <div class="flex justify-between items-center text-sm"><span class="text-slate-500 dark:text-slate-400">Input Nominal Uang</span> <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded font-mono font-bold text-slate-700 dark:text-slate-300">F8</kbd></div>
                <div class="flex justify-between items-center text-sm"><span class="text-slate-500 dark:text-slate-400">Tutup Halaman</span> <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded font-mono font-bold text-slate-700 dark:text-slate-300">Esc</kbd></div>
            </div>
            <button @click="showShortcuts = false" class="mt-6 w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold">Tutup</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- LEFT PANEL: CATALOG -->
        <div class="w-2/3 flex flex-col border-r border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
            <!-- Search & Filter -->
            <div class="p-4 bg-white dark:bg-slate-900 shadow-sm shrink-0 border-b border-slate-200 dark:border-slate-800">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="kataKunciCari" 
                           class="block w-full pl-12 pr-4 py-3.5 border-slate-200 dark:border-slate-700 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm text-lg font-medium" 
                           placeholder="Scan Barcode atau Cari Nama Produk... [Tekan F2]"
                           id="productSearchInput"
                           autofocus>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <kbd class="hidden sm:inline-block px-2 py-0.5 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-md text-xs font-semibold text-slate-400 dark:text-slate-500">F2</kbd>
                    </div>
                </div>
                
                <!-- Category Tabs -->
                <div class="flex gap-2 mt-4 overflow-x-auto pb-1 scrollbar-hide">
                    <button wire:click="$set('idKategori', null)" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all transform active:scale-95 {{ is_null($idKategori) ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                        Semua
                    </button>
                    @foreach($daftarKategori as $kat)
                        <button wire:click="$set('idKategori', {{ $kat->id }})" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all transform active:scale-95 {{ $idKategori == $kat->id ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                            {{ $kat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-slate-100 dark:bg-slate-950">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-20">
                    @forelse($produkList as $produk)
                        <button wire:click="tambahKeKeranjang({{ $produk->id }})" class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden hover:shadow-xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-200 group text-left flex flex-col h-full transform hover:-translate-y-1 relative">
                            
                            <!-- Stock Indicator -->
                            <div class="absolute top-2 right-2 z-10">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold backdrop-blur-md {{ $produk->stock_quantity <= 5 ? 'bg-rose-500/90 text-white' : 'bg-slate-900/70 text-white' }}">
                                    {{ $produk->stock_quantity }} Unit
                                </span>
                            </div>

                            <div class="h-36 bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                @if($produk->image_path)
                                    <img src="{{ Storage::url($produk->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-sm line-clamp-2 leading-snug mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $produk->name }}</h3>
                                <div class="text-[10px] font-mono text-slate-400 mb-3">{{ $produk->sku }}</div>
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="font-black text-lg text-indigo-600 dark:text-indigo-400">
                                        <span class="text-xs font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($produk->sell_price, 0, ',', '.') }}
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-20 text-center flex flex-col items-center justify-center">
                            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-full mb-4">
                                <svg class="w-12 h-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Produk Tidak Ditemukan</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 max-w-xs mx-auto">Coba cari dengan kata kunci lain atau scan barcode produk.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 shrink-0">
                {{ $produkList->links() }}
            </div>
        </div>

        <!-- RIGHT PANEL: CART & CHECKOUT -->
        <div class="w-1/3 bg-white dark:bg-slate-900 flex flex-col shadow-2xl z-20 border-l border-slate-200 dark:border-slate-800 relative">
            
            <!-- Customer & Voucher Selector -->
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 shrink-0 space-y-4">
                
                <!-- Pelanggan -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Pelanggan
                        </label>
                        @if($idMemberTerpilih)
                            <button wire:click="$set('idMemberTerpilih', null)" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 transition-colors uppercase tracking-wider">Ganti</button>
                        @endif
                    </div>
                    <div class="relative">
                        @if($idMemberTerpilih)
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-xl">
                                <div class="w-10 h-10 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold shadow-sm">
                                    {{ substr($namaTamu, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-indigo-900 dark:text-indigo-300 text-sm leading-tight">{{ $namaTamu }}</div>
                                    <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mt-0.5">Member Terdaftar</div>
                                </div>
                                <div class="ml-auto">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        @else
                            <div class="relative">
                                <input type="text" wire:model.live.debounce="cariMember" class="w-full text-sm rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent py-2.5 pl-9 transition-shadow" placeholder="Cari Nama / No. HP...">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            @if(!empty($hasilCariMember))
                                <div class="absolute z-50 w-full bg-white dark:bg-slate-800 mt-2 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-48 overflow-y-auto custom-scrollbar">
                                    @foreach($hasilCariMember as $member)
                                        <button wire:click="pilihMember({{ $member->id }})" class="w-full text-left px-4 py-3 hover:bg-indigo-50 dark:hover:bg-slate-700 text-sm border-b border-slate-50 dark:border-slate-700 last:border-0 transition-colors group">
                                            <div class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-400">{{ $member->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $member->phone ?? $member->email }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Voucher -->
                <div>
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest flex items-center gap-1 mb-2">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Voucher & Promo
                    </label>
                    <div class="flex gap-2">
                        <input type="text" wire:model="kodeVoucher" class="flex-1 text-sm rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent py-2.5 uppercase font-bold tracking-wider placeholder-slate-400" placeholder="KODE PROMO">
                        @if($voucherTerpakai)
                            <button wire:click="hapusVoucher" class="px-3 bg-rose-100 hover:bg-rose-200 text-rose-600 rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @else
                            <button wire:click="terapkanVoucher" class="px-4 bg-slate-200 hover:bg-indigo-600 hover:text-white text-slate-600 font-bold text-xs rounded-xl transition-colors">
                                TERAPKAN
                            </button>
                        @endif
                    </div>
                    @if($voucherTerpakai)
                        <div class="mt-2 text-xs text-emerald-600 font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Diskon diterapkan: {{ $voucherTerpakai->discount_type == 'percentage' ? $voucherTerpakai->discount_value . '%' : 'Rp ' . number_format($voucherTerpakai->discount_value, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-5 space-y-4 custom-scrollbar bg-white dark:bg-slate-900">
                @forelse($keranjang as $idProduk => $item)
                    <div class="flex gap-4 relative group animate-fade-in-up">
                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center shrink-0 text-slate-400 border border-slate-200 dark:border-slate-700">
                             @if(isset($item['gambar']) && $item['gambar'])
                                <img src="{{ Storage::url($item['gambar']) }}" class="w-full h-full object-cover rounded-xl">
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm truncate pr-2 leading-tight">{{ $item['nama'] }}</h4>
                                <div class="font-bold text-slate-900 dark:text-white text-sm">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-slate-500 dark:text-slate-400">@ {{ number_format($item['harga'], 0, ',', '.') }}</div>
                                <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-lg p-0.5 border border-slate-200 dark:border-slate-700">
                                    <button wire:click="perbaruiJumlah({{ $idProduk }}, {{ $item['qty'] - 1 }})" class="w-6 h-6 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:text-rose-500 dark:hover:text-rose-400 rounded-md transition-all shadow-sm font-bold text-sm">-</button>
                                    <input type="number" value="{{ $item['qty'] }}" class="w-8 text-center border-0 p-0 text-xs font-bold focus:ring-0 bg-transparent text-slate-800 dark:text-white" readonly>
                                    <button wire:click="perbaruiJumlah({{ $idProduk }}, {{ $item['qty'] + 1 }})" class="w-6 h-6 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-white dark:hover:bg-slate-700 hover:text-emerald-500 dark:hover:text-emerald-400 rounded-md transition-all shadow-sm font-bold text-sm">+</button>
                                </div>
                            </div>
                        </div>
                        <button wire:click="hapusItem({{ $idProduk }})" class="absolute -left-2 -top-2 bg-white dark:bg-slate-800 text-rose-500 border border-slate-200 dark:border-slate-700 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-all hover:bg-rose-50 dark:hover:bg-rose-900 shadow-sm z-10 hover:scale-110">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-600">
                        <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-full mb-4">
                            <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium">Keranjang Belanja Kosong</p>
                        <p class="text-xs mt-1">Pilih produk di sebelah kiri untuk memulai.</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer Summary -->
            <div class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 p-6 space-y-4 shadow-[0_-8px_30px_rgba(0,0,0,0.12)] shrink-0 z-30">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                        <span>Subtotal</span>
                        <span class="font-mono">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($diskon > 0)
                    <div class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        <span>Diskon (Voucher)</span>
                        <span class="font-mono">- Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-end border-t border-dashed border-slate-200 dark:border-slate-700 pt-3 mt-2">
                        <span class="font-bold text-slate-800 dark:text-white text-lg">Total Akhir</span>
                        <span class="font-black text-3xl text-indigo-600 dark:text-indigo-400 font-mono tracking-tight">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button wire:click="$set('metodePembayaran', 'tunai')" class="py-2.5 px-3 text-sm font-bold rounded-xl border-2 transition-all flex items-center justify-center gap-2 {{ $metodePembayaran == 'tunai' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-900/30 dark:border-indigo-400 dark:text-indigo-300' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-600' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            TUNAI
                        </button>
                        <button wire:click="$set('metodePembayaran', 'qris')" class="py-2.5 px-3 text-sm font-bold rounded-xl border-2 transition-all flex items-center justify-center gap-2 {{ $metodePembayaran == 'qris' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-900/30 dark:border-indigo-400 dark:text-indigo-300' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-600' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            QRIS / TF
                        </button>
                    </div>
                </div>

                <!-- Cash Calculation -->
                @if($metodePembayaran == 'tunai')
                    <div class="animate-fade-in-up">
                        <label class="flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            <span>Uang Diterima</span>
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-500 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">F8</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold">Rp</span>
                            <input type="number" 
                                   wire:model.live.debounce.300ms="uangDibayar" 
                                   class="w-full text-right font-mono text-xl font-bold border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent py-3 pl-10 pr-4 transition-all" 
                                   placeholder="0"
                                   id="cashInput">
                        </div>
                        @if($kembalian > 0)
                            <div class="flex justify-between items-center mt-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/50 px-4 py-3 rounded-xl">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Kembalian</span>
                                <span class="font-mono font-bold text-xl text-emerald-600 dark:text-emerald-400">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($uangDibayar > 0 && $kembalian < 0)
                             <div class="flex justify-between items-center mt-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800/50 px-4 py-3 rounded-xl">
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Kurang Bayar</span>
                                <span class="font-mono font-bold text-xl text-rose-600 dark:text-rose-400">Rp {{ number_format(abs($kembalian), 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <button wire:click="prosesCheckout" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 text-lg transition-all transform active:scale-[0.98] flex justify-center items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none group relative"
                        {{ empty($keranjang) ? 'disabled' : '' }}
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>PROSES PEMBAYARAN</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                    <div wire:loading.remove class="bg-indigo-500 rounded px-2 py-0.5 text-xs font-mono opacity-80 group-hover:opacity-100 transition-opacity">F9</div>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Notifications (Toast) -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
         class="fixed top-24 right-6 z-50 pointer-events-none">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-8"
             :class="type === 'error' ? 'bg-white dark:bg-slate-800 border-l-4 border-rose-500 text-slate-800 dark:text-white' : 'bg-white dark:bg-slate-800 border-l-4 border-emerald-500 text-slate-800 dark:text-white'"
             class="px-6 py-4 rounded-lg shadow-2xl font-bold flex items-center gap-3 min-w-[300px] pointer-events-auto border border-slate-100 dark:border-slate-700">
            <div :class="type === 'error' ? 'text-rose-500' : 'text-emerald-500'">
                 <svg x-show="type === 'success'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                 <svg x-show="type === 'error'" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div x-text="type === 'error' ? 'Terjadi Kesalahan' : 'Berhasil'" class="text-xs font-bold uppercase tracking-wider opacity-50 mb-0.5"></div>
                <div x-text="message" class="text-sm"></div>
            </div>
        </div>
    </div>

    <!-- Global Shortcuts Listener -->
    <script>
        // Print Receipt Handler
        window.addEventListener('print-receipt', event => {
            const orderId = event.detail.orderId;
            const url = "{{ route('admin.cetak.transaksi', ':id') }}".replace(':id', orderId);
            
            // Seamless Print using Hidden Iframe
            let iframe = document.getElementById('receipt-frame');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'receipt-frame';
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
            }
            iframe.src = url;
            // Wait for load then print
            iframe.onload = function() {
                setTimeout(function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                }, 500);
            };
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'F1') {
                e.preventDefault();
                document.querySelector('[x-data]').__x.$data.showShortcuts = !document.querySelector('[x-data]').__x.$data.showShortcuts;
            }
            if (e.key === 'F2') {
                e.preventDefault();
                const searchInput = document.getElementById('productSearchInput');
                if (searchInput) searchInput.focus();
            }
            if (e.key === 'F8') {
                e.preventDefault();
                const cashInput = document.getElementById('cashInput');
                if (cashInput) {
                    cashInput.focus();
                    cashInput.select();
                } else {
                    @this.set('metodePembayaran', 'tunai');
                }
            }
            if (e.key === 'F9') {
                e.preventDefault();
                @this.prosesCheckout();
            }
            if (e.key === 'Escape') {
                if(document.querySelector('[x-data]').__x.$data.showShortcuts) {
                    document.querySelector('[x-data]').__x.$data.showShortcuts = false;
                } else {
                     @this.set('kataKunciCari', '');
                }
            }
        });
    </script>
</div>