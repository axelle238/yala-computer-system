<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter mb-8">
            Klaim <span class="text-blue-600">Garansi (RMA)</span>
        </h1>

        @if($step === 1)
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-6">Langkah 1: Pilih Pesanan</h2>
                
                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-slate-500 mb-4">Anda belum memiliki pesanan yang selesai untuk diklaim.</p>
                        <a href="{{ route('store.catalog') }}" class="text-blue-600 font-bold hover:underline">Belanja Sekarang</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <button wire:click="selectOrder({{ $order->id }})" class="w-full text-left bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition-all group">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-bold text-slate-800 dark:text-white group-hover:text-blue-600 transition-colors">{{ $order->order_number }}</span>
                                    <span class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }} • {{ $order->items->count() }} Items
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

        @elseif($step === 2)
            <div class="space-y-6 animate-fade-in-up">
                <!-- Header -->
                <div class="flex items-center gap-4">
                    <button wire:click="resetSelection" class="p-2 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-800">
                        &larr; Kembali
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Langkah 2: Detail Klaim</h2>
                        <p class="text-sm text-slate-500">Order: {{ $selectedOrder->order_number }}</p>
                    </div>
                </div>

                <!-- Product Selection -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4">Pilih Produk Bermasalah</h3>
                    <div class="space-y-6">
                        @foreach($selectedOrder->items as $item)
                            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4 {{ isset($rmaItems[$item->id]) ? 'bg-blue-50 dark:bg-blue-900/10 ring-1 ring-blue-500' : 'bg-white dark:bg-slate-900' }}">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 pt-1">
                                        <input type="checkbox" wire:click="toggleItem({{ $item->id }})" {{ isset($rmaItems[$item->id]) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</h4>
                                            <span class="text-xs font-mono text-slate-500">Qty Beli: {{ $item->quantity }}</span>
                                        </div>
                                        
                                        <!-- Form for Selected Item -->
                                        @if(isset($rmaItems[$item->id]))
                                            <div class="mt-4 space-y-4 border-t border-slate-200 dark:border-slate-700 pt-4 animate-fade-in">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah Klaim</label>
                                                        <input type="number" wire:model="rmaItems.{{ $item->id }}.qty" min="1" max="{{ $item->quantity }}" class="w-full rounded-lg border-slate-300 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kondisi Fisik</label>
                                                        <select wire:model="rmaItems.{{ $item->id }}.condition" class="w-full rounded-lg border-slate-300 text-sm">
                                                            <option value="used">Bekas Pakai (Normal)</option>
                                                            <option value="damaged_box">Box Rusak</option>
                                                            <option value="scratched">Lecet / Gores</option>
                                                            <option value="new_open_box">Baru (Buka Segel)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Deskripsi Masalah</label>
                                                    <textarea wire:model="rmaItems.{{ $item->id }}.reason" rows="2" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Jelaskan kerusakan secara detail..."></textarea>
                                                    @error("rmaItems.{$item->id}.reason") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Upload Bukti (Foto/Video)</label>
                                                    <input type="file" wire:model="uploads.{{ $item->id }}" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                                    <p class="text-xs text-slate-400 mt-1">Max 2MB per file.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Resolution Preference -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-xl border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4">Preferensi Solusi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer border p-4 rounded-xl hover:bg-slate-50 {{ $resolutionPreference === 'repair' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-slate-200' }}">
                            <input type="radio" wire:model="resolutionPreference" value="repair" class="sr-only">
                            <span class="block font-bold text-slate-800">Perbaikan (Service)</span>
                            <span class="text-xs text-slate-500">Kami akan mencoba memperbaiki unit Anda.</span>
                        </label>
                        <label class="cursor-pointer border p-4 rounded-xl hover:bg-slate-50 {{ $resolutionPreference === 'replacement' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-slate-200' }}">
                            <input type="radio" wire:model="resolutionPreference" value="replacement" class="sr-only">
                            <span class="block font-bold text-slate-800">Tukar Baru</span>
                            <span class="text-xs text-slate-500">Jika stok tersedia dan kerusakan memenuhi syarat.</span>
                        </label>
                        <label class="cursor-pointer border p-4 rounded-xl hover:bg-slate-50 {{ $resolutionPreference === 'refund' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-slate-200' }}">
                            <input type="radio" wire:model="resolutionPreference" value="refund" class="sr-only">
                            <span class="block font-bold text-slate-800">Pengembalian Dana</span>
                            <span class="text-xs text-slate-500">Dana dikembalikan ke saldo / rekening (sesuai kebijakan).</span>
                        </label>
                    </div>

                    <div class="mt-6">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Catatan Tambahan</label>
                        <textarea wire:model="generalNotes" rows="3" class="w-full rounded-lg border-slate-300 text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button wire:click="submitRma" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                        <svg wire:loading.remove class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span wire:loading.remove>Kirim Permintaan RMA</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>