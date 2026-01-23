<!-- Product Detail Modal -->
@if($showModal && $selectedProduct)
    <div class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md transition-opacity" wire:click="closeModal" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl shadow-cyan-900/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full relative border border-white/10 tech-card">
                <!-- Close Button -->
                <button wire:click="closeModal" class="absolute top-4 right-4 z-20 p-2 bg-slate-800/80 backdrop-blur rounded-full text-slate-400 hover:text-white hover:bg-rose-500/20 hover:border-rose-500/50 border border-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- Image Side -->
                    <div class="bg-slate-950 p-8 flex items-center justify-center relative overflow-hidden group">
                        <!-- Grid & Glow -->
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                        <div class="absolute inset-0 cyber-grid opacity-20"></div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-all duration-700"></div>

                        @if($selectedProduct->image_path)
                            <img src="{{ asset('storage/' . $selectedProduct->image_path) }}" class="max-h-96 max-w-full object-contain relative z-10 transition-transform duration-700 group-hover:scale-110 group-hover:rotate-2 drop-shadow-2xl">
                        @else
                            <svg class="w-32 h-32 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>

                    <!-- Info Side -->
                    <div class="p-8 md:p-10 flex flex-col h-full bg-slate-900 relative">
                        <!-- Decorative Top Line -->
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-cyan-500 to-transparent opacity-50"></div>

                        <div class="flex-1">
                            <span class="inline-block px-3 py-1 rounded bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-[10px] font-bold tracking-widest uppercase mb-4">{{ $selectedProduct->category->name }}</span>
                            <h2 class="text-3xl font-black font-tech text-white leading-tight mb-2 tracking-wide">{{ $selectedProduct->name }}</h2>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="text-3xl font-bold text-cyan-400 font-mono shadow-cyan-500/50 drop-shadow-md">Rp {{ number_format($selectedProduct->sell_price, 0, ',', '.') }}</span>
                                @if($selectedProduct->stock_quantity > 0)
                                    <span class="flex items-center gap-2 text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Ready Stock ({{ $selectedProduct->stock_quantity }})
                                    </span>
                                @else
                                    <span class="flex items-center gap-2 text-xs font-bold text-rose-400 bg-rose-500/10 px-2 py-1 rounded border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Out of Stock
                                    </span>
                                @endif
                            </div>

                            <div class="prose prose-sm prose-invert text-slate-400 mb-8 max-h-60 overflow-y-auto custom-scrollbar font-light">
                                <p>{{ $selectedProduct->description }}</p>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 border-t border-white/5 flex gap-4">
                            <button wire:click="addToCart({{ $selectedProduct->id }})" class="group flex-1 bg-white text-slate-900 font-bold py-4 rounded-xl hover:bg-cyan-400 transition-all shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_30px_rgba(6,182,212,0.4)] flex items-center justify-center gap-2 {{ $selectedProduct->stock_quantity < 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $selectedProduct->stock_quantity < 1 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="uppercase tracking-wider text-xs">{{ $selectedProduct->stock_quantity > 0 ? 'Add to Cart' : 'Sold Out' }}</span>
                            </button>
                            <button wire:click="addToCompare({{ $selectedProduct->id }})" class="p-4 border border-slate-700 rounded-xl text-slate-400 hover:text-white hover:border-cyan-500/50 hover:bg-cyan-500/10 transition-all" title="Compare">
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