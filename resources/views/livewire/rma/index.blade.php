<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">Klaim Garansi (RMA)</h1>
        <a href="{{ route('admin.garansi.buat') }}" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-slate-500/20 transition-all">
            + Buat RMA Baru
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex gap-4">
            <input wire:model.live.debounce.300ms="cari" type="text" placeholder="Cari No RMA / Nama..." class="flex-1 bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
            <select wire:model.live="status" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu Konfirmasi</option>
                <option value="approved">Disetujui</option>
                <option value="received_goods">Barang Diterima</option>
                <option value="checking">Pengecekan</option>
                <option value="completed">Selesai</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-700 text-xs uppercase font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">No RMA</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($rmas as $rma)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4 font-bold font-mono text-slate-800 dark:text-white">{{ $rma->rma_number }}</td>
                        <td class="px-6 py-4">
                            {{ $rma->pengguna->name ?? $rma->guest_name }}
                            @if($rma->order)
                                <div class="text-xs text-slate-400">Ref: {{ $rma->order->order_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                {{ $rma->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $rma->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.garansi.ubah', $rma->id) }}" class="text-cyan-600 font-bold hover:underline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data RMA.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $rmas->links() }}
        </div>
    </div>
</div>
