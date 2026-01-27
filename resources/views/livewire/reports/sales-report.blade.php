<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Filter Toolbar -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Laporan <span class="text-indigo-600 dark:text-indigo-400">Penjualan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Analisis tren transaksi dan performa produk.</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            @foreach([
                'hari_ini' => 'Hari Ini',
                'minggu_ini' => 'Minggu Ini',
                'bulan_ini' => 'Bulan Ini'
            ] as $key => $label)
                <button wire:click="aturPeriode('{{ $key }}')" 
                        class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ $periode === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                    {{ $label }}
                </button>
            @endforeach
            
            <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-1">
                <input type="date" wire:model.live="tanggalMulai" class="bg-transparent border-none text-xs font-bold text-slate-700 dark:text-white focus:ring-0 p-0">
                <span class="text-slate-400">-</span>
                <input type="date" wire:model.live="tanggalAkhir" class="bg-transparent border-none text-xs font-bold text-slate-700 dark:text-white focus:ring-0 p-0">
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 group hover:border-indigo-500 transition-all relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Omzet</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 group hover:border-emerald-500 transition-all relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Volume Transaksi</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalPesanan) }} <span class="text-base font-medium text-slate-400">Pesanan</span></h3>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 group hover:border-amber-500 transition-all relative overflow-hidden">
            <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Keranjang</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white mt-1">Rp {{ number_format($rataRataNilai, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Trend Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Tren Penjualan (Harian)</h3>
            <div id="salesTrendChart" class="w-full h-80"></div>
        </div>

        <!-- Payment Methods -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6">Metode Pembayaran</h3>
            <div id="paymentChart" class="w-full h-64 flex justify-center"></div>
        </div>
    </div>

    <!-- Top Products Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <h3 class="font-bold text-slate-800 dark:text-white">Produk Terlaris (Top 5)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">Nama Produk</th>
                        <th class="px-6 py-3 text-center">Terjual</th>
                        <th class="px-6 py-3 text-right">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($produkTerlaris as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $item->product->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded text-xs font-black">{{ $item->total_qty }} Unit</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-600 dark:text-slate-300">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada data penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endassets

@script
<script>
    Livewire.hook('component.init', ({ component, cleanup }) => {
        const initCharts = () => {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#cbd5e1' : '#475569';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            // 1. Sales Trend Chart
            const trendData = @json($grafikHarian);
            if (trendData.data.length > 0) {
                new ApexCharts(document.querySelector("#salesTrendChart"), {
                    series: [{ name: 'Pendapatan', data: trendData.data }],
                    chart: { type: 'area', height: 320, toolbar: { show: false }, background: 'transparent' },
                    xaxis: { categories: trendData.label, labels: { style: { colors: textColor } }, axisBorder: { show: false }, axisTicks: { show: false } },
                    yaxis: { labels: { style: { colors: textColor }, formatter: (val) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(val) } },
                    grid: { borderColor: gridColor, strokeDashArray: 4 },
                    colors: ['#4f46e5'],
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1, stops: [0, 90, 100] } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    theme: { mode: isDark ? 'dark' : 'light' }
                }).render();
            } else {
                document.querySelector("#salesTrendChart").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 text-sm">Tidak ada data untuk ditampilkan</div>';
            }

            // 2. Payment Method Chart
            const payData = @json($grafikBayar);
            if (payData.data.length > 0) {
                new ApexCharts(document.querySelector("#paymentChart"), {
                    series: payData.data,
                    chart: { type: 'donut', height: 260, background: 'transparent' },
                    labels: payData.label,
                    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                    legend: { position: 'bottom', labels: { colors: textColor } },
                    stroke: { show: false },
                    dataLabels: { enabled: false },
                    theme: { mode: isDark ? 'dark' : 'light' }
                }).render();
            } else {
                document.querySelector("#paymentChart").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 text-sm">Belum ada transaksi</div>';
            }
        };

        initCharts();
    });
</script>
@endscript