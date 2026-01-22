<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yala Computer System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 font-['Plus_Jakarta_Sans'] antialiased text-slate-800">
    <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-50"></div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl shadow-slate-200/50 overflow-hidden sm:rounded-2xl border border-white/50 relative z-10">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
</body>
</html>
