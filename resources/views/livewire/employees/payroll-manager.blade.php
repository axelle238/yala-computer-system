<div class="space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Payroll <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-600">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Otomatisasi penggajian, hitung komisi, dan cetak slip gaji.</p>
        </div>
        <button wire:click="openGeneratePanel" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
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

    <!-- GENERATE PANEL -->
    @if($activeAction === 'generate')
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-emerald-200 dark:border-emerald-800/30 p-6 shadow-lg animate-fade-in-up">
            <div class="flex justify-between items-center mb-6 border-b border-emerald-100 dark:border-emerald-800/20 pb-4">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </span>
                    Generate Payroll Otomatis
                </h3>
                <button wire:click="closePanel" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

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
                        Hitung Kalkulasi
                    </button>
                </div>
            
            @else
                <!-- Step 2: Preview -->
                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-700 space-y-4">
                    <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-3">
                        <div class="text-sm">
                            <span class="block text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Nama Pegawai</span>
                            <span class="font-bold text-slate-800 dark:text-white text-lg">{{ $previewData['user_name'] }}</span>
                        </div>
                        <div class="text-sm text-right">
                            <span class="block text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">Periode</span>
                            <span class="font-bold text-slate-800 dark:text-white text-lg">{{ $previewData['period'] }}</span>
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
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2 rounded-xl border border-indigo-100 dark:border-indigo-800">
                            Rp {{ number_format($previewData['net_salary'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t border-emerald-100 dark:border-emerald-800/20 pt-4">
                    <button wire:click="$set('previewData', null)" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl font-bold transition-all">Kembali</button>
                    <button wire:click="storePayroll" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Slip Gaji
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- DETAIL PANEL -->
    @if($activeAction === 'detail' && $selectedPayroll)
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in-up">
            <!-- Header Slip -->
            <div class="bg-slate-900 text-white p-8 text-center relative">
                <button wire:click="closePanel" class="absolute top-6 right-6 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                
                <div class="mb-4 inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm border border-white/20">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                
                <h3 class="text-3xl font-black tracking-widest uppercase mb-1">SLIP GAJI</h3>
                <p class="text-sm opacity-60 font-mono tracking-wide">{{ $selectedPayroll->payroll_number }}</p>
                
                <div class="mt-8 p-6 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10 max-w-md mx-auto">
                    <div class="text-xs uppercase tracking-wider opacity-75 mb-1">Total Dibayarkan</div>
                    <div class="text-4xl font-mono font-bold">Rp {{ number_format($selectedPayroll->net_salary, 0, ',', '.') }}</div>
                </div>

                <div class="mt-6">
                    @if($selectedPayroll->status == 'paid')
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-full shadow-lg uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            LUNAS / PAID
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-amber-500 text-white text-xs font-bold rounded-full shadow-lg uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            DRAFT / UNPAID
                        </span>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-8 text-sm text-slate-600 dark:text-slate-300">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pegawai</span>
                        <span class="font-bold text-slate-800 dark:text-white text-xl">{{ $selectedPayroll->user->name }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Periode Gaji</span>
                        <span class="font-bold text-slate-800 dark:text-white text-xl">{{ \Carbon\Carbon::parse($selectedPayroll->period_start)->translatedFormat('F Y') }}</span>
                    </div>
                </div>
                
                <div class="border-t border-dashed border-slate-300 dark:border-slate-600"></div>
                
                <!-- Rincian -->
                @php $d = $selectedPayroll->details; @endphp
                @if(is_array($d))
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Gaji Pokok</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-white text-base">Rp {{ number_format($d['base_salary'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Tunjangan Kehadiran</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-white text-base">Rp {{ number_format($d['total_allowance'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Komisi Servis</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-white text-base">Rp {{ number_format($d['total_commission'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        @if(($d['total_deduction'] ?? 0) > 0)
                            <div class="flex justify-between items-center text-rose-600 dark:text-rose-400 pt-2 border-t border-slate-100 dark:border-slate-700">
                                <span class="font-bold">Potongan Keterlambatan</span>
                                <span class="font-mono font-bold text-base">- Rp {{ number_format($d['total_deduction'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Footer Action -->
            <div class="bg-slate-50 dark:bg-slate-900/50 p-6 flex justify-between items-center border-t border-slate-200 dark:border-slate-700">
                <button onclick="window.print()" class="px-5 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-200 font-bold hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Slip
                </button>
                
                @if($selectedPayroll->status == 'draft')
                    <button wire:click="approveAndPay" class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold shadow-xl shadow-indigo-600/30 transition-all transform active:scale-95 flex items-center gap-2" onclick="confirm('Yakin setujui dan bayar gaji ini? Saldo kasir akan terpotong.') || event.stopImmediatePropagation()">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Setujui & Bayar
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col h-full">

</div>
