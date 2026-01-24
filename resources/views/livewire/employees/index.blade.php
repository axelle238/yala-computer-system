<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pegawai</h1>
            <p class="text-gray-500">Kelola akses dan data karyawan.</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg">
            + Tambah Pegawai
        </button>
    </div>

    @if($showForm)
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-lg mb-6">
            <h3 class="font-bold text-lg mb-4">{{ $userId ? 'Edit Pegawai' : 'Pegawai Baru' }}</h3>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                    <input wire:model="name" type="text" class="w-full rounded-lg border-gray-300">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                    <input wire:model="email" type="email" class="w-full rounded-lg border-gray-300">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jabatan (Role)</label>
                    <select wire:model="role" class="w-full rounded-lg border-gray-300">
                        <option value="technician">Technician</option>
                        <option value="cashier">Cashier</option>
                        <option value="warehouse">Warehouse</option>
                        <option value="admin">Admin</option>
                        <option value="hr">HRD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. HP</label>
                    <input wire:model="phone" type="text" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Gaji Pokok</label>
                    <input wire:model="salary" type="number" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password {{ $userId ? '(Isi jika ingin ubah)' : '' }}</label>
                    <input wire:model="password" type="password" class="w-full rounded-lg border-gray-300">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="md:col-span-2 flex justify-end gap-2 border-t pt-4">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pegawai..." class="w-full md:w-1/3 rounded-lg border-gray-300 text-sm">
        </div>
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Email / Kontak</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($employees as $emp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $emp->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase bg-slate-100 text-slate-600">
                                {{ $emp->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div>{{ $emp->email }}</div>
                            <div class="text-xs text-gray-400">{{ $emp->phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-emerald-600 font-bold text-xs">Active</span>
                        </td>
                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <button wire:click="edit({{ $emp->id }})" class="text-blue-600 hover:underline">Edit</button>
                            @if($emp->id !== auth()->id())
                                <button wire:click="delete({{ $emp->id }})" wire:confirm="Hapus pegawai ini?" class="text-red-500 hover:underline">Del</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada data pegawai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $employees->links() }}</div>
    </div>
</div>
