<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Aset Perusahaan</h2>
        <button wire:click="$set('showCreateModal', true)" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-bold shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Aset
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <!-- Asset List -->
    <div class="bg-white shadow rounded-xl overflow-hidden border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tag #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nama Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Nilai Beli</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Nilai Buku (Saat Ini)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($assets as $asset)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-600">{{ $asset->asset_tag }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $asset->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $asset->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-500">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-slate-800">Rp {{ number_format($asset->current_value, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <button wire:click="viewDetail({{ $asset->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada aset terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-3 border-t border-slate-200">{{ $assets->links() }}</div>
    </div>

    <!-- CREATE MODAL -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg">
                <h3 class="text-xl font-bold text-slate-800 mb-6">Registrasi Aset Baru</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aset</label>
                        <input type="text" wire:model="name" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Laptop Teknisi 01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kode Tag</label>
                        <input type="text" wire:model="asset_tag" class="w-full rounded-lg border-slate-300" placeholder="AST-XXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                        <select wire:model="category" class="w-full rounded-lg border-slate-300">
                            <option>Elektronik</option>
                            <option>Furniture</option>
                            <option>Kendaraan</option>
                            <option>Mesin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Harga Beli (Rp)</label>
                        <input type="number" wire:model="purchase_price" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Masa Manfaat (Tahun)</label>
                        <input type="number" wire:model="useful_life_years" class="w-full rounded-lg border-slate-300">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Fisik</label>
                        <input type="text" wire:model="location" class="w-full rounded-lg border-slate-300" placeholder="Contoh: Meja Depan">
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                    <button wire:click="store" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    <!-- DETAIL MODAL -->
    @if($showDetailModal && $selectedAsset)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-start mb-6 border-b pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">{{ $selectedAsset->name }}</h3>
                        <p class="text-sm text-slate-500 font-mono">{{ $selectedAsset->asset_tag }}</p>
                    </div>
                    <button wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 p-3 rounded border border-slate-200">
                        <div class="text-xs text-slate-500 uppercase">Harga Perolehan</div>
                        <div class="text-lg font-bold text-slate-800">Rp {{ number_format($selectedAsset->purchase_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-slate-50 p-3 rounded border border-slate-200">
                        <div class="text-xs text-slate-500 uppercase">Nilai Buku Saat Ini</div>
                        <div class="text-lg font-bold text-indigo-600">Rp {{ number_format($selectedAsset->current_value, 0, ',', '.') }}</div>
                    </div>
                </div>

                <h4 class="font-bold text-slate-700 mb-2 text-sm uppercase">Jadwal Penyusutan (Depresiasi)</h4>
                <div class="overflow-hidden border border-slate-200 rounded-lg">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Tahun</th>
                                <th class="px-4 py-2 text-right text-xs font-bold text-slate-500 uppercase">Penyusutan</th>
                                <th class="px-4 py-2 text-right text-xs font-bold text-slate-500 uppercase">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($selectedAsset->depreciations as $dep)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-slate-700">{{ $dep->depreciation_date->format('Y') }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-rose-600">- Rp {{ number_format($dep->amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-bold text-slate-800">Rp {{ number_format($dep->book_value_after, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>