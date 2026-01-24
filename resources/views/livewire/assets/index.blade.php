<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Aset Toko</h1>
            <p class="text-gray-500">Inventaris barang milik toko (bukan untuk dijual).</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg">
            + Tambah Aset
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 font-bold uppercase">Total Nilai Aset</p>
            <p class="text-xl font-black text-gray-800">Rp {{ number_format(\App\Models\CompanyAsset::sum('purchase_cost'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 font-bold uppercase">Total Item</p>
            <p class="text-xl font-black text-blue-600">{{ \App\Models\CompanyAsset::count() }} Unit</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 font-bold uppercase">Dipinjam Karyawan</p>
            <p class="text-xl font-black text-indigo-600">{{ \App\Models\CompanyAsset::whereNotNull('assigned_to_user_id')->count() }} Unit</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 font-bold uppercase">Perlu Perbaikan</p>
            <p class="text-xl font-black text-rose-600">{{ \App\Models\CompanyAsset::where('status', 'maintenance')->count() }} Unit</p>
        </div>
    </div>

    @if($showForm)
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-lg mb-6">
            <h3 class="font-bold text-lg mb-4">{{ $assetId ? 'Edit Aset' : 'Input Aset Baru' }}</h3>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Aset</label>
                    <input wire:model="asset_code" type="text" class="w-full rounded-lg border-gray-300 bg-gray-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Aset</label>
                    <input wire:model="name" type="text" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori</label>
                    <select wire:model="category" class="w-full rounded-lg border-gray-300">
                        <option value="">Pilih...</option>
                        <option value="Electronics">Elektronik (Laptop/PC/Printer)</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Vehicle">Kendaraan</option>
                        <option value="Tools">Perkakas / Tools</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Serial Number</label>
                    <input wire:model="serial_number" type="text" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Beli</label>
                    <input wire:model="purchase_date" type="date" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Harga Beli</label>
                    <input wire:model="purchase_cost" type="number" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kondisi</label>
                    <select wire:model="condition" class="w-full rounded-lg border-gray-300">
                        <option value="good">Baik</option>
                        <option value="fair">Cukup (Lecet Pemakaian)</option>
                        <option value="poor">Kurang (Rusak Ringan)</option>
                        <option value="broken">Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                    <select wire:model="status" class="w-full rounded-lg border-gray-300">
                        <option value="active">Aktif Digunakan</option>
                        <option value="maintenance">Sedang Servis</option>
                        <option value="disposed">Dibuang / Dijual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pegang Jawab (User)</label>
                    <select wire:model="assigned_to_user_id" class="w-full rounded-lg border-gray-300">
                        <option value="">-- Kantor / Umum --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Lokasi</label>
                    <input wire:model="location" type="text" class="w-full rounded-lg border-gray-300" placeholder="Contoh: Gudang Belakang, Meja Kasir 1">
                </div>
                
                <div class="md:col-span-2 flex justify-end gap-2 border-t pt-4">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan Data</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama aset atau kode..." class="w-full md:w-1/3 rounded-lg border-gray-300 text-sm">
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Kode / Nama</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Kondisi</th>
                    <th class="px-6 py-4">Lokasi / Pengguna</th>
                    <th class="px-6 py-4 text-right">Nilai Aset</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $asset->name }}</div>
                            <div class="text-xs font-mono text-gray-500">{{ $asset->asset_code }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $asset->category }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badges = [
                                    'good' => 'bg-green-100 text-green-700',
                                    'fair' => 'bg-blue-100 text-blue-700',
                                    'poor' => 'bg-yellow-100 text-yellow-700',
                                    'broken' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase {{ $badges[$asset->condition] ?? 'bg-gray-100' }}">
                                {{ $asset->condition }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900">{{ $asset->location }}</div>
                            @if($asset->assignee)
                                <div class="text-xs text-indigo-600 font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    {{ $asset->assignee->name }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button wire:click="edit({{ $asset->id }})" class="text-blue-600 hover:text-blue-800">Edit</button>
                            <button wire:click="delete({{ $asset->id }})" wire:confirm="Hapus aset ini?" class="text-red-500 hover:text-red-700">Del</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data aset.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $assets->links() }}</div>
    </div>
</div>
