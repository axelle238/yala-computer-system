<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Flash Messages (Styled) -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-6 z-50 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <h4 class="font-bold text-sm uppercase tracking-wider">Sukses</h4>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto opacity-50 hover:opacity-100"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">Manajemen <span class="text-indigo-600">Shift Kasir</span></h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Kontrol saldo kas, buka/tutup shift, dan rekonsiliasi harian.</p>
        </div>
        <div class="flex items-center gap-3 bg-white dark:bg-slate-800 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="px-3 py-1 bg-slate-100 dark:bg-slate-700 rounded-lg text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                Operator
            </div>
            <div class="flex items-center gap-2 pr-2">
                <div class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 flex items-center justify-center text-xs font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    <!-- 1. STATE: KASIR TERTUTUP (CLOSED) -->
    @if(!$activeRegister)
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 p-12 text-center max-w-3xl mx-auto relative overflow-hidden group">
            <!-- Decor -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
            
            <div class="relative z-10">
                <div class="w-24 h-24 bg-white dark:bg-slate-800 rounded-full shadow-lg flex items-center justify-center mx-auto mb-8 text-slate-300 dark:text-slate-600 border border-slate-100 dark:border-slate-700">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                
                <h3 class="text-3xl font-black text-slate-800 dark:text-white mb-3 tracking-tight">Shift Kasir Ditutup</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-10 max-w-md mx-auto leading-relaxed">
                    Sistem terkunci untuk transaksi. Silakan buka shift baru dengan memasukkan modal awal kas (Cash in Drawer) untuk memulai operasional.
                </p>
                
                <button wire:click="$set('showOpenModal', true)" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3 mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>
                    Buka Shift Kasir Sekarang
                </button>
            </div>
        </div>

    <!-- 2. STATE: KASIR TERBUKA (OPEN) -->
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Balance Card -->
            <div class="lg:col-span-1 space-y-6">
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
                            <button wire:click="$set('showTransactionModal', true)" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold py-3 px-4 rounded-xl transition backdrop-blur-sm flex flex-col items-center gap-1">
                                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Catat Transaksi
                            </button>
                            <button wire:click="$set('showCloseModal', true)" class="bg-rose-500 hover:bg-rose-600 border border-rose-400 text-white text-xs font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-rose-900/20 flex flex-col items-center gap-1">
                                <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Tutup Shift
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Summary -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h4 class="font-bold text-slate-800 dark:text-white mb-4 text-sm uppercase tracking-wide">Ringkasan Sesi Ini</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                </div>
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Uang Masuk</span>
                            </div>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">+ {{ number_format($activeRegister->transactions->where('type', 'in')->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-rose-50 dark:bg-rose-900/10 rounded-xl border border-rose-100 dark:border-rose-800/30">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                </div>
                                <span class="text-xs font-bold text-rose-700 dark:text-rose-400">Uang Keluar</span>
                            </div>
                            <span class="font-bold text-rose-600 dark:text-rose-400 text-sm">- {{ number_format($activeRegister->transactions->where('type', 'out')->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Table & History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Today's Mutations -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-lg">Mutasi Kas Langsung</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftar transaksi real-time pada shift ini.</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-bold border border-indigo-200 dark:border-indigo-800">
                            {{ $activeRegister->transactions->count() }} Transaksi
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
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

    <!-- MODALS SECTION -->
    
    <!-- Modal: Buka Shift -->
    <div x-show="$wire.showOpenModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="$wire.set('showOpenModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-slate-700">
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white" id="modal-title">Buka Shift Kasir Baru</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Modal Awal Kas (Cash in Drawer)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" wire:model="openingCash" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-lg border-slate-300 rounded-xl py-3 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                                    </div>
                                    @error('openingCash') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Harian (Opsional)</label>
                                    <textarea wire:model="openNote" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-slate-300 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Contoh: Shift pagi, uang receh cukup."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="openRegister" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm font-bold">
                        Buka Kasir
                    </button>
                    <button type="button" wire:click="$set('showOpenModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Tutup Shift -->
    <div x-show="$wire.showCloseModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="$wire.set('showCloseModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-slate-700">
                <div class="bg-rose-600 px-4 py-4 sm:px-6">
                    <h3 class="text-lg leading-6 font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-rose-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Tutup Shift & Rekonsiliasi
                    </h3>
                </div>
                <div class="px-4 pt-5 pb-4 sm:p-6 bg-white dark:bg-slate-800">
                    <div class="bg-slate-100 dark:bg-slate-900 p-4 rounded-xl mb-6 text-center border border-slate-200 dark:border-slate-700">
                        <div class="text-xs text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wide mb-1">Total Saldo Sistem</div>
                        <div class="text-3xl font-black text-slate-800 dark:text-white">Rp {{ number_format($this->currentBalance, 0, ',', '.') }}</div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Hitungan Uang Fisik (Aktual)</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" wire:model.live="closingCash" class="focus:ring-rose-500 focus:border-rose-500 block w-full pl-10 sm:text-lg border-slate-300 rounded-xl py-3 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                            </div>
                        </div>

                        <!-- Variance Calculator -->
                        @php $diff = (float)$closingCash - $this->currentBalance; @endphp
                        @if($closingCash > 0)
                            <div class="flex justify-between items-center text-sm p-3 rounded-xl border {{ $diff == 0 ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400' : 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-900/30 dark:border-rose-800 dark:text-rose-400' }}">
                                <span class="font-bold">Selisih (Variance):</span>
                                <span class="font-black font-mono text-base">{{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 0, ',', '.') }}</span>
                            </div>
                            @if($diff != 0)
                                <p class="text-xs text-rose-500 italic mt-1">* Harap berikan alasan selisih pada catatan di bawah.</p>
                            @endif
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan Penutup / Laporan</label>
                            <textarea wire:model="closeNote" rows="2" class="shadow-sm focus:ring-rose-500 focus:border-rose-500 block w-full sm:text-sm border-slate-300 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="closeRegister" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm font-bold" onclick="return confirm('Apakah Anda yakin ingin menutup shift ini? Aksi ini akan mengunci transaksi.') || event.stopImmediatePropagation()">
                        Konfirmasi Tutup Shift
                    </button>
                    <button type="button" wire:click="$set('showCloseModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal: Transaksi Manual -->
     <div x-show="$wire.showTransactionModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" @click="$wire.set('showTransactionModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-200 dark:border-slate-700">
                <div class="bg-slate-50 dark:bg-slate-900 px-4 py-4 sm:px-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white">Catat Transaksi Manual</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Untuk pengeluaran operasional atau pemasukan lain di luar kasir.</p>
                </div>
                <div class="px-4 pt-5 pb-4 sm:p-6 bg-white dark:bg-slate-800">
                    <div class="space-y-5">
                        
                        <!-- Type Switcher -->
                        <div class="grid grid-cols-2 gap-2 bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                            <button wire:click="$set('trxType', 'in')" class="py-2 text-sm font-bold rounded-lg transition-all {{ $trxType == 'in' ? 'bg-white dark:bg-slate-700 shadow text-emerald-600 dark:text-emerald-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                                Pemasukan (+)
                            </button>
                            <button wire:click="$set('trxType', 'out')" class="py-2 text-sm font-bold rounded-lg transition-all {{ $trxType == 'out' ? 'bg-white dark:bg-slate-700 shadow text-rose-600 dark:text-rose-400' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                                Pengeluaran (-)
                            </button>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori Transaksi</label>
                            <select wire:model="trxCategory" class="block w-full pl-3 pr-10 py-3 text-base border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                @if($trxType == 'out')
                                    <option value="expense">Biaya Operasional (Listrik, Air, ATK)</option>
                                    <option value="prive">Tarik Tunai (Prive Owner)</option>
                                    <option value="refund">Refund / Pengembalian Dana</option>
                                @else
                                    <option value="modal">Tambah Modal Kas</option>
                                    <option value="sales">Penjualan Manual (Non-Produk)</option>
                                    <option value="service">Pendapatan Jasa</option>
                                @endif
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nominal (Rp)</label>
                            <input type="number" wire:model="trxAmount" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-lg border-slate-300 rounded-xl py-3 font-bold dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Keterangan / Deskripsi</label>
                            <textarea wire:model="trxDescription" rows="2" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-slate-300 rounded-xl dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Jelaskan detail transaksi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 dark:border-slate-700">
                    <button type="button" wire:click="saveTransaction" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm font-bold">
                        Simpan Transaksi
                    </button>
                    <button type="button" wire:click="$set('showTransactionModal', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm px-4 py-2.5 bg-white dark:bg-slate-800 text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>