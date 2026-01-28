<div class="space-y-8">
    <!-- Header Selamat Datang -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black font-tech text-slate-800 dark:text-white tracking-tight">
                Dashboard <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-cyan-500">Utama</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Halo, <span class="font-bold text-slate-700 dark:text-slate-300">{{ $pengguna->name }}</span>! Berikut adalah ringkasan aktivitas sistem hari ini.
            </p>
        </div>
        <div class="flex gap-2">
            <span class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-bold shadow-sm">
                📅 {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    <!-- AI INTELLIGENCE CARD -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Wawasan Bisnis -->
        <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-6 text-white shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 h-32 w-32 rounded-full bg-black/10 blur-2xl"></div>
            
            <div class="relative flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Wawasan Cerdas AI</h3>
                    <p class="mt-1 text-indigo-100 text-sm leading-relaxed">
                        {{ $aiInsight['pesan'] }}
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center rounded-lg bg-white/20 px-3 py-1 text-xs font-bold backdrop-blur-md">
                            Status: {{ $aiInsight['status'] }}
                        </span>
                        <span class="inline-flex items-center rounded-lg bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-100 backdrop-blur-md border border-emerald-400/30">
                            Rekomendasi: {{ $aiInsight['saran'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prediksi Stok -->
        <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Prediksi Habis Stok
                </h3>
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">AI Forecast</span>
            </div>
            
            <div class="flex-1 space-y-3">
                @forelse($prediksiStok as $p)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <div>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate w-32">{{ $p['nama'] }}</p>
                            <p class="text-[10px] text-slate-500">Sisa: {{ $p['sisa'] }} unit</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold text-rose-500">{{ $p['habis_dalam'] }}</span>
                            <span class="text-[9px] text-slate-400">Estimasi Habis</span>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-4 opacity-50">
                        <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-xs font-medium">Stok aman terkendali.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Aksi Cepat (Quick Actions) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.kasir') }}" class="flex items-center gap-3 p-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-500/20 transition-all group">
            <div class="p-2 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-xs opacity-80 font-medium">Penjualan</p>
                <p class="font-bold text-sm">Buka Kasir</p>
            </div>
        </a>

        <a href="{{ route('admin.servis.buat') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 text-slate-700 dark:text-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
            <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded-lg text-indigo-600 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Penerimaan</p>
                <p class="font-bold text-sm">Servis Baru</p>
            </div>
        </a>

        <a href="{{ route('admin.produk.buat') }}" class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 text-slate-700 dark:text-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
            <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded-lg text-indigo-600 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Inventaris</p>
                <p class="font-bold text-sm">Tambah Produk</p>
            </div>
        </a>

        <a href="{{ route('admin.laporan.harian') ?? '#' }}" class="flex items-center gap-3 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 text-slate-700 dark:text-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all group">
            <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded-lg text-indigo-600 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/30 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium">Rekapitulasi</p>
                <p class="font-bold text-sm">Laporan Harian</p>
            </div>
        </a>
    </div>

    <!-- 1. Statistik Kunci (Grid Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Omset Hari Ini -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Omset Hari Ini</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">Rp {{ number_format($ringkasan['omset_hari_ini'], 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-xs font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded w-fit">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                Update Realtime
            </div>
        </div>

        <!-- Pesanan Pending -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Pesanan Pending</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $ringkasan['pesanan_pending'] }}</h3>
            <a href="{{ route('admin.pesanan.indeks') }}" class="mt-4 inline-block text-xs font-bold text-orange-500 hover:text-orange-600">Lihat Antrian &rarr;</a>
        </div>

        <!-- Servis Aktif -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Servis Sedang Dikerjakan</p>
            <h3 class="text-3xl font-black text-slate-800 dark:text-white">{{ $ringkasan['servis_aktif'] }}</h3>
            <div class="mt-4 flex gap-2">
                <span class="text-xs px-2 py-1 bg-blue-50 text-blue-600 rounded font-bold">{{ $ringkasan['rakitan_proses'] }} Rakit PC</span>
            </div>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.1)] relative overflow-hidden group hover:scale-[1.02] transition-transform">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider mb-1">Peringatan Stok</p>
            <h3 class="text-3xl font-black text-rose-600">{{ $ringkasan['stok_kritis'] }}</h3>
            <p class="text-xs text-slate-400 mt-4">Produk di bawah batas minimum.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 2. Grafik Penjualan (Main Chart) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">Tren Pendapatan (7 Hari Terakhir)</h3>
                <a href="{{ route('admin.analitik.penjualan') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">Lihat Detail</a>
            </div>
            
            <!-- Simple Bar Chart Representation using CSS Grid -->
            <div class="h-64 flex items-end justify-between gap-2 md:gap-4">
                @foreach($grafik as $data)
                    @php 
                        $max = $grafik->max('total') ?: 1; 
                        $height = ($data['total'] / $max) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group relative">
                        <div class="w-full bg-indigo-100 dark:bg-indigo-900/30 rounded-t-lg relative overflow-hidden transition-all duration-500 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/50" style="height: {{ $height }}%">
                            <div class="absolute bottom-0 left-0 w-full bg-indigo-500 transition-all duration-500 h-full opacity-80 group-hover:opacity-100"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 mt-2">{{ $data['tanggal'] }}</span>
                        
                        <!-- Tooltip -->
                        <div class="absolute -top-10 bg-slate-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none">
                            Rp {{ number_format($data['total']) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Aktivitas Terbaru (Feed) -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6">Aktivitas Terbaru</h3>
            <div class="space-y-6 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100 dark:before:bg-slate-700">
                @foreach($aktivitas as $log)
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-1 w-4 h-4 rounded-full border-2 border-white dark:border-slate-800 bg-indigo-500 shadow-sm"></div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">{{ $log->description }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-500">{{ $log->created_at->diffForHumans() }}</span>
                            <span class="text-xs font-bold text-slate-400">•</span>
                            <span class="text-xs font-bold text-indigo-500">{{ $log->user->name ?? 'Sistem' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4. Top Produk (Table) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Produk Terlaris Minggu Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-center">Terjual</th>
                        <th class="px-6 py-4 text-right">Status Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($top_produk as $p)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $p->name }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">Rp {{ number_format($p->sell_price) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-md font-bold text-xs">{{ $p->terjual }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($p->stock_quantity > 10)
                                    <span class="text-emerald-600 font-bold text-xs">Aman ({{ $p->stock_quantity }})</span>
                                @else
                                    <span class="text-rose-600 font-bold text-xs">Menipis ({{ $p->stock_quantity }})</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>