<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="mb-8 animate-fade-in-up">
            <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 mb-2 inline-block">&larr; Kembali ke Dashboard</a>
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Klaim <span class="text-blue-600">Garansi (RMA)</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Ajukan perbaikan atau penggantian untuk produk yang bermasalah.</p>
        </div>

        <!-- STEP 1: SELECT ORDER -->
        @if($step === 1)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up delay-100">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <h2 class="font-bold text-lg text-slate-800 dark:text-white">Pilih Pesanan</h2>
                    <p class="text-sm text-slate-500">Pilih pesanan yang berisi produk yang ingin Anda klaim.</p>
                </div>
                
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($orders as $order)
                        <div class="p-6 hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors flex flex-col md:flex-row justify-between items-center gap-4">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">{{ $order->status }}</span>
                                </div>
                                <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y') }} • Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <button wire:click="selectOrder({{ $order->id }})" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-all">
                                Pilih
                            </button>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400">
                            <p>Tidak ada pesanan yang memenuhi syarat (Selesai/Diterima).</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- STEP 2: SELECT ITEMS & DETAILS -->
        @if($step === 2 && $selectedOrder)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in-up">
                <!-- Left: Items -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800 dark:text-white">Pilih Produk</h2>
                                <p class="text-sm text-slate-500">Centang produk yang bermasalah.</p>
                            </div>
                            <button wire:click="resetSelection" class="text-sm text-rose-500 hover:underline">Ganti Pesanan</button>
                        </div>

                        <div class="p-6 space-y-6">
                            @foreach($selectedOrder->items as $item)
                                <div class="border rounded-xl p-4 {{ isset($rmaItems[$item->id]) ? 'border-blue-500 bg-blue-50/30 dark:bg-blue-900/10' : 'border-slate-200 dark:border-slate-700' }} transition-all">
                                    <div class="flex items-start gap-4">
                                        <div class="pt-1">
                                            <input type="checkbox" wire:click="toggleItem({{ $item->id }})" {{ isset($rmaItems[$item->id]) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-slate-500 mb-2">Qty Beli: {{ $item->quantity }}</p>

                                            <!-- RMA Form per Item -->
                                            @if(isset($rmaItems[$item->id]))
                                                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 space-y-4 animate-fade-in">
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah Klaim</label>
                                                            <input type="number" wire:model="rmaItems.{{ $item->id }}.qty" min="1" max="{{ $item->quantity }}" class="w-full text-sm rounded-lg border-slate-300 dark:bg-slate-900 dark:border-slate-600 focus:ring-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kondisi Fisik</label>
                                                            <select wire:model="rmaItems.{{ $item->id }}.condition" class="w-full text-sm rounded-lg border-slate-300 dark:bg-slate-900 dark:border-slate-600 focus:ring-blue-500">
                                                                <option value="used">Bekas Pemakaian Wajar</option>
                                                                <option value="damaged">Ada Kerusakan Fisik</option>
                                                                <option value="like_new">Seperti Baru</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Deskripsi Masalah</label>
                                                        <textarea wire:model="rmaItems.{{ $item->id }}.reason" rows="2" class="w-full text-sm rounded-lg border-slate-300 dark:bg-slate-900 dark:border-slate-600 focus:ring-blue-500" placeholder="Jelaskan kerusakan secara detail..."></textarea>
                                                        @error("rmaItems.{$item->id}.reason") <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                                    
                                                    <!-- File Upload -->
                                                    <div>
                                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Bukti Foto/Video (Opsional)</label>
                                                        <input type="file" wire:model="uploads.{{ $item->id }}" multiple class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                        <p class="text-[10px] text-slate-400 mt-1">Max 2MB per file.</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Summary & Submit -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sticky top-24">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Metode Penyelesaian</h3>
                        
                        <div class="space-y-3 mb-6">
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $resolutionPreference == 'repair' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-slate-200 dark:border-slate-600' }}">
                                <input type="radio" wire:model="resolutionPreference" value="repair" class="text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="block font-bold text-sm text-slate-800 dark:text-white">Perbaikan (Service)</span>
                                    <span class="block text-xs text-slate-500">Estimasi 3-7 hari kerja</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $resolutionPreference == 'replacement' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-slate-200 dark:border-slate-600' }}">
                                <input type="radio" wire:model="resolutionPreference" value="replacement" class="text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="block font-bold text-sm text-slate-800 dark:text-white">Tukar Baru</span>
                                    <span class="block text-xs text-slate-500">Jika stok tersedia & memenuhi syarat</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors {{ $resolutionPreference == 'refund' ? 'border-blue-500 ring-1 ring-blue-500' : 'border-slate-200 dark:border-slate-600' }}">
                                <input type="radio" wire:model="resolutionPreference" value="refund" class="text-blue-600 focus:ring-blue-500">
                                <div>
                                    <span class="block font-bold text-sm text-slate-800 dark:text-white">Pengembalian Dana</span>
                                    <span class="block text-xs text-slate-500">Dana dikembalikan ke saldo/rek</span>
                                </div>
                            </label>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Catatan Tambahan</label>
                            <textarea wire:model="generalNotes" rows="3" class="w-full text-sm rounded-lg border-slate-300 dark:bg-slate-900 dark:border-slate-600 focus:ring-blue-500" placeholder="Info pengiriman balik, dll..."></textarea>
                        </div>

                        <button wire:click="submitRma" wire:loading.attr="disabled" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                            <span wire:loading.remove>Ajukan Klaim</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
