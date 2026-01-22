<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarOpen: false,
          toggle() { 
              this.darkMode = !this.darkMode; 
              localStorage.setItem('darkMode', this.darkMode);
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }"
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Sistem Manajemen Yala Computer' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-tech { font-family: 'Exo 2', sans-serif; }
        
        /* Glassmorphism Sidebar */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }
        .dark .glass-sidebar {
            background: rgba(15, 23, 42, 0.85);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.5); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.8); }
        
        .grid-pattern {
            background-size: 20px 20px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
        }
        .dark .grid-pattern {
             background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300 selection:bg-cyan-500 selection:text-white h-screen overflow-hidden">
    
    <!-- Background Decor -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-cyan-500/10 dark:bg-cyan-900/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-blue-600/10 dark:bg-blue-900/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="flex h-full w-full">
        
        <livewire:components.spotlight />

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 glass-sidebar transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col h-full shadow-2xl md:shadow-none flex-shrink-0">
            <!-- Brand -->
            <div class="h-24 flex items-center gap-3 px-6 border-b border-slate-100 dark:border-slate-800 flex-shrink-0">
                <div class="relative w-12 h-12">
                    <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500 to-blue-600 rounded-xl transform rotate-6 animate-pulse"></div>
                    <div class="absolute inset-0 bg-white dark:bg-slate-900 rounded-xl flex items-center justify-center border border-slate-100 dark:border-slate-700 shadow-lg">
                        <span class="font-tech font-black text-2xl text-transparent bg-clip-text bg-gradient-to-tr from-cyan-600 to-blue-600">Y</span>
                    </div>
                </div>
                <div>
                    <h1 class="font-tech font-bold text-xl text-slate-900 dark:text-white leading-none tracking-tight">{{ \App\Models\Setting::get('admin_title', 'YALA SYSTEM') }}</h1>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Management Core</p>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden ml-auto text-slate-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
                
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-4 rounded-xl text-xs font-bold uppercase tracking-wider text-cyan-700 bg-cyan-50/80 dark:bg-cyan-900/20 border border-cyan-100 dark:border-cyan-800 mb-8 hover:shadow-lg hover:shadow-cyan-500/20 transition-all group">
                    <div class="p-1.5 bg-white dark:bg-slate-800 rounded-lg group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </div>
                    <span>Storefront</span>
                    <svg class="w-4 h-4 ml-auto opacity-50 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>

                <div class="px-4 mb-2 flex items-center gap-2">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Main</span>
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                </div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group relative overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/20 dark:bg-white dark:text-slate-900 dark:shadow-white/10' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    <span class="font-medium text-sm relative z-10">Dashboard</span>
                </a>

                <div class="px-4 mb-2 mt-6 flex items-center gap-2">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inventaris</span>
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                </div>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('products.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span class="text-sm">Master Produk</span>
                </a>

                <a href="{{ route('warehouses.stock-opname') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('warehouses.stock-opname') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <span class="text-sm">Stock Opname</span>
                </a>

                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('transactions.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-sm">Riwayat Transaksi</span>
                </a>

                 <a href="{{ route('purchase-orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('purchase-orders.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    <span class="text-sm">Pembelian (PO)</span>
                </a>

                <div class="px-4 mb-2 mt-6 flex items-center gap-2">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">CRM & Service</span>
                    <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                </div>

                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('customers.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span class="text-sm">Pelanggan</span>
                </a>

                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('services.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span class="text-sm">Servis Center</span>
                </a>

                @if(auth()->user()->isAdmin())
                    <div class="px-4 mb-2 mt-6 flex items-center gap-2">
                        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Data</span>
                        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                    </div>

                    <a href="{{ route('master.categories') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('master.categories') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        <span class="text-sm">Kategori</span>
                    </a>

                    <a href="{{ route('master.suppliers') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('master.suppliers') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="text-sm">Supplier</span>
                    </a>

                    <div class="px-4 mb-2 mt-6 flex items-center gap-2">
                        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin</span>
                        <div class="h-px flex-1 bg-slate-200 dark:bg-slate-700"></div>
                    </div>

                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('activity-logs.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm">Audit Log</span>
                    </a>

                    <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('employees.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <span class="text-sm">Pegawai</span>
                    </a>

                    <a href="{{ route('finance.profit-loss') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('finance.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span class="text-sm">Keuangan</span>
                    </a>

                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('settings.*') ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-md border border-slate-100 dark:border-slate-700 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span class="text-sm">Pengaturan</span>
                    </a>
                @endif
            </nav>

            <!-- User Panel -->
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 flex-shrink-0">
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-3 flex items-center gap-3 shadow-sm">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-300">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-full min-w-0 bg-slate-50 dark:bg-slate-900 transition-all duration-300" :class="sidebarOpen ? 'md:ml-0' : ''">
            
            <!-- Topbar -->
            <header class="h-20 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 z-10 sticky top-0 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-xl font-bold font-tech text-slate-800 dark:text-white">{{ $title ?? 'Dashboard' }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search Spotlight Trigger -->
                    <button @click="$dispatch('open-spotlight')" class="hidden md:flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-full text-slate-500 dark:text-slate-400 text-sm hover:bg-white dark:hover:bg-slate-700 border border-transparent hover:border-slate-200 dark:hover:border-slate-600 shadow-sm transition-all group">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span>Cari di sistem... (Ctrl + K)</span>
                    </button>

                    <div class="h-8 w-px bg-slate-200 dark:bg-slate-700 mx-2 hidden md:block"></div>

                    <!-- Dark Mode -->
                    <button @click="toggle()" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors relative">
                        <div class="absolute inset-0 bg-slate-200 dark:bg-slate-700 rounded-full scale-0 hover:scale-100 transition-transform"></div>
                        <svg x-show="!darkMode" class="w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </button>
                </div>
            </header>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8 scroll-smooth custom-scrollbar relative">
                {{ $slot }}
            </div>

        </main>
    </div>

    <!-- Notifications -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-[100] px-6 py-4 rounded-xl shadow-2xl border flex items-center gap-3 min-w-[300px]"
         :class="type === 'success' ? 'bg-white dark:bg-slate-800 border-emerald-100 dark:border-emerald-900 text-emerald-700 dark:text-emerald-400' : 'bg-white dark:bg-slate-800 border-rose-100 dark:border-rose-900 text-rose-700 dark:text-rose-400'"
         style="display: none;">
         
         <div class="p-1 rounded-full" :class="type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/50' : 'bg-rose-100 dark:bg-rose-900/50'">
            <svg x-show="type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            <svg x-show="type === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
         </div>
         <p class="font-bold text-sm" x-text="message"></p>
    </div>

    @livewireScripts
</body>
</html>