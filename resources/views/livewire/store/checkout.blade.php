<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                Secure <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Checkout</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Selesaikan pesanan Anda dengan aman dan cepat.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Form -->
            <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100">
                
                <!-- Shipping Info -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">1</span>
                        Informasi Pengiriman
                    </h3>

                    <!-- Address Book Selection -->
                    @auth
                        @if($savedAddresses->isNotEmpty())
                            <div class="mb-6">
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Pilih Alamat Tersimpan</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($savedAddresses as $addr)
                                        <div wire:click="selectAddress({{ $addr->id }})" class="cursor-pointer border rounded-xl p-3 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all {{ $selectedAddressId === $addr->id ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 ring-1 ring-emerald-500' : 'border-slate-200 dark:border-slate-700' }}">
                                            <div class="flex justify-between items-start">
                                                <span class="font-bold text-sm text-slate-800 dark:text-white">{{ $addr->label }}</span>
                                                @if($addr->is_primary) <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded uppercase font-bold">Utama</span> @endif
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $addr->address_line }}, {{ $addr->city }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="relative flex py-5 items-center">
                                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                                    <span class="flex-shrink-0 mx-4 text-slate-400 text-xs uppercase font-bold">Atau Input Manual</span>
                                    <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                                </div>
                            </div>
                        @endif
                    @endauth
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Penerima</label>
                            <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 px-4 py-3">
                            @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">No. WhatsApp</label>
                            <input type="text" wire:model="phone" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 px-4 py-3">
                            @error('phone') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 px-4 py-3" placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan"></textarea>
                        @error('address') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Kota Tujuan</label>
                            <select wire:model.live="city" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 px-4 py-3">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($cities as $cityName => $cost)
                                    <option value="{{ $cityName }}">{{ $cityName }}</option>
                                @endforeach
                            </select>
                            @error('city') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Kurir</label>
                            <select wire:model="courier" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-emerald-500 px-4 py-3">
                                <option value="jne">JNE (Reguler)</option>
                                <option value="jnt">J&T Express</option>
                                <option value="sicepat">SiCepat Halu</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Info Placeholder -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-200 dark:border-slate-700 opacity-50">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">2</span>
                        Pembayaran
                    </h3>
                    <p class="text-sm text-slate-500 ml-10">Instruksi pembayaran akan diberikan setelah pesanan dibuat.</p>
                </div>

            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-xl sticky top-24">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6">Ringkasan Pesanan</h3>
                    
                    <!-- Items -->
                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                        @foreach($cartItems as $item)
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden">
                                    @if($item['product']->image_path)
                                        <img src="{{ asset('storage/' . $item['product']->image_path) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white line-clamp-1">{{ $item['product']->name }}</h4>
                                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                                        <span>x{{ $item['qty'] }}</span>
                                        <span class="font-mono">Rp {{ number_format($item['line_total'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4 space-y-2 mb-6">
                        <!-- Voucher Input -->
                        <div class="mb-4">
                            @if($appliedVoucher)
                                <div class="bg-pink-50 dark:bg-pink-900/20 border border-pink-100 dark:border-pink-800 rounded-xl p-3 flex justify-between items-center">
                                    <div>
                                        <span class="block text-xs font-bold text-pink-600 dark:text-pink-400 uppercase tracking-wider">Voucher Aktif</span>
                                        <span class="font-bold text-slate-700 dark:text-slate-200">{{ $appliedVoucher->code }}</span>
                                    </div>
                                    <button wire:click="removeVoucher" class="text-rose-500 hover:text-rose-700 p-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <input type="text" wire:model="voucherCode" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm uppercase font-bold placeholder-slate-400" placeholder="KODE PROMO">
                                    <button wire:click="applyVoucher" class="px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors">Pakai</button>
                                </div>
                                @error('voucherCode') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Ongkos Kirim</span>
                            <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        @if($voucherDiscount > 0)
                            <div class="flex justify-between text-sm text-pink-600 font-bold">
                                <span>Diskon Voucher</span>
                                <span>- Rp {{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($discountAmount > 0)
                            <div class="flex justify-between text-sm text-emerald-600 font-bold">
                                <span>Diskon Poin</span>
                                <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        <!-- Points Input -->
                        @auth
                            <div class="pt-2 mt-2 border-t border-dashed border-slate-200 dark:border-slate-700">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Tukar Poin</span>
                                    <span class="text-xs text-amber-500 font-bold">{{ auth()->user()->points }} Poin Tersedia</span>
                                </div>
                                <div class="flex gap-2">
                                    <input type="number" wire:model.live.debounce.500ms="pointsToRedeem" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm px-3 py-2" placeholder="0">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">1 Poin = Rp 1</p>
                            </div>
                        @endauth
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-4 mb-6">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-bold text-slate-500 uppercase">Total Bayar</span>
                            <span class="text-2xl font-black text-emerald-600 font-mono">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button wire:click="placeOrder" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 group">
                        <span>Buat Pesanan</span>
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-400 mt-4 flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Transaksi Aman & Terenkripsi
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>