<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pembelian</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Buat Pesanan Pembelian (PO) ke Supplier.</p>
        </div>
        <a href="{{ route('purchase-orders.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat PO Baru
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-64 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Cari No. PO / Supplier...">
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nomor PO</th>
                        <th class="px-6 py-4">Supplier</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $po)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 font-mono font-bold text-slate-800">{{ $po->po_number }}</td>
                            <td class="px-6 py-4">{{ $po->supplier->name }}</td>
                            <td class="px-6 py-4">{{ $po->order_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'draft' => 'bg-slate-100 text-slate-600',
                                        'ordered' => 'bg-blue-100 text-blue-600',
                                        'received' => 'bg-emerald-100 text-emerald-600',
                                        'cancelled' => 'bg-rose-100 text-rose-600',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $colors[$po->status] }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800">
                                Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('purchase-orders.edit', $po->id) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
