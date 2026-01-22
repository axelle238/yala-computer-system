<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Data Member</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Kelola pelanggan setia dan poin reward.</p>
        </div>
        <button wire:click="openModal" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Daftar Member
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-64 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Cari Nama / No HP...">
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Member</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-center">Poin</th>
                        <th class="px-6 py-4">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $customer->name }}</td>
                            <td class="px-6 py-4">
                                <div>{{ $customer->phone }}</div>
                                <div class="text-xs text-slate-400">{{ $customer->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full font-bold text-xs">
                                    {{ $customer->points }} pts
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $customer->join_date->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada member.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Registrasi Member Baru</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input wire:model="name" type="text" class="block w-full px-3 py-2 border border-slate-200 rounded-lg">
                                    @error('name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Nomor WhatsApp (ID)</label>
                                    <input wire:model="phone" type="text" class="block w-full px-3 py-2 border border-slate-200 rounded-lg" placeholder="08...">
                                    @error('phone') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">Email (Opsional)</label>
                                    <input wire:model="email" type="email" class="block w-full px-3 py-2 border border-slate-200 rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Daftar
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
