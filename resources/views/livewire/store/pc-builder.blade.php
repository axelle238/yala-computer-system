<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen">
    
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Simulasi Rakit PC</h1>
        <p class="text-slate-500 max-w-2xl mx-auto">Pilih komponen impianmu, hitung estimasi harga, dan konsultasikan langsung dengan teknisi kami.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Selection Area (Left) -->
        <div class="lg:col-span-2 space-y-6">
            @foreach($partsList as $slug => $label)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                    <div class="p-6 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors" @click="open = !open">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                {{ substr($label, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $label }}</h3>
                                @if($selection[$slug])
                                    @php $selected = \App\Models\Product::find($selection[$slug]); @endphp
                                    <p class="text-sm text-emerald-600 font-semibold mt-1">{{ $selected->name }}</p>
                                @else
                                    <p class="text-xs text-slate-400 mt-1 italic">Belum dipilih</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($selection[$slug])
                                <span class="text-sm font-bold text-slate-700">Rp {{ number_format(\App\Models\Product::find($selection[$slug])->sell_price, 0, ',', '.') }}</span>
                                <button wire:click.stop="removePart('{{ $slug }}')" class="p-2 text-rose-500 hover:bg-rose-50 rounded-full transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            @endif
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div x-show="open" class="border-t border-slate-100 bg-slate-50 p-4 max-h-80 overflow-y-auto" style="display: none;">
                        @if($catalog[$slug]->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($catalog[$slug] as $item)
                                    <div wire:click="selectPart('{{ $slug }}', {{ $item->id }}); open = false" class="bg-white p-3 rounded-xl border border-slate-200 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all flex items-start gap-3">
                                        @if($item->image_path)
                                            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-12 h-12 object-cover rounded-lg bg-slate-100">
                                        @else
                                            <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-300">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800 line-clamp-2">{{ $item->name }}</h4>
                                            <p class="text-xs text-blue-600 font-bold mt-1">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-400 text-sm">
                                Stok komponen ini sedang kosong.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary (Sticky Right) -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-slate-200/50 p-6">
                <h3 class="text-xl font-extrabold text-slate-900 mb-6">Ringkasan Rakitan</h3>
                
                <div class="space-y-3 mb-6">
                    @php $itemCount = 0; @endphp
                    @foreach($selection as $slug => $id)
                        @if($id)
                            @php $itemCount++; @endphp
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 truncate w-2/3">{{ \App\Models\Product::find($id)->name }}</span>
                                <span class="font-semibold text-slate-800">{{ number_format(\App\Models\Product::find($id)->sell_price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endforeach
                    
                    @if($itemCount === 0)
                        <p class="text-sm text-slate-400 italic text-center py-4">Belum ada komponen dipilih.</p>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-4 mb-6">
                    <div class="flex justify-between items-end">
                        <span class="text-sm font-bold text-slate-500">Total Estimasi</span>
                        <span class="text-3xl font-extrabold text-blue-600">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button wire:click="sendToWhatsapp" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2 transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed" {{ $itemCount === 0 ? 'disabled' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pesan Rakitan Ini
                </button>
                
                <p class="text-xs text-center text-slate-400 mt-4">
                    Harga dapat berubah sewaktu-waktu. Konsultasikan ketersediaan stok dengan admin.
                </p>
            </div>
        </div>
    </div>
</div>
