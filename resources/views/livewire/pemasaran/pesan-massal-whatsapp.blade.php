<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                WhatsApp <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Blast</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kirim pesan massal ke pelanggan Anda.</p>
        </div>
        <button wire:click="bukaPanelBuat" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Kampanye Baru
        </button>
    </div>

    <!-- Form Panel -->
    @if($aksiAktif === 'buat')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border-2 border-emerald-100 dark:border-emerald-900/30 p-6 shadow-lg animate-fade-in-up">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </span>
                    Buat Kampanye Baru
                </h3>
                <button wire:click="tutupPanel" class="text-slate-400 hover:text-emerald-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Kampanye</label>
                        <input wire:model="namaKampanye" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500 font-bold" placeholder="Contoh: Promo Lebaran 2026">
                        @error('namaKampanye') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Target Audiens</label>
                        <select wire:model="targetAudiens" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500">
                            <option value="all">Semua Pelanggan</option>
                            <option value="loyal">Pelanggan Loyal (>10 Juta)</option>
                            <option value="inactive">Pelanggan Pasif (>3 Bulan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Jadwal Kirim (Opsional)</label>
                        <input wire:model="jadwalKirim" type="datetime-local" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500">
                        <p class="text-[10px] text-slate-400 mt-1">Biarkan kosong untuk kirim sekarang.</p>
                        @error('jadwalKirim') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Isi Pesan</label>
                        <textarea wire:model.live="pesanTemplate" rows="6" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500 font-mono text-sm" placeholder="Halo {{ nama }}, dapatkan diskon spesial..."></textarea>
                        @error('pesanTemplate') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-[10px] text-slate-400 mt-1">Variabel tersedia: {{ nama }}, {{ telepon }}</p>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Pratinjau Pesan</label>
                        <div class="text-xs text-slate-600 dark:text-slate-300 whitespace-pre-wrap italic">
                            {{ $this->previewPesan }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button wire:click="tutupPanel" class="px-5 py-2.5 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Batal</button>
                        <button wire:click="simpan" class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition transform active:scale-95">
                            Simpan & Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-xs uppercase text-slate-500 font-bold">
                <tr>
                    <th class="px-6 py-4">Kampanye</th>
                    <th class="px-6 py-4">Target</th>
                    <th class="px-6 py-4 text-center">Penerima</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Jadwal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($daftarPesan as $pesan)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 dark:text-white">{{ $pesan->campaign_name }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5 max-w-xs truncate italic">{{ $pesan->message_template }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            @php
                                $targetLabels = [
                                    'all' => 'Semua Pelanggan',
                                    'loyal' => 'Pelanggan Loyal',
                                    'inactive' => 'Pelanggan Pasif',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-[10px] font-bold uppercase">
                                {{ $targetLabels[$pesan->target_audience] ?? $pesan->target_audience }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-mono font-black text-slate-700 dark:text-white">{{ $pesan->total_recipients }}</div>
                            @if($pesan->status === 'completed')
                                <div class="text-[9px] text-emerald-500 font-bold">Sukses: {{ $pesan->success_count }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'processing' => 'bg-blue-100 text-blue-700 border-blue-200 animate-pulse',
                                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'failed' => 'bg-rose-100 text-rose-700 border-rose-200',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'processing' => 'Diproses',
                                    'completed' => 'Selesai',
                                    'failed' => 'Gagal',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $statusColors[$pesan->status] }}">
                                {{ $statusLabels[$pesan->status] ?? $pesan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($pesan->status === 'pending')
                                    <button wire:click="prosesKampanye({{ $pesan->id }})" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-all" title="Kirim Sekarang">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    </button>
                                @endif
                                <button wire:click="hapus({{ $pesan->id }})" wire:confirm="Yakin ingin menghapus kampanye ini?" class="p-2 bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white rounded-lg transition-all" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada riwayat kampanye.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $daftarPesan->links() }}
        </div>
    </div>
</div>