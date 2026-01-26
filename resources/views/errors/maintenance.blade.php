<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sedang Dalam Perbaikan - Yala Computer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Exo 2', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen flex flex-col items-center justify-center p-4 text-center overflow-hidden relative">
    
    <!-- Background Effect -->
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none"></div>
    <div class="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] bg-cyan-500/20 rounded-full blur-[120px] animate-pulse"></div>
    <div class="absolute bottom-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s"></div>

    <div class="relative z-10 max-w-2xl">
        <div class="inline-flex p-6 rounded-full bg-slate-800/50 border border-slate-700 mb-8 shadow-2xl shadow-cyan-900/50">
            <svg class="w-16 h-16 text-cyan-400 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <h1 class="text-5xl md:text-7xl font-black tracking-tighter mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">
            SYSTEM MAINTENANCE
        </h1>
        
        <p class="text-xl text-slate-400 mb-8 font-light">
            Sistem sedang dalam perbaikan atau peningkatan performa. Kami akan segera kembali dengan fitur yang lebih baik.
        </p>

        <div class="inline-flex items-center gap-3 px-6 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm font-mono text-cyan-400">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500"></span>
            </span>
            Estimated Online: Segera
        </div>
        
        <div class="mt-12">
            <a href="{{ route('masuk') }}" class="text-xs font-bold text-slate-600 hover:text-white transition-colors uppercase tracking-widest">
                Admin Access
            </a>
        </div>
    </div>

    <style>
        .animate-spin-slow { animation: spin 4s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</body>
</html>
