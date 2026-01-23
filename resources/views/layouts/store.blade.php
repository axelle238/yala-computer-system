<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? \App\Models\Setting::get('store_name', 'Yala Computer') . ' - ' . \App\Models\Setting::get('hero_title', 'Future Tech Store') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@100;300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc;
        }
        
        h1, h2, h3, h4, h5, h6, .font-tech {
            font-family: 'Exo 2', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Cyberpunk Grid Background */
        .cyber-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -10;
            background-color: #020617; /* Slate 950 */
            background-image: 
                linear-gradient(rgba(6, 182, 212, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(6, 182, 212, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            perspective: 500px;
        }
        
        .cyber-grid::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, transparent 0%, #020617 90%);
        }

        /* Ambient Glows */
        .glow-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: pulse-glow 8s infinite alternate;
        }
        .glow-1 { top: -10%; left: -10%; width: 40vw; height: 40vw; background: #0891b2; /* Cyan 600 */ }
        .glow-2 { bottom: -10%; right: -10%; width: 40vw; height: 40vw; background: #4f46e5; /* Indigo 600 */ }
        .glow-3 { top: 40%; left: 30%; width: 20vw; height: 20vw; background: #c026d3; /* Fuchsia 600 */ opacity: 0.2; }

        @keyframes pulse-glow {
            0% { opacity: 0.3; transform: scale(1); }
            100% { opacity: 0.5; transform: scale(1.1); }
        }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tech Borders & Effects */
        .tech-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        .tech-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #06b6d4, transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }
        .tech-card:hover::before {
            transform: translateX(100%);
        }

        /* Glitch Text Effect (Optional) */
        .glitch-hover:hover {
            animation: glitch 0.3s cubic-bezier(.25, .46, .45, .94) both infinite;
            color: #22d3ee;
        }
        @keyframes glitch {
            0% { transform: translate(0) }
            20% { transform: translate(-2px, 2px) }
            40% { transform: translate(-2px, -2px) }
            60% { transform: translate(2px, 2px) }
            80% { transform: translate(2px, -2px) }
            100% { transform: translate(0) }
        }
    </style>
</head>
<body class="antialiased selection:bg-cyan-500 selection:text-white"
      x-data="{
          init() {
              const observer = new IntersectionObserver((entries) => {
                  entries.forEach(entry => {
                      if (entry.isIntersecting) {
                          entry.target.classList.add('active');
                      }
                  });
              }, { threshold: 0.1 });
              
              document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
          }
      }"
>
    <x-notification />
    
    <!-- Background -->
    <div class="cyber-grid">
        <div class="glow-blob glow-1"></div>
        <div class="glow-blob glow-2"></div>
        <div class="glow-blob glow-3"></div>
    </div>

    <!-- Announcement Bar -->
    @if(\App\Models\Setting::get('store_announcement_active', false))
        <div class="bg-gradient-to-r from-cyan-900 via-blue-900 to-purple-900 text-white text-xs font-bold py-2 px-4 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <span class="relative z-10 font-mono tracking-wider animate-pulse">{{ \App\Models\Setting::get('store_announcement_text') }}</span>
        </div>
    @endif

    <!-- Navbar -->
    <header 
        x-data="{ scrolled: false, mobileMenuOpen: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-slate-900/90 border-b border-white/10 shadow-lg': scrolled, 'bg-transparent border-transparent': !scrolled }"
        class="fixed top-0 w-full z-50 transition-all duration-300 backdrop-blur-md"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg transform rotate-6 group-hover:rotate-12 transition-transform duration-300 shadow-[0_0_20px_rgba(6,182,212,0.5)]"></div>
                        <div class="absolute inset-0 bg-slate-900 rounded-lg flex items-center justify-center border border-white/20 transform -rotate-3 group-hover:rotate-0 transition-transform duration-300">
                            <span class="font-tech font-black text-xl text-cyan-400">Y</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-tech font-bold text-xl text-white tracking-tight leading-none uppercase drop-shadow-md">{{ \App\Models\Setting::get('store_name', 'YALA COMPUTER') }}</span>
                        <span class="text-[9px] font-bold text-cyan-400 uppercase tracking-[0.3em] leading-none mt-1">{{ \App\Models\Setting::get('hero_title', 'FUTURE TECH') }}</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center bg-slate-800/50 border border-white/5 rounded-full px-2 py-1.5 backdrop-blur-sm">
                    @foreach([
                        ['label' => 'Katalog', 'route' => route('home')],
                        ['label' => 'Rakit PC', 'route' => route('pc-builder')],
                        ['label' => 'Cek Garansi', 'route' => route('warranty-check')],
                    ] as $item)
                        <a href="{{ $item['route'] }}" class="px-6 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ request()->url() == $item['route'] ? 'bg-cyan-500 text-slate-900 shadow-[0_0_15px_rgba(6,182,212,0.4)]' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    @if(\App\Models\Setting::get('feature_service_tracking', true))
                         <a href="{{ route('home') }}#services" class="px-6 py-2 rounded-full text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300">
                            Layanan
                        </a>
                    @endif
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-300 hover:text-white">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-slate-900 border-b border-white/10"
             @click.away="mobileMenuOpen = false"
             style="display: none;">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-bold text-white hover:bg-white/10">Katalog</a>
                <a href="{{ route('pc-builder') }}" class="block px-3 py-2 rounded-md text-base font-bold text-slate-300 hover:text-white hover:bg-white/10">Rakit PC</a>
                <a href="{{ route('warranty-check') }}" class="block px-3 py-2 rounded-md text-base font-bold text-slate-300 hover:text-white hover:bg-white/10">Cek Garansi</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-28 min-h-screen relative z-10">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-white/10 pt-20 pb-10 relative overflow-hidden mt-20">
        <!-- Footer Grid -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-tr from-cyan-600 to-blue-700 rounded-lg flex items-center justify-center text-white font-tech font-bold shadow-lg shadow-cyan-900/50">
                            {{ substr(\App\Models\Setting::get('store_name', 'Y'), 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-tech text-2xl font-bold text-white tracking-tight">{{ \App\Models\Setting::get('store_name', 'Yala Computer') }}</h3>
                            <p class="text-xs text-slate-500 uppercase tracking-widest">Enterprise IT Solutions</p>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-8">
                        {{ \App\Models\Setting::get('footer_description', 'Menyediakan hardware PC High-End dan layanan rakitan profesional dengan standar industri terbaik.') }}
                    </p>
                    
                    <!-- Socials -->
                    <div class="flex gap-4">
                        @if($fb = \App\Models\Setting::get('social_facebook')) 
                            <a href="{{ $fb }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all hover:-translate-y-1"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.962.925-1.962 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a> 
                        @endif
                        @if($ig = \App\Models\Setting::get('social_instagram')) 
                            <a href="{{ $ig }}" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-pink-600 hover:text-white transition-all hover:-translate-y-1"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a> 
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Navigasi</h4>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Katalog Produk</a></li>
                        <li><a href="{{ route('pc-builder') }}" class="hover:text-cyan-400 transition-colors">Simulasi Rakit PC</a></li>
                        <li><a href="{{ route('warranty-check') }}" class="hover:text-cyan-400 transition-colors">Cek Status Garansi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                         <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-cyan-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>{{ \App\Models\Setting::get('address', 'Jl. Teknologi No. 1, Jakarta') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span class="font-mono">{{ \App\Models\Setting::get('whatsapp_number', '6281234567890') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-8 border-t border-white/5">
                <p class="text-xs text-slate-600">© 2026 Yala Computer System. All Rights Reserved.</p>
                <div class="flex gap-2 text-xs font-bold text-slate-600 uppercase tracking-wider">
                    <span class="px-2 py-1 bg-white/5 rounded">Privacy Policy</span>
                    <span class="px-2 py-1 bg-white/5 rounded">Terms of Service</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', '6281234567890') }}" target="_blank" class="fixed bottom-8 left-8 z-[90] group">
        <div class="absolute inset-0 bg-emerald-500 rounded-full animate-ping opacity-20"></div>
        <div class="relative w-14 h-14 bg-slate-900 border border-emerald-500/50 hover:bg-emerald-600 rounded-full flex items-center justify-center text-emerald-500 hover:text-white shadow-[0_0_20px_rgba(16,185,129,0.3)] transition-all duration-300 group-hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        </div>
    </a>

    @livewireScripts
</body>
</html>