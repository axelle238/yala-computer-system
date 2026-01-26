<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white mb-8 uppercase tracking-tighter">
            Keranjang <span class="text-blue-600">Belanja</span>
        </h1>

        @if($cartProducts->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center shadow-sm border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Keranjangmu Kosong</h2>
                <p class="text-slate-500 mb-8">Sepertinya kamu belum menambahkan apapun.</p>
                <a href="{{ route('toko.katalog') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1 space-y-4 animate-fade-in-up delay-100">
                    @foreach($cartProducts as $product)
                        <div class="bg-white dark:bg-slate-800 p-4 md:p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row gap-6 items-center">
                            <!-- Image -->
                            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 text-center md:text-left">
                                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-1">{{ $product->name }}</h3>
                                <p class="text-sm text-slate-500 mb-2">{{ $product->sku }}</p>
                                <div class="font-mono text-blue-600 font-bold">
                                    Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-slate-200 dark:border-slate-600 rounded-lg">
                                    <button wire:click="updateQuantity({{ $product->id }}, {{ $product->cart_qty - 1 }})" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500">-</button>
                                    <input type="text" readonly value="{{ $product->cart_qty }}" class="w-12 text-center border-none bg-transparent font-bold text-slate-800 dark:text-white focus:ring-0 p-0">
                                    <button wire:click="updateQuantity({{ $product->id }}, {{ $product->cart_qty + 1 }})" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500">+</button>
                                </div>
                                <button wire:click="removeItem({{ $product->id }})" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="lg:w-96 animate-fade-in-up delay-200">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700 sticky top-24">
                        <h3 class="font-bold text-xl text-slate-800 dark:text-white mb-6 uppercase tracking-wide">Ringkasan</h3>
                        
                        <div class="space-y-4 mb-6 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Total Item</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ $cartProducts->sum('cart_qty') }} pcs</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-700">
                                <span class="font-bold text-slate-800 dark:text-white text-lg">Total</span>
                                <span class="font-black text-2xl text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('checkout') }}" class="block w-full py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 text-center text-lg">
                                Checkout Sekarang
                            </a>

                            <div class="relative flex py-2 items-center">
                                <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                                <span class="flex-shrink-0 mx-4 text-slate-400 text-xs font-bold uppercase">Atau</span>
                                <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                            </div>

                            <button wire:click="requestQuote" wire:confirm="Buat permintaan penawaran resmi untuk item ini?" class="block w-full py-3 bg-white dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-white font-bold rounded-xl hover:border-blue-500 hover:text-blue-600 transition-all text-center">
                                Minta Penawaran (B2B)
                            </button>
                            <p class="text-center text-xs text-slate-400 px-4">
                                Gunakan fitur penawaran untuk pembelian korporat atau jumlah besar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>