<aside 
    class="fixed inset-y-0 left-0 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-64 transform transition-transform duration-300 ease-in-out z-40 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    @click.away="sidebarOpen = false"
    x-cloak>
    
    <!-- Logo Header -->
    <div class="h-16 flex items-center px-6 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
            <div class="bg-indigo-600 p-1.5 rounded-lg group-hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <span class="font-tech text-xl font-bold text-slate-800 dark:text-white tracking-tight">YALA<span class="text-indigo-600">POS</span></span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden ml-auto text-slate-400 hover:text-slate-600">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <!-- Scrollable Menu -->
    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
        
        <!-- DASHBOARD -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>

        <!-- GROUP: PENJUALAN (SALES) -->
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penjualan (Front Office)</div>
        
        <a href="{{ route('sales.pos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('sales.pos') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            Kasir (POS)
        </a>

        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('orders.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            Riwayat Order
        </a>

        <!-- GROUP: SERVIS & RMA -->
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Servis & Garansi</div>

        <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('services.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            List Servis
        </a>
        <a href="{{ route('services.kanban') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('services.kanban') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
            Kanban Board
        </a>
        <a href="{{ route('rma.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('rma.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            RMA (Retur)
        </a>

        <!-- GROUP: KEUANGAN -->
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keuangan</div>

        <a href="{{ route('finance.cash-register') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('finance.cash-register') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            Kasir & Shift
        </a>
        <a href="{{ route('reports.finance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('reports.finance') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            Laporan Keuangan
        </a>

        <!-- GROUP: GUDANG -->
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Logistik & Gudang</div>

        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('products.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            Produk & Stok
        </a>
        <a href="{{ route('purchase-orders.receive') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('purchase-orders.receive') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            Penerimaan Barang
        </a>
        <a href="{{ route('warehouses.stock-opname') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('warehouses.stock-opname') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Stock Opname
        </a>
        <a href="{{ route('products.labels') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('products.labels') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            Cetak Label
        </a>

        <!-- GROUP: HR & ADMIN -->
        <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administrasi</div>

        <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('customers.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Pelanggan (CRM)
        </a>
        <a href="{{ route('employees.attendance') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('employees.attendance') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Absensi
        </a>
        <a href="{{ route('employees.payroll-manager') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('employees.payroll-manager') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            Penggajian
        </a>
        <a href="{{ route('assets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('assets.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            Aset Kantor
        </a>
        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold transition-all {{ request()->routeIs('settings.index') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Pengaturan
        </a>

        <!-- LOGOUT -->
        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Keluar
                </button>
            </form>
        </div>

    </div>
</aside>