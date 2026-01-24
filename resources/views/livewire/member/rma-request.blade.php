<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        
        <!-- Header -->
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                Klaim <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">Garansi (RMA)</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Ajukan perbaikan atau penggantian produk yang bermasalah dengan mudah.</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center justify-center mb-12 animate-fade-in-up delay-100">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $step >= 1 ? 'bg-rose-600' : 'bg-slate-200 text-slate-500' }}">1</div>
                <div class="ml-2 mr-4 font-bold text-sm {{ $step >= 1 ? 'text-rose-600' : 'text-slate-400' }}">Pilih Order</div>
            </div>
            <div class="w-16 h-1 bg-slate-200 rounded-full mx-2">
                <div class="h-full bg-rose-600 rounded-full transition-all duration-500" style="width: {{ $step >= 2 ? '100%' : '0%' }}"></div>
            </div>
            <div class="flex items-center ml-2">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white {{ $step >= 2 ? 'bg-rose-600' : 'bg-slate-200 text-slate-500' }}">2</div>
                <div class="ml-2 font-bold text-sm {{ $step >= 2 ? 'text-rose-600' : 'text-slate-400' }}">Detail Kerusakan</div>
            </div>
        </div>

        <!-- Step 1: Select Order -->
        @if($step === 1)
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Pilih Pesanan yang Ingin Diklaim</h3>
                
                @if($orders->isEmpty())
                    <div class="text-center py-12 text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <p class="text-lg font-bold">Tidak ada pesanan yang memenuhi syarat.</p>
                        <p class="text-sm">Hanya pesanan dengan status "Selesai" yang dapat diklaim garansi.</p>
                        <a href="{{ route('home') }}" class="mt-4 inline-block text-rose-600 font-bold hover:underline">Belanja Sekarang &rarr;</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($orders as $order)
                            <div wire:click="selectOrder({{ $order->id }})" class="cursor-pointer border border-slate-200 dark:border-slate-700 rounded-2xl p-6 hover:border-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/10 transition-all group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs font-bold uppercase text-slate-500 mb-1">No. Order</div>
                                        <div class="font-mono text-lg font-black text-slate-800 dark:text-white group-hover:text-rose-600">{{ $order->order_number }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold uppercase text-slate-500 mb-1">Tanggal</div>
                                        <div class="text-slate-800 dark:text-white">{{ $order->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-700">
                                    <div class="text-sm text-slate-500">{{ $order->items->count() }} Items</div>
                                    <div class="font-bold text-rose-600 text-sm flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        Pilih Pesanan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Step 2: Form Details -->
        @if($step === 2)
            <div class="space-y-8 animate-fade-in-up">
                
                <!-- Order Info -->
                <div class="bg-slate-100 dark:bg-slate-800/50 p-4 rounded-xl flex justify-between items-center border border-slate-200 dark:border-slate-700">
                    <div>
                        <span class="text-xs text-slate-500 uppercase font-bold">Pesanan Terpilih:</span>
                        <span class="font-mono font-bold text-slate-900 dark:text-white ml-2">{{ $selectedOrder->order_number }}</span>
                    </div>
                    <button wire:click="resetSelection" class="text-xs font-bold text-rose-500 hover:underline">Ganti Pesanan</button>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Pilih Produk & Jelaskan Masalah</h3>

                    <div class="space-y-6">
                        @foreach($selectedOrder->items as $item)
                            <div class="border border-slate-200 dark:border-slate-700 rounded-2xl p-6 transition-all {{ isset($rmaItems[$item->id]) ? 'bg-rose-50 border-rose-200 dark:bg-rose-900/10 dark:border-rose-800 ring-1 ring-rose-500' : 'bg-white dark:bg-slate-800' }}">
                                <div class="flex items-start gap-4">
                                    <div class="pt-1">
                                        <input type="checkbox" wire:click="toggleItem({{ $item->id }})" {{ isset($rmaItems[$item->id]) ? 'checked' : '' }} class="w-5 h-5 text-rose-600 rounded focus:ring-rose-500 border-gray-300 cursor-pointer">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-900 dark:text-white text-lg">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-slate-500 mb-2 font-mono">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        
                                        @if(isset($rmaItems[$item->id]))
                                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah</label>
                                                    <input type="number" wire:model="rmaItems.{{ $item->id }}.qty" max="{{ $item->quantity }}" min="1" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                                                    @error('rmaItems.'.$item->id.'.qty') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kondisi Fisik</label>
                                                    <select wire:model="rmaItems.{{ $item->id }}.condition" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm">
                                                        <option value="used">Bekas Pakai (Wajar)</option>
                                                        <option value="damaged">Ada Cacat Fisik</option>
                                                        <option value="like_new">Seperti Baru</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Deskripsi Kerusakan/Masalah</label>
                                                    <textarea wire:model="rmaItems.{{ $item->id }}.reason" rows="2" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg text-sm" placeholder="Contoh: Layar berkedip saat dinyalakan..."></textarea>
                                                    @error('rmaItems.'.$item->id.'.reason') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Final Preferences -->
                @if(count($rmaItems) > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Preferensi Penyelesaian</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Solusi yang Diinginkan</label>
                                <select wire:model="resolutionPreference" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500 p-3">
                                    <option value="repair">Perbaikan (Service)</option>
                                    <option value="replacement">Tukar Baru (Replacement)</option>
                                    <option value="refund">Pengembalian Dana (Refund)</option>
                                </select>
                                <p class="text-xs text-slate-400 mt-2">*Keputusan akhir tetap bergantung pada kebijakan garansi dan hasil pengecekan teknisi.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Catatan Tambahan (Opsional)</label>
                                <textarea wire:model="generalNotes" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-rose-500 p-3" placeholder="Informasi lain yang perlu kami ketahui..."></textarea>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 dark:border-slate-700 pt-6 flex justify-end">
                            <button wire:click="submitRma" wire:confirm="Pastikan data sudah benar. Ajukan klaim garansi?" class="px-8 py-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-600/30 transition-all flex items-center gap-2 text-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Kirim Pengajuan RMA
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        @endif

    </div>
</div>