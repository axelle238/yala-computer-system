<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Yala Computer - Toko Komputer & Rakit PC Terbaik Jakarta' }}</title>
    <meta name="description" content="{{ $description ?? 'Pusat belanja komputer, laptop, dan jasa rakit PC murah terbaik di Jakarta.' }}">
    <meta name="keywords" content="Beli Komputer, Rakit PC Jakarta, Laptop Murah Jakarta, Toko Komputer Terbaik, Yala Computer">
    
    <!-- Dynamic Meta Tags -->
    @stack('meta')

    <link rel="icon" href="{{ \App\Models\Setting::get('store_favicon') ? asset('storage/' . \App\Models\Setting::get('store_favicon')) : asset('favicon.ico') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Midtrans Snap -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .font-tech { font-family: 'Exo 2', sans-serif; }
        .cyber-grid { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -10; background-color: #020617; background-image: linear-gradient(rgba(6, 182, 212, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(6, 182, 212, 0.05) 1px, transparent 1px); background-size: 50px 50px; }
        .cyber-grid::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 50% 50%, transparent 0%, #020617 90%); }
        .tech-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); position: relative; overflow: hidden; }
        
        /* Modern Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="antialiased selection:bg-cyan-500 selection:text-white" x-data="{ mobileMenuOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="cyber-grid"></div>

    <!-- Announcement Bar -->
    @php $announcement = \App\Models\Setting::get('store_announcement_active', false); @endphp
    @if($announcement)
        <div class="bg-gradient-to-r from-cyan-900 via-blue-900 to-purple-900 text-white text-[10px] font-bold py-2 text-center uppercase tracking-widest border-b border-cyan-500/20 relative z-50">
            {{ \App\Models\Setting::get('store_announcement_text') }}
        </div>
    @endif

    <!-- Modern Header -->
    <header class="fixed top-0 w-full z-40 transition-all duration-300 border-b border-transparent"
            :class="{ 'bg-slate-900/80 backdrop-blur-md border-white/10 shadow-lg top-0': scrolled, 'bg-transparent top-0': !scrolled }">
        <div class="max-w-7xl mx-auto px-4 h-20 flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="group flex items-center gap-3">
                @if(\App\Models\Setting::get('store_logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}" class="w-10 h-10 object-contain group-hover:scale-110 transition-transform duration-300">
                @else
                    <div class="relative w-10 h-10">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg transform rotate-6 group-hover:rotate-12 transition-transform duration-300 shadow-[0_0_20px_rgba(6,182,212,0.5)]"></div>
                        <div class="absolute inset-0 bg-slate-900 rounded-lg flex items-center justify-center border border-white/20 transform -rotate-3 group-hover:rotate-0 transition-transform duration-300">
                            <span class="font-tech font-black text-xl text-cyan-400">Y</span>
                        </div>
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="font-tech font-bold text-xl text-white tracking-tight uppercase leading-none">{{ \App\Models\Setting::get('store_name', 'YALA COMPUTER') }}</span>
                    <span class="text-[8px] font-bold text-cyan-400 uppercase tracking-[0.3em] leading-none mt-1 group-hover:text-cyan-300 transition-colors">Future Tech Store</span>
                </div>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-6">
                @foreach([
                    ['label' => 'Katalog', 'route' => 'home'],
                    ['label' => 'Rakit PC', 'route' => 'pc-builder'],
                    ['label' => 'Service', 'route' => 'track-service'],
                ] as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="text-xs font-bold uppercase tracking-widest transition-colors {{ request()->routeIs($item['route'].'*') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- Search Bar (Centered) -->
            <div class="hidden md:block flex-1 px-8">
                <livewire:store.global-search />
            </div>

            <!-- Desktop Icons -->
            <div class="hidden md:flex items-center gap-4">
                
                <!-- Mini Cart (Global) -->
                <livewire:store.mini-cart />

                @auth
                    <!-- Notifications -->
                    <livewire:store.navbar.notifications />

                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm font-bold text-slate-300 hover:text-white transition-colors">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-slate-900 border border-white/10 rounded-xl shadow-xl overflow-hidden hidden group-hover:block">
                            <a href="{{ route('member.dashboard') }}" class="block px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors">Dashboard</a>
                            <a href="{{ route('member.profile') }}" class="block px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors">Pengaturan Profil</a>
                            <a href="{{ route('member.orders') }}" class="block px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors">Riwayat Pesanan</a>
                            <a href="{{ route('member.referrals') }}" class="block px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors">Referral & Cuan</a>
                            <div class="border-t border-white/10"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-rose-500 hover:bg-rose-500/10 transition-colors">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="text-sm font-bold text-slate-400 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('customer.register') }}" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white rounded-full text-xs font-bold transition-all shadow-lg shadow-cyan-500/20">Register</a>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 md:hidden hover:bg-white/10 hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-slate-900/95 backdrop-blur-xl border-b border-white/10 absolute w-full"
             style="display: none;">
            <div class="px-4 pt-4 pb-6 space-y-2">
                @foreach([
                    ['label' => 'Katalog Produk', 'route' => 'home'],
                    ['label' => 'Simulasi Rakit PC', 'route' => 'pc-builder'],
                    ['label' => 'Berita & Artikel', 'route' => 'news.index'],
                    ['label' => 'Cek Status Garansi', 'route' => 'warranty-check']
                ] as $item)
                    <a href="{{ route($item['route']) }}" class="block px-4 py-3 rounded-xl text-sm font-bold {{ request()->routeIs($item['route'].'*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    <main class="pt-28 min-h-screen relative z-10">
        {{ $slot }}
    </main>

    <!-- Newsletter Section -->
    <div class="max-w-7xl mx-auto px-4 mt-20">
        <livewire:front.newsletter />
    </div>

    <footer class="bg-slate-950 border-t border-white/10 py-16 relative overflow-hidden mt-12">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-12">
                <div class="text-center md:text-left">
                    <h3 class="font-tech text-white font-bold text-2xl uppercase tracking-widest mb-2">{{ \App\Models\Setting::get('store_name', 'YALA COMPUTER') }}</h3>
                    <p class="text-xs text-slate-500 max-w-xs">Building the future, one PC at a time. Enterprise grade hardware for professionals and gamers.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-6 text-xs text-slate-400 font-bold uppercase tracking-widest">
                    <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Katalog</a>
                    <a href="{{ route('track-order') }}" class="hover:text-cyan-400 transition-colors">Lacak Pesanan</a>
                    <a href="{{ route('news.index') }}" class="hover:text-cyan-400 transition-colors">Berita</a>
                    <a href="{{ route('privacy-policy') }}" class="hover:text-cyan-400 transition-colors">Privacy</a>
                    <a href="{{ route('terms-of-service') }}" class="hover:text-cyan-400 transition-colors">Terms</a>
                </div>
            </div>
            <div class="text-center border-t border-white/5 pt-8">
                <p class="text-[10px] text-slate-600 uppercase tracking-widest">© {{ date('Y') }} Yala Computer System. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <x-notification />
    <livewire:store.chat-widget />
    <livewire:store.quick-view />
    @livewireScripts

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('trigger-payment', (event) => {
                const token = event.token; // Access first param
                const orderId = event.orderId;

                snap.pay(token, {
                    onSuccess: function(result) {
                        window.location.href = "/order-success/" + orderId;
                    },
                    onPending: function(result) {
                        window.location.href = "/order-success/" + orderId;
                    },
                    onError: function(result) {
                        alert("Pembayaran Gagal!");
                    },
                    onClose: function() {
                        alert('Anda menutup popup pembayaran.');
                    }
                });
            });
        });
    </script>
</body>
</html>
