<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Sistem Manajemen Yala Computer' }}</title>
    
    <!-- Fonts: Plus Jakarta Sans untuk tampilan modern & profesional -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 font-['Plus_Jakarta_Sans'] antialiased selection:bg-blue-600 selection:text-white pb-20 md:pb-0">
    
    <!-- Background Accents (Nuansa Teknologi Bersih) -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-0 right-0 w-[800px] h-[600px] bg-blue-100/40 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-indigo-100/40 rounded-full blur-3xl opacity-50"></div>
    </div>

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <livewire:components.spotlight />

        <!-- Sidebar Desktop (Glassmorphism Light) -->
        <aside class="hidden md:flex flex-col w-72 bg-white/80 backdrop-blur-xl border-r border-slate-200 h-screen sticky top-0 shadow-sm z-20">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-tight text-slate-900 leading-tight">YALA <span class="text-blue-600">SYSTEM</span></h1>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Inventory Intelligence</p>
                </div>
            </div>
            
                        <nav class="flex-1 px-4 space-y-1.5 mt-2">
            
                            <!-- Link ke Toko Publik -->
            
                            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-emerald-600 bg-emerald-50 hover:bg-emerald-100 transition-all font-semibold mb-4 border border-emerald-100">
            
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            
                                </svg>
            
                                <span>Lihat Toko</span>
            
                            </a>
            
            
            
                            <!-- Menu: Beranda -->
            
                            @if(auth()->user()->hasPermission('view_dashboard'))
            
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            
                                </svg>
            
                                <span>Beranda</span>
            
                            </a>
            
                            @endif
            
                            
            
                            <div class="pt-4 pb-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Master Data</div>
            
            
            
                            <!-- Menu: Produk -->
            
                            @if(auth()->user()->hasPermission('view_products'))
            
                            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('products.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('products.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            
                                </svg>
            
                                <span>Produk & Stok</span>
            
                            </a>
            
                            @endif
            
            
            
                            <!-- Menu: Transaksi -->
            
                            @if(auth()->user()->hasPermission('view_reports'))
            
                            <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('transactions.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('transactions.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            
                                </svg>
            
                                                    <span>Laporan Transaksi</span>
            
                                                </a>
            
                                                @endif
            
                                
            
                                                <!-- Menu: Servis -->
            
                                                @if(auth()->user()->hasPermission('view_reports') || auth()->user()->isAdmin()) 
            
                                                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('services.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                                                   <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('services.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            
                                                    </svg>
            
                                                    <span>Pusat Servis</span>
            
                                                </a>
            
                                                @endif
            
                                
            
                                                @if(auth()->user()->isAdmin())
            
                                
            
            
            
                                            <div class="pt-4 pb-2 px-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Administrator</div>
            
            
            
                            
            
            
            
                                            <a href="{{ route('banners.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('banners.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
            
            
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('banners.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
            
            
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            
            
            
                                                </svg>
            
            
            
                                                <span>Banner Promo</span>
            
            
            
                                            </a>
            
            
            
                            
            
            
            
                                            <a href="{{ route('employees.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('employees.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
            
            
                            
            
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('employees.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            
                                </svg>
            
                                                    <span>Manajemen Pegawai</span>
            
                                                </a>
            
                                
            
                                                <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('activity-logs.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('activity-logs.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            
                                                    </svg>
            
                                                    <span>Audit Log</span>
            
                                                </a>
            
                                
            
                                                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-700 font-semibold shadow-sm ring-1 ring-blue-100' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
            
                                
            
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-colors {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            
                                </svg>
            
                                <span>Pengaturan</span>
            
                            </a>
            
                            @endif
            
                        </nav>
            
            

            <!-- User Profile di Sidebar -->
            <div class="p-4 border-t border-slate-100 mt-auto">
                <form method="POST" action="{{ route('logout') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors w-full" onclick="this.closest('form').submit()">
                    @csrf
                    <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-slate-600 font-bold uppercase">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden text-left">
                        <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate capitalize">{{ auth()->user()->role }} - Keluar</p>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto h-screen p-4 md:p-8 scroll-smooth">
            <!-- Mobile Header (Hanya terlihat di HP) -->
            <div class="md:hidden flex items-center justify-between mb-6 sticky top-0 bg-slate-50/90 backdrop-blur-md z-10 py-2">
                <div class="flex items-center gap-2">
                     <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <span class="font-bold text-lg">Y</span>
                     </div>
                     <div>
                        <span class="font-bold text-lg text-slate-900 block leading-none">YALA</span>
                        <span class="text-[10px] text-slate-500 font-medium tracking-widest uppercase">Mobile</span>
                     </div>
                </div>
                <button class="p-2 text-slate-600 hover:bg-slate-200 rounded-full transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
            </div>

            {{ $slot }}
        </main>
    </div>

    <!-- Mobile Bottom Navigation (Modern Floating Bar) -->
    <div class="md:hidden fixed bottom-4 left-4 right-4 bg-white/90 backdrop-blur-xl border border-white/20 shadow-2xl shadow-slate-300/50 rounded-2xl flex justify-around py-4 z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ request()->routeIs('dashboard') ? 'fill-blue-100' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-bold">Beranda</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-slate-400">
             <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-600/40 -mt-8 border-4 border-slate-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <span class="text-[10px] font-bold text-blue-700">Scan</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('products.*') ? 'text-blue-600' : 'text-slate-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ request()->routeIs('products.*') ? 'fill-blue-100' : 'fill-none' }}" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
             <span class="text-[10px] font-bold">Stok</span>
        </a>
    </div>

    @livewireScripts
</body>
</html>