<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-black font-tech uppercase text-slate-900 dark:text-white mb-8">Shopping Cart</h1>

    @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $id => $item)
                    <div class="flex flex-col sm:flex-row items-center gap-4 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-xl flex items-center justify-center flex-shrink-0">
                            <!-- Placeholder Image -->
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-bold text-slate-800 dark:text-white">{{ $item['name'] }}</h3>
                            <div class="text-purple-600 font-bold font-tech mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] - 1 }})" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 hover:bg-slate-200">-</button>
                            <span class="font-bold w-8 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 hover:bg-slate-200">+</button>
                        </div>
                        <div class="text-right min-w-[100px] hidden sm:block">
                            <div class="font-black text-slate-800 dark:text-white font-tech">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                        </div>
                        <button wire:click="removeItem('{{ $id }}')" class="text-slate-400 hover:text-rose-500 transition-colors p-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 h-fit">
                <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Order Summary</h3>
                <div class="flex justify-between items-center mb-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <span class="font-bold text-slate-500">Total</span>
                    <span class="font-black text-2xl text-purple-600 font-tech">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="block w-full py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold text-center rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-1 transition-all">
                    Proceed to Checkout
                </a>
                <a href="{{ route('home') }}" class="block w-full mt-4 py-3 text-slate-500 font-bold text-center hover:text-purple-600">
                    Continue Shopping
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Your cart is empty</h2>
            <p class="text-slate-500 mb-8">Looks like you haven't added any products yet.</p>
            <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition-colors">Start Shopping</a>
        </div>
    @endif
</div>
