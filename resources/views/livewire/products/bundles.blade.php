<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Product <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-500">Bundles</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kelola paket produk dan bundling stok.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Product List (Select Parent) -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">1. Pilih Produk Induk (Paket)</h3>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm mb-4" placeholder="Cari Produk...">
                
                <div class="space-y-2 max-h-[500px] overflow-y-auto">
                    @foreach($products as $product)
                        <div wire:click="selectParent({{ $product->id }})" 
                             class="p-3 rounded-xl border cursor-pointer transition-all {{ $selectedParentId == $product->id ? 'bg-pink-50 border-pink-500 ring-1 ring-pink-500' : 'bg-slate-50 border-slate-200 hover:bg-white hover:shadow-md' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-slate-700">{{ $product->name }}</h4>
                                    <p class="text-xs text-slate-500">{{ $product->sku }}</p>
                                </div>
                                @if($product->is_bundle)
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-[10px] font-bold">BUNDLE</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $products->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>

        <!-- Right: Bundle Configuration -->
        <div class="lg:col-span-2 space-y-6">
            @if($selectedParentId)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative">
                    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                        <h3 class="font-bold text-slate-800 dark:text-white">2. Konfigurasi Isi Paket</h3>
                        <button wire:click="save" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white font-bold rounded-lg shadow-lg shadow-pink-600/30 transition-all">
                            Simpan Paket
                        </button>
                    </div>

                    <!-- Add Child Form -->
                    <div class="mb-6 relative">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tambah Produk Anak</label>
                        <input wire:model.live.debounce.300ms="childSearch" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm" placeholder="Ketik nama produk untuk ditambahkan...">
                        
                        @if(!empty($childCandidates))
                            <div class="absolute z-10 top-full left-0 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl mt-2 overflow-hidden">
                                @foreach($childCandidates as $child)
                                    <div wire:click="addChild({{ $child->id }})" class="p-3 hover:bg-pink-50 dark:hover:bg-slate-700 cursor-pointer border-b border-slate-100 last:border-0 transition-colors">
                                        <div class="flex justify-between">
                                            <span class="font-bold text-sm text-slate-700 dark:text-slate-300">{{ $child->name }}</span>
                                            <span class="text-xs text-slate-500">Stok: {{ $child->stock_quantity }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Bundle Items List -->
                    <div class="space-y-3">
                        @forelse($bundleItems as $index => $item)
                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 animate-fade-in">
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">{{ $item['name'] }}</h4>
                                </div>
                                <div class="w-32 flex items-center gap-2">
                                    <span class="text-xs text-slate-500">Qty:</span>
                                    <input type="number" wire:change="updateQty({{ $index }}, $event.target.value)" value="{{ $item['qty'] }}" min="1" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-center text-sm font-bold">
                                </div>
                                <button wire:click="removeChild({{ $index }})" class="text-rose-500 hover:bg-rose-100 p-2 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-12 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl text-slate-400">
                                <p>Belum ada produk dalam paket ini.</p>
                                <p class="text-xs mt-1">Gunakan pencarian di atas untuk menambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="h-full flex items-center justify-center text-slate-400 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        <p class="font-bold">Pilih Produk Induk di sebelah kiri</p>
                        <p class="text-sm">untuk mulai mengonfigurasi bundling.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
