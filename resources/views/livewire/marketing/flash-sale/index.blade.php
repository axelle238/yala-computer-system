<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Flash <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-600">Sale</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kelola promo waktu terbatas untuk meningkatkan penjualan.</p>
        </div>
        <a href="{{ route('marketing.flash-sale.create') }}" class="px-6 py-3 bg-gradient-to-r from-rose-600 to-orange-600 hover:from-rose-500 hover:to-orange-500 text-white font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Buat Flash Sale
        </a>
    </div>

    <!-- Active Sales Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($flashSales as $sale)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:border-rose-300 dark:hover:border-rose-700 transition-all">
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($sale->isRunning())
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Live
                        </span>
                    @elseif(!$sale->is_active)
                        <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold uppercase tracking-wider">Non-Aktif</span>
                    @elseif($sale->end_time < now())
                        <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase tracking-wider">Berakhir</span>
                    @else
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold uppercase tracking-wider">Terjadwal</span>
                    @endif
                </div>

                <div class="flex gap-4">
                    <div class="w-24 h-24 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($sale->product->image_path)
                            <img src="{{ asset('storage/' . $sale->product->image_path) }}" class="w-20 h-20 object-contain">
                        @else
                            <span class="text-xs font-bold text-slate-400">No Image</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-900 dark:text-white text-lg line-clamp-1">{{ $sale->product->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-rose-600 font-bold text-xl">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</span>
                            <span class="text-slate-400 text-sm line-through decoration-rose-500/50">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</span>
                        </div>
                        
                        <!-- Progress -->
                        <div class="mt-3">
                            <div class="flex justify-between text-xs font-bold text-slate-500 mb-1">
                                <span>Stok Promo</span>
                                <span>{{ $sale->quota }} Unit</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-rose-500 to-orange-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center text-sm">
                    <div class="flex flex-col">
                        <span class="text-slate-400 text-xs">Periode</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300">
                            {{ $sale->start_time->format('d M H:i') }} - {{ $sale->end_time->format('d M H:i') }}
                        </span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" wire:click="toggleStatus({{ $sale->id }})" {{ $sale->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-rose-300 dark:peer-focus:ring-rose-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-rose-600"></div>
                    </label>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">Belum ada Flash Sale yang dibuat.</div>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $flashSales->links() }}
    </div>
</div>