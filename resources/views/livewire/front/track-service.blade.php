<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 flex flex-col justify-center">
    <div class="container mx-auto px-4 max-w-3xl">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                Service <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Tracker</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Masukkan nomor tiket servis (Contoh: SRV-2024...) untuk melacak status perbaikan perangkat Anda.</p>
        </div>

        <!-- Search Box -->
        <div class="bg-white dark:bg-slate-800 p-2 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 mb-8 animate-fade-in-up delay-100 max-w-xl mx-auto">
            <form wire:submit.prevent="track" class="flex gap-2">
                <input wire:model="search_ticket" type="text" class="flex-1 bg-transparent border-none focus:ring-0 text-slate-800 dark:text-white font-bold placeholder-slate-400 px-4" placeholder="SRV-XXXXXXXX">
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg shadow-cyan-600/30">
                    Lacak
                </button>
            </form>
        </div>
        @error('search_ticket') <p class="text-center text-rose-500 font-bold mb-4 animate-bounce">{{ $message }}</p> @enderror

        <!-- Result -->
        @if($result)
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 animate-fade-in-up">
                <!-- Ticket Header -->
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white flex justify-between items-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold uppercase text-slate-400 tracking-wider">Ticket Number</p>
                        <h2 class="text-3xl font-mono font-black tracking-tight">{{ $result->ticket_number }}</h2>
                    </div>
                    <div class="relative z-10 text-right">
                         <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-white/10 border border-white/20">
                            {{ str_replace('_', ' ', $result->status) }}
                         </span>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Customer Info -->
                    <div class="grid grid-cols-2 gap-6 mb-8 pb-8 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500 mb-1">Nama Pelanggan</p>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $result->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500 mb-1">Perangkat</p>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $result->device_name }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs font-bold uppercase text-slate-500 mb-1">Keluhan / Masalah</p>
                            <p class="text-slate-600 dark:text-slate-300 italic">"{{ $result->problem_description }}"</p>
                        </div>
                    </div>

                    <!-- Timeline Logic -->
                    @php
                        $steps = [
                            ['status' => 'pending', 'label' => 'Diterima', 'desc' => 'Perangkat diterima oleh admin.'],
                            ['status' => 'diagnosing', 'label' => 'Pengecekan', 'desc' => 'Teknisi sedang menganalisa kerusakan.'],
                            ['status' => 'repairing', 'label' => 'Perbaikan', 'desc' => 'Sedang dalam proses perbaikan/penggantian part.'],
                            ['status' => 'completed', 'label' => 'Selesai', 'desc' => 'Perangkat selesai diperbaiki dan siap diambil.'],
                            ['status' => 'picked_up', 'label' => 'Diambil', 'desc' => 'Perangkat telah diserahkan kembali.'],
                        ];
                        
                        // Determine current step index
                        $currentStatus = $result->status;
                        $currentIndex = -1;
                        foreach ($steps as $idx => $step) {
                            if ($step['status'] === $currentStatus) {
                                $currentIndex = $idx;
                                break;
                            }
                        }
                        // Handle waiting_parts as equivalent to diagnosing/repairing visual level but special label
                        if ($currentStatus === 'waiting_parts') $currentIndex = 2; // Treat roughly as middle
                        if ($currentStatus === 'cancelled') $currentIndex = 0; // Show stopped
                    @endphp

                    <div class="relative">
                        <!-- Connecting Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-100 dark:bg-slate-700"></div>

                        <div class="space-y-8 relative z-10">
                            @foreach($steps as $idx => $step)
                                <div class="flex gap-4 group">
                                    <!-- Indicator -->
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 
                                        {{ $idx <= $currentIndex ? 'bg-cyan-600 border-cyan-100 dark:border-cyan-900 text-white' : 'bg-slate-200 dark:bg-slate-700 border-white dark:border-slate-800 text-slate-400' }} 
                                        transition-all shadow-sm">
                                        @if($idx < $currentIndex)
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @elseif($idx === $currentIndex)
                                            <div class="w-2.5 h-2.5 bg-white rounded-full animate-pulse"></div>
                                        @else
                                            <span class="text-xs font-bold">{{ $idx + 1 }}</span>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="{{ $idx <= $currentIndex ? 'opacity-100' : 'opacity-40 grayscale' }} transition-all flex-1">
                                        <h4 class="font-bold text-slate-800 dark:text-white text-lg">{{ $step['label'] }}</h4>
                                        <p class="text-sm text-slate-500">{{ $step['desc'] }}</p>
                                        
                                        @if($idx === $currentIndex && $result->technician_notes)
                                            <div class="mt-3 bg-amber-50 dark:bg-amber-900/20 p-3 rounded-lg border border-amber-100 dark:border-amber-800">
                                                <p class="text-xs font-bold uppercase text-amber-600 mb-1">Catatan Teknisi Terbaru:</p>
                                                <p class="text-sm text-amber-800 dark:text-amber-200">{{ $result->technician_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($result->status === 'cancelled')
                        <div class="mt-8 bg-rose-50 dark:bg-rose-900/20 p-4 rounded-xl text-center border border-rose-100 dark:border-rose-800">
                            <p class="font-bold text-rose-600">Servis Dibatalkan</p>
                            <p class="text-sm text-rose-500">Mohon hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>