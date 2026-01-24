<div class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans">
    
    <!-- Hero Banner -->
    <div class="relative py-24 md:py-32 overflow-hidden bg-slate-900">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-900/20 to-blue-900/20"></div>
            <div class="cyber-grid opacity-30"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-black font-tech text-white mb-6 uppercase tracking-tighter">
                Future <span class="text-cyan-500">Tech</span> Store
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Membangun masa depan komputasi dengan hardware terbaik dan layanan profesional.
            </p>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="container mx-auto px-4 lg:px-8 py-20">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
            <div class="order-2 md:order-1">
                <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-6">Siapa Kami?</h2>
                <div class="prose dark:prose-invert text-slate-600 dark:text-slate-400">
                    <p class="text-lg leading-relaxed mb-4">
                        {{ $storeName }} adalah pusat belanja teknologi terdepan yang berdedikasi untuk menyediakan komponen PC high-end, laptop gaming, dan perlengkapan IT profesional.
                    </p>
                    <p>
                        Berdiri sejak tahun 2024, kami telah melayani ribuan gamers, content creator, dan profesional di seluruh Indonesia. Komitmen kami bukan hanya menjual produk, tetapi memberikan solusi komputasi yang tepat guna.
                    </p>
                </div>
            </div>
            <div class="order-1 md:order-2 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500 to-blue-500 rounded-3xl blur-3xl opacity-20 transform rotate-6"></div>
                <div class="relative bg-slate-800 rounded-3xl p-1 overflow-hidden shadow-2xl transform -rotate-2 hover:rotate-0 transition-all duration-500">
                    <div class="bg-slate-900 rounded-[20px] h-64 md:h-80 flex items-center justify-center">
                        <span class="font-tech font-black text-6xl text-slate-800 dark:text-slate-700">YALA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 text-center">
                <div class="w-16 h-16 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Original & Resmi</h3>
                <p class="text-slate-500">Jaminan 100% produk original dengan garansi resmi distributor Indonesia.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 text-center">
                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Rakit Profesional</h3>
                <p class="text-slate-500">Teknisi berpengalaman dengan standar cable management yang rapi dan presisi.</p>
            </div>
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 text-center">
                <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Support 24/7</h3>
                <p class="text-slate-500">Layanan purna jual yang responsif dan siap membantu masalah teknis Anda.</p>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-indigo-600 rounded-3xl p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-black text-white mb-6">Siap Membangun PC Impianmu?</h2>
                <a href="{{ route('pc-builder') }}" class="inline-block px-10 py-4 bg-white text-indigo-600 font-black uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all transform hover:-translate-y-1 shadow-xl">
                    Mulai Rakit Sekarang
                </a>
            </div>
        </div>

    </div>
</div>