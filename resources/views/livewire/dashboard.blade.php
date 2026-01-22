<div class="space-y-8">
    <!-- Header: Sambutan & Ringkasan -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Panel Utama</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Ringkasan inventaris real-time dan peringatan sistem.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('transactions.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 rounded-xl shadow-sm transition-all font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Catat Transaksi
            </a>
            <a href="{{ route('products.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 transition-all font-semibold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Produk Baru
            </a>
        </div>
    </div>

    <!-- Stats Grid: Modern Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <!-- Card 1: Total Stock -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Total SKU</p>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($totalProducts) }}</h3>
                <p class="text-emerald-600 text-xs mt-2 flex items-center gap-1 font-semibold bg-emerald-50 w-fit px-2 py-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 7l-5 5 1.5 1.5L12 10l5 5 1.5-1.5z" clip-rule="evenodd" transform="rotate(180 10 10)" />
                    </svg>
                    Item Aktif
                </p>
            </div>
        </div>

        <!-- Card 2: Low Stock Alert -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border {{ $lowStockCount > 0 ? 'border-rose-100' : 'border-slate-100' }} relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 {{ $lowStockCount > 0 ? 'bg-rose-50' : 'bg-emerald-50' }} rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 {{ $lowStockCount > 0 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} rounded-lg">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Stok Menipis</p>
                </div>
                <h3 class="text-3xl font-extrabold {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-slate-800' }}">{{ $lowStockCount }}</h3>
                <p class="{{ $lowStockCount > 0 ? 'text-rose-600 bg-rose-50' : 'text-emerald-600 bg-emerald-50' }} text-xs mt-2 font-semibold w-fit px-2 py-1 rounded-full">
                    {{ $lowStockCount > 0 ? 'Perlu Perhatian' : 'Stok Aman' }}
                </p>
            </div>
        </div>

        <!-- Card 3: Inventory Value -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-purple-50 rounded-full group-hover:scale-110 transition-transform"></div>
             <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-purple-100 rounded-lg text-purple-600">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Nilai Aset</p>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">
                    <span class="text-lg text-slate-400 font-medium">Rp</span>{{ number_format($totalValue, 0, ',', '.') }}
                </h3>
                <p class="text-purple-600 text-xs mt-2 font-semibold bg-purple-50 w-fit px-2 py-1 rounded-full">Estimasi Modal</p>
            </div>
        </div>

         <!-- Card 4: Monthly Outgoing -->
        <div class="bg-white p-5 rounded-2xl shadow-[0_2px_20px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
             <div class="absolute right-[-10px] top-[-10px] w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-110 transition-transform"></div>
             <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-xs uppercase font-bold tracking-wider">Penjualan (Bulan Ini)</p>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ number_format($monthlySales) }}</h3>
                <p class="text-indigo-600 text-xs mt-2 font-semibold bg-indigo-50 w-fit px-2 py-1 rounded-full">Item Keluar</p>
            </div>
        </div>
    </div>

    <!-- Analytics Chart & Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 lg:col-span-1">
            <h3 class="font-bold text-lg text-slate-800 mb-6">Tren Penjualan (7 Hari)</h3>
            <div class="flex items-end justify-between h-48 gap-2">
                @foreach($chartData as $data)
                    <div class="w-full flex flex-col items-center gap-2 group cursor-pointer">
                        <div class="relative w-full bg-blue-100 rounded-t-lg transition-all duration-500 group-hover:bg-blue-500 overflow-hidden" style="height: {{ $data['height'] }}%">
                             <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-300 group-hover:bg-blue-600"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-400 group-hover:text-blue-600">{{ $data['day'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Riwayat Transaksi Terakhir</h3>
                    <p class="text-slate-500 text-xs">Aktivitas pergerakan barang dalam sistem.</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Barang</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4 text-right">Jumlah</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $transaction->product->name }}
                                    <div class="text-[10px] font-normal text-slate-400">{{ $transaction->product->sku }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'in' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'out' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'adjustment' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'return' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        ];
                                        $typeLabels = [
                                            'in' => 'Masuk',
                                            'out' => 'Keluar',
                                            'adjustment' => 'Penyesuaian',
                                            'return' => 'Retur',
                                        ];
                                        $colorClass = $typeColors[$transaction->type] ?? 'bg-slate-100 text-slate-600';
                                        $labelText = $typeLabels[$transaction->type] ?? ucfirst($transaction->type);
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-xs font-bold border {{ $colorClass }}">
                                        {{ $labelText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold {{ $transaction->type == 'out' ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $transaction->type == 'out' ? '-' : '+' }}{{ $transaction->quantity }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $transaction->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                            {{ substr($transaction->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-medium">{{ $transaction->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                    Belum ada transaksi yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
