<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Employee <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">Reimbursement</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Klaim biaya operasional dan pengeluaran dinas.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Ajukan Klaim Baru</h3>
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tanggal</label>
                        <input type="date" wire:model="date" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                        @error('date') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kategori</label>
                        <select wire:model="category" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                            <option value="Transport">Transportasi / Bensin</option>
                            <option value="Meal">Makan Lembur</option>
                            <option value="Medical">Kesehatan</option>
                            <option value="Office">ATK / Kantor</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah (Rp)</label>
                        <input type="number" wire:model="amount" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-bold">
                        @error('amount') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Keterangan</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm"></textarea>
                        @error('description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Bukti Foto (Struk)</label>
                        <input type="file" wire:model="proof" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        @error('proof') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-lg transition-all text-sm">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

        <!-- List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-4">No. Klaim</th>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-right">Jumlah</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($claims as $claim)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-700 dark:text-slate-300">
                                    {{ $claim->claim_number }}
                                    <div class="text-[10px] text-slate-400 font-normal mt-0.5">{{ $claim->date->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold">{{ $claim->user->name }}</td>
                                <td class="px-6 py-4">{{ $claim->category }}</td>
                                <td class="px-6 py-4 text-right font-mono font-bold">Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-rose-100 text-rose-700',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $colors[$claim->status] ?? 'bg-slate-100' }}">
                                        {{ $claim->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($claim->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->isOwner()))
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="approve({{ $claim->id }})" wire:confirm="Setujui klaim ini?" class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                            <button wire:click="reject({{ $claim->id }})" wire:confirm="Tolak klaim ini?" class="p-1.5 bg-rose-100 text-rose-700 rounded-lg hover:bg-rose-200">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    @elseif($claim->proof_file)
                                        <a href="{{ asset('storage/' . $claim->proof_file) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline">Lihat Bukti</a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data klaim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $claims->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
