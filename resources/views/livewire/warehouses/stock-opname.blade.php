<div class="space-y-6" x-data>

    <!-- Header & Navigasi Aksi -->
    <div class="flex flex-col md:flex-row justify-between items-start gap-6 animate-fade-in-up">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Manajemen <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-green-600">Stok Opname</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm italic border-l-2 border-emerald-500 pl-3">Audit fisik inventaris gudang dan sinkronisasi neraca keuangan.</p>
        </div>
        
        @if($opnameBerjalan)
            <div class="flex flex-wrap gap-3">
                <button wire:click="hapusSesiIni" class="px-5 py-3 text-sm font-black bg-white dark:bg-slate-800 border border-rose-200 dark:border-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all shadow-sm" onclick="return confirm('Yakin batalkan sesi ini? Semua input fisik akan hilang.')">BATALKAN SESI</button>
                <button wire:click="selesaikanDanFinalisasi" class="px-8 py-3 text-sm font-black bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-500/30 transition-all transform active:scale-95" onclick="return confirm('Finalisasi akan menyesuaikan stok sistem dan mencatat kerugian keuangan (jika ada). Lanjutkan?')">FINALISASI & UPDATE STOK</button>
            </div>
        @else
            <button wire:click="bukaSesiOpname" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 flex items-center gap-3 transition-all transform hover:-translate-y-1">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                MULAI PERHITUNGAN BARU
            </button>
        @endif
    </div>

    @if($opnameBerjalan)
        <!-- Dashboard Sesi Opname Aktif -->
        <div class="space-y-6 animate-fade-in-up delay-100">
            
            <!-- Bilah Pencarian & Info Sesi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Cari Produk Inventaris</label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="kataKunciCari" type="text" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 dark:text-white font-bold" placeholder="Ketik Nama Produk atau Scan SKU Barcode...">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl text-white relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-32 h-32" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="block text-[10px] font-black uppercase tracking-widest opacity-70 mb-1">Nomor Dokumen Sesi</span>
                    <span class="text-lg font-mono font-black">{{ $opnameBerjalan->opname_number }}</span>
                    <div class="mt-4 text-xs font-bold bg-white/20 w-fit px-3 py-1 rounded-full border border-white/10 uppercase">Status: Menghitung</div>
                </div>
            </div>

            <!-- Tabel Audit Inventaris -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden border-b-8 border-b-indigo-500">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 font-black uppercase text-[10px] tracking-[0.1em] border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="px-8 py-5">Produk & Identitas</th>
                                <th class="px-8 py-5 text-center">Data Sistem</th>
                                <th class="px-8 py-5 text-center w-56">Input Fisik (Riil)</th>
                                <th class="px-8 py-5 text-center">Selisih Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($daftarItem as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="font-black text-slate-800 dark:text-white uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $item->product->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1">{{ $item->product->sku }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 rounded-xl font-mono font-black text-slate-600 dark:text-slate-300 text-lg">
                                            {{ $item->system_stock }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="relative">
                                            <input type="number"
                                                wire:change="updateFisik({{ $item->id }}, $event.target.value)"
                                                value="{{ $item->physical_stock }}"
                                                class="w-full text-center font-mono font-black text-xl bg-slate-50 dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 py-3 transition-all"
                                                placeholder="---">
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 text-center">
                                        @php $var = $item->variance; @endphp
                                        @if(!is_null($item->physical_stock))
                                            <div class="flex flex-col items-center">
                                                <span class="font-mono font-black text-xl {{ $var > 0 ? 'text-emerald-500' : ($var < 0 ? 'text-rose-500' : 'text-slate-400') }}">
                                                    {{ $var > 0 ? '+' : '' }}{{ $var }}
                                                </span>
                                                @if($var < 0)
                                                    <span class="text-[10px] font-bold text-rose-400 uppercase">Barang Hilang</span>
                                                @elseif($var > 0)
                                                    <span class="text-[10px] font-bold text-emerald-400 uppercase">Barang Lebih</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-300 italic text-xs">Belum dihitung</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-20">
                                        <div class="flex flex-col items-center opacity-30">
                                            <svg class="w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            <p class="font-black uppercase tracking-widest text-sm">Produk tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($daftarItem && $daftarItem->hasPages())
                    <div class="p-8 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                        {{ $daftarItem->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Keadaan Kosong (Hero) -->
        <div class="flex flex-col items-center justify-center py-32 bg-white dark:bg-slate-800 rounded-[3rem] shadow-sm border-2 border-dashed border-slate-200 dark:border-slate-700 animate-fade-in-up">
            <div class="w-24 h-24 bg-indigo-50 dark:bg-indigo-900/20 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6 shadow-inner">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Sesi Stok Opname Belum Dimulai</h3>
            <p class="text-slate-500 dark:text-slate-400 mt-2 max-w-sm text-center font-medium">Lakukan perhitungan fisik secara rutin untuk menjamin keakuratan stok dan kesehatan neraca keuangan perusahaan.</p>
            <button wire:click="bukaSesiOpname" class="mt-10 px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-2xl shadow-indigo-500/40 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                MULAI AUDIT SEKARANG
            </button>
        </div>
    @endif
</div>