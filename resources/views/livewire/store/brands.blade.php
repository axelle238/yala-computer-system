<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Mitra <span class="text-cyan-500">Brand</span>
            </h1>
            <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Kami bekerja sama dengan produsen teknologi terbaik dunia untuk menghadirkan produk original dan bergaransi resmi.
            </p>
        </div>

        @if($brands->isEmpty())
            <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700">
                <p class="text-slate-500">Belum ada data brand.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 animate-fade-in-up delay-100">
                @foreach($brands as $brand)
                    <a href="{{ route('toko.katalog', ['search' => $brand->name]) }}" class="group bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-xl hover:border-cyan-500/50 transition-all flex flex-col items-center justify-center text-center h-48 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 text-2xl font-black text-slate-400 group-hover:text-cyan-500 transition-colors">
                            {{ substr($brand->name, 0, 1) }}
                        </div>
                        
                        <h3 class="font-bold text-slate-800 dark:text-white group-hover:text-cyan-500 transition-colors">{{ $brand->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $brand->products_count }} Produk</p>
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>