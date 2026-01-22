<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Yala Computer - Toko Komputer Terlengkap' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 font-['Plus_Jakarta_Sans'] antialiased selection:bg-blue-600 selection:text-white">
    
    <!-- Navbar (Sticky & Glassmorphism) -->
    <header class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" wire:navigate class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        <span class="font-extrabold text-xl">Y</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl text-slate-900 tracking-tight leading-none">YALA</span>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest leading-none">Computer</span>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Katalog</a>
                    <a href="{{ route('pc-builder') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Rakit PC</a>
                    <a href="{{ route('warranty-check') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Cek Garansi</a>
                    <a href="#services" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Layanan</a>
                    <a href="#footer" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Kontak</a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-slate-600 hover:bg-slate-100 rounded-full transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute top-1 right-0 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                    
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white transition-all duration-200 bg-slate-900 rounded-full hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-600/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900">
                        Masuk Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer id="footer" class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">Y</div>
                        <span class="font-bold text-xl text-slate-900">Yala Computer</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed max-w-sm">
                        Penyedia solusi teknologi terdepan. Kami menyediakan hardware komputer berkualitas tinggi, layanan rakit PC, dan konsultasi IT untuk kebutuhan personal maupun bisnis.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-blue-600">Penjualan Hardware</a></li>
                        <li><a href="#" class="hover:text-blue-600">Rakit PC Gaming</a></li>
                        <li><a href="#" class="hover:text-blue-600">Servis & Maintenance</a></li>
                        <li><a href="#" class="hover:text-blue-600">Instalasi Jaringan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            +62 812 3456 7890
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Jl. Teknologi No. 88, Jakarta
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-400">© 2026 Yala Computer System. All rights reserved.</p>
                <div class="flex gap-4">
                    <!-- Social Icons placeholder -->
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Help -->
    <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number', '6281234567890') }}" target="_blank" class="fixed bottom-8 left-8 z-50 bg-emerald-500 hover:bg-emerald-600 text-white p-4 rounded-full shadow-2xl shadow-emerald-500/40 transition-all transform hover:scale-110 group flex items-center gap-3 pr-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        <span class="font-bold hidden group-hover:block transition-all duration-300">Hubungi Kami</span>
    </a>

    @livewireScripts
</body>
</html>
