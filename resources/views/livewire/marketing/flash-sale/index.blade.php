<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Flash Sale Manager</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Atur jadwal diskon kilat untuk menarik pelanggan.</p>
        </div>
        <a href="{{ route('marketing.flash-sale.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Promo Baru
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Harga Promo</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4 text-center">Quota</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $sale->product->name }}
                                <div class="text-xs text-slate-400 line-through">Rp {{ number_format($sale->product->sell_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-rose-600">
                                Rp {{ number_format($sale->discount_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="font-bold">Mulai: {{ $sale->start_time->format('d M H:i') }}</div>
                                <div class="text-slate-400">Selesai: {{ $sale->end_time->format('d M H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-mono">
                                {{ $sale->quota }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sale->isRunning())
                                    <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold animate-pulse">LIVE</span>
                                @elseif(now() > $sale->end_time)
                                    <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-xs font-bold">Expired</span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">Scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="delete({{ $sale->id }})" wire:confirm="Hapus promo ini?" class="text-slate-400 hover:text-rose-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada promo aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $sales->links() }}
        </div>
    </div>
</div>
