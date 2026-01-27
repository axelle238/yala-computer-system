<aside 
    class="fixed inset-y-0 left-0 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-72 transform transition-transform duration-300 ease-in-out z-40 flex flex-col shadow-2xl"
    :class="sidebarTerbuka ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    @click.away="sidebarTerbuka = false"
    x-cloak
    x-data="{ 
        grupAktif: '{{ 
            request()->routeIs('admin.beranda') ? 'dashboard' : 
            (request()->routeIs('sales.pos', 'admin.pesanan.*', 'quotations.*') ? 'penjualan' : 
            (request()->routeIs('services.*', 'admin.garansi.*', 'assembly.*', 'admin.pengetahuan.*') ? 'servis' : 
            (request()->routeIs('admin.produk.*', 'admin.gudang.*', 'admin.pesanan-pembelian.*', 'admin.procurement.*', 'admin.logistik.*', 'purchase-requisitions.*') ? 'inventaris' : 
            (request()->routeIs('admin.keuangan.*', 'reports.*', 'admin.pengeluaran.*') ? 'keuangan' : 
            (request()->routeIs('admin.pelanggan.*', 'customers.inbox', 'admin.pemasaran.*') ? 'crm' : 
            (request()->routeIs('admin.karyawan.*', 'admin.shift.*') ? 'sdm' : 
            (request()->routeIs('admin.berita.*', 'admin.spanduk.*') ? 'media' :
            (request()->routeIs('admin.pengaturan.*', 'admin.sistem.*', 'admin.log-aktivitas.*', 'admin.aset.*') ? 'sistem' : ''))))))))
        }}',
        toggleGrup(grup) {
            this.grupAktif = this.grupAktif === grup ? null : grup;
        }
    }"
>
    
    <!-- Header Logo -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
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
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard Utama
        </a>

        <!-- JUDUL MENU -->
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Operasional Bisnis</div>

        <!-- GRUP: FRONT OFFICE -->
        @if(auth()->user()->punyaAkses('akses_pos') || auth()->user()->punyaAkses('lihat_pesanan'))
        <div>
            <button @click="toggleGrup('penjualan')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('sales.pos', 'orders.*', 'quotations.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('sales.pos', 'orders.*', 'quotations.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    <span>Penjualan (Garda Depan)</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'penjualan' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'penjualan'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                @if(auth()->user()->punyaAkses('akses_pos'))
                <a href="{{ route('sales.pos') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('sales.pos') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Kasir (POS)
                </a>
                @endif
                <a href="{{ route('orders.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('orders.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Riwayat Transaksi
                </a>
                <a href="{{ route('quotations.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('quotations.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Penawaran Harga (B2B)
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: PUSAT SERVIS -->
        @if(auth()->user()->punyaAkses('akses_servis') || auth()->user()->punyaAkses('lihat_tiket'))
        <div>
            <button @click="toggleGrup('servis')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('services.*', 'rma.*', 'assembly.*', 'knowledge.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('services.*', 'rma.*', 'assembly.*', 'knowledge.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Layanan & Servis</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'servis' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'servis'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('services.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('services.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Tiket Servis
                </a>
                <a href="{{ route('services.kanban') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('services.kanban') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Papan Kanban
                </a>
                <a href="{{ route('assembly.manager') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('assembly.manager') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Perakitan PC
                </a>
                <a href="{{ route('rma.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('rma.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Garansi & Retur (RMA)
                </a>
                <a href="{{ route('knowledge.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('knowledge.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Basis Pengetahuan
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: INVENTARIS -->
        @if(auth()->user()->punyaAkses('akses_gudang') || auth()->user()->punyaAkses('lihat_produk'))
        <div>
            <button @click="toggleGrup('inventaris')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('products.*', 'warehouses.*', 'purchase-orders.*', 'logistics.*', 'purchase-requisitions.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.produk.*', 'admin.gudang.*', 'admin.pesanan-pembelian.*', 'admin.procurement.*', 'admin.logistik.*', 'admin.permintaan-pembelian.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span>Gudang & Logistik</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'inventaris' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'inventaris'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('products.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('products.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Data Produk
                </a>
                <a href="{{ route('purchase-requisitions.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('purchase-requisitions.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Permintaan Stok
                </a>
                <a href="{{ route('purchase-orders.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('purchase-orders.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pembelian (PO)
                </a>
                <a href="{{ route('purchase-orders.receive') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('purchase-orders.receive') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Penerimaan Barang
                </a>
                <a href="{{ route('warehouses.stock-opname') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('warehouses.stock-opname') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Stok Opname
                </a>
                <a href="{{ route('warehouses.transfer') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('warehouses.transfer') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Mutasi Antar Gudang
                </a>
                <a href="{{ route('logistics.manager') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('logistics.manager') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pengiriman & Kurir
                </a>
                <a href="{{ route('products.labels') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('products.labels') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Cetak Label Barcode
                </a>
                <a href="{{ route('master.suppliers') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('master.suppliers') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Manajemen Supplier
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: KEUANGAN -->
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
                <a href="{{ route('finance.cash-register') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('finance.cash-register') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Buka/Tutup Kasir
                </a>
                <a href="{{ route('expenses.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('expenses.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Pengeluaran Operasional
                </a>
                <a href="{{ route('reports.sales') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('reports.sales') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Laporan Penjualan
                </a>
                <a href="{{ route('reports.finance') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('reports.finance') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Laporan Laba/Rugi
                </a>
            </div>
        </div>
        @endif

        <!-- JUDUL MENU -->
        @if(auth()->user()->punyaAkses('akses_admin'))
        <div class="mt-6 mb-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Manajemen Perusahaan</div>
        @endif

        <!-- GRUP: MEDIA & CRM -->
        <div>
            <button @click="toggleGrup('media')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('admin.news.*', 'banners.*', 'customers.*', 'marketing.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.news.*', 'banners.*', 'customers.*', 'marketing.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span>Media & Hubungan Pelanggan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'media' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'media'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <!-- Media -->
                <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-2">Konten & Media</div>
                <a href="{{ route('admin.news.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('admin.news.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Berita & Artikel
                </a>
                <a href="{{ route('banners.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('banners.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Banner & Media
                </a>

                <!-- Pelanggan -->
                <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-2">Pelanggan (CRM)</div>
                <a href="{{ route('customers.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('customers.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Database Pelanggan
                </a>
                <a href="{{ route('customers.inbox') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('customers.inbox') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Kotak Masuk
                </a>
                <a href="{{ route('customers.live-chat') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('customers.live-chat') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Live Chat
                </a>
                
                <!-- Pemasaran -->
                <div class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-2">Pemasaran</div>
                <a href="{{ route('marketing.vouchers.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('marketing.vouchers.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Voucher & Promo
                </a>
                <a href="{{ route('marketing.flash-sale.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('marketing.flash-sale.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Flash Sale
                </a>
                <a href="{{ route('marketing.reviews.manager') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('marketing.reviews.manager') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Ulasan & Rating
                </a>
                <a href="{{ route('marketing.whatsapp.blast') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('marketing.whatsapp.blast') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    WhatsApp Blast
                </a>
            </div>
        </div>

        <!-- GRUP: SDM -->
        @if(auth()->user()->punyaAkses('akses_admin') || auth()->user()->punyaAkses('lihat_karyawan'))
        <div>
            <button @click="toggleGrup('sdm')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('employees.*', 'shift.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('employees.*', 'shift.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span>SDM & Karyawan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'sdm' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'sdm'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('employees.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('employees.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Database Karyawan
                </a>
                <a href="{{ route('employees.roles') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('employees.roles') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Peran & Hak Akses
                </a>
                <a href="{{ route('employees.attendance') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('employees.attendance') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Presensi Kehadiran
                </a>
                <a href="{{ route('employees.payroll.manager') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('employees.payroll.manager') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Penggajian (Payroll)
                </a>
            </div>
        </div>
        @endif

        <!-- GRUP: SISTEM -->
        @if(auth()->user()->punyaAkses('akses_admin'))
        <div>
            <button @click="toggleGrup('sistem')" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200 group {{ request()->routeIs('settings.index', 'system.*', 'activity-logs.*', 'assets.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-slate-800/50' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('settings.index', 'system.*', 'activity-logs.*', 'assets.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>Sistem & Pengaturan</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="grupAktif === 'sistem' ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="grupAktif === 'sistem'" x-collapse class="space-y-1 pl-11 pr-2 mt-1">
                <a href="{{ route('settings.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('settings.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Konfigurasi Aplikasi
                </a>
                <a href="{{ route('assets.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('assets.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Manajemen Aset
                </a>
                <a href="{{ route('activity-logs.index') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('activity-logs.index') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Log Aktivitas User
                </a>
                <a href="{{ route('system.info') }}" class="block py-2 px-3 rounded-md text-sm transition-colors {{ request()->routeIs('system.info') ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                    Informasi Sistem
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Keluar Sistem">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
