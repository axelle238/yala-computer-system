<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Asset <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-500">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Inventaris aset tetap dan penyusutan nilai.</p>
        </div>
        
        <div class="flex gap-3">
            <button wire:click="runDepreciation" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl shadow-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                Hitung Penyusutan
            </button>
            <a href="{{ route('assets.create') }}" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Aset
            </a>
        </div>
    </div>

    <!-- Asset List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-700">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-96 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm" placeholder="Cari aset...">
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Aset / Tag</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4 text-right">Harga Beli</th>
                    <th class="px-6 py-4 text-right">Nilai Buku</th>
                    <th class="px-6 py-4 text-center">Umur (Thn)</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($assets as $asset)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800 dark:text-white">{{ $asset->name }}</p>
                            <p class="text-xs text-slate-500 font-mono">{{ $asset->asset_tag }}</p>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $asset->location ?? '-' }}</td>
                        <td class="px-6 py-4 text-right font-mono text-slate-500">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-cyan-600">Rp {{ number_format($asset->current_value, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">{{ $asset->useful_life_years }}</td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="delete({{ $asset->id }})" class="text-rose-500 hover:text-rose-700 font-bold text-xs" wire:confirm="Hapus aset ini?">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada aset terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $assets->links() }}
        </div>
    </div>
</div>
