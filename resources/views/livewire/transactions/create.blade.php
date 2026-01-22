<div class="h-[calc(100vh-8rem)] flex flex-col lg:flex-row gap-6 overflow-hidden animate-fade-in-up">
    <!-- Left Panel: Product Grid -->
    <div class="flex-1 flex flex-col h-full bg-white/50 dark:bg-slate-800/50 backdrop-blur-md border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden relative tech-border">
        
        <!-- Toolbar -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-4 bg-white dark:bg-slate-900 sticky top-0 z-10">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-xl bg-slate-100 dark:bg-slate-800 text-sm focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-500" placeholder="Scan Barcode / Cari Produk..." autofocus>
                
                <!-- Helper Shortcut Hint -->
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <kbd class="hidden sm:inline-block border border-slate-300 dark:border-slate-600 rounded px-1 text-[10px] font-mono text-slate-500">AUTOFOCUS</kbd>
                </div>
            </div>
            
            <select wire:model.live="category" class="py-2.5 px-4 border-none rounded-xl bg-slate-100 dark:bg-slate-800 text-sm text-slate-600 dark:text-slate-300 focus:ring-2 focus:ring-cyan-500 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Grid Content -->
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($products as $product)
                    <button wire:click="addToCart({{ $product->id }})" class="group relative flex flex-col bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden hover:border-cyan-500 dark:hover:border-cyan-500 hover:shadow-lg hover:shadow-cyan-500/10 transition-all text-left">
                        <div class="aspect-square bg-slate-50 dark:bg-slate-800 flex items-center justify-center p-4 relative overflow-hidden">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="object-contain w-full h-full mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                            @else
                                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            @endif
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $product->stock_quantity > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                Stok: {{ $product->stock_quantity }}
                            </div>
                        </div>
                        <div class="p-3 flex-1 flex flex-col">
                            <h4 class="font-bold text-slate-800 dark:text-white text-xs leading-tight line-clamp-2 mb-1 group-hover:text-cyan-600 transition-colors">{{ $product->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-mono mb-2">{{ $product->sku }}</p>
                            <div class="mt-auto flex justify-between items-end">
                                <span class="font-black font-tech text-sm text-slate-900 dark:text-slate-200">Rp {{ number_format($product->sell_price / 1000, 0) }}k</span>
                                <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </div>
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <p class="text-sm">Produk tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Right Panel: Cart / Invoice -->
    <div class="w-full lg:w-96 flex flex-col h-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-xl overflow-hidden relative flex-shrink-0">
        <!-- Header Cart -->
        <div class="p-4 bg-slate-50 dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
            <div>
                <h3 class="font-bold font-tech text-lg text-slate-900 dark:text-white">Current Order</h3>
                <p class="text-[10px] text-slate-400 font-mono">{{ $reference_number }}</p>
            </div>
            <div class="px-2 py-1 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600 text-xs font-bold text-slate-500">
                {{ count($cart) }} Items
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
            @forelse($cart as $index => $item)
                <div class="flex gap-3 items-start animate-slide-in-right">
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-lg flex-shrink-0 flex items-center justify-center border border-slate-100 dark:border-slate-700">
                        @if($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" class="w-10 h-10 object-contain">
                        @else
                             <span class="text-xs font-bold text-slate-400">{{ substr($item['name'], 0, 2) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-slate-800 dark:text-white text-xs truncate pr-2">{{ $item['name'] }}</h4>
                            <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300">
                                {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] text-slate-400">@ {{ number_format($item['price'], 0, ',', '.') }}</p>
                            
                            <!-- Qty Control -->
                            <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-lg p-0.5">
                                <button wire:click="updateQty({{ $index }}, {{ $item['quantity'] - 1 }})" class="w-5 h-5 flex items-center justify-center rounded bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-rose-500 text-xs shadow-sm">-</button>
                                <input type="text" class="w-6 text-center bg-transparent border-none p-0 text-xs font-bold text-slate-800 dark:text-white focus:ring-0" value="{{ $item['quantity'] }}" readonly>
                                <button wire:click="updateQty({{ $index }}, {{ $item['quantity'] + 1 }})" class="w-5 h-5 flex items-center justify-center rounded bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-emerald-500 text-xs shadow-sm">+</button>
                            </div>
                        </div>
                    </div>
                    <button wire:click="removeFromCart({{ $index }})" class="text-slate-300 hover:text-rose-500 transition-colors self-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-slate-300 dark:text-slate-600 opacity-50">
                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <p class="text-xs font-medium">Keranjang Kosong</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Summary -->
        <div class="bg-slate-50 dark:bg-slate-800 p-4 border-t border-slate-200 dark:border-slate-700 space-y-3">
            <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Subtotal</span>
                <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-end">
                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">Total Tagihan</span>
                <span class="text-xl font-black font-tech text-slate-900 dark:text-white">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
            
            <!-- Type Selector -->
            <div class="grid grid-cols-2 gap-2 mt-2">
                <button wire:click="$set('type', 'out')" class="py-2 text-xs font-bold rounded-lg border {{ $type === 'out' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">
                    JUAL (Out)
                </button>
                <button wire:click="$set('type', 'in')" class="py-2 text-xs font-bold rounded-lg border {{ $type === 'in' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">
                    BELI (In)
                </button>
            </div>

            <div class="space-y-2">
                <input wire:model="customer_phone" type="text" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-1 focus:ring-cyan-500" placeholder="No. HP Member (Opsional)">
                <textarea wire:model="notes" rows="1" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-1 focus:ring-cyan-500" placeholder="Catatan Transaksi..."></textarea>
            </div>

            <button wire:click="save" class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold hover:shadow-lg hover:shadow-slate-900/20 transition-all flex items-center justify-center gap-2 {{ empty($cart) ? 'opacity-50 cursor-not-allowed' : '' }}" {{ empty($cart) ? 'disabled' : '' }}>
                <span wire:loading.remove>PROSES TRANSAKSI</span>
                <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
    </div>
</div>