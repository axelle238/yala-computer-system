<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header Utama -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">WhatsApp Blast</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                Otomatisasi pengiriman pesan massal terintegrasi dengan data loyalitas pelanggan (CRM).
            </p>
        </div>
        <button wire:click="bukaFormBuat" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-xl shadow-emerald-500/30 transition-all flex items-center gap-3 transform hover:-translate-y-1 active:scale-95">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            KAMPANYE BARU
        </button>
    </div>

    <!-- FORMULIR PEMBUATAN -->
    @if($aksiAktif === 'buat')
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border-2 border-emerald-100 dark:border-emerald-900/30 p-10 shadow-2xl animate-fade-in-up relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
            
            <div class="flex justify-between items-center mb-10">
                <h3 class="font-black text-2xl text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-3">
                    <span class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-inner">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </span>
                    Konfigurasi Pengiriman Massal
                </h3>
                <button wire:click="tutupForm" class="text-slate-400 hover:text-rose-500 transition-colors bg-slate-50 dark:bg-slate-900 p-3 rounded-full shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Kampanye Intern</label>
                        <input wire:model="namaKampanye" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 focus:border-emerald-500 font-bold py-4 px-5 text-slate-800 dark:text-white" placeholder="Misal: Flash Sale Ramadhan 2026">
                        @error('namaKampanye') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Segmentasi Target (CRM)</label>
                        <select wire:model.live="kriteriaAudiens" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 font-bold py-4 px-5 dark:text-white">
                            <option value="semua">Semua Database Pelanggan</option>
                            <option value="loyal">Pelanggan Loyal (Belanja > 10jt)</option>
                            <option value="tidak_aktif">Pelanggan Pasif (> 3 Bulan)</option>
                            <option value="gold_platinum">Eksklusif Tier: Gold & Platinum</option>
                        </select>
                        <div class="flex items-center gap-2 text-[10px] text-emerald-600 font-bold uppercase mt-2 italic">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Estimasi: {{ $this->hitungEstimasiPenerima($kriteriaAudiens) }} Nomor Terdeteksi
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Jadwal Eksekusi (Opsional)</label>
                        <input wire:model="jadwalKirim" type="datetime-local" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 font-bold py-4 px-5 dark:text-white">
                        <p class="text-[10px] text-slate-400 italic">Kosongkan jika ingin peluncuran manual segera.</p>
                        @error('jadwalKirim') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Susunan Pesan (Template)</label>
                        <textarea wire:model.live="templatePesan" rows="6" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-emerald-500 font-mono text-sm p-5 leading-relaxed dark:text-white" placeholder="Halo {{nama}}, dapatkan promo {{produk_terakhir}} spesial hari ini..."></textarea>
                        @error('templatePesan') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-[9px] font-bold text-slate-500 border border-slate-200 dark:border-slate-600 uppercase">{{ '{{nama}}' }}</span>
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-[9px] font-bold text-slate-500 border border-slate-200 dark:border-slate-600 uppercase">{{ '{{produk_terakhir}}' }}</span>
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 rounded text-[9px] font-bold text-slate-500 border border-slate-200 dark:border-slate-600 uppercase">{{ '{{poin}}' }}</span>
                        </div>
                    </div>

                    <div class="p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-[2rem] border-2 border-dashed border-indigo-200 dark:border-indigo-800 shadow-inner">
                        <label class="block text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3">Simulasi Tampilan Pesan</label>
                        <div class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap italic font-serif leading-relaxed">
                            {{ $this->pratinjauPesan }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <button wire:click="tutupForm" class="px-6 py-3 text-slate-400 font-bold hover:text-slate-800 transition uppercase tracking-widest text-xs">Batalkan</button>
                        <button wire:click="simpanKampanye" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-2xl shadow-emerald-500/40 transition transform active:scale-95 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            DAFTARKAN KAMPANYE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TABEL RIWAYAT KAMPANYE -->
    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-8 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Log Aktivitas Pengiriman WhatsApp</h3>
            <div class="relative w-64">
                <input wire:model.live.debounce.300ms="kataKunciCari" type="text" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs focus:ring-emerald-500" placeholder="Cari Nama Kampanye...">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[10px] uppercase font-black text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-5">Identitas Kampanye</th>
                        <th class="px-8 py-5">Segmen Target</th>
                        <th class="px-8 py-5 text-center">Data Penerima</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($daftarKampanye as $pesan)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="font-black text-slate-800 dark:text-white uppercase tracking-tight text-sm">{{ $pesan->campaign_name }}</div>
                                <div class="text-[10px] text-slate-400 mt-1 max-w-xs truncate italic font-medium leading-relaxed">{{ $pesan->message_template }}</div>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $labelTarget = [
                                        'semua' => 'Semua Pelanggan',
                                        'loyal' => 'Loyalitas Tinggi',
                                        'tidak_aktif' => 'Pasif (>3 bln)',
                                        'gold_platinum' => 'Premium (Gold/Plat)',
                                    ];
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase border border-indigo-100 dark:border-indigo-800">
                                    {{ $labelTarget[$pesan->target_audience] ?? $pesan->target_audience }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="font-mono font-black text-lg text-slate-700 dark:text-white leading-none">{{ $pesan->total_recipients }}</div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Target Kontak</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $warnaStatus = [
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400',
                                        'processing' => 'bg-blue-100 text-blue-700 border-blue-200 animate-pulse dark:bg-blue-900/30 dark:text-blue-400',
                                        'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'failed' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400',
                                    ];
                                    $teksStatus = [
                                        'pending' => 'Antrian',
                                        'processing' => 'Berjalan',
                                        'completed' => 'Selesai',
                                        'failed' => 'Gagal',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $warnaStatus[$pesan->status] }}">
                                    {{ $teksStatus[$pesan->status] ?? $pesan->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($pesan->status === 'pending')
                                        <button wire:click="luncurkanSekarang({{ $pesan->id }})" class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white rounded-xl transition-all shadow-sm" title="Luncurkan Kampanye">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        </button>
                                    @endif
                                    <button wire:click="hapusData({{ $pesan->id }})" wire:confirm="Data kampanye akan dihapus permanen. Lanjutkan?" class="p-3 bg-slate-50 dark:bg-slate-900 text-slate-400 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm" title="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <svg class="w-20 h-20 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                    <p class="font-black uppercase tracking-widest">Belum ada riwayat kampanye</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($daftarKampanye->hasPages())
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700">
                {{ $daftarKampanye->links() }}
            </div>
        @endif
    </div>
</div>