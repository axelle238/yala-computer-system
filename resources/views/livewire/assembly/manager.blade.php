<div class="p-6 animate-fade-in-up">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div>
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajer <span class="text-indigo-600">Produksi Rakitan</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Pantau dan kelola alur kerja perakitan PC pesanan pelanggan.</p>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <select wire:model.live="filterStatus" class="flex-1 md:flex-none rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-indigo-500 font-bold text-sm">
                <option value="">Semua Status</option>
                <option value="queued">Menunggu (Antrian)</option>
                <option value="picking">Pengambilan Komponen</option>
                <option value="building">Dalam Perakitan</option>
                <option value="testing">QC & Pengujian</option>
                <option value="completed">Selesai</option>
            </select>
            <div class="relative flex-1 md:w-64">
                <input type="text" wire:model.live.debounce.300ms="cari" placeholder="Cari Order / Pelanggan..." class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-indigo-500 font-medium text-sm pl-10">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Rakitan -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden relative border-b-8 border-b-indigo-600">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/50 uppercase text-[10px] font-black text-slate-500 dark:text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-8 py-5">Info Pesanan</th>
                        <th class="px-8 py-5">Nama Rakitan</th>
                        <th class="px-8 py-5">Teknisi PJ</th>
                        <th class="px-8 py-5 text-center">Status Produksi</th>
                        <th class="px-8 py-5 text-center">Waktu Masuk</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($daftarRakitan as $rakitan)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-900 dark:text-white text-base group-hover:text-indigo-600 transition-colors uppercase tracking-tighter">{{ $rakitan->pesanan->order_number }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $rakitan->pesanan->guest_name }}</div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-black bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    {{ $rakitan->build_name ?? 'Rakitan Kustom' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                @if($rakitan->teknisi)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-black text-slate-500 border border-slate-200 dark:border-slate-600 shadow-sm">
                                            {{ substr($rakitan->teknisi->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-700 dark:text-slate-200">{{ $rakitan->teknisi->name }}</span>
                                    </div>
                                @else
                                    <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-amber-100 dark:border-amber-800">Belum Ditunjuk</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $warna = [
                                        'queued' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'picking' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'building' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'testing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    ];
                                    $label = [
                                        'queued' => 'ANTRIAN',
                                        'picking' => 'PENGAMBILAN',
                                        'building' => 'PERAKITAN',
                                        'testing' => 'QC & TEST',
                                        'completed' => 'SELESAI',
                                        'cancelled' => 'DIBATALKAN',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-widest {{ $warna[$rakitan->status] ?? 'bg-slate-100' }}">
                                    {{ $label[$rakitan->status] ?? $rakitan->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center font-mono text-xs font-bold text-slate-500">
                                {{ $rakitan->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                <button wire:click="bukaPanelDetail({{ $rakitan->id }})" class="p-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-400 font-medium">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    <p class="uppercase font-black text-xs tracking-widest">Tidak ada antrian perakitan aktif.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
            {{ $daftarRakitan->links() }}
        </div>
    </div>

    <!-- Panel Detail & Kontrol Produksi -->
    @if($aksiAktif === 'detail' && $rakitanTerpilih)
        <div class="mt-8 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border-2 border-indigo-500/20 overflow-hidden animate-fade-in-up relative border-t-8 border-t-indigo-600">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $rakitanTerpilih->build_name }}</h2>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Nomor Pesanan #{{ $rakitanTerpilih->pesanan->order_number }}</p>
                </div>
                <button wire:click="tutupPanel" class="p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full text-slate-400 hover:text-rose-500 transition-all shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Kiri: Status & Kontrol Alur -->
                <div class="space-y-8">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-inner">
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-4">Kontrol Tahapan Produksi</label>
                        <div class="grid grid-cols-1 gap-3">
                            <button wire:click="perbaruiStatus('picking')" class="w-full text-left px-5 py-4 rounded-xl border-2 transition-all {{ $rakitanTerpilih->status === 'picking' ? 'bg-amber-50 border-amber-500 text-amber-700 ring-4 ring-amber-500/10' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-amber-300 group' }}">
                                <span class="font-black block uppercase text-xs tracking-tight">1. Pengambilan Barang</span>
                                <span class="text-[10px] font-medium opacity-60">Kumpulkan komponen fisik dari gudang sesuai BOM.</span>
                            </button>
                            <button wire:click="perbaruiStatus('building')" class="w-full text-left px-5 py-4 rounded-xl border-2 transition-all {{ $rakitanTerpilih->status === 'building' ? 'bg-blue-50 border-blue-500 text-blue-700 ring-4 ring-blue-500/10' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-blue-300' }}">
                                <span class="font-black block uppercase text-xs tracking-tight">2. Proses Perakitan</span>
                                <span class="text-[10px] font-medium opacity-60">Pemasangan hardware & Manajemen kabel profesional.</span>
                            </button>
                            <button wire:click="perbaruiStatus('testing')" class="w-full text-left px-5 py-4 rounded-xl border-2 transition-all {{ $rakitanTerpilih->status === 'testing' ? 'bg-purple-50 border-purple-500 text-purple-700 ring-4 ring-purple-500/10' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-purple-300' }}">
                                <span class="font-black block uppercase text-xs tracking-tight">3. QC & Pengujian</span>
                                <span class="text-[10px] font-medium opacity-60">BIOS update, Stress test, & Instalasi sistem operasi.</span>
                            </button>
                            <button wire:click="perbaruiStatus('completed')" class="w-full text-left px-5 py-4 rounded-xl border-2 transition-all {{ $rakitanTerpilih->status === 'completed' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 ring-4 ring-emerald-500/10' : 'border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-emerald-300' }}">
                                <span class="font-black block uppercase text-xs tracking-tight">4. Finalisasi Selesai</span>
                                <span class="text-[10px] font-medium opacity-60">Unit siap dikemas dan diserahkan ke kurir pengiriman.</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Skor Benchmark (3DMark/Cinebench)</label>
                            <input type="text" wire:model="skorBenchmark" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 font-mono font-bold text-sm focus:ring-indigo-500" placeholder="Contoh: TimeSpy: 12500 pts">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-2">Catatan Teknis Internal</label>
                            <textarea wire:model="catatanTeknisi" rows="4" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 text-sm focus:ring-indigo-500 font-medium" placeholder="Tulis catatan pengerjaan atau kendala..."></textarea>
                        </div>
                        
                        <button wire:click="simpanCatatan" class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-black uppercase tracking-widest text-xs shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            Simpan Laporan Teknis
                        </button>
                    </div>
                </div>

                <!-- Kanan: Spesifikasi & Standar Prosedur -->
                <div class="lg:col-span-2 space-y-10">
                    <div>
                        <h3 class="font-black text-slate-900 dark:text-white mb-6 uppercase tracking-widest text-sm flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                            Spesifikasi Perangkat (Snapshot)
                        </h3>
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm border-l-4 border-l-indigo-500">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                                    @foreach($spesifikasi as $kunci => $item)
                                        @if($item)
                                            <tr class="bg-white dark:bg-slate-900/50 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                                                <td class="px-6 py-4 font-black text-slate-400 uppercase text-[10px] tracking-widest w-1/3">
                                                    {{ str_replace('_', ' ', $kunci) }}
                                                </td>
                                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">
                                                    {{ is_array($item) ? ($item['nama'] ?? 'Tidak Diketahui') : $item }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-8 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-800 dark:text-indigo-300 rounded-[2rem] text-sm border-2 border-dashed border-indigo-200 dark:border-indigo-800 relative">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-white dark:bg-slate-900 rounded-full flex items-center justify-center border-2 border-indigo-100 dark:border-indigo-800 shadow-sm text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="font-black mb-4 uppercase tracking-widest text-xs">Standard Operating Procedure (SOP) Perakitan</h4>
                        <ul class="space-y-3 font-medium opacity-90">
                            <li class="flex items-start gap-3">
                                <span class="bg-indigo-600 text-white rounded-md px-1.5 py-0.5 text-[10px] font-black mt-0.5">01</span>
                                <span>Verifikasi seluruh fisik komponen terhadap faktur pesanan untuk mencegah salah rakit.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="bg-indigo-600 text-white rounded-md px-1.5 py-0.5 text-[10px] font-black mt-0.5">02</span>
                                <span>Wajib menggunakan sarung tangan antistatis / ESD wrist strap untuk mencegah lonjakan listrik statis.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="bg-indigo-600 text-white rounded-md px-1.5 py-0.5 text-[10px] font-black mt-0.5">03</span>
                                <span>Manajemen kabel sisi belakang (back-panel) harus rapi menggunakan velcro/zip-ties demi airflow optimal.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="bg-indigo-600 text-white rounded-md px-1.5 py-0.5 text-[10px] font-black mt-0.5">04</span>
                                <span>Pengujian stres (Burn-in test) minimal 2 jam untuk memastikan stabilitas sistem di bawah beban berat.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>