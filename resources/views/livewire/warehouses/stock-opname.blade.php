<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Stok <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Opname</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Audit inventaris fisik, rekonsiliasi selisih, dan penyesuaian stok.</p>
        </div>
        
        <div class="flex gap-2">
            @if($isSessionActive)
                <button wire:click="finalizeOpname" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2"
                        onclick="confirm('Yakin ingin menyelesaikan Stok Opname? Stok sistem akan disesuaikan dengan stok fisik yang diinput.') || event.stopImmediatePropagation()">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Selesaikan & Sesuaikan
                </button>
                <button wire:click="cancelSession" class="px-6 py-3 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold rounded-xl transition-all" onclick="confirm('Batalkan sesi ini? Inputan akan hilang.') || event.stopImmediatePropagation()">
                    Batal
                </button>
            @else
                <button wire:click="startSession" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Mulai Sesi Opname Baru
                </button>
            @endif
        </div>
    </div>

    <!-- Notification -->
    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Active Session View -->
    @if($isSessionActive)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col h-full">
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-96">
                    <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-amber-500 text-sm" placeholder="Scan Barcode / Cari Nama Produk...">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                        <span class="text-slate-600 dark:text-slate-300">Cocok: <b>{{ $stats['matched'] }}</b></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-rose-500 rounded-full"></span>
                        <span class="text-slate-600 dark:text-slate-300">Selisih: <b>{{ $stats['mismatch'] }}</b></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-slate-300 rounded-full"></span>
                        <span class="text-slate-600 dark:text-slate-300">Belum: <b>{{ $stats['pending'] }}</b></span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4 text-center w-32">Stok Sistem</th>
                            <th class="px-6 py-4 text-center w-40">Stok Fisik (Input)</th>
                            <th class="px-6 py-4 text-center w-32">Selisih</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($products as $product)
                            @php 
                                $inputVal = $tempStock[$product->id] ?? null;
                                $variance = is_numeric($inputVal) ? $inputVal - $product->stock_quantity : null;
                                $statusClass = is_null($inputVal) ? 'bg-slate-100 text-slate-500' : ($variance == 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700');
                                $statusText = is_null($inputVal) ? 'Belum Dihitung' : ($variance == 0 ? 'Cocok' : ($variance > 0 ? 'Surplus (+)' : 'Defisit (-)'));
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ !is_null($inputVal) && $variance != 0 ? 'bg-rose-50/30 dark:bg-rose-900/10' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $product->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $product->sku }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300">{{ $product->stock_quantity }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" 
                                           wire:model.blur="tempStock.{{ $product->id }}" 
                                           class="w-24 text-center font-bold border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-slate-800 dark:bg-slate-900 dark:border-slate-600 dark:text-white"
                                           placeholder="...">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!is_null($variance))
                                        <span class="font-bold {{ $variance == 0 ? 'text-emerald-600' : ($variance > 0 ? 'text-blue-600' : 'text-rose-600') }}">
                                            {{ $variance > 0 ? '+' : '' }}{{ $variance }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <!-- Empty State / Start Screen -->
        <div class="text-center py-20 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 border-dashed">
            <div class="w-24 h-24 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Siap Melakukan Stok Opname?</h3>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8">
                Proses ini akan membekukan aktivitas stok sementara. Anda dapat membandingkan stok fisik dengan sistem dan melakukan penyesuaian massal.
            </p>
            <button wire:click="startSession" class="px-8 py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-xl shadow-amber-500/30 transition-all transform hover:-translate-y-1">
                Mulai Audit Sekarang
            </button>
        </div>
    @endif
</div>
