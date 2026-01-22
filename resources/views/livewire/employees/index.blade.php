<div class="space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Data <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Pegawai</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen akses dan data staf.</p>
        </div>
        <a href="{{ route('employees.create') }}" class="px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
            + Tambah Pegawai
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $employee->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $employee->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $employee->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $employee->email }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('employees.edit', $employee->id) }}" class="text-cyan-600 font-bold hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $employees->links() }}
        </div>
    </div>
</div>