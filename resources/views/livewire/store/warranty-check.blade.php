<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Warranty <span class="text-blue-600">Check</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                Pastikan produk Yala Computer Anda original dan cek masa berlaku garansinya.
            </p>
        </div>

        <div class="max-w-2xl mx-auto animate-fade-in-up delay-100">
            <!-- Search Box -->
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <form wire:submit.prevent="check" class="relative z-10">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2 tracking-widest">Masukkan Serial Number (SN)</label>
                    <div class="flex gap-2">
                        <input wire:model="serial_number" type="text" class="flex-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-5 py-4 font-mono font-bold text-lg focus:ring-blue-500 focus:border-blue-500 uppercase placeholder-slate-400" placeholder="SN-XXXX-XXXX">
                        <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                            <span wire:loading.remove>Cek</span>
                            <span wire:loading><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>
                        </button>
                    </div>
                    @error('serial_number') <span class="text-rose-500 text-sm mt-2 block font-medium">{{ $message }}</span> @enderror
                </form>
            </div>

            <!-- Result Card -->
            @if($result)
                <div class="mt-8 bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                    <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-white/10">
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-widest mb-1">Status Garansi</p>
                            @if($result['status'] === 'valid')
                                <div class="flex items-center gap-2 text-emerald-400 font-black text-2xl uppercase tracking-wide">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    ACTIVE
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-rose-500 font-black text-2xl uppercase tracking-wide">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    EXPIRED
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-widest mb-1">Sisa Waktu</p>
                            <p class="text-xl font-mono font-bold text-white">{{ $result['days_left'] }} Hari</p>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase mb-1">Produk</p>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $result['product'] }}</h3>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-8">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-700">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Tanggal Pembelian</p>
                                <p class="font-mono font-bold text-slate-800 dark:text-white">{{ $result['purchase_date'] }}</p>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-700">
                                <p class="text-xs font-bold text-slate-500 uppercase mb-1">Berakhir Pada</p>
                                <p class="font-mono font-bold text-slate-800 dark:text-white">{{ $result['expiry_date'] }}</p>
                            </div>
                        </div>

                        @if($result['status'] === 'valid')
                            <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                                <p class="text-sm text-slate-500 mb-4">Produk Anda terlindungi garansi resmi Yala Computer. Jika mengalami masalah, silakan ajukan klaim.</p>
                                <a href="{{ route('anggota.garansi.ajukan') }}" class="inline-block w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-center hover:opacity-90 transition-opacity">Ajukan Klaim Garansi</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
