<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Yala Computer - Login' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-900 text-white antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-cyan-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="w-full max-w-md bg-slate-800/50 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl relative z-10">
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-3xl font-black font-tech text-white uppercase tracking-tight">YALA COMPUTER</h1>
                    <p class="text-[10px] font-bold text-cyan-400 uppercase tracking-[0.3em]">Cyberpunk Enterprise</p>
                </a>
            </div>

            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>
</html>
