<div class="max-w-2xl mx-auto py-12">
    @if(!$activeRegister)
        <!-- Open Register Form -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
            <div class="p-8 text-center bg-slate-900 text-white">
                <div class="w-16 h-16 bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">🔓</div>
                <h1 class="text-2xl font-black font-tech uppercase">Buka Shift Kasir</h1>
                <p class="text-slate-400 mt-2">Masukkan modal awal (uang tunai) di laci kasir untuk memulai transaksi.</p>
            </div>
            
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">Modal Awal (Cash)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">Rp</span>
                        <input wire:model="opening_amount" type="number" class="w-full pl-12 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-2xl font-black font-mono focus:ring-4 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="0">
                    </div>
                    @error('opening_amount') <span class="text-rose-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <button wire:click="openRegister" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-lg shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
                    BUKA REGISTER & MULAI JUALAN
                </button>
            </div>
        </div>
    @else
        <!-- Close Register & Stats -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-emerald-50 dark:bg-emerald-900/20">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                    <div>
                        <h2 class="font-bold text-emerald-800 dark:text-emerald-400">SHIFT AKTIF</h2>
                        <p class="text-xs text-emerald-600 dark:text-emerald-500">Dibuka: {{ $activeRegister->opened_at->format('d M H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-xs font-bold text-slate-500 uppercase">Cash in Drawer</span>
                    <span class="font-mono font-black text-xl text-slate-800 dark:text-white">Rp {{ number_format($stats['cash_in_drawer'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-6 grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-xs text-slate-500 font-bold uppercase">Total Penjualan</span>
                    <div class="text-lg font-black text-slate-800 dark:text-white mt-1">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</div>
                </div>
                <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-xs text-slate-500 font-bold uppercase">Jumlah Transaksi</span>
                    <div class="text-lg font-black text-slate-800 dark:text-white mt-1">{{ $stats['transaction_count'] }}</div>
                </div>
            </div>

            <div class="px-6 pb-6">
                <h3 class="font-bold text-sm mb-3">Rincian Pembayaran</h3>
                <div class="space-y-2">
                    @foreach($stats['breakdown'] as $row)
                        <div class="flex justify-between text-sm">
                            <span class="capitalize text-slate-600 dark:text-slate-400">{{ $row->payment_method }}</span>
                            <span class="font-mono font-bold">Rp {{ number_format($row->total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 space-y-4">
                <h3 class="font-black text-rose-600 dark:text-rose-400 uppercase tracking-wide">Tutup Shift (Z-Report)</h3>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Total Uang Fisik (Hitung Manual)</label>
                    <input wire:model="closing_amount" type="number" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-800 font-mono font-bold" placeholder="Masukkan jumlah uang di laci...">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1 uppercase">Catatan (Opsional)</label>
                    <textarea wire:model="note" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 dark:bg-slate-800 text-sm" rows="2"></textarea>
                </div>

                <button wire:click="closeRegister" wire:confirm="Yakin ingin menutup shift? Pastikan uang fisik sudah dihitung dengan benar." class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-lg shadow-rose-500/20">
                    TUTUP SHIFT & CETAK LAPORAN
                </button>
            </div>
        </div>
    @endif
</div>
