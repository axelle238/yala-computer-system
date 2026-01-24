<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <a href="{{ route('member.dashboard') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white text-sm mb-2 inline-block">&larr; Kembali</a>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Penawaran <span class="text-blue-600">{{ $quotation->quotation_number }}</span>
                </h1>
                <p class="text-slate-500">Dibuat pada {{ $quotation->created_at->format('d F Y') }}</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="printPdf" class="px-6 py-2 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition-colors">
                    Download PDF
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Banner -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-2">Status Penawaran</h3>
                    <div class="flex items-center gap-4">
                        @if($quotation->approval_status === 'pending')
                            <div class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-bold text-sm">
                                Menunggu Review Admin
                            </div>
                            <p class="text-sm text-slate-500">Kami sedang meninjau permintaan Anda. Estimasi 1x24 jam.</p>
                        @elseif($quotation->approval_status === 'approved' && !$quotation->converted_order_id)
                            <div class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-bold text-sm">
                                Disetujui / Siap Diterima
                            </div>
                            <p class="text-sm text-slate-500">Penawaran telah disetujui. Silakan tinjau harga dan terima jika setuju.</p>
                        @elseif($quotation->converted_order_id)
                            <div class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg font-bold text-sm">
                                Selesai (Dikonversi ke Order)
                            </div>
                        @elseif($quotation->approval_status === 'rejected')
                            <div class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-bold text-sm">
                                Ditolak
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="p-4">Item</th>
                                <th class="p-4 text-center">Qty</th>
                                <th class="p-4 text-right">Harga Satuan</th>
                                <th class="p-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($quotation->items as $item)
                                <tr>
                                    <td class="p-4 font-medium text-slate-800 dark:text-white">{{ $item->item_name }}</td>
                                    <td class="p-4 text-center text-slate-600">{{ $item->quantity }}</td>
                                    <td class="p-4 text-right text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="p-4 text-right font-bold text-slate-800 dark:text-white">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700">
                            <tr>
                                <td colspan="3" class="p-4 text-right font-bold text-slate-600 uppercase">Grand Total</td>
                                <td class="p-4 text-right font-black text-xl text-blue-600">Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($quotation->terms_and_conditions)
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-2">Syarat & Ketentuan</h3>
                        <div class="prose prose-sm dark:prose-invert text-slate-600">
                            {!! nl2br(e($quotation->terms_and_conditions)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-4">Informasi Pelanggan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block text-slate-500 text-xs uppercase">Nama</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $quotation->user->name }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 text-xs uppercase">Email</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $quotation->user->email }}</span>
                        </div>
                        <div>
                            <span class="block text-slate-500 text-xs uppercase">Berlaku Hingga</span>
                            <span class="font-bold text-red-600">{{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                @if($quotation->approval_status === 'approved' && !$quotation->converted_order_id)
                    <button wire:click="acceptQuote" wire:confirm="Anda yakin ingin menerima penawaran ini? Pesanan akan dibuat otomatis." class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all text-lg">
                        Terima Penawaran
                    </button>
                    <p class="text-xs text-center text-slate-500 mt-2">
                        Dengan mengklik tombol di atas, Anda menyetujui harga dan syarat yang berlaku.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>