<div class="space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Payroll <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Otomatisasi penggajian, hitung komisi, dan cetak slip gaji.</p>
        </div>
        <button wire:click="openGenerateModal" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Payroll Baru
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-6 z-50 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-emerald-400 animate-slide-in-right">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <div>
                <h4 class="font-bold text-sm uppercase tracking-wider">Berhasil</h4>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-6 z-50 bg-rose-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 border border-rose-400 animate-slide-in-right">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <h4 class="font-bold text-sm uppercase tracking-wider">Gagal</h4>
                <p class="text-sm opacity-90">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">
        <!-- Table Header -->
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700 dark:text-slate-200 text-sm uppercase tracking-wider">Riwayat Penggajian</h3>
            <div class="text-xs font-medium text-slate-500">
                Total: {{ $payrolls->total() }} Data
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Pegawai</th>
                        <th class="px-6 py-4">No. Slip</th>
                        <th class="px-6 py-4 text-right">Gaji Bersih (Net)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($payrolls as $p)
                        <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($p->period_start)->translatedFormat('F Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($p->period_start)->format('d') }} - {{ \Carbon\Carbon::parse($p->period_end)->format('d M') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-400">
                                        {{ substr($p->user->name, 0, 2) }}
                                    </div>
                                    <span class="font-medium text-slate-900 dark:text-white">{{ $p->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-mono text-xs">
                                {{ $p->payroll_number }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-slate-800 dark:text-white text-base">
                                    Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($p->status == 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wide border border-emerald-200 dark:border-emerald-800">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wide border border-amber-200 dark:border-amber-800">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="viewDetail({{ $p->id }})" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-bold text-xs bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-lg border border-emerald-100 dark:border-emerald-800 hover:border-emerald-300 transition-all">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center gap-2 opacity-60">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Belum ada riwayat penggajian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30">
            {{ $payrolls->links() }}
        </div>
    </div>

    <!-- MODAL GENERATE -->
    @if($showGenerateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">Generate Payroll Otomatis</h3>
                    <button wire:click="$set('showGenerateModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    @if(!$previewData)
                        <!-- Step 1: Input -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Pegawai</label>
                                <select wire:model="selectedUserId" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500 focus:border-emerald-500 dark:text-white py-2.5">
                                    <option value="">-- Pilih --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedUserId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Periode Bulan</label>
                                <input type="month" wire:model="selectedMonth" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-emerald-500 focus:border-emerald-500 dark:text-white py-2.5">
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-8">
                            <button wire:click="calculatePreview" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Hitung Kalkulasi
                            </button>
                        </div>
                    
                    @else
                        <!-- Step 2: Preview -->
                        <div class="bg-slate-50 dark:bg-slate-900/50 p-5 rounded-xl border border-slate-200 dark:border-slate-700 space-y-4">
                            <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-3">
                                <div class="text-sm">
                                    <span class="block text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Nama Pegawai</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $previewData['user_name'] }}</span>
                                </div>
                                <div class="text-sm text-right">
                                    <span class="block text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Periode</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $previewData['period'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Income Details -->
                            <div>
                                <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-2">Pendapatan</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                                        <span>Gaji Pokok</span>
                                        <span class="font-mono">Rp {{ number_format($previewData['base_salary'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                                        <span>Tunjangan ({{ $previewData['attendance_count'] }} hari)</span>
                                        <span class="font-mono">Rp {{ number_format($previewData['total_allowance'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                                        <span>Komisi Servis ({{ $previewData['commission_count'] }} tiket)</span>
                                        <span class="font-mono">Rp {{ number_format($previewData['total_commission'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Deduction Details -->
                            @if($previewData['total_deduction'] > 0)
                                <div class="pt-2 border-t border-slate-200 dark:border-slate-700">
                                    <h4 class="text-xs font-bold text-rose-500 dark:text-rose-400 uppercase tracking-widest mb-2">Potongan</h4>
                                    <div class="flex justify-between text-sm text-rose-600 dark:text-rose-400">
                                        <span>Keterlambatan ({{ $previewData['late_minutes'] }} menit)</span>
                                        <span class="font-mono">- Rp {{ number_format($previewData['total_deduction'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Net Total -->
                            <div class="flex justify-between items-center pt-4 mt-2 border-t-2 border-slate-300 dark:border-slate-600">
                                <span class="text-lg font-bold text-slate-800 dark:text-white">Total Gaji Bersih</span>
                                <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg">
                                    Rp {{ number_format($previewData['net_salary'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button wire:click="$set('previewData', null)" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl font-bold transition-all">Kembali</button>
                            <button wire:click="storePayroll" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Slip Gaji
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL DETAIL -->
    @if($showDetailModal && $selectedPayroll)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col transform transition-all scale-100">
                <!-- Header Slip -->
                <div class="bg-slate-900 text-white p-8 text-center relative">
                    <button wire:click="$set('showDetailModal', false)" class="absolute top-4 right-4 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="mb-4 inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-black tracking-widest uppercase mb-1">SLIP GAJI</h3>
                    <p class="text-sm opacity-60 font-mono tracking-wide">{{ $selectedPayroll->payroll_number }}</p>
                    
                    <div class="mt-6 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                        <div class="text-xs uppercase tracking-wider opacity-75 mb-1">Total Dibayarkan</div>
                        <div class="text-3xl font-mono font-bold">Rp {{ number_format($selectedPayroll->net_salary, 0, ',', '.') }}</div>
                    </div>

                    <div class="mt-4">
                        @if($selectedPayroll->status == 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full shadow-lg">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                LUNAS / PAID
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-lg">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                DRAFT / UNPAID
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 space-y-6 text-sm text-slate-600 dark:text-slate-300">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pegawai</span>
                            <span class="font-bold text-slate-800 dark:text-white text-lg">{{ $selectedPayroll->user->name }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Periode Gaji</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($selectedPayroll->period_start)->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-dashed border-slate-300 dark:border-slate-600"></div>
                    
                    <!-- Rincian -->
                    @php $d = $selectedPayroll->details; @endphp
                    @if(is_array($d))
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>Gaji Pokok</span>
                                <span class="font-mono font-medium text-slate-800 dark:text-white">Rp {{ number_format($d['base_salary'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tunjangan Kehadiran</span>
                                <span class="font-mono font-medium text-slate-800 dark:text-white">Rp {{ number_format($d['total_allowance'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Komisi Servis</span>
                                <span class="font-mono font-medium text-slate-800 dark:text-white">Rp {{ number_format($d['total_commission'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if(($d['total_deduction'] ?? 0) > 0)
                                <div class="flex justify-between text-rose-600 dark:text-rose-400 font-medium">
                                    <span>Potongan</span>
                                    <span class="font-mono">- Rp {{ number_format($d['total_deduction'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Footer Action -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 flex justify-between items-center border-t border-slate-200 dark:border-slate-700">
                    <button onclick="window.print()" class="text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white text-sm font-bold flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Slip
                    </button>
                    
                    @if($selectedPayroll->status == 'draft')
                        <button wire:click="approveAndPay" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-600/30 transition-all transform active:scale-95" onclick="confirm('Yakin setujui dan bayar gaji ini? Saldo kasir akan terpotong.') || event.stopImmediatePropagation()">
                            Setujui & Bayar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>