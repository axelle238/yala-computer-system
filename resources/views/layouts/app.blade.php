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
    <link rel="icon" href="{{ \App\Models\Setting::get('store_favicon') ? asset('storage/' . \App\Models\Setting::get('store_favicon')) : asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, .font-tech { font-family: 'Exo 2', sans-serif; }
        
        /* Scrollbar Halus */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.5); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.8); }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300 selection:bg-indigo-500 selection:text-white h-screen overflow-hidden font-sans">
    
    <div class="flex h-full w-full">
        
        <livewire:components.spotlight />

        <!-- Sidebar Component -->
        <x-layouts.sidebar />

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-full min-w-0 bg-slate-50 dark:bg-slate-900 transition-all duration-300 relative" 
              :class="sidebarOpen ? 'md:ml-0' : ''">
            
            <!-- Topbar (Sticky) -->
            <header class="h-16 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 z-30 sticky top-0 flex-shrink-0 transition-colors">
                
                <!-- Left: Mobile Toggle & Breadcrumb/Title -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    
                    <div class="hidden md:block">
                        <h2 class="text-lg font-bold text-slate-800 dark:text-white">{{ $title ?? 'Beranda' }}</h2>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Search Trigger -->
                    <button @click="$dispatch('open-spotlight')" class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-500 dark:text-slate-400 text-xs font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors group">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span class="hidden md:inline">Cari (Ctrl + K)</span>
                    </button>

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 mx-1"></div>

                    <!-- Notifications -->
                    <livewire:components.admin-notification />

                    <!-- Dark Mode Toggle -->
                    <button @click="toggle()" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors relative" title="Ganti Tema">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 scroll-smooth custom-scrollbar p-6 md:p-8">
                <div class="max-w-7xl mx-auto w-full">
                    {{ $slot }}
                </div>
            </div>

        </main>
    </div>

    <x-notification />

    @livewireScripts
</body>
</html>
