<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Detail <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500">Pengajuan</span>
            </h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-slate-500 dark:text-slate-400 font-mono font-bold">{{ $pr->pr_number }}</span>
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                        'converted_to_po' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    ];
                @endphp
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$pr->status] }}">
                    {{ str_replace('_', ' ', $pr->status) }}
                </span>
            </div>
        </div>
        <a href="{{ route('purchase-requisitions.index') }}" class="text-slate-500 hover:text-slate-700 font-bold text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-4 tracking-wider">Informasi Pengajuan</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-slate-500">Requester</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $pr->requester->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Tanggal Pengajuan</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $pr->created_at->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-slate-500">Tanggal Dibutuhkan</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $pr->required_date->format('d M Y') }}</dd>
                </div>
                @if($pr->approved_by)
                <div class="flex justify-between">
                    <dt class="text-slate-500">Disetujui Oleh</dt>
                    <dd class="font-bold text-slate-900 dark:text-white">{{ $pr->approver->name }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="text-xs font-bold uppercase text-slate-500 mb-4 tracking-wider">Catatan</h3>
            <p class="text-sm text-slate-700 dark:text-slate-300 italic">
                "{{ $pr->notes ?: 'Tidak ada catatan.' }}"
            </p>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white">Daftar Barang Diminta</h3>
        </div>
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4 text-center">Qty</th>
                    <th class="px-6 py-4">Catatan Item</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($pr->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            {{ $item->product->name }}
                            <div class="text-[10px] text-slate-500 font-normal mt-0.5">{{ $item->product->sku }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-mono">
                            {{ $item->quantity_requested }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 italic">
                            {{ $item->notes ?: '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Actions -->
    @if(auth()->user()->isAdmin() && $pr->status === 'pending')
        <div class="flex items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-700">
            <button wire:click="reject" wire:confirm="Yakin ingin menolak pengajuan ini?" class="flex-1 py-3 bg-rose-100 text-rose-700 hover:bg-rose-200 font-bold rounded-xl transition-colors">
                Tolak Pengajuan
            </button>
            <button wire:click="approve" wire:confirm="Setujui pengajuan ini?" class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all">
                Setujui Pengajuan
            </button>
        </div>
    @endif

    @if($pr->status === 'approved' && auth()->user()->isAdmin())
        <div class="flex justify-end pt-4">
            <button wire:click="convertToPo" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                Buat Purchase Order (PO)
            </button>
        </div>
    @endif
</div>
