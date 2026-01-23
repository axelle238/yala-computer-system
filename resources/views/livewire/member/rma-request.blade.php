<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech mb-6">Pengajuan Garansi (RMA)</h1>

    @if($step === 1)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-lg">Pilih Pesanan</h3>
                <p class="text-sm text-slate-500">Pilih transaksi yang berisi barang yang ingin Anda klaim.</p>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($orders as $order)
                    <div class="p-6 flex justify-between items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div>
                            <div class="font-bold text-slate-800 dark:text-white">Order #{{ $order->order_number }}</div>
                            <div class="text-sm text-slate-500">{{ $order->created_at->format('d M Y') }} • Rp {{ number_format($order->total_amount) }}</div>
                        </div>
                        <button wire:click="selectOrder({{ $order->id }})" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-lg font-bold text-sm">Pilih</button>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($step === 2)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="font-bold text-lg mb-4">Pilih Barang & Jelaskan Masalah</h3>
            
            <div class="space-y-4">
                @foreach($orderItems as $item)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4">
                        <div class="flex items-start gap-4">
                            <input type="checkbox" wire:model.live="selectedItems.{{ $item->id }}.selected" class="mt-1 rounded text-cyan-600 focus:ring-cyan-500">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</h4>
                                <p class="text-xs text-slate-500 mb-2">Qty Beli: {{ $item->quantity }}</p>
                                
                                @if($selectedItems[$item->id]['selected'] ?? false)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3 animate-fade-in-up">
                                        <div>
                                            <label class="text-xs font-bold uppercase text-slate-400">Jumlah Retur</label>
                                            <input type="number" wire:model="selectedItems.{{ $item->id }}.qty" min="1" max="{{ $item->quantity }}" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-lg text-sm">
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold uppercase text-slate-400">Keluhan / Masalah</label>
                                            <input type="text" wire:model="selectedItems.{{ $item->id }}.reason" class="w-full mt-1 bg-slate-50 border-slate-200 rounded-lg text-sm" placeholder="Contoh: Mati total, layar bergaris...">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button wire:click="$set('step', 1)" class="px-6 py-3 text-slate-500 font-bold hover:underline">Kembali</button>
                <button wire:click="submitRma" class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg hover:shadow-emerald-500/30">Kirim Pengajuan RMA</button>
            </div>
        </div>
    @endif
</div>
