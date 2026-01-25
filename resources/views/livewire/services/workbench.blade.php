<div class="min-h-screen bg-slate-50 dark:bg-slate-950 p-4 md:p-8 animate-fade-in-up">
    <!-- Header: Ringkasan Tiket & Aksi -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-2xl md:text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">Tiket #{{ $tiket->ticket_number }}</h1>
                <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $tiket->status_color }}">
                    {{ $tiket->status_label }}
                </span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Dibuat pada {{ $tiket->created_at->format('d M Y, H:i') }} oleh {{ $tiket->technician->name ?? 'Sistem' }}</p>
        </div>
        
        <!-- Aksi Global -->
        <div class="flex flex-wrap gap-3 w-full lg:w-auto">
            @if($tiket->status !== 'picked_up' && $tiket->status !== 'cancelled' && !$tampilkanFormPembayaran)
                <button wire:click="$set('tampilkanFormPembayaran', true)" class="flex-1 lg:flex-none px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2 font-bold transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Proses Pembayaran
                </button>
            @endif
            <button onclick="window.print()" class="px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm flex items-center justify-center gap-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Nota
            </button>
            <a href="{{ route('services.index') }}" class="px-6 py-3 bg-slate-800 dark:bg-slate-700 text-white rounded-xl hover:bg-slate-700 dark:hover:bg-slate-600 transition shadow-sm flex items-center justify-center font-bold">
                &larr; Kembali
            </a>
        </div>
    </div>
    
    <!-- FORM PEMBAYARAN INLINE (Pengganti Modal) -->
    @if($tampilkanFormPembayaran)
        <div class="mb-8 animate-fade-in-up">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border-2 border-emerald-500 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 bg-emerald-50 dark:bg-emerald-900/20 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-emerald-800 dark:text-emerald-400 flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Penyelesaian & Pembayaran
                    </h3>
                    <button wire:click="$set('tampilkanFormPembayaran', false)" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 h-fit">
                        <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-2">Total Tagihan Servis</div>
                        <div class="text-4xl font-black text-slate-900 dark:text-white font-mono tracking-tighter">
                            Rp {{ number_format($tiket->parts->sum(function($p) { return $p->jumlah * $p->harga_satuan; }), 0, ',', '.') }}
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 italic">*Total biaya jasa dan suku cadang yang digunakan.</p>
                    </div>

                    <div class="space-y-5">
                        @if($errors->has('payment'))
                            <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl text-sm font-medium">
                                {{ $errors->first('payment') }}
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Metode Pembayaran</label>
                            <select wire:model="metodePembayaran" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-emerald-500 dark:text-white py-3">
                                <option value="tunai">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                                <option value="debit">Kartu Debit</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Catatan Pembayaran (Opsional)</label>
                            <textarea wire:model="catatanPembayaran" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 focus:ring-emerald-500 dark:text-white py-3" rows="2" placeholder="Contoh: Pembayaran lunas via BCA..."></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button wire:click="$set('tampilkanFormPembayaran', false)" class="px-6 py-3 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">Batal</button>
                            <button wire:click="prosesPembayaran" class="px-10 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition transform active:scale-95">
                                Konfirmasi & Selesaikan Tiket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- KOLOM KIRI: INFO PERANGKAT & PELANGGAN -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Kartu Pelanggan -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Informasi Pelanggan</h3>
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl shadow-inner">
                        {{ substr($tiket->customer_name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-slate-900 dark:text-white truncate">{{ $tiket->customer_name }}</div>
                        <div class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $tiket->customer_phone }}</div>
                        @if($tiket->user_id)
                            <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Member Aktif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-2">
                    <button class="w-full py-3 text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl transition-all">
                        Hubungi via WhatsApp
                    </button>
                </div>
            </div>

            <!-- Detail Perangkat -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Detail Perangkat</h3>
                <div class="space-y-5">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Model / Tipe</label>
                        <div class="font-bold text-slate-800 dark:text-white">{{ $tiket->device_name }}</div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Serial Number</label>
                            <div class="font-mono text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 px-2 py-1.5 rounded-lg border border-slate-100 dark:border-slate-700">
                                {{ $tiket->serial_number_in ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 block">Passcode/PIN</label>
                            <div class="font-mono text-xs text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 px-2 py-1.5 rounded-lg border border-rose-100 dark:border-rose-800">
                                {{ $tiket->passcode ?? 'TIDAK ADA' }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Kelengkapan</label>
                        <div class="flex flex-wrap gap-2">
                            @if($tiket->completeness)
                                @foreach($tiket->completeness as $item)
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        {{ $item }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada data kelengkapan</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 block">Keluhan Pelanggan</label>
                        <div class="text-sm text-slate-700 dark:text-slate-300 bg-amber-50 dark:bg-amber-900/10 p-4 rounded-2xl border border-amber-100 dark:border-amber-900/30 italic leading-relaxed shadow-inner">
                            "{{ $tiket->problem_description }}"
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM TENGAH: MEJA KERJA (5 cols) -->
        <div class="lg:col-span-5 space-y-8">
            
            <!-- Update Status -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-tight">Perbarui Status Pengerjaan</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @foreach(['diagnosing' => ['1. Diagnosa', 'Pengecekan awal', 'text-blue-600', 'bg-blue-50', 'border-blue-500'], 
                              'waiting_part' => ['2. Tunggu Part', 'Menunggu stok', 'text-amber-600', 'bg-amber-50', 'border-amber-500'],
                              'repairing' => ['3. Perbaikan', 'Sedang dikerjakan', 'text-indigo-600', 'bg-indigo-50', 'border-indigo-500'],
                              'ready' => ['4. Selesai', 'Siap diambil', 'text-emerald-600', 'bg-emerald-50', 'border-emerald-500']] as $key => $style)
                        <button wire:click="perbaruiStatus('{{ $key }}')" 
                                class="p-4 rounded-2xl border-2 {{ $statusSaatIni === $key ? "$style[3] $style[4] shadow-md" : 'border-slate-100 dark:border-slate-800 hover:border-slate-300' }} text-left transition-all group relative overflow-hidden">
                            <div class="font-black {{ $statusSaatIni === $key ? $style[2] : 'text-slate-400' }} text-sm uppercase tracking-tight">{{ $style[0] }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $style[1] }}</div>
                        </button>
                    @endforeach
                </div>

                <div class="space-y-4">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan Progres</label>
                    <textarea wire:model="inputCatatan" rows="3" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-indigo-500 dark:text-white text-sm py-3 px-4 shadow-inner" placeholder="Tulis rincian pengerjaan atau hasil diagnosa..."></textarea>
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <label class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase cursor-pointer">
                            <input type="checkbox" wire:model="catatanPublik" class="rounded-md border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                            Tampilkan di Halaman Lacak
                        </label>
                        <button wire:click="simpanProgres" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-indigo-600/20 transition transform active:scale-95">
                            Simpan Progres
                        </button>
                    </div>
                </div>
            </div>

            <!-- Manajemen Suku Cadang -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Suku Cadang & Jasa</h2>
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Biaya</span>
                        <span class="text-lg font-black text-slate-900 dark:text-white font-mono">Rp {{ number_format($tiket->parts->sum(function($p){ return $p->jumlah * $p->harga_satuan; }), 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Formulir Tambah Suku Cadang Inline -->
                <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 mb-8">
                    <div class="mb-4 relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Cari Produk / Jasa</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <input type="text" wire:model.live="cariSukuCadang" class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all shadow-sm" placeholder="Ketik nama atau kode SKU...">
                        </div>
                        
                        @if(!empty($hasilPencarian))
                            <div class="absolute z-30 w-full bg-white dark:bg-slate-800 mt-2 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl max-h-64 overflow-y-auto overflow-x-hidden">
                                @foreach($hasilPencarian as $p)
                                    <button wire:click="pilihProduk({{ $p->id }})" class="w-full text-left px-5 py-4 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 border-b border-slate-50 dark:border-slate-700 last:border-0 transition flex justify-between items-center group">
                                        <div class="min-w-0 pr-4">
                                            <div class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 truncate">{{ $p->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">SKU: {{ $p->sku }} • Stok: {{ $p->stock_quantity }}</div>
                                        </div>
                                        <div class="font-black text-indigo-600 dark:text-indigo-400 shrink-0">Rp {{ number_format($p->sell_price, 0, ',', '.') }}</div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($produkTerpilih)
                        <div class="p-5 bg-white dark:bg-slate-900 border border-indigo-100 dark:border-indigo-900/50 rounded-2xl shadow-sm animate-fade-in-down">
                            <div class="flex justify-between items-start mb-4 border-b border-slate-50 dark:border-slate-800 pb-3">
                                <div>
                                    <div class="font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $produkTerpilih->name }}</div>
                                    <div class="text-[10px] font-bold text-indigo-500 uppercase">{{ $produkTerpilih->category->name ?? 'Produk' }}</div>
                                </div>
                                <button wire:click="$set('produkTerpilih', null)" class="text-slate-400 hover:text-rose-500 p-1"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Harga Satuan</label>
                                    <input type="number" wire:model="hargaKustom" class="w-full text-sm font-bold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 dark:text-white">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Jumlah Unit</label>
                                    <input type="number" wire:model="jumlah" min="1" class="w-full text-sm font-bold rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white">
                                </div>
                            </div>
                            @error('jumlah') <span class="text-rose-500 text-[10px] mt-2 block font-bold uppercase">{{ $message }}</span> @enderror
                            <button wire:click="tambahSukuCadang" class="w-full mt-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-indigo-600/20 transition-all">
                                Tambahkan ke Tiket
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Daftar Suku Cadang -->
                <div class="space-y-4">
                    @forelse($tiket->parts as $part)
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm hover:border-indigo-200 dark:hover:border-indigo-900 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-500 dark:text-slate-400 text-xs font-black border border-slate-100 dark:border-slate-800 shadow-inner">
                                    {{ $part->jumlah }}x
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{{ $part->produk->name ?? 'Barang Tidak Dikenal' }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        @ Rp {{ number_format($part->harga_satuan, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="font-black text-slate-900 dark:text-white font-mono">Rp {{ number_format($part->jumlah * $part->harga_satuan, 0, ',', '.') }}</div>
                                <button wire:click="hapusSukuCadang({{ $part->id }})" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" wire:confirm="Hapus suku cadang ini dan kembalikan stok?">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400 bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-xs font-bold uppercase tracking-[0.2em]">Belum ada suku cadang/jasa</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: LINIMASA AKTIVITAS (4 cols) -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 h-full lg:max-h-[calc(100vh-12rem)] overflow-y-auto custom-scrollbar">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-8 sticky top-0 bg-white dark:bg-slate-900 z-10 pb-4 border-b border-slate-100 dark:border-slate-800 uppercase tracking-tight">Riwayat Pengerjaan</h2>
                
                <div class="relative pl-8 border-l-2 border-slate-100 dark:border-slate-800 space-y-10">
                    @foreach($tiket->progressLogs()->latest()->get() as $log)
                        <div class="relative">
                            <!-- Indikator Status -->
                            <div class="absolute -left-[41px] top-0 w-6 h-6 rounded-full border-4 border-white dark:border-slate-950 shadow-md 
                                {{ match($log->status_label) {
                                    'ready' => 'bg-emerald-500 shadow-emerald-500/20',
                                    'repairing' => 'bg-indigo-500 shadow-indigo-500/20',
                                    'diagnosing' => 'bg-blue-500 shadow-blue-500/20',
                                    'waiting_part' => 'bg-amber-500 shadow-amber-500/20',
                                    'picked_up' => 'bg-emerald-600',
                                    'cancelled' => 'bg-rose-500 shadow-rose-500/20',
                                    default => 'bg-slate-400'
                                } }}">
                            </div>

                            <!-- Konten Log -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50 hover:shadow-md transition-all">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-lg bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 uppercase tracking-widest border border-slate-100 dark:border-slate-800 shadow-sm">
                                        {{ str_replace('_', ' ', $log->status_label) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                                    {{ $log->description }}
                                </p>

                                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-[10px] font-black text-indigo-600 dark:text-indigo-400">
                                            {{ substr($log->technician->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $log->technician->name ?? 'Sistem' }}</span>
                                    </div>
                                    @if($log->is_public)
                                        <span class="flex items-center gap-1 text-emerald-500 text-[9px] font-black uppercase tracking-[0.2em]" title="Terlihat oleh Pelanggan">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Publik
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em]" title="Hanya Internal">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                            Internal
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
