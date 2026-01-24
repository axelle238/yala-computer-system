<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Flash Sales Manager</h1>
            <p class="text-gray-500">Atur produk promo terbatas waktu untuk halaman depan.</p>
        </div>
        <a href="{{ route('marketing.flash-sale.create') }}" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 font-bold text-sm shadow-lg shadow-rose-500/30">
            + Tambah Flash Sale
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari produk..." class="w-full md:w-1/3 rounded-lg border-gray-300 text-sm">
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Harga Promo</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4 text-center">Quota / Terjual</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $sale->product->name }}</div>
                            <div class="text-xs text-gray-400">{{ $sale->product->sku }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-mono font-bold text-rose-600">Rp {{ number_format($sale->discount_price, 0, ',', '.') }}</div>
                            <div class="text-xs line-through text-gray-400">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="text-emerald-600">Mulai: {{ $sale->start_time->format('d M H:i') }}</div>
                            <div class="text-rose-600">Selesai: {{ $sale->end_time->format('d M H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold">{{ $sale->sold_quantity ?? 0 }}</span> / {{ $sale->quota }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleStatus({{ $sale->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $sale->is_active ? 'bg-blue-600' : 'bg-gray-200' }}">
                                <span class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $sale->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $sale->id }})" wire:confirm="Hapus flash sale ini?" class="text-rose-500 hover:text-rose-700 font-bold text-xs">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada flash sale aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $sales->links() }}
        </div>
    </div>
</div>
