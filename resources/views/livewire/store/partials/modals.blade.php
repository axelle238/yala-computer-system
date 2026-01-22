<!-- Product Detail Modal -->
@if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModal" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full relative">
                <button wire:click="closeModal" class="absolute top-4 right-4 z-10 p-2 bg-white/80 rounded-full text-slate-400 hover:text-slate-600 hover:bg-white transition-all">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Image Side -->
                    <div class="bg-slate-100 p-8 flex items-center justify-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
                        @if($selectedProduct->image_path)
                            <img src="{{ asset('storage/' . $selectedProduct->image_path) }}" class="max-h-96 max-w-full object-contain mix-blend-multiply relative z-10 transition-transform duration-500 group-hover:scale-110">
                        @else
                            <svg class="w-32 h-32 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>

                    <!-- Info Side -->
                    <div class="p-8 md:p-10 flex flex-col h-full bg-white">
                        <div class="flex-1">
                            <span class="inline-block px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-bold tracking-wide uppercase mb-4">{{ $selectedProduct->category->name }}</span>
                            <h2 class="text-3xl font-black font-tech text-slate-900 leading-tight mb-2">{{ $selectedProduct->name }}</h2>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="text-3xl font-bold text-rose-600 font-tech">Rp {{ number_format($selectedProduct->sell_price, 0, ',', '.') }}</span>
                                @if($selectedProduct->stock_quantity > 0)
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Stok Tersedia ({{ $selectedProduct->stock_quantity }})
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-md">
                                        <span class="w-2 h-2 bg-rose-500 rounded-full"></span> Stok Habis
                                    </span>
                                @endif
                            </div>

                            <div class="prose prose-sm text-slate-500 mb-8 max-h-60 overflow-y-auto custom-scrollbar">
                                <p>{{ $selectedProduct->description }}</p>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 border-t border-slate-100 flex gap-4">
                            <button wire:click="addToCart({{ $selectedProduct->id }})" class="flex-1 bg-slate-900 text-white font-bold py-4 rounded-xl hover:bg-cyan-600 transition-all shadow-lg hover:shadow-cyan-500/30 flex items-center justify-center gap-2 {{ $selectedProduct->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $selectedProduct->stock_quantity < 1 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                {{ $selectedProduct->stock_quantity > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                            </button>
                            <button wire:click="addToCompare({{ $selectedProduct->id }})" class="p-4 border border-slate-200 rounded-xl text-slate-400 hover:text-cyan-600 hover:border-cyan-200 hover:bg-cyan-50 transition-all" title="Bandingkan">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('livewire.store.partials.compare-modal')