<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Aset</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pelacakan aset tetap perusahaan, inventarisasi, dan kalkulasi depresiasi.</p>
        </div>
        @if(!$tampilkanForm && !$tampilkanInfoDepresiasi)
            <button wire:click="buat" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Aset Baru
            </button>
        @endif
    </div>

    <!-- Statistik -->
    @if(!$tampilkanForm && !$tampilkanInfoDepresiasi)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Aset</p>
                <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">{{ \App\Models\CompanyAsset::count() }}</h3>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl -mr-6 -mt-6"></div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Nilai Awal</p>
                <h3 class="text-3xl font-black font-tech text-slate-900 dark:text-white mt-2">Rp {{ number_format(\App\Models\CompanyAsset::sum('purchase_cost') / 1000000, 1, ',', '.') }}M</h3>
            </div>
            <div class="bg-gradient-to-br from-indigo-600 to-blue-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg shadow-indigo-600/20">
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-wider text-indigo-100">Nilai Aset Saat Ini (Est)</p>
                    <h3 class="text-2xl font-black font-tech mt-2">Rp {{ number_format(\App\Models\CompanyAsset::sum('current_value') / 1000000, 1, ',', '.') }}M</h3>
                    <p class="text-[10px] mt-1 text-indigo-200">Setelah depresiasi</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Inline (Pengganti Modal) -->
    @if($tampilkanForm)
        <div class="bg-white dark:bg-slate-800 w-full rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $idAset ? 'Ubah Data Aset' : 'Tambah Aset Baru' }}</h3>
                <button wire:click="$set('tampilkanForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Aset</label>
                    <input wire:model="nama" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                    @error('nama') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tgl Pembelian</label>
                        <input wire:model="tanggalBeli" type="date" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                        @error('tanggalBeli') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Masa Pakai (Tahun)</label>
                        <input wire:model="umurEkonomisTahun" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500" placeholder="5">
                        @error('umurEkonomisTahun') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Beli</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400 text-xs">Rp</span>
                            <input wire:model="biayaBeli" type="number" class="w-full pl-8 rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                        </div>
                        @error('biayaBeli') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nomor Seri</label>
                        <input wire:model="nomorSeri" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lokasi</label>
                    <input wire:model="lokasi" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500" placeholder="Contoh: Ruang Meeting Lt.2">
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                <button wire:click="$set('tampilkanForm', false)" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Batal</button>
                <button wire:click="simpan" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition">Simpan Data</button>
            </div>
        </div>
    @endif

    <!-- Info Depresiasi Inline (Pengganti Modal) -->
    @if($tampilkanInfoDepresiasi && $asetTerpilih)
        <div class="bg-white dark:bg-slate-800 w-full rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Jadwal Penyusutan Aset</h3>
                    <p class="text-xs text-slate-500">{{ $asetTerpilih->name }}</p>
                </div>
                <button wire:click="tutupInfoDepresiasi" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-100 dark:bg-slate-700 font-bold uppercase text-[10px] text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Tahun</th>
                            <th class="px-4 py-3 text-right">Nilai Awal</th>
                            <th class="px-4 py-3 text-right">Penyusutan</th>
                            <th class="px-6 py-3 text-right">Nilai Buku (Akhir)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($jadwalDepresiasi as $baris)
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-700 dark:text-slate-300">{{ $baris['tahun'] }}</td>
                                <td class="px-4 py-3 text-right text-slate-500">Rp {{ number_format($baris['nilai_awal'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-rose-500">- Rp {{ number_format($baris['depresiasi'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($baris['nilai_akhir'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 text-right">
                <button wire:click="tutupInfoDepresiasi" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Tutup</button>
            </div>
        </div>
    @endif

    <!-- Tabel Data -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            <input wire:model.live.debounce.300ms="cari" type="text" class="w-full md:w-96 pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 text-sm" placeholder="Cari Aset (Nama, Nomor Seri)...">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Aset</th>
                        <th class="px-6 py-4">Nomor Seri</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4 text-right">Nilai Beli</th>
                        <th class="px-6 py-4 text-right">Nilai Saat Ini</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($daftarAset as $aset)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $aset->name }}</div>
                                <div class="text-[10px] text-slate-500">Beli: {{ $aset->purchase_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600 dark:text-slate-300 text-xs">
                                {{ $aset->serial_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-xs font-medium">{{ $aset->location ?? 'Kantor Utama' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-slate-500">
                                Rp {{ number_format($aset->purchase_cost, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($aset->current_value, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">
                                <button wire:click="tampilkanDepresiasi({{ $aset->id }})" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded text-xs font-bold transition">
                                    Depresiasi
                                </button>
                                <button wire:click="ubah({{ $aset->id }})" class="text-slate-400 hover:text-blue-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                <button wire:click="hapus({{ $aset->id }})" wire:confirm="Hapus aset ini?" class="text-slate-400 hover:text-rose-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada data aset.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $daftarAset->links() }}</div>
    </div>
</div>
