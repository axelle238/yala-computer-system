<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Operational <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-pink-600">Expenses</span>
            </h2>
            <p class="text-slate-500 mt-1 text-sm">Catat dan pantau pengeluaran biaya operasional toko.</p>
        </div>
        <button wire:click="create" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-600/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Catat Pengeluaran
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-rose-600 to-pink-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-white/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold uppercase tracking-wider text-rose-100">Total Biaya Bulan Ini</p>
            <h3 class="text-3xl font-black font-tech mt-2">Rp {{ number_format($monthlyTotal, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Data List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50 dark:bg-slate-900/50">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-96 pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-rose-500 text-sm" placeholder="Cari Deskripsi...">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-700 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">Jumlah</th>
                        <th class="px-6 py-4 text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($expenses as $exp)
                        <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $exp->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $exp->description }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 text-xs font-bold uppercase">{{ $exp->category }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-rose-600 dark:text-rose-400">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($exp->receipt_path)
                                    <a href="{{ asset('storage/' . $exp->receipt_path) }}" target="_blank" class="text-blue-500 underline text-xs">Lihat</a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $expenses->links() }}</div>
    </div>

    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-slate-800">Catat Pengeluaran</h3>
                    <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500">&times;</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi</label>
                        <input wire:model="description" type="text" class="w-full rounded-lg border-slate-300 focus:ring-rose-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jumlah (Rp)</label>
                            <input wire:model="amount" type="number" class="w-full rounded-lg border-slate-300 focus:ring-rose-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal</label>
                            <input wire:model="date" type="date" class="w-full rounded-lg border-slate-300 focus:ring-rose-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kategori</label>
                        <select wire:model="category" class="w-full rounded-lg border-slate-300">
                            <option value="operational">Operasional (Listrik/Air)</option>
                            <option value="supplies">Perlengkapan (ATK)</option>
                            <option value="marketing">Marketing (Iklan)</option>
                            <option value="maintenance">Perbaikan & Maintenance</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Metode Bayar</label>
                        <select wire:model="payment_method" class="w-full rounded-lg border-slate-300">
                            <option value="cash">Tunai (Potong Kasir)</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bukti Struk (Opsional)</label>
                        <input wire:model="receipt_image" type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100"/>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button wire:click="save" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg shadow-lg">Simpan</button>
                </div>
            </div>
        </div>
    @endif
</div>
