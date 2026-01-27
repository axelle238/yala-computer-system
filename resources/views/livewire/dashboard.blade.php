    <!-- Bagian Log Aktivitas Terbaru -->
    <div class="mt-8 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg text-indigo-600 dark:text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-sm">Log Aktivitas Terbaru</h3>
                    <p class="text-xs text-slate-500">Jejak audit tindakan pengguna di sistem</p>
                </div>
            </div>
            <a href="{{ route('admin.log-aktivitas.indeks') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-500 transition-colors">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($statistik['log_aktivitas'] as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap w-48">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-500">
                                        {{ substr($log->user->name ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="text-xs">
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $log->user->name ?? 'Sistem' }}</div>
                                        <div class="text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 dark:text-slate-400 italic">
                                {{ $log->generateNarrative() }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-500 text-[10px] font-mono border border-slate-200 dark:border-slate-600">
                                    {{ $log->ip_address }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-10 text-center text-slate-400 italic text-sm">Belum ada aktivitas yang tercatat.</td></tr>
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
        const data = @json($statistik['grafik_penjualan']);
        const categories = data.map(item => item.tanggal);
        const series = data.map(item => item.total);
        const isDark = document.documentElement.classList.contains('dark');

        const options = {
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true },
                background: 'transparent'
            },
            series: [{
                name: 'Pendapatan',
                data: series
            }],
            xaxis: {
                categories: categories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: isDark ? '#94a3b8' : '#64748b' } }
            },
            yaxis: {
                labels: {
                    formatter: (value) => {
                        return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value);
                    },
                    style: { colors: isDark ? '#94a3b8' : '#64748b' }
                }
            },
            colors: ['#4f46e5'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            grid: {
                borderColor: isDark ? '#334155' : '#e2e8f0',
                strokeDashArray: 4,
            },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function (val) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart-penjualan"), options);
        chart.render();
    });
</script>
@endscript
