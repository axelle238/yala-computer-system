@if($showCompareModal)
    <div class="fixed inset-0 z-[100] overflow-hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeCompare"></div>
        
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-5xl">
                
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-900">Perbandingan Produk</h3>
                    <button wire:click="closeCompare" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 w-48 bg-slate-50/50 sticky left-0 z-10 font-bold text-slate-500 uppercase text-xs tracking-wider">Spesifikasi</th>
                                @foreach($this->compareProducts as $product)
                                    <th class="p-4 min-w-[200px] relative group">
                                        <button wire:click="removeFromCompare({{ $product->id }})" class="absolute top-2 right-2 text-slate-300 hover:text-rose-500 p-1 bg-white rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <div class="h-32 flex items-center justify-center mb-4">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="max-h-full object-contain mix-blend-multiply">
                                            @else
                                                <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-slate-900 text-sm line-clamp-2 h-10">{{ $product->name }}</h4>
                                        <p class="text-lg font-black font-tech text-rose-600 mt-2">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        <button wire:click="addToCart({{ $product->id }})" class="mt-3 w-full py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-cyan-600 transition-colors">
                                            + Keranjang
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 border-t border-slate-100">
                            <tr>
                                <td class="p-4 bg-slate-50/50 sticky left-0 font-bold text-slate-600 text-sm">Kategori</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-sm text-slate-600">{{ $product->category->name }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-4 bg-slate-50/50 sticky left-0 font-bold text-slate-600 text-sm">Stok</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-sm {{ $product->stock_quantity > 0 ? 'text-emerald-600 font-bold' : 'text-rose-500' }}">
                                        {{ $product->stock_quantity > 0 ? 'Tersedia (' . $product->stock_quantity . ')' : 'Habis' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-4 bg-slate-50/50 sticky left-0 font-bold text-slate-600 text-sm align-top">Deskripsi</td>
                                @foreach($this->compareProducts as $product)
                                    <td class="p-4 text-xs text-slate-500 leading-relaxed align-top">
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
