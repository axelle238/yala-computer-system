<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen relative">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <!-- Header -->
    <div class="text-center mb-16 relative z-10">
        <h1 class="text-4xl md:text-5xl font-black font-tech text-white tracking-tight mb-4 uppercase drop-shadow-sm">
            Simulasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Rakit PC</span>
        </h1>
        <p class="text-slate-400 max-w-2xl mx-auto text-lg">Bangun PC impianmu dengan tool simulasi real-time. Pilih komponen, cek kompatibilitas, dan dapatkan penawaran terbaik.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start relative z-10">
        
        <!-- Selection Area (Left) -->
        <div class="lg:col-span-2 space-y-6">
            @foreach($partsList as $slug => $label)
                <div class="group bg-slate-900/80 backdrop-blur-md rounded-2xl border border-white/10 shadow-lg shadow-black/20 overflow-hidden transition-all duration-300 hover:shadow-cyan-500/10 hover:border-cyan-500/30" x-data="{ open: false }">
                    <div class="p-6 flex items-start justify-between cursor-pointer" @click="open = !open">
                        <div class="flex items-start gap-5 flex-1">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-800 to-slate-700 text-slate-400 flex items-center justify-center font-bold text-lg shadow-inner group-hover:from-cyan-600 group-hover:to-blue-600 group-hover:text-white transition-all duration-300 flex-shrink-0">
                                {{ substr($label, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-white text-lg group-hover:text-cyan-400 transition-colors">{{ $label }}</h3>
                                @if($selection[$slug])
                                    @php $selected = \App\Models\Product::find($selection[$slug]); @endphp
                                    <div class="mt-2">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <p class="text-sm text-white font-bold line-clamp-1">{{ $selected->name }}</p>
                                        </div>
                                        <!-- Show Specs -->
                                        <p class="text-xs text-slate-400 leading-relaxed">{{ $selected->description }}</p>
                                    </div>
                                @else
                                    <p class="text-xs text-slate-500 mt-1 font-medium uppercase tracking-wider">Belum dipilih</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-4 pl-4">
                            @if($selection[$slug])
                                <span class="text-base font-bold font-tech text-white whitespace-nowrap">Rp {{ number_format(\App\Models\Product::find($selection[$slug])->sell_price, 0, ',', '.') }}</span>
                                <button wire:click.stop="removePart('{{ $slug }}')" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-rose-500/20 hover:text-rose-500 rounded-lg transition-colors" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            @else
                                <div class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-500 group-hover:bg-cyan-500/10 group-hover:text-cyan-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="border-t border-white/5 bg-slate-950/50 p-4 max-h-96 overflow-y-auto custom-scrollbar" style="display: none;">
                        @if($catalog[$slug]->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($catalog[$slug] as $item)
                                    <div wire:click="selectPart('{{ $slug }}', {{ $item->id }}); open = false" class="bg-slate-900 p-4 rounded-xl border border-white/5 cursor-pointer hover:border-cyan-500/50 hover:shadow-lg hover:shadow-cyan-900/20 transition-all group/item flex items-start gap-4 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-transparent opacity-0 group-hover/item:opacity-100 transition-opacity pointer-events-none"></div>
                                        
                                        <div class="w-16 h-16 bg-white rounded-lg border border-slate-700 flex items-center justify-center p-1 relative z-10 shrink-0">
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/' . $item->image_path) }}" class="max-w-full max-h-full object-contain mix-blend-multiply">
                                            @else
                                                <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 relative z-10 min-w-0">
                                            <h4 class="text-sm font-bold text-slate-200 line-clamp-2 leading-snug group-hover/item:text-cyan-400 transition-colors">{{ $item->name }}</h4>
                                            <p class="text-[10px] text-slate-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                                            <div class="mt-2 flex justify-between items-end">
                                                <p class="text-sm font-tech font-bold text-white">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</p>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-400 group-hover/item:bg-cyan-500/20 group-hover/item:text-cyan-400 transition-colors">Pilih</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 text-slate-500">
                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <p class="text-sm">Stok komponen ini sedang kosong.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary (Sticky Right) -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-slate-900/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl shadow-blue-900/10 p-0 overflow-hidden tech-card">
                <div class="p-6 bg-gradient-to-br from-slate-950 to-slate-900 text-white relative overflow-hidden border-b border-white/5">
                    <div class="absolute inset-0 grid-pattern opacity-10"></div>
                    <h3 class="text-xl font-bold font-tech relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Ringkasan Rakitan
                    </h3>
                </div>
                
                <div class="p-6">
                    <!-- Compatibility Alerts -->
                    @if(count($compatibilityIssues) > 0)
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl">
                            <h4 class="text-sm font-bold text-rose-400 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Isu Kompatibilitas
                            </h4>
                            <ul class="text-xs text-rose-300 space-y-1 list-disc pl-4">
                                @foreach($compatibilityIssues as $issue)
                                    <li>{{ $issue }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif($estimatedWattage > 0)
                        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl flex items-center gap-3">
                            <div class="p-2 bg-emerald-500/20 rounded-lg text-emerald-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-emerald-400 font-bold">Kompatibel</p>
                                <p class="text-[10px] text-slate-400">Estimasi Daya: {{ $estimatedWattage }}W</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4 mb-8">
                        @php $itemCount = 0; @endphp
                        @foreach($selection as $slug => $id)
                            @if($id)
                                @php $itemCount++; @endphp
                                <div class="flex justify-between items-start text-sm group">
                                    <span class="text-slate-400 w-2/3 leading-tight group-hover:text-cyan-400 transition-colors cursor-default">{{ \App\Models\Product::find($id)->name }}</span>
                                    <span class="font-bold text-white font-mono">{{ number_format(\App\Models\Product::find($id)->sell_price, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        @endforeach
                        
                        @if($itemCount === 0)
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                <p class="text-sm text-slate-500 italic">Mulai pilih komponen di sebelah kiri.</p>
                            </div>
                        @endif
                    </div>

                    <div class="border-t-2 border-dashed border-slate-700 pt-6 mb-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Estimasi</span>
                                <p class="text-[10px] text-slate-600 mt-0.5">*Belum termasuk ongkir</p>
                            </div>
                            <span class="text-3xl font-black font-tech text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <button wire:click="addToCart" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-1 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none" {{ $itemCount === 0 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart & Checkout
                        </button>
                        
                        <button wire:click="sendToWhatsapp" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl font-bold border border-slate-700 hover:border-slate-600 transition-all flex items-center justify-center gap-2 disabled:opacity-50" {{ $itemCount === 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Consult via WhatsApp
                        </button>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        <span>Transaksi Aman & Bergaransi Resmi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
