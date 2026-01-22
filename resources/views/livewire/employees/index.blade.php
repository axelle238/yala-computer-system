<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pegawai</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Kelola akses, role, dan akun pengguna sistem.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah User
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50">
            <input wire:model.live.debounce.300ms="search" type="text" class="w-full md:w-64 px-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Cari nama atau email...">
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Hak Akses</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500 uppercase border-2 border-white shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-slate-900 text-white',
                                        'owner' => 'bg-purple-100 text-purple-700',
                                        'employee' => 'bg-blue-100 text-blue-700',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $roleColors[$user->role] }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->isAdmin())
                                    <span class="text-xs text-slate-400 italic">Full Access</span>
                                @elseif($user->isOwner())
                                    <span class="text-xs text-slate-400 italic">View Only + Reports</span>
                                @else
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($user->access_rights ?? [] as $right)
                                            <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[10px] text-slate-600">
                                                {{ str_replace('_', ' ', ucfirst($right)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('employees.edit', $user->id) }}" class="text-slate-400 hover:text-blue-600 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus user ini?" class="text-slate-400 hover:text-rose-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
