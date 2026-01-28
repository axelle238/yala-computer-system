<div class="space-y-8 animate-fade-in-up">
    <!-- Header Profil CRM -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kartu Identitas -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[2rem] p-8 shadow-xl border border-slate-200 dark:border-slate-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row gap-8 relative z-10 items-center md:items-start">
                <!-- Avatar & Tier -->
                <div class="flex flex-col items-center gap-4 text-center">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-4xl font-black text-slate-400 dark:text-slate-500 border-4 border-white dark:border-slate-800 shadow-lg">
                            {{ substr($pelanggan->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white dark:border-slate-800 shadow-sm uppercase tracking-wider">
                            {{ $pelanggan->loyalty_tier ?? 'Bronze' }}
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ $pelanggan->name }}</h2>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Bergabung {{ $pelanggan->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex gap-2 w-full">
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pelanggan->phone) }}" target="_blank" class="flex-1 py-2 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.536 0 1.52 1.115 2.988 1.264 3.186.149.198 2.19 3.349 5.302 4.695.742.322 1.321.515 1.772.658.75.238 1.433.204 1.977.124.604-.088 1.857-.757 2.118-1.487.262-.73.262-1.356.184-1.487-.079-.131-.272-.208-.569-.356z"/></svg>
                            WhatsApp
                        </a>
                    </div>
                </div>

                <!-- Statistik Grid -->
                <div class="flex-1 grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Belanja</span>
                        <span class="block text-lg font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($pelanggan->orders->sum('total_amount') / 1000000, 1) }}Jt</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pesanan</span>
                        <span class="block text-lg font-black text-slate-800 dark:text-white">{{ $pelanggan->orders->count() }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Poin Aktif</span>
                        <span class="block text-lg font-black text-amber-500">{{ number_format($pelanggan->points) }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Servis</span>
                        <span class="block text-lg font-black text-slate-800 dark:text-white">{{ $pelanggan->serviceTickets->count() }}</span>
                    </div>
                    <div class="col-span-2">
                        <h4 class="font-bold text-slate-800 dark:text-white mb-1 text-xs">Kontak</h4>
                        <div class="text-xs text-slate-500">{{ $pelanggan->email }} • {{ $pelanggan->phone }}</div>
                        <div class="text-xs text-slate-500 mt-1">{{ $pelanggan->address }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Analisis AI -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-[2rem] p-8 shadow-xl relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <svg class="w-32 h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
            
            <div>
                <h3 class="font-black text-lg mb-1 flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span> Analisis Retensi
                </h3>
                <p class="text-slate-400 text-xs">Prediksi perilaku pelanggan berbasis AI.</p>
            </div>

            <div class="mt-6 space-y-6 relative z-10">
                <div>
                    <div class="text-[10px] uppercase tracking-widest font-bold text-slate-500 mb-1">Risiko Churn (Berhenti)</div>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-3 bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full {{ $churnAnalysis['risiko'] == 'Tinggi' ? 'bg-rose-500' : ($churnAnalysis['risiko'] == 'Sedang' ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $churnAnalysis['skor_churn'] }}%"></div>
                        </div>
                        <span class="font-black {{ $churnAnalysis['risiko'] == 'Tinggi' ? 'text-rose-500' : ($churnAnalysis['risiko'] == 'Sedang' ? 'text-amber-500' : 'text-emerald-500') }}">{{ $churnAnalysis['risiko'] }}</span>
                    </div>
                </div>

                <div class="bg-white/10 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <div class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mb-2">Rekomendasi Tindakan</div>
                    <p class="text-sm font-medium leading-relaxed">
                        "{{ $churnAnalysis['saran'] }}"
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigasi Tab -->
    <div class="flex gap-2 overflow-x-auto pb-2 border-b border-slate-200 dark:border-slate-700">
        @foreach(['ringkasan' => 'Ringkasan', 'pesanan' => 'Riwayat Pesanan', 'servis' => 'Tiket Servis', 'poin' => 'Mutasi Poin'] as $key => $label)
            <button wire:click="gantiTab('{{ $key }}')" class="px-6 py-3 rounded-t-xl font-bold text-sm transition-all {{ $tabAktif === $key ? 'bg-white dark:bg-slate-800 text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Konten Tab -->
    <div class="bg-white dark:bg-slate-800 rounded-b-2xl rounded-tr-2xl p-6 min-h-[400px]">
        
        @if($tabAktif === 'ringkasan')
            <div class="text-center py-20 text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p>Pilih tab di atas untuk melihat detail aktivitas pelanggan.</p>
            </div>
        @elseif($tabAktif === 'pesanan')
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">No. Order</th>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($pelanggan->orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 font-mono font-bold">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-bold uppercase">{{ $order->status_label }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.pesanan.tampil', $order->id) }}" class="text-indigo-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($tabAktif === 'servis')
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">No. Tiket</th>
                            <th class="px-6 py-3">Perangkat</th>
                            <th class="px-6 py-3">Masalah</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($pelanggan->serviceTickets as $ticket)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="px-6 py-4 font-mono font-bold">{{ $ticket->ticket_number }}</td>
                                <td class="px-6 py-4">{{ $ticket->device_name }}</td>
                                <td class="px-6 py-4 truncate max-w-xs">{{ $ticket->problem_description }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-bold uppercase">{{ $ticket->status_label }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.servis.meja-kerja', $ticket->id) }}" class="text-indigo-600 hover:underline">Kelola</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($tabAktif === 'poin')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Keterangan</th>
                                <th class="px-6 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($pelanggan->pointHistories->sortByDesc('created_at') as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">{{ $log->description }}</td>
                                    <td class="px-6 py-4 text-right font-bold {{ $log->amount > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $log->amount > 0 ? '+' : '' }}{{ number_format($log->amount) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(auth()->user()->isAdmin() || auth()->user()->isOwner())
                <div class="bg-slate-50 dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 h-fit">
                    <h4 class="font-bold text-slate-800 dark:text-white mb-4">Penyesuaian Manual</h4>
                    <form wire:submit.prevent="sesuaikanPoin" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Jumlah Poin (+/-)</label>
                            <input type="number" wire:model="penyesuaianPoin" class="w-full rounded-lg border-slate-300 text-sm">
                            @error('penyesuaianPoin') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Alasan</label>
                            <input type="text" wire:model="alasanPoin" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Misal: Bonus Ulang Tahun">
                            @error('alasanPoin') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Simpan Perubahan</button>
                    </form>
                </div>
                @endif
            </div>
        @endif

    </div>
</div>