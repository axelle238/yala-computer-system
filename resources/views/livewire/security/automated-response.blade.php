<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight mb-2">
            Automated Threat <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-500">Mitigation</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto">
            Konfigurasi respon otomatis sistem terhadap vektor serangan yang terdeteksi.
        </p>
    </div>

    <!-- GLOBAL WAF RULES -->
    <h3 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 pb-2">Global WAF Rules</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Brute Force -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Anti Brute Force</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Otomatis memblokir IP setelah 5x percobaan login gagal dalam 1 menit.
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['brute_force'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['brute_force'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('brute_force')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['brute_force'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['brute_force'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- SQL Injection -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">SQL Injection Shield</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Mendeteksi dan sanitasi input berbahaya pada parameter URL dan Form.
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['sqli'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['sqli'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('sqli')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['sqli'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['sqli'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- XSS Filter -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">XSS Filter</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Membersihkan output HTML dari skrip berbahaya (Cross-Site Scripting).
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['xss'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['xss'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('xss')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['xss'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['xss'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- STOREFRONT PROTECTION -->
    <h3 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 pb-2 mt-8">StoreFront (E-Commerce) Protection</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Bad Bot -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Anti-Scraper Bot</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Memblokir bot scraping yang mengambil data harga/produk.
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['bad_bot'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['bad_bot'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('bad_bot')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['bad_bot'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['bad_bot'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Login Protection -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center text-teal-600 dark:text-teal-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Customer Login Shield</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Rate limiting ketat untuk halaman login pelanggan (3x/menit).
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['storefront_login'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['storefront_login'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('storefront_login')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['storefront_login'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['storefront_login'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Checkout Fraud -->
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600 dark:text-rose-400 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2">Checkout Fraud Check</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 min-h-[40px]">
                    Analisis pola pesanan untuk mencegah penipuan kartu kredit/bot.
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest {{ $rules['storefront_checkout'] ? 'text-emerald-500' : 'text-slate-400' }}">
                        {{ $rules['storefront_checkout'] ? 'Active' : 'Inactive' }}
                    </span>
                    <button wire:click="toggleRule('storefront_checkout')" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $rules['storefront_checkout'] ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition {{ $rules['storefront_checkout'] ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>