<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Audit Logs</h1>
            <p class="text-gray-500">Rekam jejak aktivitas sistem dan pengguna.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="actionFilter" class="rounded-lg border-gray-300 text-sm">
                <option value="">Semua Aksi</option>
                <option value="create">Create</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
                <option value="login">Login</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari log..." class="rounded-lg border-gray-300 text-sm">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Aksi</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badges = [
                                    'create' => 'bg-green-100 text-green-700',
                                    'update' => 'bg-blue-100 text-blue-700',
                                    'delete' => 'bg-red-100 text-red-700',
                                    'login' => 'bg-purple-100 text-purple-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $badges[$log->action] ?? 'bg-gray-100' }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 truncate max-w-md" title="{{ $log->description }}">
                            {{ $log->description }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada aktivitas tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $logs->links() }}</div>
    </div>
</div>