<div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-cyan-500/10 to-transparent -z-10"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl -z-10"></div>

    <div class="w-full max-w-md space-y-8">
        <!-- Logo / Brand -->
        <div class="text-center">
            <h1 class="text-4xl font-black font-tech tracking-tighter text-slate-900 dark:text-white uppercase">
                Yala <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-violet-500">Service</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Tracking System & Status Checker</p>
        </div>

        <!-- Search Box -->
        <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/50 border border-slate-100 dark:border-slate-700 relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-violet-500 rounded-3xl opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            
            <form wire:submit="track" class="relative z-10 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Nomor Tiket Service</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">#</span>
                        <input wire:model="search_ticket" type="text" 
                            class="block w-full pl-8 pr-4 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-700 rounded-xl font-mono text-lg font-bold text-slate-800 dark:text-white focus:border-cyan-500 focus:ring-0 transition-colors uppercase placeholder-slate-300" 
                            placeholder="SRV-2024..." required>
                    </div>
                    @error('search_ticket') <span class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold hover:shadow-lg hover:shadow-cyan-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span wire:loading.remove>CEK STATUS</span>
                    <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
        </div>

        <!-- Result -->
        @if($result)
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700 animate-fade-in-up">
            <!-- Header Status -->
            <div class="p-6 bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-slate-800 dark:text-white">{{ $result->device_name }}</h2>
                    <p class="text-xs text-slate-500">Milik: {{ $result->customer_name }}</p>
                </div>
                @php
                    $colors = [
                        'pending' => 'bg-slate-200 text-slate-600',
                        'diagnosing' => 'bg-blue-100 text-blue-600',
                        'waiting_part' => 'bg-amber-100 text-amber-600',
                        'repairing' => 'bg-purple-100 text-purple-600',
                        'ready' => 'bg-emerald-100 text-emerald-600',
                        'picked_up' => 'bg-slate-800 text-slate-200',
                        'cancelled' => 'bg-rose-100 text-rose-600',
                    ];
                    $labels = [
                        'pending' => 'Menunggu',
                        'diagnosing' => 'Pengecekan',
                        'waiting_part' => 'Tunggu Part',
                        'repairing' => 'Sedang Dikerjakan',
                        'ready' => 'Siap Diambil',
                        'picked_up' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $colors[$result->status] ?? 'bg-slate-100' }}">
                    {{ $labels[$result->status] ?? $result->status }}
                </span>
            </div>

            <div class="p-6 space-y-4">
                <!-- Timeline/Progress -->
                <div class="relative pt-2">
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                        @php
                            $progress = match($result->status) {
                                'pending' => 10,
                                'diagnosing' => 30,
                                'waiting_part' => 40,
                                'repairing' => 60,
                                'ready' => 90,
                                'picked_up' => 100,
                                default => 0
                            };
                        @endphp
                        <div class="h-full bg-gradient-to-r from-cyan-500 to-violet-500 transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-xs text-slate-400 uppercase font-bold">Masuk Tanggal</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $result->created_at->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 uppercase font-bold">Estimasi Biaya</span>
                        <span class="font-bold font-mono text-slate-800 dark:text-white">Rp {{ number_format($result->estimated_cost, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($result->technician_notes)
                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-100 dark:border-amber-800/50">
                    <span class="block text-xs text-amber-600 dark:text-amber-400 uppercase font-bold mb-1">Catatan Teknisi</span>
                    <p class="text-sm text-slate-700 dark:text-slate-300 italic">"{{ $result->technician_notes }}"</p>
                </div>
                @endif
                
                @if($result->status == 'ready' || $result->status == 'picked_up')
                <div class="text-center mt-4">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mb-2">Perangkat Anda sudah selesai diperbaiki.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Yala Computer System.
        </div>
    </div>
</div>
