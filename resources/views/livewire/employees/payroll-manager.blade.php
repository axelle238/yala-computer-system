<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Manajemen Penggajian</h2>
        <button wire:click="openGenerateModal" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Payroll Baru
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <!-- Payroll List -->
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-slate-200">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Pegawai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">No. Slip</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Gaji Bersih</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($payrolls as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                            {{ \Carbon\Carbon::parse($p->period_start)->translatedFormat('F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $p->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            {{ $p->payroll_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-800">
                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($p->status == 'paid')
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">LUNAS</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">DRAFT</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <button wire:click="viewDetail({{ $p->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Lihat Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">Belum ada data penggajian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-3 border-t border-slate-200">
            {{ $payrolls->links() }}
        </div>
    </div>

    <!-- MODAL GENERATE -->
    @if($showGenerateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <h3 class="text-xl font-bold text-slate-800 mb-6 border-b pb-2">Generate Payroll Otomatis</h3>

                @if(!$previewData)
                    <!-- Step 1: Input -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Pegawai</label>
                            <select wire:model="selectedUserId" class="w-full rounded-lg border-slate-300">
                                <option value="">-- Pilih --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedUserId') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Periode Bulan</label>
                            <input type="month" wire:model="selectedMonth" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('showGenerateModal', false)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                        <button wire:click="calculatePreview" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Hitung Kalkulasi</button>
                    </div>
                
                @else
                    <!-- Step 2: Preview -->
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 mb-6 space-y-3">
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Nama Pegawai</span>
                            <span class="font-bold text-slate-800">{{ $previewData['user_name'] }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Periode</span>
                            <span class="font-bold text-slate-800">{{ $previewData['period'] }}</span>
                        </div>
                        
                        <!-- Income -->
                        <div class="pt-2">
                            <div class="text-xs font-bold text-slate-400 uppercase mb-1">Pendapatan</div>
                            <div class="flex justify-between text-sm">
                                <span>Gaji Pokok</span>
                                <span>Rp {{ number_format($previewData['base_salary'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Tunjangan ({{ $previewData['attendance_count'] }} hari)</span>
                                <span>Rp {{ number_format($previewData['total_allowance'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Komisi Servis ({{ $previewData['commission_count'] }} tiket)</span>
                                <span>Rp {{ number_format($previewData['total_commission'], 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Deduction -->
                        @if($previewData['total_deduction'] > 0)
                            <div class="pt-2">
                                <div class="text-xs font-bold text-rose-400 uppercase mb-1">Potongan</div>
                                <div class="flex justify-between text-sm text-rose-600">
                                    <span>Keterlambatan ({{ $previewData['late_minutes'] }} menit)</span>
                                    <span>- Rp {{ number_format($previewData['total_deduction'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Net -->
                        <div class="flex justify-between items-center pt-4 mt-2 border-t-2 border-slate-200">
                            <span class="text-lg font-bold text-slate-800">Total Gaji Bersih</span>
                            <span class="text-xl font-black text-indigo-600">Rp {{ number_format($previewData['net_salary'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button wire:click="$set('previewData', null)" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg">Kembali</button>
                        <button wire:click="storePayroll" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold shadow-lg">Simpan Slip Gaji</button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- MODAL DETAIL -->
    @if($showDetailModal && $selectedPayroll)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden">
                <!-- Header Slip -->
                <div class="bg-slate-800 text-white p-6 text-center relative">
                    <button wire:click="$set('showDetailModal', false)" class="absolute top-4 right-4 text-slate-400 hover:text-white">&times;</button>
                    <h3 class="text-xl font-bold tracking-widest uppercase">Slip Gaji</h3>
                    <p class="text-sm opacity-75">Periode: {{ \Carbon\Carbon::parse($selectedPayroll->period_start)->translatedFormat('F Y') }}</p>
                    <div class="mt-4 text-3xl font-mono font-bold">Rp {{ number_format($selectedPayroll->net_salary, 0, ',', '.') }}</div>
                    @if($selectedPayroll->status == 'paid')
                        <div class="mt-2 inline-block px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full">LUNAS / PAID</div>
                    @else
                        <div class="mt-2 inline-block px-3 py-1 bg-slate-500 text-white text-xs font-bold rounded-full">DRAFT / UNPAID</div>
                    @endif
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Nama Pegawai</span>
                        <span class="font-bold text-slate-800">{{ $selectedPayroll->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jabatan</span>
                        <span class="font-bold text-slate-800">{{ $selectedPayroll->user->employeeDetail->job_title ?? '-' }}</span>
                    </div>
                    
                    <hr class="border-dashed border-slate-300 my-4">
                    
                    <!-- Rincian dari JSON Details (Snapshot) -->
                    @php $d = $selectedPayroll->details; @endphp
                    @if(is_array($d))
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Gaji Pokok</span>
                                <span>Rp {{ number_format($d['base_salary'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tunjangan Kehadiran</span>
                                <span>Rp {{ number_format($d['total_allowance'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Komisi Servis</span>
                                <span>Rp {{ number_format($d['total_commission'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if(($d['total_deduction'] ?? 0) > 0)
                                <div class="flex justify-between text-rose-600">
                                    <span>Potongan (Telat/Lainnya)</span>
                                    <span>- Rp {{ number_format($d['total_deduction'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- Footer Action -->
                <div class="bg-slate-50 p-4 flex justify-between items-center border-t border-slate-200">
                    <button onclick="window.print()" class="text-slate-500 hover:text-slate-800 text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </button>
                    
                    @if($selectedPayroll->status == 'draft')
                        <button wire:click="approveAndPay" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-500/30" onclick="confirm('Yakin setujui dan bayar gaji ini? Saldo kasir akan terpotong.') || event.stopImmediatePropagation()">
                            Setujui & Bayar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
