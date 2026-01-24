<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    <!-- 1. JIKA KASIR BELUM BUKA (CLOSED) -->
    @if(!$activeRegister)
        <div class="bg-white rounded-xl shadow-lg p-8 text-center max-w-2xl mx-auto border border-slate-200">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Shift Kasir Belum Dibuka</h2>
            <p class="text-slate-500 mb-8">Anda harus membuka sesi kasir baru untuk mulai mencatat transaksi penjualan, servis, atau pengeluaran.</p>
            
            <button wire:click="$set('showOpenModal', true)" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold shadow-lg hover:bg-indigo-700 transition w-full sm:w-auto">
                Buka Shift Baru
            </button>
        </div>

    <!-- 2. JIKA KASIR SUDAH BUKA (OPEN) -->
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Balance Card -->
            <div class="bg-indigo-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <h3 class="text-indigo-100 font-medium text-sm mb-1">Saldo Kas Fisik (Sistem)</h3>
                    <div class="text-3xl font-bold mb-4">Rp {{ number_format($this->currentBalance, 0, ',', '.') }}</div>
                    
                    <div class="flex gap-2">
                        <button wire:click="$set('showTransactionModal', true)" class="flex-1 bg-white/20 hover:bg-white/30 text-white text-xs font-bold py-2 px-3 rounded transition flex items-center justify-center gap-1">
                            <span>+</span> Catat Transaksi
                        </button>
                        <button wire:click="$set('showCloseModal', true)" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold py-2 px-3 rounded transition shadow-sm">
                            Tutup Shift
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-center">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-slate-500 text-sm">Uang Masuk (Shift Ini)</div>
                    <div class="text-emerald-600 font-bold">+ Rp {{ number_format($activeRegister->transactions->where('type', 'in')->sum('amount'), 0, ',', '.') }}</div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-slate-500 text-sm">Uang Keluar (Shift Ini)</div>
                    <div class="text-rose-600 font-bold">- Rp {{ number_format($activeRegister->transactions->where('type', 'out')->sum('amount'), 0, ',', '.') }}</div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-400">
                    Shift dimulai: {{ $activeRegister->opened_at->format('d M H:i') }}
                </div>
            </div>

             <!-- Quick Actions -->
             <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-700 mb-3">Pintasan Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                     <a href="{{ route('services.index') }}" class="p-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-lg transition text-center">
                        <div class="text-indigo-600 font-bold mb-1">Servis</div>
                        <div class="text-xs text-slate-500">Pembayaran Servis</div>
                    </a>
                    <button disabled class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center opacity-50 cursor-not-allowed">
                        <div class="text-slate-600 font-bold mb-1">POS Toko</div>
                        <div class="text-xs text-slate-500">Coming Soon</div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Mutasi Kas Hari Ini</h3>
                <span class="text-xs bg-slate-200 text-slate-600 px-2 py-1 rounded">Shift ID: #{{ $activeRegister->id }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <!-- Opening Balance Row -->
                         <tr class="bg-slate-50/50">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500">{{ $activeRegister->opened_at->format('H:i') }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm"><span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Modal Awal</span></td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-600">Saldo Awal Shift</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-right font-medium text-slate-700">{{ number_format($activeRegister->opening_cash, 0, ',', '.') }}</td>
                        </tr>

                        @foreach($this->todayTransactions as $trx)
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500">{{ $trx->created_at->format('H:i') }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $trx->type == 'in' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ ucfirst($trx->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $trx->description }}
                                    @if($trx->reference_id)
                                        <span class="text-xs text-slate-400 ml-1">Ref: #{{ $trx->reference_id }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-right font-bold {{ $trx->type == 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $trx->type == 'in' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- History List -->
    <div class="mt-8">
        <h3 class="text-lg font-bold text-slate-700 mb-4">Riwayat Shift Sebelumnya</h3>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Buka</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tutup</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Saldo Akhir</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Selisih</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($history as $reg)
                        <tr>
                            <td class="px-6 py-3 text-sm font-medium text-slate-900">{{ $reg->user->name }}</td>
                            <td class="px-6 py-3 text-sm text-slate-500">{{ $reg->opened_at->format('d M H:i') }}</td>
                            <td class="px-6 py-3 text-sm text-slate-500">{{ $reg->closed_at ? $reg->closed_at->format('d M H:i') : '-' }}</td>
                            <td class="px-6 py-3 text-sm text-right text-slate-700">{{ number_format($reg->closing_cash, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm text-right font-medium {{ $reg->variance < 0 ? 'text-rose-600' : ($reg->variance > 0 ? 'text-emerald-600' : 'text-slate-400') }}">
                                {{ number_format($reg->variance, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $reg->status == 'open' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800' }}">
                                    {{ ucfirst($reg->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-3 border-t border-slate-200">
                {{ $history->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL: BUKA SHIFT -->
    @if($showOpenModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Buka Shift Baru</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Uang Modal Awal (Cash in Drawer)</label>
                        <input type="number" wire:model="openingCash" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                        @error('openingCash') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan (Opsional)</label>
                        <textarea wire:model="openNote" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="$set('showOpenModal', false)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                        <button wire:click="openRegister" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Buka Kasir</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL: TRANSAKSI MANUAL -->
    @if($showTransactionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Catat Transaksi Kas Manual</h3>
                <div class="space-y-4">
                    <div class="flex bg-slate-100 p-1 rounded-lg">
                        <button wire:click="$set('trxType', 'in')" class="flex-1 py-1 rounded-md text-sm font-medium transition {{ $trxType == 'in' ? 'bg-white shadow text-emerald-600' : 'text-slate-500 hover:text-slate-700' }}">Pemasukan (+)</button>
                        <button wire:click="$set('trxType', 'out')" class="flex-1 py-1 rounded-md text-sm font-medium transition {{ $trxType == 'out' ? 'bg-white shadow text-rose-600' : 'text-slate-500 hover:text-slate-700' }}">Pengeluaran (-)</button>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                        <select wire:model="trxCategory" class="w-full rounded-lg border-slate-300">
                            <option value="expense">Biaya Operasional (Listrik/Air/Makan)</option>
                            <option value="sales">Penjualan Manual</option>
                            <option value="modal">Tambah Modal</option>
                            <option value="prive">Tarik Tunai (Prive)</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah (Rp)</label>
                        <input type="number" wire:model="trxAmount" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                        <textarea wire:model="trxDescription" class="w-full rounded-lg border-slate-300" rows="2" placeholder="Contoh: Beli token listrik"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="$set('showTransactionModal', false)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                        <button wire:click="saveTransaction" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Simpan Transaksi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL: TUTUP SHIFT -->
    @if($showCloseModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Tutup Shift Kasir</h3>
                
                <div class="bg-indigo-50 p-4 rounded-lg mb-4 text-center">
                    <div class="text-xs text-indigo-500 uppercase font-bold tracking-wide">Saldo Sistem</div>
                    <div class="text-2xl font-bold text-indigo-700">Rp {{ number_format($this->currentBalance, 0, ',', '.') }}</div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hitungan Uang Fisik (Real)</label>
                        <input type="number" wire:model.live="closingCash" class="w-full rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                    </div>

                    @php $diff = (float)$closingCash - $this->currentBalance; @endphp
                    @if($closingCash > 0)
                        <div class="flex justify-between items-center text-sm p-2 rounded {{ $diff == 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            <span>Selisih:</span>
                            <span class="font-bold">{{ number_format($diff, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Penutup</label>
                        <textarea wire:model="closeNote" class="w-full rounded-lg border-slate-300" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="$set('showCloseModal', false)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                        <button wire:click="closeRegister" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700" onclick="confirm('Yakin tutup shift? Aksi ini tidak bisa dibatalkan.') || event.stopImmediatePropagation()">Tutup Shift Selesai</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>