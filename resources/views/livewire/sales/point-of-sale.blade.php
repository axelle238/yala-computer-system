<div class="h-screen flex flex-col bg-slate-100 overflow-hidden">
    <!-- Navbar Khusus POS -->
    <div class="bg-indigo-700 px-6 py-3 flex justify-between items-center shadow-md z-10 shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-indigo-200 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-xl font-bold text-white tracking-wide">POS <span class="font-light opacity-80">| Kasir</span></h1>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right hidden md:block">
                <div class="text-xs text-indigo-200 uppercase tracking-wider">Kasir Aktif</div>
                <div class="font-bold text-white">{{ Auth::user()->name }}</div>
            </div>
            <div class="text-right hidden md:block">
                <div class="text-xs text-indigo-200 uppercase tracking-wider">Waktu</div>
                <div class="font-bold text-white font-mono">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- LEFT PANEL: CATALOG -->
        <div class="w-2/3 flex flex-col border-r border-slate-200 bg-slate-50">
            <!-- Search & Filter -->
            <div class="p-4 bg-white shadow-sm shrink-0">
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchQuery" 
                           class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 bg-slate-100 focus:bg-white focus:ring-2 focus:ring-indigo-500 transition text-lg" 
                           placeholder="Cari Produk (Scan Barcode)... [F2]"
                           autofocus>
                    <svg class="w-6 h-6 text-slate-400 absolute left-4 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                
                <!-- Category Tabs -->
                <div class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                    <button wire:click="$set('categoryId', null)" class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition {{ is_null($categoryId) ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                        Semua
                    </button>
                    @foreach($categories as $cat)
                        <button wire:click="$set('categoryId', {{ $cat->id }})" class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition {{ $categoryId == $cat->id ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($products as $product)
                        <button wire:click="addToCart({{ $product->id }})" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:border-indigo-300 transition group text-left flex flex-col h-full">
                            <div class="h-32 bg-slate-100 relative overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded-md backdrop-blur-sm">
                                    Stok: {{ $product->stock_quantity }}
                                </div>
                            </div>
                            <div class="p-3 flex-1 flex flex-col">
                                <h3 class="font-bold text-slate-800 text-sm line-clamp-2 leading-tight mb-1 group-hover:text-indigo-600">{{ $product->name }}</h3>
                                <div class="text-xs text-slate-500 mb-2">{{ $product->sku }}</div>
                                <div class="mt-auto font-black text-lg text-indigo-700">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</div>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <p>Produk tidak ditemukan.</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: CART & CHECKOUT -->
        <div class="w-1/3 bg-white flex flex-col shadow-xl z-20">
            <!-- Customer Selector -->
            <div class="p-4 border-b border-slate-100 bg-slate-50 relative">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pelanggan</label>
                    @if($selectedMemberId)
                        <button wire:click="$set('selectedMemberId', null)" class="text-xs text-rose-500 hover:underline">Reset</button>
                    @endif
                </div>
                <div class="relative">
                    @if($selectedMemberId)
                        <div class="flex items-center gap-3 p-2 bg-indigo-50 border border-indigo-100 rounded-lg">
                            <div class="w-8 h-8 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold">
                                {{ substr($guestName, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-indigo-900 text-sm">{{ $guestName }}</div>
                                <div class="text-xs text-indigo-600">Member Terdaftar</div>
                            </div>
                        </div>
                    @else
                        <input type="text" wire:model.live.debounce="memberSearch" class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500" placeholder="Cari Member (Nama/HP)...">
                        @if(!empty($searchResultsMember))
                            <div class="absolute z-50 w-full bg-white mt-1 border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto">
                                @foreach($searchResultsMember as $member)
                                    <button wire:click="selectMember({{ $member->id }})" class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm border-b border-slate-50 last:border-0">
                                        <div class="font-bold text-slate-800">{{ $member->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $member->phone ?? $member->email }}</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                @forelse($cart as $productId => $item)
                    <div class="flex gap-3 relative group">
                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-slate-800 text-sm truncate pr-2">{{ $item['name'] }}</h4>
                                <div class="font-bold text-slate-900 text-sm">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-xs text-slate-500">@ {{ number_format($item['price'], 0, ',', '.') }}</div>
                                <div class="flex items-center border border-slate-300 rounded-md bg-white ml-auto">
                                    <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] - 1 }})" class="px-2 py-0.5 text-slate-600 hover:bg-slate-100 hover:text-rose-500 transition">-</button>
                                    <input type="number" value="{{ $item['qty'] }}" class="w-10 text-center border-0 p-0 text-xs font-bold focus:ring-0" readonly>
                                    <button wire:click="updateQty({{ $productId }}, {{ $item['qty'] + 1 }})" class="px-2 py-0.5 text-slate-600 hover:bg-slate-100 hover:text-emerald-500 transition">+</button>
                                </div>
                            </div>
                        </div>
                        <button wire:click="removeItem({{ $productId }})" class="absolute -left-2 -top-2 bg-rose-500 text-white rounded-full p-0.5 opacity-0 group-hover:opacity-100 transition shadow-sm">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-60">
                        <svg class="w-20 h-20 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <p class="text-sm">Keranjang Kosong</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer Summary -->
            <div class="bg-slate-50 border-t border-slate-200 p-4 space-y-3 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <!-- Discount Input (Optional Expansion) -->
                @if($discount > 0)
                <div class="flex justify-between text-sm text-emerald-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="flex justify-between items-end border-t border-slate-200 pt-3 mb-2">
                    <span class="font-bold text-slate-800 text-lg">Total</span>
                    <span class="font-black text-2xl text-indigo-700">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <!-- Payment Method -->
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <button wire:click="$set('paymentMethod', 'cash')" class="py-2 text-xs font-bold rounded border {{ $paymentMethod == 'cash' ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'bg-white border-slate-300 text-slate-600' }}">TUNAI</button>
                    <button wire:click="$set('paymentMethod', 'qris')" class="py-2 text-xs font-bold rounded border {{ $paymentMethod == 'qris' ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'bg-white border-slate-300 text-slate-600' }}">QRIS / TRANSFER</button>
                </div>

                <!-- Cash Calculation -->
                @if($paymentMethod == 'cash')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Uang Diterima (F8)</label>
                        <input type="number" wire:model.live.debounce.300ms="cashGiven" class="w-full text-right font-mono text-lg font-bold border-slate-300 rounded focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                        @if($change > 0)
                            <div class="flex justify-between items-center mt-2 text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded">
                                <span class="text-xs uppercase">Kembali</span>
                                <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <button wire:click="processCheckout" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 text-lg transition transform active:scale-95 flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ empty($cart) ? 'disabled' : '' }}>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    BAYAR (F9)
                </button>
            </div>
        </div>
    </div>
    
    <!-- Flash Notification -->
    <div x-data="{ show: false, message: '', type: 'success' }" 
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
         class="fixed top-20 right-6 z-50">
        <div x-show="show" x-transition 
             :class="type === 'error' ? 'bg-rose-500' : 'bg-emerald-500'"
             class="text-white px-6 py-3 rounded-lg shadow-lg font-bold flex items-center gap-2">
            <span x-text="message"></span>
        </div>
    </div>

    <!-- Keyboard Shortcuts -->
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F2') {
                e.preventDefault();
                document.querySelector('input[placeholder*="F2"]').focus();
            }
            if (e.key === 'F9') {
                e.preventDefault();
                @this.processCheckout();
            }
        });
    </script>
</div>
