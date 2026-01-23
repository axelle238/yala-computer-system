<div class="flex gap-6 h-[calc(100vh-8rem)]">
    <!-- Left: Form -->
    <div class="w-1/3 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Buat Penawaran</h2>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold mb-1">No. Penawaran</label>
                <input wire:model="quote_number" type="text" class="w-full bg-slate-100 border-none rounded-lg font-mono font-bold" readonly>
            </div>
            
            <div>
                <label class="block text-sm font-bold mb-1">Pilih Customer (B2B)</label>
                <select wire:model="customer_id" class="w-full bg-slate-50 border-none rounded-lg">
                    <option value="">-- Pilih Customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Personal' }})</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">Berlaku Sampai</label>
                <input wire:model="valid_until" type="date" class="w-full bg-slate-50 border-none rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">Cari Produk</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full bg-slate-50 border-none rounded-lg" placeholder="Ketik nama produk...">
                
                @if(!empty($products))
                    <div class="mt-2 space-y-1">
                        @foreach($products as $p)
                            <button wire:click="addToCart({{ $p->id }})" class="w-full text-left px-3 py-2 bg-slate-50 hover:bg-cyan-50 rounded-lg text-sm flex justify-between">
                                <span>{{ $p->name }}</span>
                                <span class="font-bold">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="flex-1 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col">
        <h3 class="text-lg font-bold mb-4">Item Penawaran</h3>
        
        <div class="flex-1 overflow-y-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cart as $index => $item)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $item['name'] }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <input type="number" wire:change="updateQty({{ $index }}, $event.target.value)" value="{{ $item['qty'] }}" class="w-16 text-center border rounded py-1">
                        </td>
                        <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="remove({{ $index }})" class="text-red-500 font-bold">x</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t pt-4 mt-4">
            <div class="flex justify-between items-end mb-4">
                <span class="text-lg font-bold">Total Estimasi</span>
                <span class="text-3xl font-black font-tech">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
            </div>
            <button wire:click="save" class="w-full py-3 bg-slate-900 text-white rounded-xl font-bold hover:shadow-lg transition-all">SIMPAN DRAFT PENAWARAN</button>
        </div>
    </div>
</div>
