<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-16 relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-blue-600/10 to-transparent pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Search Section -->
        <div class="max-w-xl mx-auto text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Service <span class="text-blue-600">Tracking</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mb-8">Pantau status perbaikan perangkat Anda secara real-time.</p>

            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <form wire:submit.prevent="track" class="space-y-4">
                    <div>
                        <label class="block text-left text-xs font-bold text-slate-500 uppercase mb-1">Nomor Tiket (SRV-xxx)</label>
                        <input wire:model="search_ticket" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-lg focus:ring-blue-500" placeholder="SRV-20260124-ABCD">
                        @error('search_ticket') <span class="text-rose-500 text-xs block text-left mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-left text-xs font-bold text-slate-500 uppercase mb-1">Verifikasi No. HP (4 Digit Terakhir)</label>
                        <input wire:model="phone_verification" type="text" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-lg focus:ring-blue-500" placeholder="Contoh: 8899">
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
        @if($result)
            <div class="max-w-4xl mx-auto animate-fade-in-up">
                
                <!-- Status Header -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
                    <div class="p-6 md:p-8 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row justify-between gap-4">
                        <div>
                            <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-bold uppercase tracking-wider mb-2">Service Ticket</span>
                            <h2 class="text-3xl font-black font-mono text-slate-900 dark:text-white">{{ $result->ticket_number }}</h2>
                            <p class="text-slate-500 text-sm mt-1">Dibuat pada: {{ $result->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-500 uppercase font-bold mb-1">Status Saat Ini</p>
                            <div class="text-2xl font-bold {{ $result->status_color }} px-4 py-2 rounded-xl inline-block">
                                {{ $result->status_label }}
                            </div>
                        </div>
                    </div>

                    <!-- Device Info -->
                    <div class="p-6 md:p-8 bg-slate-50 dark:bg-slate-900/50 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Informasi Perangkat</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Perangkat</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $result->device_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Keluhan</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $result->problem_description }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Estimasi Selesai</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $result->estimated_completion ? $result->estimated_completion->format('d M Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Biaya & Teknisi</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Teknisi</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $result->technician->name ?? 'Belum Ditunjuk' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Estimasi Biaya</span>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($result->estimated_cost, 0, ',', '.') }}</span>
                                </div>
                                @if($result->final_cost > 0)
                                    <div class="flex justify-between pt-2 border-t border-slate-200 dark:border-slate-700">
                                        <span class="font-bold text-slate-800 dark:text-white">Total Akhir</span>
                                        <span class="font-black text-emerald-500 text-lg">Rp {{ number_format($result->final_cost, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 md:p-8">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6 uppercase text-sm tracking-wider flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat Pengerjaan
                    </h3>

                    <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8 pb-2">
                        @forelse($timeline as $log)
                            <div class="relative pl-8 group">
                                <!-- Dot -->
                                <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-2 {{ $loop->first ? 'border-blue-500 bg-blue-500' : 'border-slate-300 dark:border-slate-600' }} transition-colors"></div>
                                
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white text-sm capitalize {{ $loop->first ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                            {{ $log->status }}
                                        </p>
                                        @if($log->notes)
                                            <p class="text-xs text-slate-500 mt-1 bg-slate-50 dark:bg-slate-900 p-2 rounded-lg border border-slate-100 dark:border-slate-700 inline-block">
                                                {{ $log->notes }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right sm:text-right">
                                        <p class="text-xs font-mono text-slate-400">{{ $log->created_at->format('d M Y') }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm pl-8">Belum ada riwayat aktivitas.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
