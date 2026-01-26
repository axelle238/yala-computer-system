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
                        <td class="px-6 py-4 font-bold text-slate-700 dark:text-white">{{ $pesan->campaign_name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            <span class="px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-xs font-bold uppercase">
                                {{ $pesan->target_audience }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono font-bold">{{ $pesan->total_recipients }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'failed' => 'bg-rose-100 text-rose-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase {{ $statusColors[$pesan->status] }}">
                                {{ $pesan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-slate-500">
                            {{ $pesan->scheduled_at ? $pesan->scheduled_at->format('d/m/Y H:i') : 'Langsung' }}
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