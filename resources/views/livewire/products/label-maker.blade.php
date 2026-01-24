<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 print:p-0 print:max-w-none">
    
    <!-- NO PRINT UI -->
    <div class="print:hidden">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Cetak Label Produk</h2>
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 shadow-lg font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Sekarang
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Selector Panel -->
            <div class="bg-white shadow rounded-xl p-6 border border-slate-200 h-fit">
                <h3 class="font-bold text-slate-700 mb-4">Pilih Produk</h3>
                
                <div class="relative mb-4">
                    <input type="text" wire:model.live.debounce="search" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500" placeholder="Cari nama / SKU...">
                    
                    @if(!empty($searchResults))
                        <div class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded-lg shadow-xl max-h-48 overflow-y-auto">
                            @foreach($searchResults as $p)
                                <button wire:click="addItem({{ $p->id }})" class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-sm border-b border-slate-50 last:border-0 flex justify-between">
                                    <span class="font-medium text-slate-800">{{ $p->name }}</span>
                                    <span class="text-slate-500 text-xs">{{ $p->sku }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="space-y-2">
                    @foreach($selectedProducts as $p)
                        <div class="flex justify-between items-center bg-slate-50 p-2 rounded border border-slate-200">
                            <div class="text-sm font-medium text-slate-700 truncate w-32">{{ $p->name }}</div>
                            <div class="flex items-center gap-2">
                                <input type="number" wire:change="updateQty({{ $p->id }}, $event.target.value)" value="{{ $items[$p->id] }}" class="w-16 text-center text-sm border-slate-300 rounded p-1">
                                <button wire:click="removeItem({{ $p->id }})" class="text-rose-500 hover:text-rose-700">&times;</button>
                            </div>
                        </div>
                    @endforeach
                    @if($selectedProducts->isEmpty())
                        <div class="text-center text-slate-400 text-sm py-4 italic">Belum ada produk dipilih.</div>
                    @endif
                </div>

                <div class="mt-6 border-t pt-4">
                    <h4 class="font-bold text-slate-700 mb-2 text-sm">Konfigurasi Cetak</h4>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" wire:model.live="showPrice" class="rounded text-indigo-600"> Tampilkan Harga
                        </label>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" wire:model.live="showName" class="rounded text-indigo-600"> Tampilkan Nama Produk
                        </label>
                        <select wire:model.live="paperSize" class="w-full mt-2 text-sm border-slate-300 rounded">
                            <option value="a4">Kertas A4 (Grid 3x7)</option>
                            <option value="thermal">Printer Thermal (Roll)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preview Panel (Visual Guide) -->
            <div class="lg:col-span-2 bg-slate-200 p-8 rounded-xl flex justify-center overflow-auto max-h-[80vh]">
                <div class="bg-white shadow-2xl p-8 min-h-[297mm] w-[210mm] transform scale-75 origin-top">
                    <!-- Simulate A4 Page Structure -->
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($selectedProducts as $p)
                            @for($i=0; $i<$items[$p->id]; $i++)
                                <div class="border border-dashed border-slate-300 p-2 text-center flex flex-col items-center justify-center h-24">
                                    @if($showName)
                                        <div class="text-[10px] font-bold leading-tight mb-1 line-clamp-2">{{ $p->name }}</div>
                                    @endif
                                    <!-- Barcode Placeholder (Real Barcode in Print View) -->
                                    <div class="bg-black h-8 w-3/4 mb-1"></div>
                                    <div class="text-[9px] font-mono">{{ $p->sku }}</div>
                                    @if($showPrice)
                                        <div class="text-[10px] font-bold">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            @endfor
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRINT VIEW ONLY -->
    <div class="hidden print:block">
        @if($paperSize == 'a4')
            <style>
                @page { size: A4; margin: 10mm; }
                .label-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5mm; }
                .label-item { border: 1px solid #ddd; padding: 2mm; text-align: center; height: 35mm; display: flex; flex-direction: column; justify-content: center; align-items: center; page-break-inside: avoid; }
            </style>
            <div class="label-grid">
                @foreach($selectedProducts as $p)
                    @for($i=0; $i<$items[$p->id]; $i++)
                        <div class="label-item">
                            @if($showName)
                                <div class="text-[10pt] font-bold leading-tight mb-1">{{ $p->name }}</div>
                            @endif
                            <!-- Simple Barcode Simulation using Font/Divs -->
                            <div class="flex justify-center my-1 h-8 w-full px-4">
                                @php
                                    // Simple visual simulation of barcode bars
                                    $code = crc32($p->sku);
                                @endphp
                                @for($j=0; $j<30; $j++)
                                    <div class="bg-black h-full" style="width: {{ rand(1,3) }}px; margin-right: 1px;"></div>
                                @endfor
                            </div>
                            <div class="text-[8pt] font-mono">{{ $p->sku }}</div>
                            @if($showPrice)
                                <div class="text-[9pt] font-bold mt-1">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    @endfor
                @endforeach
            </div>
        @else
            <!-- Thermal Layout -->
            <style>
                @page { size: 80mm auto; margin: 0; }
                .thermal-item { width: 76mm; margin-bottom: 5mm; text-align: center; page-break-after: always; padding: 2mm; }
            </style>
            @foreach($selectedProducts as $p)
                @for($i=0; $i<$items[$p->id]; $i++)
                    <div class="thermal-item">
                        @if($showName)
                            <div class="text-sm font-bold mb-1">{{ $p->name }}</div>
                        @endif
                        <div class="flex justify-center my-1 h-10 w-full px-2">
                             @for($j=0; $j<40; $j++)
                                <div class="bg-black h-full" style="width: {{ rand(1,4) }}px; margin-right: 1px;"></div>
                            @endfor
                        </div>
                        <div class="text-xs font-mono">{{ $p->sku }}</div>
                        @if($showPrice)
                            <div class="text-sm font-bold mt-1">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                @endfor
            @endforeach
        @endif
    </div>
</div>
