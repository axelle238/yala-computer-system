<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Flash <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-500">Sale</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Atur promo waktu terbatas untuk meningkatkan urgensi pembelian.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Jadwalkan Flash Sale
        </button>
    </div>

    <!-- Active Sales Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($sales as $sale)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-rose-500 transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if($sale->product->image_path)
                            <img src="{{ asset('storage/' . $sale->product->image_path) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-lg text-slate-900 dark:text-white truncate pr-2">{{ $sale->product->name }}</h3>
                            <div class="flex gap-2">
                                <button wire:click="delete({{ $sale->id }})" class="text-slate-400 hover:text-rose-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-rose-600 font-black text-xl">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</span>
                            <span class="text-slate-400 text-sm line-through">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500">
                            <span>Kuota: {{ $sale->quota }}</span>
                            <span>{{ \Carbon\Carbon::parse($sale->end_time)->diffForHumans() }}</span>
                        </div>
                        
                        <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-2 overflow-hidden">
                            @php $progress = now()->diffInMinutes($sale->start_time) / \Carbon\Carbon::parse($sale->start_time)->diffInMinutes($sale->end_time) * 100; @endphp
                            <div class="bg-rose-500 h-full" style="width: {{ 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 border border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                Belum ada jadwal Flash Sale.
            </div>
        @endforelse
    </div>

    <!-- FORM MODAL -->
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Jadwal Flash Sale</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                
                <div class="p-6 overflow-y-auto space-y-4">
                    
                    <!-- Product Search -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pilih Produk</label>
                        @if($selectedProductName)
                            <div class="flex items-center justify-between p-3 bg-indigo-50 border border-indigo-100 rounded-xl">
                                <span class="font-bold text-indigo-900">{{ $selectedProductName }}</span>
                                <button wire:click="$set('selectedProductName', '')" class="text-indigo-400 hover:text-indigo-600 text-xs font-bold">Ganti</button>
                            </div>
                        @else
                            <input wire:model.live.debounce.300ms="searchProduct" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-rose-500" placeholder="Ketik nama produk...">
                            @if(!empty($productSearchResults))
                                <div class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                    @foreach($productSearchResults as $p)
                                        <button wire:click="selectProduct({{ $p->id }}, '{{ $p->name }}')" class="w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-50 last:border-0 text-sm font-medium">
                                            {{ $p->name }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        @error('product_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Diskon</label>
                            <input wire:model="discount_price" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-rose-500" placeholder="Rp">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kuota Stok</label>
                            <input wire:model="quota" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-rose-500" placeholder="100">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Waktu Mulai</label>
                            <input wire:model="start_time" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-rose-500 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Waktu Berakhir</label>
                            <input wire:model="end_time" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-rose-500 text-xs">
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input wire:model="is_active" type="checkbox" class="rounded text-rose-600 focus:ring-rose-500 w-5 h-5 border-slate-300">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Aktifkan Segera</label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="$set('showForm', false)" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Batal</button>
                    <button wire:click="save" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shadow-lg transition">Simpan Jadwal</button>
                </div>
            </div>
        </div>
    @endif
</div>