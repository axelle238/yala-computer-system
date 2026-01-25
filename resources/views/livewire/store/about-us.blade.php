<div class="min-h-screen bg-slate-50 dark:bg-slate-900 overflow-hidden">
    <!-- Hero Section -->
    <div class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-cyan-500/10 rounded-full blur-[120px] animate-pulse"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-black font-tech text-white uppercase tracking-tighter mb-6 animate-fade-in-up">
                Building The <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">Future</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed animate-fade-in-up delay-100">
                Yala Computer bukan sekadar toko. Kami adalah arsitek performa yang mendedikasikan diri untuk menyediakan teknologi terbaik bagi para profesional dan gamers.
            </p>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-16 bg-white dark:bg-slate-800 border-y border-slate-200 dark:border-slate-700">
        <div class="container mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-2">
                <h3 class="text-4xl font-black text-slate-900 dark:text-white">5+</h3>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Tahun Pengalaman</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-4xl font-black text-slate-900 dark:text-white">10K+</h3>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">PC Terakit</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-4xl font-black text-slate-900 dark:text-white">50+</h3>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Brand Partner</p>
            </div>
            <div class="space-y-2">
                <h3 class="text-4xl font-black text-slate-900 dark:text-white">4.9</h3>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Rating Kepuasan</p>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <div class="py-20 container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Lebih Dari Sekadar Hardware</h2>
                <div class="prose prose-lg prose-slate dark:prose-invert">
                    <p>
                        Berdiri sejak 2020, Yala Computer bermula dari garasi kecil dengan mimpi besar: membuat komputasi performa tinggi dapat diakses oleh siapa saja.
                    </p>
                    <p>
                        Kami percaya bahwa setiap komponen memiliki cerita. Dari pemilihan prosesor hingga manajemen kabel yang presisi, tim teknisi kami memperlakukan setiap rakitan PC seperti sebuah karya seni.
                    </p>
                </div>
                <div class="flex gap-4 pt-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Garansi Resmi
                    </div>
                    <div class="flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-300">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Konsultasi Gratis
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500 to-blue-600 rounded-3xl transform rotate-3 opacity-20 blur-lg"></div>
                <div class="relative bg-slate-800 rounded-3xl overflow-hidden shadow-2xl border border-slate-700">
                    <!-- Placeholder Image -->
                    <div class="aspect-video bg-slate-900 flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="py-20 bg-slate-100 dark:bg-slate-800/50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white mb-12 uppercase tracking-wide">The <span class="text-cyan-600">Squad</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($teams as $member)
                    <div class="group bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all hover:-translate-y-2 border border-slate-200 dark:border-slate-700">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full overflow-hidden border-4 border-slate-100 dark:border-slate-700 group-hover:border-cyan-500 transition-colors">
                            <img src="{{ $member['image'] }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $member['name'] }}</h3>
                        <p class="text-sm text-cyan-600 font-bold uppercase tracking-wider mt-1">{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
