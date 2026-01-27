<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quotation Manager (B2B)</h1>
            <p class="text-gray-500">Kelola permintaan penawaran harga dari pelanggan korporat.</p>
        </div>
        <a href="{{ route('admin.penawaran.buat') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
            + Buat Penawaran Manual
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Menunggu Persetujuan</p>
                <h3 class="text-2xl font-black text-yellow-600">{{ $pendingCount }}</h3>
            </div>
            <div class="bg-yellow-100 p-2 rounded-lg text-yellow-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Approved / Waiting</p>
                <h3 class="text-2xl font-black text-blue-600">{{ $approvedCount }}</h3>
            </div>
            <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase">Converted to Order</p>
                <h3 class="text-2xl font-black text-emerald-600">{{ $convertedCount }}</h3>
            </div>
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari No. Penawaran / Pelanggan..." class="w-full md:w-1/3 rounded-lg border-gray-300 text-sm">
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Nomor</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($quotes as $quote)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-mono font-medium text-gray-900">
                            {{ $quote->quotation_number }}
                            <div class="text-xs text-gray-400 mt-1">{{ $quote->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $quote->pengguna->name }}</div>
                            <div class="text-xs">{{ $quote->pengguna->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($quote->converted_order_id)
                                <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold uppercase">Converted</span>
                            @elseif($quote->approval_status === 'pending')
                                <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs font-bold uppercase">Menunggu Persetujuan</span>
                            @elseif($quote->approval_status === 'approved')
                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-bold uppercase">Approved</span>
                            @else
                                <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-bold uppercase">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                            Rp {{ number_format($quote->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.penawaran.ubah', $quote->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            Tidak ada data penawaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $quotes->links() }}
        </div>
    </div>
</div>
