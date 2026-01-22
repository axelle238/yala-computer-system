<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pusat Servis</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Kelola antrian dan status perbaikan perangkat pelanggan.</p>
        </div>
        <a href="{{ route('services.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tiket Baru
        </a>
    </div>

    <!-- Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Cari No. Tiket / Nama Pelanggan...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
        <select wire:model.live="statusFilter" class="px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu</option>
            <option value="repairing">Sedang Dikerjakan</option>
            <option value="ready">Siap Diambil</option>
            <option value="picked_up">Selesai</option>
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tiket</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Perangkat</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Biaya</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-1 rounded text-xs border border-slate-200">
                                    {{ $ticket->ticket_number }}
                                </span>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $ticket->customer_name }}</div>
                                <div class="text-xs text-slate-500">{{ $ticket->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $ticket->device_name }}</div>
                                <div class="text-xs text-slate-500 truncate max-w-[150px]" title="{{ $ticket->problem_description }}">
                                    {{ Str::limit($ticket->problem_description, 30) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $ticket->status_color }}">
                                    {{ $ticket->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700">Rp {{ number_format($ticket->estimated_cost, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('print.service', $ticket->id) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors" title="Cetak Tanda Terima">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </a>
                                    <a href="{{ route('services.edit', $ticket->id) }}" class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada tiket servis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
