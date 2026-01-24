<section class="py-12 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
    <div class="container mx-auto px-4 lg:px-8">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Terakhir Dilihat</h3>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            @foreach($products as $product)
                <a href="{{ route('product.detail', $product->id) }}" class="group block">
                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 mb-2 relative overflow-hidden transition-all group-hover:shadow-lg group-hover:-translate-y-1">
                        <div class="aspect-square flex items-center justify-center">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                            @else
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate group-hover:text-blue-600 transition-colors">{{ $product->name }}</h4>
                    <p class="text-xs font-mono text-slate-500">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
