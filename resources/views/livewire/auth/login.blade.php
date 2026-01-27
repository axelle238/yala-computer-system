<div>
    <div class="flex flex-col items-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 mb-4">
            <span class="font-extrabold text-xl">Y</span>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Portal Admin</h2>
        <p class="text-slate-500 text-sm">Masuk untuk mengelola inventaris Yala Computer.</p>
    </div>

    <form wire:submit="authenticate" class="space-y-6">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat Email</label>
            <div class="relative">
                <input wire:model="email" id="email" type="email" class="block w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all sm:text-sm" placeholder="admin@yala.com" autofocus autocomplete="username">
                @error('email') <span class="absolute right-3 top-3.5 text-rose-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span> @enderror
            </div>
            @error('email') <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Kata Sandi</label>
            <div class="relative">
                <input wire:model="password" id="password" type="password" class="block w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all sm:text-sm" placeholder="••••••••" autocomplete="current-password">
            </div>
            @error('password') <p class="mt-2 text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="remember" id="remember" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
            </label>
            
            <a href="/" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Kembali ke Toko</a>
        </div>

        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-600/20 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk Sistem</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            </span>
        </button>
    </form>
</div>
