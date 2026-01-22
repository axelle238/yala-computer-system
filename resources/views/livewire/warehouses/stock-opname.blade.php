<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Stock <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Opname</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Penyesuaian stok fisik dan sistem.</p>
        </div>
        
        <!-- Search -->
        <div class="flex gap-3 bg-white dark:bg-slate-800 p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 placeholder-slate-400 w-full md:w-64" placeholder="Cari barang...">
            <div class="w-px bg-slate-200 dark:bg-slate-700 my-1"></div>
            <select wire:model.live="category" class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0 cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Produk Info</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Stok Sistem</th>
                        <th class="px-6 py-4 w-48 text-center">Stok Fisik (Real)</th>
                        <th class="px-6 py-4 w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 dark:text-slate-300">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-contain p-1">
                                        @else
                                            {{ substr($product->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white line-clamp-1">{{ $product->name }}</p>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $product->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $product->category->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-300 text-lg">{{ $product->stock_quantity }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <input 
                                    wire:model="adjustments.{{ $product->id }}" 
                                    type="number" 
                                    class="w-full text-center font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition-all placeholder-slate-300"
                                    placeholder="{{ $product->stock_quantity }}"
                                    min="0"
                                >
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(isset($adjustments[$product->id]) && $adjustments[$product->id] !== '')
                                    <button 
                                        wire:click="saveAdjustment({{ $product->id }})" 
                                        class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold shadow-md transition-all flex items-center justify-center gap-1 w-full"
                                    >
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Simpan
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Barang tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $products->links() }}
        </div>
    </div>

    <!-- History Section -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-900 dark:text-white">Riwayat Penyesuaian Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($history as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                {{ $log->product->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                {{ $log->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ str_contains($log->notes, 'Surplus') ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    {{ $log->notes }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="deleteAdjustment({{ $log->id }})" class="text-rose-500 hover:text-rose-700 font-bold text-xs underline">
                                    Batalkan
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada riwayat penyesuaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
