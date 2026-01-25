<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="flex items-center gap-4 mb-8 animate-fade-in-up">
            <a href="{{ route('member.dashboard') }}" class="p-2 rounded-full bg-white dark:bg-slate-800 text-slate-500 hover:text-cyan-500 shadow-sm transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">Klaim Garansi (RMA)</h1>
                <p class="text-slate-500 text-sm">Ajukan perbaikan atau penggantian produk yang bermasalah.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- FORMULIR PENGAJUAN -->
            <div class="lg:col-span-2 space-y-6 animate-fade-in-up delay-100">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                    <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-black">1</span>
                        Pilih Pesanan
                    </h2>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Invoice / Order</label>
                        <select wire:model.live="selectedOrderId" class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-cyan-500 focus:border-cyan-500 dark:text-white">
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">
                                    {{ $order->order_number }} - {{ $order->created_at->format('d M Y') }} (Total: Rp {{ number_format($order->total_amount) }})
                                </option>
                            @endforeach
                        </select>
                        @if($orders->isEmpty())
                            <p class="text-xs text-rose-500 mt-2">* Tidak ada pesanan yang memenuhi syarat garansi (Selesai & < 1 Tahun).</p>
                        @endif
                    </div>

                    @if($selectedOrderId && count($orderItems) > 0)
                        <div class="space-y-4">
                            <h3 class="font-bold text-slate-700 dark:text-slate-200 text-sm">Pilih Produk Bermasalah:</h3>
                            @foreach($orderItems as $item)
                                <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 flex flex-col gap-4 {{ isset($selectedItems[$item->id]['selected']) && $selectedItems[$item->id]['selected'] ? 'bg-cyan-50 dark:bg-cyan-900/10 border-cyan-200' : 'bg-white dark:bg-slate-800' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="pt-1">
                                            <input type="checkbox" wire:model.live="selectedItems.{{ $item->id }}.selected" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 w-5 h-5">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-slate-500">Harga Beli: Rp {{ number_format($item->price) }}</p>
                                        </div>
                                    </div>

                                    <!-- Detail Masalah (Muncul jika dipilih) -->
                                    @if(isset($selectedItems[$item->id]['selected']) && $selectedItems[$item->id]['selected'])
                                        <div class="pl-8 grid grid-cols-1 md:grid-cols-2 gap-4 animate-fade-in">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kondisi Fisik</label>
                                                <select wire:model="selectedItems.{{ $item->id }}.condition" class="w-full text-xs rounded-lg border-slate-300">
                                                    <option>Lengkap (Dus + Aksesoris)</option>
                                                    <option>Batangan (Unit Only)</option>
                                                    <option>Rusak Fisik / Cacat</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Masalah Utama</label>
                                                <input type="text" wire:model="selectedItems.{{ $item->id }}.reason" class="w-full text-xs rounded-lg border-slate-300" placeholder="Contoh: Mati total, blue screen...">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
                            <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-black">2</span>
                                Deskripsi & Bukti
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kronologi Kerusakan</label>
                                    <textarea wire:model="description" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 text-sm focus:ring-cyan-500 dark:text-white" placeholder="Jelaskan bagaimana kerusakan terjadi..."></textarea>
                                    @error('description') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-800 text-xs flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <p>Pastikan produk dikemas dengan aman saat dikirim. Kerusakan akibat pengiriman bukan tanggung jawab kami.</p>
                                </div>

                                <button wire:click="submitRequest" class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                    Kirim Permintaan RMA
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RIWAYAT KLAIM -->
            <div class="lg:col-span-1 space-y-6 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 h-full max-h-[800px] overflow-y-auto custom-scrollbar">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 uppercase text-sm tracking-wider">Riwayat Klaim</h3>
                    
                    @forelse($rmaHistory as $rma)
                        <div class="mb-4 pb-4 border-b border-slate-100 dark:border-slate-700 last:border-0 last:pb-0">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-mono text-xs font-bold text-cyan-600 bg-cyan-50 dark:bg-cyan-900/20 px-2 py-0.5 rounded">{{ $rma->rma_number }}</span>
                                <span class="text-[10px] text-slate-400">{{ $rma->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="px-2 py-1 text-[10px] font-bold rounded uppercase {{ $rma->status_color }}">
                                    {{ $rma->status_label }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                @foreach($rma->items as $item)
                                    <p class="text-xs text-slate-600 dark:text-slate-300 truncate">• {{ $item->product->name }}</p>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">
                            <p class="text-sm">Belum ada riwayat klaim.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
