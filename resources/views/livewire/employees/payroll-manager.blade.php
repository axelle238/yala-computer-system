<div class="space-y-8 animate-fade-in-up">
    
    <!-- Judul Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Penggajian</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Otomatisasi gaji, perhitungan komisi performa, dan pengelolaan pajak PPh 21.</p>
        </div>
        <button wire:click="bukaPanelBuat" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Terbitkan Gaji Baru
        </button>
    </div>

    <!-- PANEL PEMBUATAN GAJI -->
    @if($aksiAktif === 'buat')
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-emerald-200 dark:border-emerald-800/30 p-8 shadow-xl animate-fade-in-up relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
            
            <div class="flex justify-between items-center mb-8 border-b border-emerald-100 dark:border-emerald-800/20 pb-4">
                <h3 class="font-bold text-xl text-slate-800 dark:text-white flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    Kalkulasi Payroll Otomatis
                </h3>
                <button wire:click="tutupPanel" class="text-slate-400 hover:text-rose-500 transition-colors bg-slate-50 dark:bg-slate-900 p-2 rounded-full"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            @if(!$dataPratinjau)
                <!-- Tahap 1: Input Parameter -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Pilih Karyawan</label>
                        <select wire:model="idUserTerpilih" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 focus:border-emerald-500 dark:text-white py-3.5 px-4 font-bold">
                            <option value="">-- Cari Nama Karyawan --</option>
                            @foreach($daftarKaryawan as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                            @endforeach
                        </select>
                        @error('idUserTerpilih') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Periode Gaji (Bulan)</label>
                        <input type="month" wire:model="bulanTerpilih" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 focus:border-emerald-500 dark:text-white py-3.5 px-4 font-bold">
                    </div>
                </div>
                
                <div class="flex justify-end mt-10">
                    <button wire:click="hitungPratinjau" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 transition-all flex items-center gap-3 transform hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        MULAI KALKULASI DATA
                    </button>
                </div>
            
            @else
                <!-- Tahap 2: Rincian Pratinjau -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 space-y-6">
                    <div class="flex flex-col md:flex-row justify-between gap-4 border-b border-slate-200 dark:border-slate-700 pb-6">
                        <div>
                            <span class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Penerima Gaji</span>
                            <span class="font-black text-slate-800 dark:text-white text-2xl uppercase tracking-tight">{{ $dataPratinjau['nama_user'] }}</span>
                            <div class="text-sm text-emerald-600 font-bold mt-1">{{ $dataPratinjau['jabatan'] }}</div>
                        </div>
                        <div class="md:text-right">
                            <span class="block text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Periode Laporan</span>
                            <span class="font-black text-slate-800 dark:text-white text-2xl">{{ $dataPratinjau['periode'] }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Komponen Penambah -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-emerald-600 uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pendapatan & Bonus
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center group">
                                    <span class="text-slate-500 font-medium">Gaji Pokok Terdaftar</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($dataPratinjau['gaji_pokok'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center group">
                                    <span class="text-slate-500 font-medium">Tunjangan Hadir ({{ $dataPratinjau['jumlah_hadir'] }} hari)</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($dataPratinjau['total_tunjangan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center group">
                                    <span class="text-slate-500 font-medium">Komisi Servis ({{ $dataPratinjau['jumlah_tiket'] }} unit)</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($dataPratinjau['total_komisi'], 0, ',', '.') }}</span>
                                </div>
                                @if($dataPratinjau['bonus_performa'] > 0)
                                    <div class="flex justify-between items-center p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/50 animate-pulse">
                                        <span class="text-emerald-700 dark:text-emerald-400 font-black text-xs uppercase">Bonus Performa Ekselen</span>
                                        <span class="font-mono font-black text-emerald-700 dark:text-emerald-400">+ Rp {{ number_format($dataPratinjau['bonus_performa'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Komponen Pengurang -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-rose-600 uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Potongan & Pajak
                            </h4>
                            <div class="space-y-3">
                                @if($dataPratinjau['total_potongan'] > 0)
                                    <div class="flex justify-between items-center group">
                                        <span class="text-slate-500 font-medium">Keterlambatan ({{ $dataPratinjau['menit_telat'] }} mnt)</span>
                                        <span class="font-mono font-bold text-rose-600">- Rp {{ number_format($dataPratinjau['total_potongan'], 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-400 italic">Tidak ada potongan keterlambatan.</div>
                                @endif

                                @if($dataPratinjau['potongan_pajak'] > 0)
                                    <div class="flex justify-between items-center group pt-2 border-t border-slate-200 dark:border-slate-700">
                                        <span class="text-slate-500 font-medium">Pajak Penghasilan (PPh 21)</span>
                                        <span class="font-mono font-bold text-rose-600">- Rp {{ number_format($dataPratinjau['potongan_pajak'], 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Total Akhir -->
                    <div class="flex flex-col md:flex-row justify-between items-center pt-8 mt-4 border-t-2 border-slate-200 dark:border-slate-700 gap-4">
                        <div class="text-center md:text-left">
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Take Home Pay</span>
                            <span class="text-lg font-bold text-slate-800 dark:text-white">Gaji Bersih Karyawan</span>
                        </div>
                        <div class="text-4xl font-black text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 px-8 py-4 rounded-3xl border-2 border-indigo-100 dark:border-indigo-900 shadow-lg font-mono">
                            Rp {{ number_format($dataPratinjau['gaji_bersih'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button wire:click="$set('dataPratinjau', null)" class="px-6 py-3 text-slate-500 dark:text-slate-400 hover:text-slate-800 font-bold transition-all uppercase tracking-widest text-xs">Ulangi Hitungan</button>
                    <button wire:click="simpanGaji" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-500/30 transition-all flex items-center gap-3 transform hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        TERBITKAN SLIP GAJI (DRAFT)
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- DETAIL PANEL (MODAL ALTERNATIF) -->
    @if($aksiAktif === 'detail' && $penggajianTerpilih)
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up border-t-8 border-t-indigo-600">
            <!-- Header Header -->
            <div class="p-8 flex flex-col md:flex-row justify-between items-start gap-6 bg-slate-50 dark:bg-slate-900/50">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h3 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white uppercase">SLIP GAJI RESMI</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $penggajianTerpilih->status == 'paid' ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white' }}">
                            {{ $penggajianTerpilih->status == 'paid' ? 'LUNAS' : 'MENUNGGU PERSETUJUAN' }}
                        </span>
                    </div>
                    <p class="text-sm font-mono text-slate-500 uppercase">{{ $penggajianTerpilih->payroll_number }}</p>
                </div>
                <button wire:click="tutupPanel" class="p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-400 hover:text-rose-500 transition-all shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Body Slip -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Karyawan</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $penggajianTerpilih->user->name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Periode</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($penggajianTerpilih->period_start)->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4">Rincian Pendapatan (Income)</h4>
                        @foreach($penggajianTerpilih->items->where('type', 'income') as $item)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600 dark:text-slate-400">{{ $item->title }}</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-white text-sm">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6 bg-slate-50 dark:bg-slate-900/30 p-6 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div class="space-y-3">
                        <h4 class="text-xs font-black text-rose-600 uppercase tracking-widest mb-4">Rincian Potongan (Deduction)</h4>
                        @php $potongans = $penggajianTerpilih->items->where('type', 'deduction'); @endphp
                        @forelse($potongans as $item)
                            <div class="flex justify-between items-center">
                                <span class="text-slate-600 dark:text-slate-400">{{ $item->title }}</span>
                                <span class="font-mono font-bold text-rose-600 text-sm">- Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 italic">Tidak ada potongan bulan ini.</div>
                        @endforelse
                    </div>
                    
                    <div class="pt-6 mt-6 border-t-2 border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Gaji Bersih</span>
                                <span class="text-xs text-slate-500">Dibayarkan via Kasir Toko</span>
                            </div>
                            <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono tracking-tighter">Rp {{ number_format($penggajianTerpilih->net_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="p-8 bg-slate-100 dark:bg-slate-950 flex flex-wrap justify-between items-center gap-4 border-t border-slate-200 dark:border-slate-800">
                <button onclick="window.print()" class="px-6 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-200 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    CETAK DOKUMEN SLIP
                </button>
                
                @if($penggajianTerpilih->status == 'draft')
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-slate-500 font-medium italic">Menunggu otorisasi manager...</span>
                        <button wire:click="setujuiDanBayar" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 font-black shadow-2xl shadow-indigo-600/40 transition-all transform active:scale-95 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            SETUJUI & BAYAR TUNAI
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- TABEL RIWAYAT PENGGAJIAN -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 dark:text-white uppercase tracking-wider text-xs">Arsip Payroll & Pembayaran</h3>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Data Real-time</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[10px] uppercase font-black text-slate-500 tracking-[0.1em]">
                    <tr>
                        <th class="px-6 py-4">No. Payroll</th>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4 text-right">Gaji Bersih</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($daftarGaji as $gaji)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/30 transition-colors group">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400 group-hover:text-indigo-500 transition-colors">{{ $gaji->payroll_number }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-black text-slate-500">{{ substr($gaji->user->name, 0, 1) }}</div>
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">{{ $gaji->user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 font-medium">{{ \Carbon\Carbon::parse($gaji->period_start)->translatedFormat('F Y') }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-900 dark:text-white">Rp {{ number_format($gaji->net_salary, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $gaji->status == 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $gaji->status == 'paid' ? 'Lunas' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="lihatDetail({{ $gaji->id }})" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
            {{ $daftarGaji->links() }}
        </div>
    </div>
</div>