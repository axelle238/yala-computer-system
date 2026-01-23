@if($showCompareModal)
    <div class="fixed inset-0 z-[100] overflow-hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-md transition-opacity" wire:click="closeCompare"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-slate-900 text-left shadow-2xl shadow-cyan-900/20 transition-all sm:my-8 w-full max-w-5xl border border-white/10 tech-card">
                
                <div class="bg-slate-900 px-6 py-4 border-b border-white/5 flex justify-between items-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-transparent opacity-50"></div>
                    <h3 class="text-lg font-bold text-white font-tech uppercase tracking-widest relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Device Comparison
                    </h3>
                    <button wire:click="closeCompare" class="text-slate-500 hover:text-white transition-colors relative z-10">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-x-auto custom-scrollbar bg-slate-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 w-48 bg-slate-800/50 sticky left-0 z-10 font-bold text-slate-400 uppercase text-[10px] tracking-widest border-r border-white/5">Specs</th>
                                @foreach($this->compareProducts as $product)
                                    <th class="p-4 min-w-[220px] relative group border-r border-white/5 last:border-0">
                                        <button wire:click="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 text-slate-500 hover:text-rose-500 p-1 bg-slate-800 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-all z-20">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <div class="h-32 flex items-center justify-center mb-4 bg-slate-800/30 rounded-lg overflow-hidden relative">
                                             <div class="absolute inset-0 bg-gradient-to-tr from-cyan-500/5 to-transparent"></div>
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-[85%] object-contain relative z-10">
                                            @else
                                                <svg class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-white text-sm line-clamp-2 h-10 mb-2">{{ $product->name }}</h4>
                                        <p class="text-lg font-bold font-mono text-cyan-400">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        <button wire:click="addToCart({{ $product->id }})" class="mt-4 w-full py-2.5 bg-white text-slate-900 text-xs font-bold rounded-lg hover:bg-cyan-400 transition-colors uppercase tracking-wider shadow-lg shadow-white/5">
                                            Add to Cart
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 border-t border-white/5">
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 bg-slate-800/30 sticky left-0 font-bold text-slate-400 text-xs uppercase border-r border-white/5">Category</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-sm text-slate-300 border-r border-white/5 last:border-0">
                                        <span class="inline-block px-2 py-1 rounded bg-slate-800 text-xs font-medium">{{ $product->category->name }}</span>
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 bg-slate-800/30 sticky left-0 font-bold text-slate-400 text-xs uppercase border-r border-white/5">Availability</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-sm border-r border-white/5 last:border-0 {{ $product->stock_quantity > 0 ? 'text-emerald-400 font-bold' : 'text-rose-400' }}">
                                        {{ $product->stock_quantity > 0 ? 'In Stock (' . $product->stock_quantity . ')' : 'Sold Out' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 bg-slate-800/30 sticky left-0 font-bold text-slate-400 text-xs uppercase border-r border-white/5 align-top">Specs</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-xs text-slate-400 leading-relaxed align-top border-r border-white/5 last:border-0">
                                        {{ $product->description ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif