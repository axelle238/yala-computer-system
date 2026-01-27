<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Aset Tetap</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Inventarisasi aset perusahaan dan perhitungan penyusutan otomatis.</p>
        </div>
        
        @if(!$tampilkanForm && !$tampilkanInfoDepresiasi)
            <button wire:click="buat" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Aset Baru
            </button>
        @endif
    </div>

    @if($tampilkanForm)
        <!-- Form Input Aset -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $idAset ? 'Perbarui Data Aset' : 'Registrasi Aset Baru' }}</h3>
                <button wire:click="$set('tampilkanForm', false)" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Aset</label>
                            <input wire:model="nama" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 font-bold text-slate-700 dark:text-white">
                            @error('nama') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nomor Seri / Inventaris</label>
                            <input wire:model="nomorSeri" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 font-mono text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Lokasi Penyimpanan</label>
                            <input wire:model="lokasi" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kondisi Fisik</label>
                            <select wire:model="kondisi" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 cursor-pointer">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                                <option value="Dijual">Dijual/Dihapus</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan (Nilai) -->
                    <div class="space-y-4 bg-slate-50 dark:bg-slate-900/30 p-6 rounded-xl border border-slate-100 dark:border-slate-700">
                        <h4 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-4">Informasi Nilai & Depresiasi</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Pembelian</label>
                            <input wire:model="tanggalBeli" type="date" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500">
                            @error('tanggalBeli') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Harga Perolehan (Rp)</label>
                                <input wire:model="biayaBeli" type="number" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 font-mono font-bold">
                                @error('biayaBeli') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Umur Ekonomis (Tahun)</label>
                                <input wire:model="umurEkonomisTahun" type="number" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-indigo-500 text-center font-bold">
                                @error('umurEkonomisTahun') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Foto Aset (Opsional)</label>
                            <input wire:model="gambar" type="file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                    <button wire:click="$set('tampilkanForm', false)" class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors text-sm">Batal</button>
                    <button wire:click="simpan" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all transform active:scale-95">
                        Simpan Data Aset
                    </button>
                </div>
            </div>
        </div>
    @elseif($tampilkanInfoDepresiasi && $asetTerpilih)
        <!-- Info Depresiasi -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analisis Penyusutan Nilai
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Aset: <span class="font-bold text-indigo-600">{{ $asetTerpilih->name }}</span> ({{ $asetTerpilih->purchase_date->year }})</p>
                </div>
                <button wire:click="tutupInfoDepresiasi" class="text-slate-400 hover:text-rose-500 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            
            <div class="p-8">
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Tahun Buku</th>
                                <th class="px-6 py-4 text-right">Nilai Awal</th>
                                <th class="px-6 py-4 text-right">Beban Penyusutan</th>
                                <th class="px-6 py-4 text-right">Nilai Akhir (Buku)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($jadwalDepresiasi as $jadwal)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $jadwal['tahun'] == now()->year ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                    <td class="px-6 py-4 font-bold font-mono">{{ $jadwal['tahun'] }} {{ $jadwal['tahun'] == now()->year ? '(Saat Ini)' : '' }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-slate-500">Rp {{ number_format($jadwal['nilai_awal'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-rose-500">Rp {{ number_format($jadwal['depresiasi'], 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-mono font-black text-slate-800 dark:text-white">Rp {{ number_format($jadwal['nilai_akhir'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Daftar Aset -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Toolbar -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-96">
                    <input wire:model.live.debounce.300ms="cari" type="text" class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-indigo-500" placeholder="Cari nama aset atau nomor seri...">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-black uppercase text-[10px] tracking-widest border-b border-slate-100 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4">Informasi Aset</th>
                            <th class="px-6 py-4">Lokasi & Kondisi</th>
                            <th class="px-6 py-4 text-right">Nilai Perolehan</th>
                            <th class="px-6 py-4 text-right">Nilai Saat Ini</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($daftarAset as $aset)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex-shrink-0 overflow-hidden border border-slate-200 dark:border-slate-600">
                                            @if($aset->image_path)
                                                <img src="{{ asset('storage/' . $aset->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $aset->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $aset->serial_number ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $aset->location }}</div>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide {{ $aset->condition == 'Baik' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $aset->condition }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono text-slate-500">
                                    Rp {{ number_format($aset->purchase_cost, 0, ',', '.') }}
                                    <div class="text-[10px] text-slate-400">{{ $aset->purchase_date->format('Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-black text-slate-800 dark:text-white">
                                    Rp {{ number_format($aset->current_value, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="tampilkanDepresiasi({{ $aset->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors" title="Hitung Depresiasi">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </button>
                                        <button wire:click="ubah({{ $aset->id }})" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="hapus({{ $aset->id }})" wire:confirm="Hapus aset ini secara permanen?" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data aset perusahaan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $daftarAset->links() }}
            </div>
        </div>
    @endif
</div>