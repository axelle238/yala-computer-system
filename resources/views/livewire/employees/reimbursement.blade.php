<div class="space-y-8 animate-fade-in-up">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">Reimbursement</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm border-l-2 border-emerald-500 pl-3">Klaim biaya operasional karyawan dan pengeluaran dinas lapangan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel Formulir Pengajuan -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-8 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24 overflow-hidden relative group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-50 dark:bg-teal-900/20 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                
                <h3 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 relative z-10">
                    <svg class="w-5 h-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Ajukan Klaim Baru
                </h3>

                <form wire:submit.prevent="simpan" class="space-y-5 relative z-10">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Tanggal Pengeluaran</label>
                        <input type="date" wire:model="tanggal" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-teal-500 transition-all">
                        @error('tanggal') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Kategori Biaya</label>
                        <select wire:model="kategori" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm font-bold focus:ring-2 focus:ring-teal-500 transition-all cursor-pointer">
                            <option value="Transportasi">Transportasi / BBM</option>
                            <option value="Konsumsi">Makan Lembur / Dinas</option>
                            <option value="Kesehatan">Kesehatan / Obat</option>
                            <option value="Operasional">ATK / Kantor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Jumlah Klaim (IDR)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-xs">Rp</span>
                            </div>
                            <input type="number" wire:model="jumlah" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 pl-10 pr-4 text-sm font-black focus:ring-2 focus:ring-teal-500 transition-all" placeholder="0">
                        </div>
                        @error('jumlah') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Keterangan Aktivitas</label>
                        <textarea wire:model="keterangan" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-teal-500 transition-all" placeholder="Jelaskan detail pengeluaran..."></textarea>
                        @error('keterangan') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2">Bukti Struk/Nota</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 transition-all overflow-hidden relative">
                                @if($bukti)
                                    <img src="{{ $bukti->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
                                @endif
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-10">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Klik untuk unggah foto</p>
                                </div>
                                <input type="file" wire:model="bukti" class="hidden">
                            </label>
                        </div>
                        @error('bukti') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-500 hover:to-emerald-500 text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-xl shadow-teal-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                        <span wire:loading.remove>Kirim Pengajuan Klaim</span>
                        <svg wire:loading class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Panel Riwayat & Persetujuan -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="font-black text-xs uppercase tracking-[0.2em] text-slate-500">Log Aktivitas Reimbursement</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Data Real-time</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 uppercase font-black text-[10px] tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Identitas & Waktu</th>
                                <th class="px-8 py-5">Kategori</th>
                                <th class="px-8 py-5 text-right">Total Klaim</th>
                                <th class="px-8 py-5 text-center">Status Sesi</th>
                                <th class="px-8 py-5 text-center">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($daftarKlaim as $klaim)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $klaim->claim_number }}</div>
                                        <div class="flex flex-col mt-1">
                                            <span class="text-[10px] font-bold text-teal-600 uppercase">{{ $klaim->user->name }}</span>
                                            <span class="text-[9px] text-slate-400 font-mono">{{ $klaim->date->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-wider">
                                            {{ $klaim->category }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="font-mono font-black text-slate-900 dark:text-white text-base">
                                            Rp {{ number_format($klaim->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php
                                            $warna = [
                                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            ];
                                            $label = [
                                                'pending' => 'MENUNGGU',
                                                'approved' => 'DISETUJUI',
                                                'rejected' => 'DITOLAK',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black border uppercase {{ $warna[$klaim->status] ?? 'bg-slate-100' }}">
                                            {{ $label[$klaim->status] ?? $klaim->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="flex justify-center gap-3">
                                            @if($klaim->status === 'pending' && $isManajer)
                                                <button wire:click="setujui({{ $klaim->id }})" wire:confirm="Otorisasi pembayaran klaim ini via Kasir?" class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                </button>
                                                <button wire:click="tolak({{ $klaim->id }})" wire:confirm="Tolak pengajuan klaim biaya ini?" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            @endif

                                            @if($klaim->proof_file)
                                                <a href="{{ asset('storage/' . $klaim->proof_file) }}" target="_blank" class="p-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Lihat Bukti Foto">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-30">
                                            <svg class="w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <p class="font-black uppercase tracking-widest text-xs">Belum Ada Data Klaim</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($daftarKlaim->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30">
                        {{ $daftarKlaim->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>