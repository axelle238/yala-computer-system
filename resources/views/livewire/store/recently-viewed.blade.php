@if($products->count() > 0)
    <div class="py-12 border-t border-white/5">
        <div class="container mx-auto px-4 lg:px-8">
            <h3 class="text-xl font-bold text-slate-400 mb-6 uppercase tracking-widest text-xs">Terakhir Dilihat</h3>
            <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                @foreach($products as $product)
                    <a href="{{ route('toko.produk.detail', $product->id) }}" class="min-w-[160px] group">
                        <div class="aspect-square bg-slate-900 rounded-xl mb-3 flex items-center justify-center p-4 border border-white/5 group-hover:border-cyan-500/30 transition-all relative overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                            @else
                                <svg class="w-8 h-8 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                        </div>
                        <h4 class="text-sm font-bold text-slate-300 group-hover:text-cyan-400 truncate transition-colors">{{ $product->name }}</h4>
                        <p class="text-xs text-slate-500 font-mono">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif