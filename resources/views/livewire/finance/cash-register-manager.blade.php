<div>
    <!-- 1. STATE: KASIR TERTUTUP (CLOSED) -->
    @if(!$activeRegister)
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-12 text-center max-w-3xl mx-auto relative overflow-hidden group">
            <!-- Decor -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
            
            <div class="relative z-10">
                
                @if($activeAction === 'open')
                    <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-2xl max-w-md mx-auto text-left animate-fade-in-up">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Buka Shift Baru</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Modal Awal (Cash in Drawer)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" wire:model="openingCash" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-lg border-slate-300 rounded-xl py-3 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                                </div>
                                @error('openingCash') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Harian</label>
                                <textarea wire:model="openNote" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-slate-300 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white"></textarea>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button wire:click="setAction(null)" class="flex-1 py-3 text-slate-500 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                                <button wire:click="openRegister" class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg">Buka Kasir</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="w-24 h-24 bg-white dark:bg-slate-800 rounded-full shadow-lg flex items-center justify-center mx-auto mb-8 text-slate-300 dark:text-slate-600 border border-slate-100 dark:border-slate-700">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white mb-3 tracking-tight">Shift Kasir Ditutup</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-10 max-w-md mx-auto leading-relaxed">
                        Sistem terkunci untuk transaksi. Silakan buka shift baru dengan memasukkan modal awal kas untuk memulai operasional.
                    </p>
                    
                    <button wire:click="setAction('open')" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3 mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                        Buka Shift Kasir Sekarang
                    </button>
                @endif
            </div>
        </div>

    <!-- 2. STATE: KASIR TERBUKA (OPEN) -->
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Balance Card & Action Panel -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Balance Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-3xl shadow-xl shadow-indigo-600/20 p-8 text-white relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-20 -mt-20 group-hover:scale-110 transition-transform duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-6 opacity-80">
                            <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                            <span class="text-xs font-bold uppercase tracking-widest">Status: Shift Aktif</span>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-indigo-100 font-medium text-sm mb-2 opacity-80">Total Saldo Kas Fisik (Sistem)</h3>
                            <div class="text-4xl font-black tracking-tight font-tech">Rp {{ number_format($this->currentBalance, 0, ',', '.') }}</div>
                            <div class="text-xs text-indigo-200 mt-2 font-mono">ID Shift: #{{ $activeRegister->id }}</div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="setAction('transaction')" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold py-3 px-4 rounded-xl transition backdrop-blur-sm flex flex-col items-center gap-1 {{ $activeAction === 'transaction' ? 'bg-white/30 border-white/50 ring-2 ring-white/30' : '' }}">
                                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Catat Transaksi
                            </button>
                            <button wire:click="setAction('close')" class="bg-rose-500 hover:bg-rose-600 border border-rose-400 text-white text-xs font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-rose-900/20 flex flex-col items-center gap-1 {{ $activeAction === 'close' ? 'ring-2 ring-rose-300' : '' }}">
                                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Tutup Shift
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ACTION PANELS (Replaces Modals) -->
                
                <!-- Close Shift Panel -->
                @if($activeAction === 'close')
                    <div class="bg-rose-50 dark:bg-rose-900/10 rounded-2xl shadow-sm border border-rose-100 dark:border-rose-800/30 p-6 animate-fade-in-up">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-rose-800 dark:text-rose-400 text-sm uppercase tracking-wide">Tutup Shift & Rekonsiliasi</h4>
                            <button wire:click="setAction(null)" class="text-rose-400 hover:text-rose-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-rose-700 dark:text-rose-300 mb-1 uppercase">Uang Fisik (Aktual)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-rose-500 sm:text-sm font-bold">Rp</span>
                                    </div>
                                    <input type="number" wire:model.live="closingCash" class="focus:ring-rose-500 focus:border-rose-500 block w-full pl-10 sm:text-lg border-rose-200 rounded-xl py-2 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                                </div>
                            </div>

                            @php $diff = (float)$closingCash - $this->currentBalance; @endphp
                            @if($closingCash > 0)
                                <div class="flex justify-between items-center text-sm p-3 rounded-xl border {{ $diff == 0 ? 'bg-emerald-100 border-emerald-200 text-emerald-800' : 'bg-rose-100 border-rose-200 text-rose-800' }}">
                                    <span class="font-bold">Selisih:</span>
                                    <span class="font-black font-mono">{{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-rose-700 dark:text-rose-300 mb-1 uppercase">Catatan</label>
                                <textarea wire:model="closeNote" rows="2" class="shadow-sm focus:ring-rose-500 focus:border-rose-500 block w-full sm:text-sm border-rose-200 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white"></textarea>
                            </div>

                            <button wire:click="closeRegister" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all" onclick="return confirm('Tutup shift ini?') || event.stopImmediatePropagation()">
                                Konfirmasi Tutup
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Transaction Panel -->
                @if($activeAction === 'transaction')
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 animate-fade-in-up">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide">Transaksi Manual</h4>
                            <button wire:click="setAction(null)" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-2 bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                                <button wire:click="$set('trxType', 'in')" class="py-2 text-xs font-bold rounded-lg transition-all {{ $trxType == 'in' ? 'bg-white dark:bg-slate-700 shadow text-emerald-600 dark:text-emerald-400' : 'text-slate-500' }}">Masuk (+)</button>
                                <button wire:click="$set('trxType', 'out')" class="py-2 text-xs font-bold rounded-lg transition-all {{ $trxType == 'out' ? 'bg-white dark:bg-slate-700 shadow text-rose-600 dark:text-rose-400' : 'text-slate-500' }}">Keluar (-)</button>
                            </div>

                            <select wire:model="trxCategory" class="block w-full py-2 px-3 border border-slate-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                @if($trxType == 'out')
                                    <option value="expense">Biaya Operasional</option>
                                    <option value="prive">Tarik Tunai (Prive)</option>
                                    <option value="refund">Refund</option>
                                @else
                                    <option value="modal">Tambah Modal</option>
                                    <option value="sales">Penjualan Lain</option>
                                @endif
                                <option value="other">Lainnya</option>
                            </select>

                            <div>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" wire:model="trxAmount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-slate-300 rounded-xl py-2 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                                </div>
                            </div>

                            <textarea wire:model="trxDescription" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-slate-300 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Keterangan..."></textarea>

                            <button wire:click="saveTransaction" class="w-full py-2.5 bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all">
                                Simpan
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Stats Summary (Only visible when no action active to save space) -->
                @if(!$activeAction)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 animate-fade-in">
                        <h4 class="font-bold text-slate-800 dark:text-white mb-4 text-sm uppercase tracking-wide">Ringkasan Sesi Ini</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Masuk</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">+ {{ number_format($activeRegister->transactions->where('type', 'in')->sum('amount'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-rose-50 dark:bg-rose-900/10 rounded-xl border border-rose-100 dark:border-rose-800/30">
                                <span class="text-xs font-bold text-rose-700 dark:text-rose-400">Keluar</span>
                                <span class="font-bold text-rose-600 dark:text-rose-400 text-sm">- {{ number_format($activeRegister->transactions->where('type', 'out')->sum('amount'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Transaction Table & History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Today's Mutations -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden h-full flex flex-col">
                    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Mutasi Kas Langsung</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar transaksi real-time pada shift ini.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold border border-indigo-200 dark:border-indigo-800">
                            {{ $activeRegister->transactions->count() }} Transaksi
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Nominal (IDR)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <!-- Opening Balance -->
                                <tr class="bg-slate-50/30 dark:bg-slate-800/30">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-500">{{ $activeRegister->opened_at->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">Modal Awal</span></td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 italic">Saldo awal saat buka kasir</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-slate-700 dark:text-slate-200">{{ number_format($activeRegister->opening_cash, 0, ',', '.') }}</td>
                                </tr>

                                @forelse($this->todayTransactions as $trx)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-500">{{ $trx->created_at->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase {{ $trx->type == 'in' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400' }}">
                                                {{ ucfirst($trx->category) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            {{ $trx->description }}
                                            @if($trx->reference_id)
                                                <span class="inline-block ml-2 px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-[10px] text-slate-500 dark:text-slate-400 font-mono">Ref: {{ $trx->reference_id }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black {{ $trx->type == 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                            {{ $trx->type == 'in' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm italic">Belum ada transaksi tambahan pada shift ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- History Section (Always Visible) -->
    <div class="mt-12">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Riwayat Shift Sebelumnya
        </h3>
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Operator</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Buka</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Tutup</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Saldo Akhir</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Selisih (Variance)</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($history as $reg)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-200">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-[10px] font-bold border border-indigo-100 dark:border-indigo-800">
                                            {{ substr($reg->user->name, 0, 1) }}
                                        </div>
                                        {{ $reg->user->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $reg->opened_at->format('d/m/y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $reg->closed_at ? $reg->closed_at->format('d/m/y H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-right font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($reg->closing_cash, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-right font-bold {{ $reg->variance < 0 ? 'text-rose-600' : ($reg->variance > 0 ? 'text-emerald-600' : 'text-slate-400') }}">
                                    {{ $reg->variance != 0 ? number_format($reg->variance, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border {{ $reg->status == 'open' ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 border-indigo-200' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200' }}">
                                        {{ $reg->status == 'open' ? 'Aktif' : 'Selesai' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                {{ $history->links() }}
            </div>
        </div>
    </div>
</div>
</div>
