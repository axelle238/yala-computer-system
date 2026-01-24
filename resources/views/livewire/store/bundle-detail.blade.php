<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-xl border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row">
            <div class="md:w-1/2 p-8 bg-slate-100 dark:bg-slate-900 flex items-center justify-center">
                @if($bundle->image_path)
                    <img src="{{ asset('storage/' . $bundle->image_path) }}" class="max-w-full rounded-xl shadow-lg">
                @else
                    <div class="w-64 h-64 bg-slate-200 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-400">
                        <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                @endif
            </div>
            <div class="md:w-1/2 p-8 md:p-12">
                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold uppercase tracking-wider mb-4">Special Bundle</span>
                <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4">{{ $bundle->name }}</h1>
                <p class="text-slate-500 mb-8">{{ $bundle->description }}</p>

                <div class="space-y-4 mb-8">
                    <h3 class="font-bold text-gray-800 dark:text-white">Isi Paket:</h3>
                    @foreach($bundle->items as $item)
                        <div class="flex items-center gap-4 p-3 border border-slate-200 dark:border-slate-700 rounded-xl">
                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</p>
                                <p class="text-xs text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between border-t border-slate-200 dark:border-slate-700 pt-8">
                    <div>
                        <p class="text-sm text-slate-500 line-through">Rp {{ number_format($bundle->items->sum(fn($i) => $i->product->sell_price * $i->quantity), 0, ',', '.') }}</p>
                        <p class="text-3xl font-black text-purple-600">Rp {{ number_format($bundle->price, 0, ',', '.') }}</p>
                    </div>
                    <button wire:click="addToCart" class="px-8 py-4 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transition-all">
                        Beli Bundle
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>