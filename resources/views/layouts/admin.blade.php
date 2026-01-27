<!DOCTYPE html>
<html lang="id" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          sidebarTerbuka: false,
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    
        <title>{{ $title ?? 'Dasbor' }} - Admin Yala Computer</title>
    <link rel="icon" href="{{ \App\Models\Setting::get('store_favicon') ? asset('storage/' . \App\Models\Setting::get('store_favicon')) : asset('favicon.ico') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-tech { font-family: 'Exo 2', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.5); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.8); }

        /* Admin V3 Background */
        .admin-grid { 
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -10; 
            pointer-events: none;
        }
        .dark .admin-grid {
            background-color: #0f172a; 
            background-image: 
                linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px), 
                linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px); 
            background-size: 40px 40px;
        }
        .dark .admin-grid::after {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.05) 0%, transparent 60%);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300 selection:bg-indigo-500 selection:text-white h-screen overflow-hidden font-sans">
    
    <livewire:security.alert-overlay />

    <div class="admin-grid"></div>

    <!-- Mobile Backdrop -->
    <div x-show="sidebarTerbuka" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-30 md:hidden"
         @click="sidebarTerbuka = false"></div>

    <div class="flex h-full w-full">
        <!-- Global Command Palette -->
        <livewire:components.pencarian-pintar />

        <!-- Sidebar Component -->
        <x-layouts.sidebar />

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-full min-w-0 bg-slate-50 dark:bg-slate-950 transition-all duration-300 relative md:ml-72">
            
            <!-- Topbar (Sticky) -->
            <header class="h-16 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800/50 z-30 sticky top-0 flex-shrink-0 transition-colors shadow-sm">
                
                <!-- Left: Mobile Toggle & Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarTerbuka = !sidebarTerbuka" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    
                    <div class="hidden md:flex flex-col">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white leading-tight flex items-center gap-2">
                            {{ $title ?? 'Dashboard' }}
                        </h2>
                        <div class="text-xs text-slate-400 font-medium">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </div>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-3">
                    <button @click="$dispatch('open-spotlight')" class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-md text-slate-500 dark:text-slate-400 text-xs font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-transparent hover:border-slate-300 dark:hover:border-slate-600 group">
                        <svg class="w-4 h-4 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span>Cari (Ctrl + K)</span>
                    </button>

                    <button @click="$dispatch('open-spotlight')" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors">
                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                    <livewire:components.admin-notification />

                    <button @click="toggle()" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-800 rounded-full transition-colors relative" title="Ganti Mode Gelap/Terang">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 scroll-smooth custom-scrollbar p-6 md:p-8">
                <div class="max-w-7xl mx-auto w-full">
                    <!-- Flash Messages -->
                    @if (session()->has('success'))
                        <div class="mb-6 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3 shadow-sm"
                             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-6 p-4 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center gap-3 shadow-sm"
                             x-data="{ show: true }" x-show="show">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="font-medium">{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto hover:bg-rose-100 dark:hover:bg-rose-800 rounded p-1"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
                
                <footer class="max-w-7xl mx-auto w-full mt-12 py-6 border-t border-slate-200 dark:border-slate-800 text-center md:text-left flex flex-col md:flex-row justify-between items-center text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} Yala Computer. Hak Cipta Dilindungi.</p>
                    <p>Sistem v2.5.0 (Versi Perusahaan)</p>
                </footer>
            </div>
        </main>
    </div>

    <x-notification />

    @livewireScripts
    @stack('scripts')
</body>
</html>