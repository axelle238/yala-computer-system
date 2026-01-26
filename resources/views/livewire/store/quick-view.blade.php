<div>
    @if($isOpen && $product)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] transition-opacity" wire:click="close"></div>

        <!-- Modal -->
        <div class="fixed inset-0 z-[101] flex items-center justify-center p-4">
            <div class="bg-slate-900 w-full max-w-4xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden relative animate-fade-in-up flex flex-col md:flex-row">
                <button wire:click="close" class="absolute top-4 right-4 text-slate-400 hover:text-white z-10 p-2 bg-slate-800/50 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <!-- Image -->
                <div class="md:w-1/2 bg-slate-950/50 p-8 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="max-w-full max-h-[300px] object-contain drop-shadow-2xl">
                    @else
                        <svg class="w-24 h-24 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    @endif
                </div>

                <!-- Info -->
                <div class="md:w-1/2 p-8 flex flex-col">
                    <span class="text-cyan-500 font-bold text-xs uppercase tracking-widest mb-2">{{ $product->category->name }}</span>
                    <h2 class="text-2xl font-black font-tech text-white leading-tight mb-4">{{ $product->name }}</h2>
                    
                    <div class="text-3xl font-bold text-white font-mono mb-6">
                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                    </div>

                    <div class="prose prose-invert prose-sm text-slate-400 mb-8 line-clamp-4">
                        {{ strip_tags($product->description) }}
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('toko.produk.detail', $product->id) }}" class="block w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl text-center transition-all shadow-lg shadow-cyan-500/20">
                            Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>