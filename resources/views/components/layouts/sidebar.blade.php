<aside 
    class="fixed inset-y-0 left-0 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-72 transform transition-transform duration-300 ease-in-out z-40 flex flex-col shadow-2xl"
    :class="sidebarTerbuka ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    @click.away="sidebarTerbuka = false"
    x-cloak
    x-data="{ 
        grupAktif: '{{ 
            request()->routeIs('admin.beranda') ? 'dashboard' : 
            (request()->routeIs('admin.kasir', 'admin.pesanan.*', 'admin.penawaran.*') ? 'penjualan' : 
            (request()->routeIs('admin.servis.*', 'admin.garansi.*', 'admin.perakitan.*', 'admin.pengetahuan.*') ? 'servis' : 
            (request()->routeIs('admin.produk.*', 'admin.gudang.*', 'admin.pesanan-pembelian.*', 'admin.procurement.*', 'admin.logistik.*', 'admin.permintaan-stok.*') ? 'inventaris' : 
            (request()->routeIs('admin.keuangan.*', 'admin.analitik.*', 'admin.pengeluaran.*') ? 'keuangan' : 
            (request()->routeIs('admin.pelanggan.*', 'admin.berita.*', 'admin.spanduk.*', 'admin.pemasaran.*') ? 'media' : 
            (request()->routeIs('admin.karyawan.*', 'admin.shift.*') ? 'sdm' : 
            (request()->routeIs('admin.keamanan.*') ? 'keamanan' : 
            (request()->routeIs('admin.pengaturan.*', 'admin.sistem.*', 'admin.log-aktivitas.*', 'admin.aset.*') ? 'sistem' : ''))))))))
        }}',
        toggleGrup(grup) {
            this.grupAktif = this.grupAktif === grup ? null : grup;
        }
    }"
>
    
    <!-- Header Logo -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
        <a href="{{ route('admin.beranda') }}" class="flex items-center gap-3 group">
            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-2 rounded-xl group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <div class="flex flex-col">
                <span class="font-tech text-xl font-bold text-slate-800 dark:text-white leading-none tracking-tight">YALA <span class="text-indigo-600">COMPUTER</span></span>
                <span class="text-[10px] text-slate-400 font-medium tracking-widest uppercase">Sistem Terintegrasi</span>
            </div>
        </a>
        <button @click="sidebarTerbuka = false" class="md:hidden text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <!-- Menu Gulir -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        
        <!-- DASHBOARD -->
        <a href="{{ route('admin.beranda') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 group {{ request()->routeIs('admin.beranda') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.beranda') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            <span class="truncate">Dasbor Utama</span>
        </a>

        <!-- JUDUL MENU -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Operasional Bisnis</div>

        <!-- GRUP: PENJUALAN -->
        @if(auth()->user()->punyaAkses('akses_pos') || auth()->user()->punyaAkses('lihat_pesanan'))
        <div>
            <button @click="toggleGrup('penjualan')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.kasir', 'admin.pesanan.*', 'admin.penawaran.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.kasir', 'admin.pesanan.*', 'admin.penawaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    <span>Penjualan & Kasir</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'penjualan' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'penjualan'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                @if(auth()->user()->punyaAkses('akses_pos'))
                <a href="{{ route('admin.kasir') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.kasir') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Buka Kasir (POS)
                </a>
                @endif
                <a href="{{ route('admin.pesanan.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pesanan.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Riwayat Transaksi
                </a>
                <a href="{{ route('admin.penawaran.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.penawaran.indeks', 'admin.penawaran.buat', 'admin.penawaran.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Penawaran (B2B)
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: LAYANAN SERVIS -->
        @if(auth()->user()->punyaAkses('akses_servis') || auth()->user()->punyaAkses('lihat_tiket'))
        <div>
            <button @click="toggleGrup('servis')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.servis.*', 'admin.garansi.*', 'admin.perakitan.*', 'admin.pengetahuan.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.servis.*', 'admin.garansi.*', 'admin.perakitan.*', 'admin.pengetahuan.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Layanan & Servis</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'servis' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'servis'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.servis.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.servis.indeks', 'admin.servis.buat', 'admin.servis.ubah', 'admin.servis.meja-kerja') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Tiket Servis
                </a>
                <a href="{{ route('admin.servis.papan') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.servis.papan') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Papan Kanban
                </a>
                <a href="{{ route('admin.perakitan.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.perakitan.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Perakitan PC
                </a>
                <a href="{{ route('admin.garansi.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.garansi.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Garansi (RMA)
                </a>
                <a href="{{ route('admin.pengetahuan.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pengetahuan.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Basis Pengetahuan
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: GUDANG & LOGISTIK -->
        @if(auth()->user()->punyaAkses('akses_gudang') || auth()->user()->punyaAkses('lihat_produk'))
        <div>
            <button @click="toggleGrup('inventaris')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.produk.*', 'admin.gudang.*', 'admin.pesanan-pembelian.*', 'admin.logistik.*', 'admin.permintaan-stok.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.produk.*', 'admin.gudang.*', 'admin.pesanan-pembelian.*', 'admin.logistik.*', 'admin.permintaan-stok.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>Gudang & Logistik</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'inventaris' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'inventaris'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.produk.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.produk.indeks', 'admin.produk.buat', 'admin.produk.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Daftar Produk
                </a>
                <a href="{{ route('admin.permintaan-stok.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.permintaan-stok.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Permintaan Stok
                </a>
                <a href="{{ route('admin.pesanan-pembelian.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pesanan-pembelian.indeks', 'admin.pesanan-pembelian.buat', 'admin.pesanan-pembelian.ubah', 'admin.pesanan-pembelian.tampil') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pesanan Pembelian
                </a>
                <a href="{{ route('admin.pesanan-pembelian.terima') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pesanan-pembelian.terima') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Penerimaan Barang
                </a>
                <a href="{{ route('admin.gudang.stok-opname') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.gudang.stok-opname') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Stok Opname
                </a>
                <a href="{{ route('admin.gudang.transfer') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.gudang.transfer') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Mutasi Barang
                </a>
                <a href="{{ route('admin.logistik') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.logistik') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pengiriman
                </a>
                <a href="{{ route('admin.produk.label') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.produk.label') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Cetak Barcode
                </a>
                <a href="{{ route('admin.master.pemasok') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.master.pemasok') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Data Pemasok
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: KEUANGAN & LAPORAN -->
        @if(auth()->user()->punyaAkses('akses_keuangan') || auth()->user()->punyaAkses('lihat_laporan'))
        <div>
            <button @click="toggleGrup('keuangan')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.keuangan.*', 'admin.analitik.*', 'admin.pengeluaran.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.keuangan.*', 'admin.analitik.*', 'admin.pengeluaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Keuangan & Laporan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'keuangan' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'keuangan'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.keuangan.kasir') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keuangan.kasir') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Status Kasir
                </a>
                <a href="{{ route('admin.pengeluaran') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pengeluaran') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Beban Operasional
                </a>
                <a href="{{ route('admin.analitik.penjualan') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.analitik.penjualan') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Analisa Penjualan
                </a>
                <a href="{{ route('admin.analitik.keuangan') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.analitik.keuangan') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Analisa Laba Rugi
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: MEDIA & HUBUNGAN PELANGGAN -->
        @if(auth()->user()->punyaAkses('akses_media') || auth()->user()->punyaAkses('lihat_pelanggan'))
        <div>
            <button @click="toggleGrup('media')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.pelanggan.*', 'admin.berita.*', 'admin.spanduk.*', 'admin.pemasaran.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.pelanggan.*', 'admin.berita.*', 'admin.spanduk.*', 'admin.pemasaran.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span>Media & Hubungan Pelanggan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'media' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'media'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.pelanggan.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pelanggan.indeks', 'admin.pelanggan.buat', 'admin.pelanggan.tampil', 'admin.pelanggan.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Database Pelanggan
                </a>
                <a href="{{ route('admin.pelanggan.kotak-masuk') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pelanggan.kotak-masuk') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Kotak Pesan
                </a>
                <a href="{{ route('admin.pelanggan.obrolan') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pelanggan.obrolan') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Obrolan Langsung (Chat)
                </a>
                <a href="{{ route('admin.pemasaran.whatsapp') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pemasaran.whatsapp') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pesan Massal (WA)
                </a>
                <a href="{{ route('admin.berita.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.berita.indeks', 'admin.berita.buat', 'admin.berita.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Artikel & Berita
                </a>
                <a href="{{ route('admin.spanduk.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.spanduk.indeks', 'admin.spanduk.buat', 'admin.spanduk.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Banner Promosi
                </a>
                <a href="{{ route('admin.pemasaran.voucher.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pemasaran.voucher.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Kupon & Diskon
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: SDM & KARYAWAN -->
        @if(auth()->user()->punyaAkses('akses_admin') || auth()->user()->punyaAkses('lihat_karyawan'))
        <div>
            <button @click="toggleGrup('sdm')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.karyawan.*', 'admin.shift') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.karyawan.*', 'admin.shift') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span>SDM & Karyawan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'sdm' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'sdm'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.karyawan.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.karyawan.indeks', 'admin.karyawan.buat', 'admin.karyawan.ubah') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Manajemen Karyawan
                </a>
                <a href="{{ route('admin.karyawan.peran.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.karyawan.peran.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Hak Akses & Peran
                </a>
                <a href="{{ route('admin.karyawan.kehadiran') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.karyawan.kehadiran') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Presensi Kehadiran
                </a>
                <a href="{{ route('admin.karyawan.gaji-pengelola') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.karyawan.gaji-pengelola') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Manajemen Payroll
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: KEAMANAN SISTEM -->
        @if(auth()->user()->punyaAkses('akses_keamanan') || auth()->user()->punyaAkses('akses_admin'))
        <div>
            <button @click="toggleGrup('keamanan')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.keamanan.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.keamanan.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span>Keamanan Sistem</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'keamanan' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'keamanan'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.keamanan.dashboard') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.dashboard') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                        <span class="truncate">Dasbor Keamanan</span>
                </a>
                <a href="{{ route('admin.keamanan.firewall') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.firewall') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Firewall & Blokir IP
                </a>
                <a href="{{ route('admin.keamanan.ids') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.ids') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Deteksi Ancaman
                </a>
                <a href="{{ route('admin.keamanan.audit') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.audit') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Audit Keamanan
                </a>
                <a href="{{ route('admin.keamanan.scanner') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.scanner') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pemindai Kerentanan
                </a>
                <a href="{{ route('admin.keamanan.atm') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.atm') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Respon Otomatis (ATM)
                </a>
                <a href="{{ route('admin.keamanan.honeypot') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.honeypot') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Honeypot System
                </a>
                <a href="{{ route('admin.keamanan.traffic') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.keamanan.traffic') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Inspeksi Trafik (Live)
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: SISTEM & PENGATURAN -->
        @if(auth()->user()->punyaAkses('akses_admin'))
        <div>
            <button @click="toggleGrup('sistem')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.pengaturan', 'admin.sistem.*', 'admin.log-aktivitas.*', 'admin.aset.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.pengaturan', 'admin.sistem.*', 'admin.log-aktivitas.*', 'admin.aset.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Sistem & Pengaturan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'sistem' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'sistem'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('admin.pengaturan') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.pengaturan') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Konfigurasi Utama
                </a>
                <a href="{{ route('admin.aset.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.aset.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Aset Perusahaan
                </a>
                <a href="{{ route('admin.log-aktivitas.indeks') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.log-aktivitas.indeks') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Log Aktivitas Audit
                </a>
                <a href="{{ route('admin.sistem.info') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.sistem.info') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Informasi Server
                </a>
            </div>
        </div>
        @endif

    </div>

    <!-- Profil Pengguna (Bawah) -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-300 font-bold">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->peran->nama ?? auth()->user()->role }}</p>
            </div>
            
            <!-- Tombol Keluar -->
            <form method="POST" action="{{ route('keluar') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Keluar Sistem">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</aside>