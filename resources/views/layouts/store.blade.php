<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Yala Computer' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .font-tech { font-family: 'Exo 2', sans-serif; }
        .cyber-grid { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -10; background-color: #020617; background-image: linear-gradient(rgba(6, 182, 212, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(6, 182, 212, 0.05) 1px, transparent 1px); background-size: 50px 50px; }
    </style>
</head>
<body class="antialiased">
    <div class="cyber-grid"></div>
    <main class="min-h-screen">
        {{ $slot }}
    </main>
    @livewireScripts
</body>
</html>