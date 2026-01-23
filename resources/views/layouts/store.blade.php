<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Yala Computer - Toko Komputer & Rakit PC Terbaik Jakarta' }}</title>
    <meta name="description" content="Pusat belanja komputer, laptop, dan jasa rakit PC murah terbaik di Jakarta.">
    <meta name="keywords" content="Beli Komputer, Rakit PC Jakarta, Laptop Murah Jakarta, Toko Komputer Terbaik, Yala Computer">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .font-tech { font-family: 'Exo 2', sans-serif; }
        .cyber-grid { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -10; background-color: #020617; background-image: linear-gradient(rgba(6, 182, 212, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(6, 182, 212, 0.05) 1px, transparent 1px); background-size: 50px 50px; }
        .cyber-grid::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 50% 50%, transparent 0%, #020617 90%); }
    </style>
</head>
<body class="antialiased" x-data="{ mobileMenuOpen: false }">
    <div class="cyber-grid"></div>

    @if(\App\Models\Setting::get('store_announcement_active', false))
        <div class="bg-cyan-900 text-white text-[10px] font-bold py-1.5 text-center uppercase tracking-widest border-b border-cyan-500/20">
            {{ \App\Models\Setting::get('store_announcement_text') }}
        </div>
    @endif

    <header class="fixed top-0 w-full z-50 bg-slate-900/90 border-b border-white/10 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
            <a href="/" class="flex flex-col group">
                <span class="font-tech font-bold text-xl text-white tracking-tight uppercase group-hover:text-cyan-400 transition-colors">{{ \App\Models\Setting::get('store_name', 'YALA COMPUTER') }}</span>
                <span class="text-[8px] font-bold text-cyan-600 uppercase tracking-[0.3em] group-hover:text-cyan-300 transition-colors">Cyberpunk Enterprise</span>
            </a>
            
            <nav class="hidden md:flex items-center gap-1 bg-white/5 border border-white/5 rounded-full px-2 py-1">
                <a href="{{ route('home') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request()->routeIs('home') ? 'bg-cyan-500 text-slate-900' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Katalog</a>
                <a href="{{ route('pc-builder') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request()->routeIs('pc-builder') ? 'bg-cyan-500 text-slate-900' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Rakit PC</a>
                <a href="{{ route('news.index') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request()->routeIs('news.*') ? 'bg-cyan-500 text-slate-900' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Berita</a>
                <a href="{{ route('warranty-check') }}" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all {{ request()->routeIs('warranty-check') ? 'bg-cyan-500 text-slate-900' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">Garansi</a>
            </nav>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 md:hidden hover:bg-white/10 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-slate-950 border-b border-white/10" style="display: none;">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10">Katalog</a>
                <a href="{{ route('pc-builder') }}" class="block px-3 py-2 rounded-md text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10">Rakit PC</a>
                <a href="{{ route('news.index') }}" class="block px-3 py-2 rounded-md text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10">Berita</a>
                <a href="{{ route('warranty-check') }}" class="block px-3 py-2 rounded-md text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10">Garansi</a>
            </div>
        </div>
    </header>

    <main class="pt-20 min-h-screen relative z-10">
        {{ $slot }}
    </main>

    <footer class="bg-slate-950 border-t border-white/10 py-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h3 class="font-tech text-white font-bold mb-6 uppercase text-2xl tracking-widest">{{ \App\Models\Setting::get('store_name', 'YALA COMPUTER') }}</h3>
            <div class="flex flex-wrap justify-center gap-4 md:gap-8 text-xs text-slate-500 font-bold uppercase tracking-widest mb-8">
                <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Katalog</a>
                <a href="{{ route('pc-builder') }}" class="hover:text-cyan-400 transition-colors">Rakit PC</a>
                <a href="{{ route('news.index') }}" class="hover:text-cyan-400 transition-colors">Berita</a>
                <a href="{{ route('privacy-policy') }}" class="hover:text-cyan-400 transition-colors">Privacy Policy</a>
                <a href="{{ route('terms-of-service') }}" class="hover:text-cyan-400 transition-colors">Terms of Service</a>
            </div>
            <p class="text-[10px] text-slate-700 uppercase tracking-widest">© 2026 Yala Computer System. All Rights Reserved.</p>
        </div>
    </footer>

    <x-notification />
    @livewireScripts
</body>
</html>