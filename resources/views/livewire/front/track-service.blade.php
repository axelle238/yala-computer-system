<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-16 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-blue-600/10 to-transparent pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Search Section -->
        <div class="max-w-xl mx-auto text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Lacak <span class="text-blue-600">Servis</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mb-8">Pantau status perbaikan perangkat Anda secara real-time.</p>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <form wire:submit.prevent="lacak" class="space-y-4">
                    <div>
                        <label class="block text-left text-xs font-bold text-slate-500 uppercase mb-1">Nomor Tiket (SRV-xxx)</label>
                        <input wire:model="nomorTiket" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-lg focus:ring-blue-500" placeholder="Contoh: SRV-20260124-ABCD">
                        @error('nomorTiket') <span class="text-rose-500 text-xs block text-left mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-left text-xs font-bold text-slate-500 uppercase mb-1">Verifikasi No. HP (4 Digit Terakhir / Full)</label>
                        <input wire:model="verifikasiNomor" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-lg focus:ring-blue-500" placeholder="Contoh: 8899">
                        @error('verifikasiNomor') <span class="text-rose-500 text-xs block text-left mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2">
                        <svg wire:loading.remove class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <svg wire:loading class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Lacak Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Result Section -->
        @if($hasil)
            <div class="max-w-4xl mx-auto animate-fade-in-up space-y-8">
                
                <!-- 1. Visual Stepper (Horizontal) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 md:p-8 overflow-x-auto">
                    <div class="flex items-center justify-between min-w-[600px]">
                        @php
                            $tahapan = [
                                'pending' => 'Menunggu',
                                'diagnosing' => 'Pengecekan',
                                'waiting_part' => 'Tunggu Part',
                                'repairing' => 'Perbaikan',
                                'ready' => 'Selesai',
                                'picked_up' => 'Diambil'
                            ];
                            $ketemu = false;
                        @endphp
                        @foreach($tahapan as $kunci => $label)
                            <div class="flex flex-col items-center relative z-10 w-full group">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 
                                    {{ $hasil->status === $kunci || (!$ketemu && $hasil->status !== 'cancelled') ? 'bg-blue-600 border-blue-600 text-white' : 'bg-slate-50 border-slate-200 text-slate-400' }}
                                    transition-all duration-300 font-bold text-sm">
                                    @if($hasil->status === $kunci)
                                        <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @elseif(!$ketemu && $hasil->status !== 'cancelled')
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <span class="text-xs font-bold uppercase mt-2 {{ $hasil->status === $kunci ? 'text-blue-600' : 'text-slate-400' }}">{{ $label }}</span>
                                
                                {{-- Garis Konektor --}}
                                @if(!$loop->last)
                                    <div class="absolute top-5 left-1/2 w-full h-[2px] -z-10 {{ (!$ketemu && $hasil->status !== 'cancelled') ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                                @endif
                            </div>
                            @if($hasil->status === $kunci) @php $ketemu = true; @endphp @endif
                        @endforeach
                    </div>
                </div>

                <!-- 2. Kartu Info Utama -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-bold uppercase tracking-wider">Tiket Servis</span>
                                @if($hasil->status === 'cancelled')
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase">DIBATALKAN</span>
                                @endif
                            </div>
                            <h2 class="text-3xl font-black font-mono text-slate-900 dark:text-white">{{ $hasil->ticket_number }}</h2>
                            <p class="text-slate-500 text-sm mt-1">Dibuat pada: {{ $hasil->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-sm text-slate-500 uppercase font-bold mb-1">Estimasi Selesai</p>
                             <p class="text-lg font-bold text-slate-800 dark:text-white">
                                 {{ $hasil->estimated_completion ? $hasil->estimated_completion->format('d M Y') : 'Menunggu Diagnosa' }}
                             </p>
                        </div>
                    </div>

                    <!-- Grid Detail -->
                    <div class="p-6 md:p-8 bg-slate-50 dark:bg-slate-900/50 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-4 border-b pb-2">Informasi Perangkat</h3>
                            <div class="space-y-3 text-sm">
                                <div class="grid grid-cols-3">
                                    <span class="text-slate-500">Perangkat</span>
                                    <span class="col-span-2 font-medium text-slate-700 dark:text-slate-300">{{ $hasil->device_name }}</span>
                                </div>
                                <div class="grid grid-cols-3">
                                    <span class="text-slate-500">Keluhan</span>
                                    <span class="col-span-2 font-medium text-slate-700 dark:text-slate-300">{{ $hasil->problem_description }}</span>
                                </div>
                                <div class="grid grid-cols-3">
                                    <span class="text-slate-500">Teknisi</span>
                                    <span class="col-span-2 font-medium text-slate-700 dark:text-slate-300">{{ $hasil->teknisi->name ?? 'Belum Ditunjuk' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Biaya & Sparepart --}}
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-4 border-b pb-2">Rincian Biaya & Sparepart</h3>
                            
                            {{-- Jika menggunakan relasi sukuCadang (SukuCadangServis) --}}
                            @if($hasil->sukuCadang && $hasil->sukuCadang->count() > 0)
                                <div class="space-y-2 text-sm mb-4">
                                    @foreach($hasil->sukuCadang as $item)
                                        <div class="flex justify-between items-start">
                                            <div class="pr-2">
                                                <span class="block text-slate-700 dark:text-slate-300">{{ $item->item_name }}</span>
                                                <span class="text-xs text-slate-500">Qty: {{ $item->quantity }}</span>
                                            </div>
                                            <span class="font-mono text-slate-600 dark:text-slate-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 italic mb-4">Belum ada sparepart atau jasa yang ditambahkan.</p>
                            @endif

                            <div class="border-t border-slate-200 dark:border-slate-700 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-slate-800 dark:text-white">Estimasi Total</span>
                                    {{-- Gunakan estimated_cost dari tiket jika suku cadang kosong --}}
                                    <span class="font-black text-2xl text-emerald-600">
                                        Rp {{ number_format($hasil->final_cost > 0 ? $hasil->final_cost : $hasil->estimated_cost, 0, ',', '.') }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">*Biaya final dapat berubah sesuai pengerjaan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Log Aktivitas Timeline -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 md:p-8">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 uppercase text-sm tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Log Progres Pengerjaan
                    </h3>

                    <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8 pb-2">
                        @forelse($riwayatProgres as $log)
                            <div class="relative pl-8 group">
                                <!-- Dot -->
                                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-2 {{ $loop->first ? 'border-blue-500 bg-blue-500' : 'border-slate-300 dark:border-slate-600' }} transition-colors"></div>
                                
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white text-sm capitalize {{ $loop->first ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                            {{ match($log->status) {
                                                'pending' => 'Menunggu Antrian',
                                                'diagnosing' => 'Sedang Diperiksa (Diagnosa)',
                                                'waiting_part' => 'Menunggu Sparepart',
                                                'repairing' => 'Sedang Diperbaiki',
                                                'ready' => 'Selesai - Siap Diambil',
                                                'picked_up' => 'Telah Diambil',
                                                'cancelled' => 'Dibatalkan',
                                                default => $log->status
                                            } }}
                                        </p>
                                        @if($log->deskripsi)
                                            <p class="text-xs text-slate-500 mt-1 bg-slate-50 dark:bg-slate-900 p-2 rounded-lg border border-slate-100 dark:border-slate-700 inline-block">
                                                {{ $log->deskripsi }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right sm:text-right">
                                        <p class="text-xs font-mono text-slate-400">{{ $log->created_at->format('d M Y') }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i') }} WIB</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm pl-8">Belum ada riwayat aktivitas publik.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>