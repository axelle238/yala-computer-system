<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Penawaran <span class="text-blue-600">Saya</span>
            </h1>
            <a href="{{ route('anggota.beranda') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <tr>
                            <th class="p-6">Nomor Penawaran</th>
                            <th class="p-6">Tanggal</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Total Nilai</th>
                            <th class="p-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                        @forelse($quotations as $quote)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="p-6 font-mono font-bold text-slate-800 dark:text-white">
                                    {{ $quote->quotation_number }}
                                </td>
                                <td class="p-6 text-slate-500">
                                    {{ $quote->created_at->format('d M Y') }}
                                </td>
                                <td class="p-6">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-blue-100 text-blue-700', // Admin approved, waiting user
                                            'accepted' => 'bg-emerald-100 text-emerald-700', // User accepted
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                        // Map logic
                                        $status = $quote->approval_status === 'pending' ? 'pending' : $quote->approval_status;
                                        if ($quote->converted_order_id) $status = 'accepted';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $colors[$status] ?? 'bg-slate-100' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="p-6 text-right font-bold text-slate-800 dark:text-white">
                                    Rp {{ number_format($quote->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="p-6 text-center">
                                    <a href="{{ route('quotations.show', $quote->id) }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400">
                                    Belum ada riwayat penawaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($quotations->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                    {{ $quotations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
