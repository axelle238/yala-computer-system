    <!-- Comparison Floating Bar -->
    @if(count($compareList) > 0)
        <div class="fixed bottom-0 left-0 right-0 z-[80] bg-white/90 backdrop-blur-xl border-t border-white/50 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] p-4 transform transition-transform duration-300 animate-slide-up">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <span class="font-bold text-slate-800 text-sm uppercase tracking-wider">{{ count($compareList) }} Produk Dipilih</span>
                    <div class="flex gap-3">
                        @foreach($compareList as $id)
                            @php $prod = \App\Models\Product::find($id); @endphp
                            @if($prod)
                                <div class="relative w-12 h-12 bg-white rounded-xl border border-slate-200 overflow-hidden group shadow-sm">
                                    @if($prod->image_path)
                                        <img src="{{ asset('storage/' . $prod->image_path) }}" class="w-full h-full object-cover">
                                    @endif
                                    <button wire:click="removeFromCompare({{ $id }})" class="absolute inset-0 bg-rose-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('compareList', [])" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-rose-600 transition-colors">Reset</button>
                    <button wire:click="openCompare" class="px-8 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-cyan-600 transition-all shadow-lg hover:shadow-cyan-500/30">
                        Bandingkan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Comparison Modal -->
    @if($showCompareModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeCompare"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white w-full max-w-6xl rounded-3xl shadow-2xl overflow-hidden border border-white/50">
                    <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900">Perbandingan Spesifikasi</h3>
                            <p class="text-slate-500 text-sm">Bandingkan fitur teknis secara detail.</p>
                        </div>
                        <button wire:click="closeCompare" class="p-3 bg-white rounded-full hover:bg-rose-50 hover:text-rose-600 transition-colors shadow-sm border border-slate-100">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="p-0 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="p-6 w-1/4 bg-slate-50/30 border-r border-slate-100"></th>
                                    @foreach($compareList as $id)
                                        @php $prod = \App\Models\Product::find($id); @endphp
                                        <th class="p-6 w-1/4 align-top border-r border-slate-100 min-w-[250px]">
                                            <div class="h-40 bg-slate-50 rounded-2xl mb-6 flex items-center justify-center p-4 border border-slate-100">
                                                @if($prod->image_path)
                                                    <img src="{{ asset('storage/' . $prod->image_path) }}" class="max-h-full object-contain mix-blend-multiply">
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-slate-900 text-lg leading-tight mb-2">{{ $prod->name }}</h4>
                                            <p class="text-2xl font-tech font-bold text-cyan-600">Rp {{ number_format($prod->sell_price, 0, ',', '.') }}</p>
                                            <button wire:click="addToCart({{ $prod->id }})" class="mt-4 w-full py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-cyan-600 transition-colors">
                                                Add to Cart
                                            </button>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <tr>
                                    <td class="p-6 font-bold text-slate-500 uppercase tracking-wider text-xs bg-slate-50/30 border-r border-slate-100">Kategori</td>
                                    @foreach($compareList as $id)
                                        <td class="p-6 border-r border-slate-100">
                                            <span class="bg-cyan-50 text-cyan-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                                                {{ \App\Models\Product::find($id)->category->name }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="p-6 font-bold text-slate-500 uppercase tracking-wider text-xs bg-slate-50/30 border-r border-slate-100">Garansi</td>
                                    @foreach($compareList as $id)
                                        <td class="p-6 border-r border-slate-100 font-medium text-slate-700">{{ \App\Models\Product::find($id)->warranty_duration }} Bulan</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="p-6 font-bold text-slate-500 uppercase tracking-wider text-xs bg-slate-50/30 border-r border-slate-100">Deskripsi</td>
                                    @foreach($compareList as $id)
                                        <td class="p-6 border-r border-slate-100 text-slate-600 leading-relaxed">{{ Str::limit(\App\Models\Product::find($id)->description, 150) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Details Modal (Redesigned) -->
    @if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-[90] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" wire:click="closeModal"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border border-white/20">
                
                <button wire:click="closeModal" class="absolute top-6 right-6 p-3 bg-white/80 backdrop-blur rounded-full text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition-colors z-10 border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="flex flex-col md:flex-row h-full md:h-[600px]">
                    <!-- Image Side -->
                    <div class="w-full md:w-1/2 bg-slate-50 p-12 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-slate-50 to-slate-100"></div>
                         @if($selectedProduct->image_path)
                            <img src="{{ asset('storage/' . $selectedProduct->image_path) }}" class="max-w-full max-h-full object-contain mix-blend-multiply relative z-10 drop-shadow-2xl">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        @endif
                    </div>

                    <!-- Info Side -->
                    <div class="w-full md:w-1/2 p-10 md:p-12 bg-white flex flex-col overflow-y-auto">
                        <div class="mb-4">
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-cyan-50 text-cyan-700 uppercase tracking-wider border border-cyan-100">
                                {{ $selectedProduct->category->name }}
                            </span>
                        </div>
                        
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 leading-tight">{{ $selectedProduct->name }}</h2>
                        
                        <div class="flex items-center gap-4 mb-8">
                             <div class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                SKU: {{ $selectedProduct->sku }}
                            </div>
                            <div class="flex items-center gap-1 text-amber-400">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                <span class="text-sm font-bold text-slate-700 ml-1">4.8</span>
                            </div>
                        </div>

                        <div class="prose prose-slate text-slate-600 mb-8 leading-relaxed">
                            <p>{{ $selectedProduct->description ?? 'Tidak ada deskripsi detail.' }}</p>
                        </div>

                        <div class="mt-auto border-t border-slate-100 pt-8">
                            <div class="flex justify-between items-end mb-8">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Harga Special</p>
                                    <p class="text-4xl font-tech font-extrabold text-slate-900">
                                        Rp {{ number_format($selectedProduct->sell_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @if($selectedProduct->stock_quantity > 0)
                                        <p class="text-emerald-600 font-bold flex items-center justify-end gap-2 bg-emerald-50 px-3 py-1 rounded-lg">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Ready Stock ({{ $selectedProduct->stock_quantity }})
                                        </p>
                                    @else
                                        <p class="text-rose-600 font-bold bg-rose-50 px-3 py-1 rounded-lg">Out of Stock</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button wire:click.stop="addToCompare({{ $selectedProduct->id }})" class="px-4 py-4 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                </button>
                                @if($selectedProduct->stock_quantity > 0)
                                    <button wire:click="addToCart({{ $selectedProduct->id }})" class="flex-1 py-4 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-cyan-600 hover:to-blue-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-slate-900/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                        Masukkan Keranjang
                                    </button>
                                @else
                                    <button disabled class="flex-1 py-4 bg-slate-100 text-slate-400 rounded-2xl font-bold text-lg cursor-not-allowed border border-slate-200">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
