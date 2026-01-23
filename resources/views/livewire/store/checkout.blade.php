<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-black font-tech uppercase text-slate-900 dark:text-white mb-8">Checkout</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Form -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-lg mb-6 text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-sm">1</span>
                    Customer Details
                </h3>

                <form wire:submit.prevent="placeOrder" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Full Name</label>
                        <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-purple-500 outline-none transition-all" placeholder="Enter your name">
                        @error('name') <span class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">WhatsApp Number</label>
                        <input wire:model="whatsapp" type="text" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-purple-500 outline-none transition-all" placeholder="e.g. 628123456789">
                        @error('whatsapp') <span class="text-rose-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Notes (Optional)</label>
                        <textarea wire:model="notes" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-purple-500 outline-none transition-all" placeholder="Any special instructions?"></textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold text-center rounded-xl shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 hover:-translate-y-1 transition-all">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary -->
            <div class="space-y-6">
                 <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-3xl border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-lg mb-6 text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">2</span>
                        Order Summary
                    </h3>
                    <div class="space-y-3 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $item['name'] }}</span>
                                    <span class="text-xs text-slate-500 block">x{{ $item['quantity'] }}</span>
                                </div>
                                <span class="font-mono font-bold text-slate-600 dark:text-slate-400">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-600 pt-4 flex justify-between items-center">
                        <span class="font-bold text-slate-500">Total Payment</span>
                        <span class="font-black text-2xl text-purple-600 font-tech">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
