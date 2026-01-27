<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- HEADER PROFIL -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="h-32 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
        <div class="px-8 pb-6 relative">
            <div class="flex flex-col md:flex-row items-end -mt-12 mb-4 gap-6">
                <!-- Avatar -->
                <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg">
                    <div class="w-full h-full rounded-full bg-slate-200 flex items-center justify-center text-2xl font-bold text-slate-500 uppercase">
                        {{ substr($pelanggan->name, 0, 2) }}
                    </div>
                </div>
                
                <!-- Informasi -->
                <div class="flex-1 mb-2">
                    <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                        {{ $pelanggan->name }}
                        <span class="px-2 py-0.5 rounded text-xs border uppercase tracking-wider {{ $pelanggan->tier_color }}">
                            {{ $pelanggan->loyalty_tier }}
                        </span>
                    </h1>
                    <p class="text-slate-500">{{ $pelanggan->email }} | {{ $pelanggan->phone ?? 'Tidak Ada Telepon' }}</p>
                    <p class="text-xs text-slate-400 mt-1">Bergabung sejak: {{ $pelanggan->created_at->format('d M Y') }}</p>
                </div>

                <!-- Statistik -->
                <div class="flex gap-6 mb-2">
                    <div class="text-center">
                        <div class="text-xs text-slate-500 uppercase font-bold">Total Belanja</div>
                        <div class="text-xl font-black text-slate-800">Rp {{ number_format($pelanggan->total_spent, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-slate-500 uppercase font-bold">Poin Reward</div>
                        <div class="text-xl font-black text-indigo-600">{{ number_format($pelanggan->loyalty_points) }}</div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigasi -->
            <div class="flex border-b border-slate-200 gap-6 overflow-x-auto">
                @foreach(['ringkasan' => 'Ringkasan', 'pesanan' => 'Riwayat Belanja', 'servis' => 'Servis & Perbaikan', 'garansi' => 'Retur & Garansi', 'poin' => 'Log Poin'] as $kunci => $label)
                    <button wire:click="$set('tabAktif', '{{ $kunci }}')" 
                            class="pb-3 text-sm font-bold whitespace-nowrap border-b-2 transition {{ $tabAktif == $kunci ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KOLOM KIRI -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- TAB: RINGKASAN -->
            @if($tabAktif == 'ringkasan')
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Catatan CRM (Internal)</h3>
                    <textarea wire:model="catatan" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500" rows="4" placeholder="Tulis preferensi pelanggan, keluhan khusus, dll..."></textarea>
                    <div class="mt-2 text-right">
                        <button wire:click="perbaruiCatatan" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 text-sm font-bold">Simpan Catatan</button>
                    </div>
                </div>
            @endif

            <!-- TAB: PESANAN -->
            @if($tabAktif == 'pesanan')
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">No. Order</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($pelanggan->orders as $pesanan)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-indigo-600">
                                        <a href="{{ route('admin.pesanan.tampil', $pesanan->id) }}">{{ $pesanan->order_number }}</a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-500">{{ $pesanan->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-bold">Rp {{ number_format($pesanan->total_amount) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800">{{ $pesanan->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- TAB: SERVIS -->
            @if($tabAktif == 'servis')
                <div class="space-y-4">
                    @forelse($pelanggan->serviceTickets as $tiket)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-slate-800">{{ $tiket->device_name }}</div>
                                <div class="text-xs text-slate-500">Tiket: #{{ $tiket->ticket_number }} | {{ $tiket->created_at->format('d M Y') }}</div>
                                <div class="text-sm text-rose-600 mt-1">Masalah: {{ $tiket->problem_description }}</div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $tiket->status_color }}">{{ $tiket->status_label }}</span>
                                <div class="mt-2 text-sm font-bold">Rp {{ number_format($tiket->final_cost) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">Belum ada riwayat servis.</div>
                    @endforelse
                </div>
            @endif

            <!-- TAB: POIN -->
            @if($tabAktif == 'poin')
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase">Keterangan</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-slate-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($pelanggan->pointHistories->sortByDesc('created_at') as $riwayat)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-slate-500">{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-700">{{ $riwayat->description }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-bold {{ $riwayat->amount > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $riwayat->amount > 0 ? '+' : '' }}{{ $riwayat->amount }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-6 text-center text-slate-400">Belum ada riwayat poin.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

        <!-- SIDEBAR KANAN -->
        <div class="space-y-6">
            <!-- Informasi Tier -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-2">Status Keanggotaan</h3>
                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min(100, ($pelanggan->total_spent / 50000000) * 100) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-500 mb-4">
                    <span>Perunggu</span>
                    <span>Platinum (50jt)</span>
                </div>
                <p class="text-sm text-slate-600">
                    Pelanggan perlu belanja <strong>Rp {{ number_format(max(0, 5000000 - $pelanggan->total_spent), 0, ',', '.') }}</strong> lagi untuk naik ke Perak.
                </p>
            </div>

            <!-- Penyesuaian Manual -->
            <div class="bg-slate-50 rounded-xl border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase">Penyesuaian Poin Manual</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-slate-500">Jumlah Poin (+/-)</label>
                        <input type="number" wire:model="penyesuaianPoin" class="w-full text-sm rounded border-slate-300" placeholder="Contoh: 50 atau -20">
                        @error('penyesuaianPoin') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500">Alasan</label>
                        <input type="text" wire:model="alasanPoin" class="w-full text-sm rounded border-slate-300" placeholder="Kompensasi keluhan, Bonus ultah...">
                        @error('alasanPoin') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    <button wire:click="sesuaikanPoin" class="w-full py-2 bg-indigo-600 text-white rounded text-sm font-bold hover:bg-indigo-700">Proses</button>
                </div>
            </div>
        </div>

    </div>
</div>
