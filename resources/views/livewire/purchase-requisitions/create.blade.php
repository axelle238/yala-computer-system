<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Buat <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Pengajuan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Form permintaan pembelian barang baru.</p>
        </div>
        <a href="{{ route('purchase-requisitions.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Main Info -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">No. PR (Otomatis)</label>
                    <input type="text" wire:model="pr_number" class="w-full bg-slate-100 dark:bg-slate-700 border-none rounded-xl text-slate-500 font-mono font-bold cursor-not-allowed" readonly>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tanggal Dibutuhkan</label>
                    <input type="date" wire:model="required_date" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-purple-500 text-sm">
                    @error('required_date') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Catatan / Alasan</label>
                    <textarea wire:model="notes" rows="2" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-purple-500 text-sm" placeholder="Contoh: Stok menipis untuk project A..."></textarea>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Daftar Barang
                </h3>
                <button type="button" wire:click="addItem" class="text-xs font-bold text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors border border-purple-200">
                    + Tambah Baris
                </button>
            </div>

            <div class="space-y-4">
                @foreach($items as $index => $item)
                    <div class="flex flex-col md:flex-row gap-4 items-start bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 animate-fade-in relative group">
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Produk</label>
                            <select wire:model="items.{{ $index }}.product_id" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-purple-500">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock_quantity }})</option>
                                @endforeach
                            </select>
                            @error('items.'.$index.'.product_id') <span class="text-rose-500 text-[10px] block mt-1">Wajib dipilih</span> @enderror
                        </div>

                        <div class="w-24">
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Qty</label>
                            <input type="number" wire:model="items.{{ $index }}.qty" min="1" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-purple-500 text-center">
                            @error('items.'.$index.'.qty') <span class="text-rose-500 text-[10px] block mt-1">Min 1</span> @enderror
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Catatan Item (Opsional)</label>
                            <input type="text" wire:model="items.{{ $index }}.notes" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-purple-500" placeholder="Spesifikasi khusus...">
                        </div>

                        @if(count($items) > 1)
                            <button type="button" wire:click="removeItem({{ $index }})" class="mt-6 p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Baris">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg shadow-purple-600/30 hover:-translate-y-1 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Kirim Pengajuan
            </button>
        </div>
    </form>
</div>
