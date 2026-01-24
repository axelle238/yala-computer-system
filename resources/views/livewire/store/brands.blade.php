<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Brands</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">Mitra resmi yang menjamin kualitas produk kami.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 animate-fade-in-up delay-100">
            @foreach($brands as $brand)
                <a href="{{ route('store.catalog', ['brand' => $brand->id]) }}" class="group block bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:border-blue-500 hover:shadow-xl transition-all text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 group-hover:text-white transition-colors text-slate-400">
                        <!-- Icon Placeholder (First Letter) -->
                        <span class="font-black text-2xl">{{ substr($brand->name, 0, 1) }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">{{ $brand->name }}</h3>
                    <p class="text-xs text-slate-500">{{ $brand->products_count }} Produk</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
