<div class="space-y-8 animate-fade-in-up">
    <!-- Live Status Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white/50 dark:bg-slate-800/50 backdrop-blur-md px-4 py-3 rounded-xl border border-white/20 dark:border-slate-700 shadow-sm mb-6 text-xs font-mono gap-2 md:gap-0">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-emerald-600 dark:text-emerald-400 font-bold tracking-wider">SYSTEM ONLINE</span>
            </div>
            <div class="hidden md:block text-slate-300 dark:text-slate-600">|</div>
            <div class="hidden md:block text-slate-500 dark:text-slate-400">Database: <span class="text-slate-700 dark:text-slate-200 font-semibold uppercase">{{ DB::connection()->getDatabaseName() }}</span></div>
        </div>
        <div class="flex items-center gap-4" x-data="{ time: new Date().toLocaleTimeString('id-ID') }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID'), 1000)">
            <div class="text-slate-500 dark:text-slate-400">Server Time: <span class="text-slate-900 dark:text-white font-bold" x-text="time"></span></div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Command <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Center</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Real-time inventory intelligence & analytics.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('services.index') }}" class="group flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 hover:border-violet-400 dark:hover:border-violet-500 rounded-xl shadow-sm hover:shadow-violet-500/20 transition-all font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 group-hover:text-violet-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Service Baru
            </a>
            <a href="{{ route('transactions.create') }}" class="group flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 hover:border-cyan-400 dark:hover:border-cyan-500 rounded-xl shadow-sm hover:shadow-cyan-500/20 transition-all font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 group-hover:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Catat Transaksi
            </a>
            <a href="{{ route('products.create') }}" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl shadow-lg shadow-cyan-500/30 hover:shadow-cyan-500/50 transition-all font-bold text-sm hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Produk Baru
            </a>
        </div>
    </div>

    <!-- AI Insights (New Feature) -->
    @if(count($insights) > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($insights as $insight)
            <div class="flex items-start gap-3 p-4 rounded-xl border transition-all hover:-translate-y-1 
                {{ $insight['type'] == 'success' ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-100 dark:border-emerald-800' : '' }}
                {{ $insight['type'] == 'warning' ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-100 dark:border-amber-800' : '' }}
                {{ $insight['type'] == 'info' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800' : '' }}
            ">
                <div class="mt-0.5">
                    @if($insight['type'] == 'success') <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    @elseif($insight['type'] == 'warning') <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    @else <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider mb-1 opacity-70 {{ $insight['type'] == 'success' ? 'text-emerald-700 dark:text-emerald-400' : ($insight['type'] == 'warning' ? 'text-amber-700 dark:text-amber-400' : 'text-blue-700 dark:text-blue-400') }}">
                        AI Insight
                    </p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-snug">{{ $insight['message'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <!-- Card 1: Total Stock -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-700 overflow-hidden group hover:border-cyan-200 dark:hover:border-cyan-800 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/5 rounded-full blur-2xl -mr-8 -mt-8 group-hover:bg-cyan-500/10 transition-colors"></div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-cyan-50 dark:bg-cyan-900/30 rounded-xl text-cyan-600 dark:text-cyan-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <span class="flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-cyan-400 opacity-20"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-cyan-500/20"></span>
                </span>
            </div>
            
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total SKU</p>
                <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white group-hover:text-cyan-600 transition-colors">{{ number_format($totalProducts) }}</h3>
            </div>
        </div>

        <!-- Card 2: Low Stock -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border {{ $lowStockCount > 0 ? 'border-rose-100 dark:border-rose-900/50' : 'border-slate-100 dark:border-slate-700' }} overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 {{ $lowStockCount > 0 ? 'bg-rose-500/5' : 'bg-emerald-500/5' }} rounded-full blur-2xl -mr-8 -mt-8"></div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 {{ $lowStockCount > 0 ? 'bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' }} rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Status Stok</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black font-tech {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-slate-900 dark:text-white' }}">{{ $lowStockCount }}</h3>
                    <span class="text-xs font-bold mb-1.5 {{ $lowStockCount > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                        {{ $lowStockCount > 0 ? 'Item Menipis' : 'Semua Aman' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Asset Value -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-700 overflow-hidden group hover:border-violet-200 dark:hover:border-violet-800 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-violet-500/5 rounded-full blur-2xl -mr-8 -mt-8 group-hover:bg-violet-500/10 transition-colors"></div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-violet-50 dark:bg-violet-900/30 rounded-xl text-violet-600 dark:text-violet-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Estimasi Aset</p>
                <h3 class="text-2xl font-black font-tech text-slate-900 dark:text-white truncate" title="Rp {{ number_format($totalValue, 0, ',', '.') }}">
                    <span class="text-sm font-normal text-slate-400 mr-1">Rp</span>{{ number_format($totalValue / 1000000, 1, ',', '.') }}<span class="text-sm font-normal text-slate-400 ml-1">Juta</span>
                </h3>
            </div>
        </div>

        <!-- Card 4: Service Queue (New) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-700 overflow-hidden group hover:border-amber-200 dark:hover:border-amber-800 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-2xl -mr-8 -mt-8 group-hover:bg-amber-500/10 transition-colors"></div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-xl text-amber-600 dark:text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Service Queue</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white">{{ $serviceStats['repairing'] + $serviceStats['pending'] }}</h3>
                    <span class="text-xs font-bold mb-1.5 text-amber-500">
                        {{ $serviceStats['repairing'] }} Sedang Dikerjakan
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 4: News Stats (Updated) -->
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-slate-100 dark:border-slate-700 overflow-hidden group hover:border-blue-200 dark:hover:border-blue-800 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl -mr-8 -mt-8 group-hover:bg-blue-500/10 transition-colors"></div>
            
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
            </div>
            
            <div>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Berita & Pembaca</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white">{{ number_format($totalArticles) }}</h3>
                    <span class="text-xs font-bold mb-1.5 text-slate-400">
                        Artikel / {{ number_format($totalViews) }} Views
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart Section -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm p-6 lg:col-span-1 flex flex-col relative overflow-hidden">
            <div class="absolute inset-0 grid-pattern opacity-5 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-8 relative z-10">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white font-tech">Tren 7 Hari</h3>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span>Volume</span>
                </div>
            </div>
            
            <div class="flex-1 flex items-end justify-between gap-3 relative z-10 h-48">
                @foreach($chartData as $data)
                    <div class="w-full flex flex-col items-center gap-3 group cursor-pointer h-full justify-end">
                        <div class="relative w-full bg-slate-100 dark:bg-slate-700 rounded-t-sm transition-all duration-500 group-hover:bg-blue-500/20 overflow-hidden" style="height: {{ $data['height'] > 0 ? $data['height'] : 5 }}%">
                             <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-blue-600 to-cyan-400 transition-all duration-500 group-hover:opacity-80" style="height: 100%"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 group-hover:text-blue-500 transition-colors">{{ substr($data['day'], 0, 3) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden lg:col-span-2 flex flex-col">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50 backdrop-blur-sm">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white font-tech">Aktivitas Terkini</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Pantau pergerakan barang secara real-time.</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                    Lihat Semua
                </a>
            </div>
            
            <div class="overflow-x-auto flex-1 custom-scrollbar">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Item</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Qty</th>
                            <th class="px-6 py-4">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 dark:text-white group-hover:text-cyan-600 transition-colors">{{ $transaction->product->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $transaction->product->sku }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $badges = [
                                            'in' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                            'out' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'adjustment' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                            'return' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                        ];
                                        $labels = [ 'in' => 'Masuk', 'out' => 'Keluar', 'adjustment' => 'Opname', 'return' => 'Retur' ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badges[$transaction->type] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $labels[$transaction->type] ?? $transaction->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold font-mono {{ $transaction->type == 'out' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $transaction->type == 'out' ? '-' : '+' }}{{ $transaction->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 italic">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
