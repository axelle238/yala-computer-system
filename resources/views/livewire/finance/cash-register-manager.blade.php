<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Cash <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Manajemen Shift Kasir, Saldo, dan Rekonsiliasi Harian.</p>
        </div>
        
        @if($activeRegister)
            <div class="flex items-center gap-4 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-2 rounded-xl border border-emerald-100 dark:border-emerald-800">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <div>
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase">Shift Aktif</p>
                    <p class="text-sm font-mono font-bold text-slate-700 dark:text-slate-200">Dibuka: {{ $activeRegister->opened_at->format('H:i') }}</p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-4 bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-xl">
                <div class="w-3 h-3 rounded-full bg-slate-400"></div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase">Kasir Tutup</p>
                    <p class="text-sm text-slate-400">Belum ada sesi aktif</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Active Register Control Panel -->
    @if($activeRegister && $viewMode === 'dashboard')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Summary Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm md:col-span-2">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 3.666A5.102 5.102 0 0117.165 9.3c.6.113 1.157.29 1.674.527a1 1 0 01.445.864v9.414a1 1 0 01-1.29 1.054 8.001 8.001 0 00-4.305-.989 8.001 8.001 0 00-3.328.718 1 1 0 01-1.29-1.054V4.352a1 1 0 00-.445-.864A5.102 5.102 0 019.3 3.666 5.102 5.102 0 004.835 4.3c-.6.113-1.157.29-1.674.527a1 1 0 01-.445.864v9.414a1 1 0 011.29 1.054 8.001 8.001 0 004.305-.989 8.001 8.001 0 003.328.718 1 1 0 011.29-1.054V10.666z" /></svg>
                    Ringkasan Shift Berjalan
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase font-bold">Modal Awal</p>
                        <p class="text-lg font-mono font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($activeRegister->opening_cash, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 uppercase font-bold">Penjualan Tunai</p>
                        <p class="text-lg font-mono font-bold text-emerald-700 dark:text-emerald-300">+ Rp {{ number_format($calculatedStats['cash_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                        <p class="text-xs text-blue-600 dark:text-blue-400 uppercase font-bold">Transfer/QRIS</p>
                        <p class="text-lg font-mono font-bold text-blue-700 dark:text-blue-300">Rp {{ number_format($calculatedStats['transfer_sales'] + $calculatedStats['qris_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border-2 border-indigo-100 dark:border-indigo-800">
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-bold">Estimasi Laci</p>
                        <p class="text-xl font-mono font-black text-indigo-700 dark:text-indigo-300">Rp {{ number_format($calculatedStats['expected_cash_in_drawer'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-center gap-4">
                <a href="{{ route('transactions.create') }}" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 text-center transition-all">
                    Buka Mesin Kasir (POS)
                </a>
                <button wire:click="prepareClose" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all">
                    Tutup Shift & Rekonsiliasi
                </button>
            </div>
        </div>
    @elseif(!$activeRegister && $viewMode === 'dashboard')
        <!-- Closed State CTA -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-8 text-center border border-slate-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10 max-w-lg mx-auto">
                <div class="w-20 h-20 bg-indigo-500 rounded-2xl mx-auto flex items-center justify-center mb-6 shadow-2xl shadow-indigo-500/50 transform -rotate-6">
                    <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Shift Kasir Belum Dibuka</h2>
                <p class="text-slate-400 mb-8">Anda harus membuka sesi kasir (Open Register) sebelum dapat melakukan transaksi penjualan.</p>
                
                <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/10 text-left max-w-sm mx-auto">
                    <label class="block text-xs font-bold uppercase text-slate-300 mb-2">Modal Awal (Cash in Hand)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400 font-bold">Rp</span>
                        <input type="number" wire:model="openingCash" class="w-full bg-slate-900/50 border border-slate-600 text-white rounded-xl pl-10 focus:ring-indigo-500 focus:border-indigo-500 font-mono font-bold" placeholder="0">
                    </div>
                    <input type="text" wire:model="openNote" class="w-full bg-slate-900/50 border border-slate-600 text-white rounded-xl mt-3 text-sm" placeholder="Catatan (opsional)...">
                    
                    <button wire:click="openRegister" class="w-full mt-4 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg transition-all">
                        Buka Shift Sekarang
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Close Register Modal/View -->
    @if($viewMode === 'close_modal')
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-rose-900/20 flex justify-between items-center">
                    <h3 class="font-bold text-rose-700 dark:text-rose-400 text-lg">Konfirmasi Tutup Shift</h3>
                    <button wire:click="$set('viewMode', 'dashboard')" class="text-slate-400 hover:text-rose-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-slate-500">Saldo Awal</span>
                            <span class="font-mono font-bold">Rp {{ number_format($activeRegister->opening_cash, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-emerald-600">+ Penjualan Tunai</span>
                            <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($calculatedStats['cash_sales'], 0, ',', '.') }}</span>
                        </div>
                        <div class="h-px bg-slate-200 dark:bg-slate-700 my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Total Sistem (Diharapkan)</span>
                            <span class="font-mono font-black text-lg text-indigo-600 dark:text-indigo-400">Rp {{ number_format($calculatedStats['expected_cash_in_drawer'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Total Uang Fisik (Hitung Manual)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-slate-400 font-bold">Rp</span>
                            <input type="number" wire:model.live.debounce.500ms="closingCash" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl pl-12 py-3 font-mono font-bold text-lg focus:ring-rose-500 focus:border-rose-500">
                        </div>
                        
                        @php $variance = (float)$closingCash - $calculatedStats['expected_cash_in_drawer']; @endphp
                        @if($closingCash > 0)
                            <div class="mt-2 text-right">
                                <span class="text-xs font-bold uppercase mr-2">Selisih:</span>
                                <span class="font-mono font-bold {{ $variance == 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $variance > 0 ? '+' : '' }} Rp {{ number_format($variance, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <textarea wire:model="closeNote" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl p-3 text-sm" rows="3" placeholder="Catatan penutupan (opsional)..."></textarea>

                    <button wire:click="closeRegister" wire:confirm="Yakin tutup shift? Aksi ini tidak dapat dibatalkan." class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg transition-all">
                        Konfirmasi Tutup Shift
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- History Table -->
    @if($viewMode === 'dashboard')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-700">
                <h3 class="font-bold text-slate-800 dark:text-white">Riwayat Shift Kasir</h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4">Tanggal / Waktu</th>
                        <th class="px-6 py-4">Kasir</th>
                        <th class="px-6 py-4 text-right">Saldo Awal</th>
                        <th class="px-6 py-4 text-right">Total Akhir</th>
                        <th class="px-6 py-4 text-center">Selisih</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($history as $reg)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-700 dark:text-slate-300">{{ $reg->opened_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-500">{{ $reg->opened_at->format('H:i') }} - {{ $reg->closed_at?->format('H:i') ?? 'Now' }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $reg->user->name }}</td>
                            <td class="px-6 py-4 text-right font-mono text-slate-500">Rp {{ number_format($reg->opening_cash, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-800 dark:text-white">
                                {{ $reg->status === 'closed' ? 'Rp ' . number_format($reg->closing_cash, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($reg->status === 'closed')
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $reg->variance == 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $reg->variance == 0 ? 'OK' : number_format($reg->variance) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $reg->status === 'open' ? 'bg-emerald-500 text-white animate-pulse' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $reg->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="viewDetail({{ $reg->id }})" class="text-indigo-600 hover:text-indigo-800 font-bold text-xs">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada riwayat shift.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $history->links() }}
            </div>
        </div>
    @endif

    <!-- Detail View Overlay -->
    @if($viewMode === 'history_detail' && $detailRegister)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-4xl w-full h-[80vh] flex flex-col border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900">
                    <div>
                        <h3 class="font-bold text-xl text-slate-800 dark:text-white">Detail Shift #{{ $detailRegister->id }}</h3>
                        <p class="text-sm text-slate-500">{{ $detailRegister->user->name }} | {{ $detailRegister->opened_at->format('d M Y, H:i') }}</p>
                    </div>
                    <button wire:click="$set('viewMode', 'dashboard')" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition">
                        Tutup
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                            <p class="text-xs text-slate-500 uppercase">Modal Awal</p>
                            <p class="text-xl font-mono font-bold">Rp {{ number_format($detailRegister->opening_cash) }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                            <p class="text-xs text-slate-500 uppercase">Uang Fisik (Closing)</p>
                            <p class="text-xl font-mono font-bold text-indigo-600">Rp {{ number_format($detailRegister->closing_cash) }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                            <p class="text-xs text-slate-500 uppercase">Selisih</p>
                            <p class="text-xl font-mono font-bold {{ $detailRegister->variance != 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                Rp {{ number_format($detailRegister->variance) }}
                            </p>
                        </div>
                    </div>

                    <h4 class="font-bold text-slate-800 dark:text-white mb-4">Daftar Transaksi</h4>
                    <table class="w-full text-sm text-left border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        <thead class="bg-slate-100 dark:bg-slate-900 text-slate-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Order ID</th>
                                <th class="px-4 py-3">Waktu</th>
                                <th class="px-4 py-3">Metode</th>
                                <th class="px-4 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($detailOrders as $order)
                                <tr>
                                    <td class="px-4 py-3 font-mono">{{ $order->order_number }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('H:i:s') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono font-bold">Rp {{ number_format($order->total_amount) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-slate-50 dark:bg-slate-900 font-bold">
                                <td colspan="3" class="px-4 py-3 text-right uppercase text-xs text-slate-500">Total Omzet Shift</td>
                                <td class="px-4 py-3 text-right font-mono text-lg">Rp {{ number_format($detailOrders->sum('total_amount')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    @if($detailRegister->note)
                        <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl border border-yellow-100 dark:border-yellow-800">
                            <p class="text-xs text-yellow-600 font-bold uppercase mb-1">Catatan Kasir:</p>
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">{{ $detailRegister->note }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
