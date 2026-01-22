<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Yala Computer - Future Tech Store' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Custom Animations & Utilities */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6, .font-tech {
            font-family: 'Exo 2', sans-serif;
        }

        /* Animated Mesh Gradient Background */
        .mesh-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -10;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0) 0, transparent 50%);
        }
        
        .mesh-blob {
            position: absolute;
            filter: blur(80px);
            opacity: 0.6;
            animation: blob-bounce 10s infinite ease-in-out alternate;
        }
        .blob-1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: #e0f2fe; animation-delay: 0s; }
        .blob-2 { bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: #f0f9ff; animation-delay: 2s; }
        .blob-3 { top: 40%; left: 40%; width: 30vw; height: 30vw; background: #eff6ff; animation-delay: 4s; }

        @keyframes blob-bounce {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(20px, -20px) scale(1.1); }
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Scroll Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Tech UI Utilities */
        .tech-border {
            position: relative;
            background: white;
            clip-path: polygon(
                0 0, 
                100% 0, 
                100% calc(100% - 20px), 
                calc(100% - 20px) 100%, 
                0 100%
            );
        }
        .tech-border::before {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, transparent 50%, rgba(15, 23, 42, 0.1) 50%);
            z-index: 10;
        }
        
        .grid-pattern {
            background-size: 20px 20px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-cyan-500 selection:text-white"
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
    
    <!-- Background Animation -->
    <div class="mesh-bg">
        <div class="mesh-blob blob-1"></div>
        <div class="mesh-blob blob-2"></div>
        <div class="mesh-blob blob-3"></div>
    </div>

    <!-- Futuristic Navbar -->
    <header 
        x-data="{ scrolled: false }" 
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'py-2 bg-white/80 shadow-lg border-b border-white/50': scrolled, 'py-6 bg-transparent border-transparent': !scrolled }"
        class="fixed top-0 w-full z-50 transition-all duration-500 backdrop-blur-md"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <div class="relative w-10 h-10">
                        <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500 to-blue-600 rounded-xl transform rotate-6 group-hover:rotate-12 transition-transform duration-300"></div>
                        <div class="absolute inset-0 bg-white rounded-xl flex items-center justify-center border border-slate-100 transform -rotate-3 group-hover:rotate-0 transition-transform duration-300">
                            <span class="font-tech font-black text-xl text-transparent bg-clip-text bg-gradient-to-tr from-cyan-600 to-blue-600">Y</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-tech font-bold text-xl text-slate-900 tracking-tight leading-none">YALA<span class="text-cyan-600">.ID</span></span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] leading-none mt-0.5">Future Tech Store</span>
                    </div>
                </a>

                <!-- Desktop Nav (Floating Pill) -->
                <nav class="hidden md:flex items-center gap-1 bg-white/50 border border-white/50 rounded-full px-2 py-1.5 shadow-sm backdrop-blur-sm">
                    @foreach(['Katalog', 'Rakit PC', 'Cek Garansi', 'Layanan'] as $menu)
                        @php 
                            $route = match($menu) {
                                'Katalog' => route('home'),
                                'Rakit PC' => route('pc-builder'),
                                'Cek Garansi' => route('warranty-check'),
                                default => '#services'
                            };
                            $active = request()->url() == $route;
                        @endphp
                        <a href="{{ $route }}" class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 {{ $active ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-cyan-600 hover:bg-white' }}">
                            {{ $menu }}
                        </a>
                    @endforeach
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <button class="p-3 bg-white/50 hover:bg-white text-slate-600 hover:text-cyan-600 rounded-full border border-white shadow-sm transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                    
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-300 bg-slate-900 rounded-full hover:bg-cyan-600 hover:shadow-lg hover:shadow-cyan-500/30 hover:-translate-y-0.5">
                        Admin Area
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-24 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Futuristic Footer -->
    <footer id="footer" class="bg-slate-50 relative pt-24 pb-12 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-tech font-bold">Y</div>
                        <span class="font-tech text-2xl font-bold text-slate-900">Yala Computer</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                        Menyediakan hardware PC High-End dan layanan rakitan profesional dengan standar internasional. Partner resmi brand teknologi terkemuka.
                    </p>
                </div>
                <!-- ... Footer Links same as before but styled ... -->
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-8 border-t border-slate-200">
                <p class="text-xs text-slate-400">© 2026 Yala Computer System. Built with Gemini AI.</p>
                <div class="flex gap-4 opacity-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp (Redesigned) -->
    <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', '6281234567890') }}" target="_blank" class="fixed bottom-8 left-8 z-[100] group">
        <div class="absolute inset-0 bg-emerald-500 rounded-full animate-ping opacity-20"></div>
        <div class="relative bg-white hover:bg-emerald-50 text-emerald-600 p-3 rounded-full shadow-xl border border-emerald-100 flex items-center gap-3 transition-all duration-300 pr-5 group-hover:pr-6">
            <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold tracking-wider opacity-60">Butuh Bantuan?</span>
                <span class="text-sm font-bold leading-none">Chat Admin</span>
            </div>
        </div>
    </a>

    @livewireScripts

    <script>
        // Ripple Effect
        document.addEventListener('click', function (e) {
            if (e.target.closest('.ripple')) {
                const button = e.target.closest('.ripple');
                const circle = document.createElement('span');
                const diameter = Math.max(button.clientWidth, button.clientHeight);
                const radius = diameter / 2;

                circle.style.width = circle.style.height = `${diameter}px`;
                circle.style.left = `${e.clientX - button.getBoundingClientRect().left - radius}px`;
                circle.style.top = `${e.clientY - button.getBoundingClientRect().top - radius}px`;
                circle.classList.add('ripple-circle');

                const ripple = button.getElementsByClassName('ripple-circle')[0];
                if (ripple) {
                    ripple.remove();
                }

                button.appendChild(circle);
            }
        });
    </script>
    <style>
        .ripple { position: relative; overflow: hidden; }
        .ripple-circle {
            position: absolute;
            border-radius: 50%;
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            background-color: rgba(255, 255, 255, 0.7);
        }
        @keyframes ripple-animation {
            to { transform: scale(4); opacity: 0; }
        }
    </style>
</body>
</html>