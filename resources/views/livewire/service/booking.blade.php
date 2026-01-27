<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-16 font-sans">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <div class="text-center mb-12 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Booking <span class="text-cyan-500">Service</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Jadwalkan perbaikan perangkat Anda tanpa antre lama.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-2xl border border-slate-200 dark:border-slate-700 relative overflow-hidden animate-fade-in-up delay-100">
            <!-- Progress Bar -->
            <div class="absolute top-0 left-0 h-1.5 bg-slate-100 dark:bg-slate-700 w-full">
                <div class="h-full bg-cyan-500 transition-all duration-500" style="width: {{ ($step / 4) * 100 }}%"></div>
            </div>

            @if($step === 1)
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Langkah 1: Info Perangkat</h2>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Jenis Perangkat</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach(['laptop' => 'Laptop', 'pc' => 'PC / Komputer', 'printer' => 'Printer / Lainnya'] as $val => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="device_type" value="{{ $val }}" class="peer sr-only">
                                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-center peer-checked:border-cyan-500 peer-checked:bg-cyan-50 dark:peer-checked:bg-cyan-900/20 peer-checked:text-cyan-600 transition-all hover:bg-slate-100 dark:hover:bg-slate-800">
                                        <span class="text-sm font-bold">{{ $label }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Deskripsi Masalah</label>
                        <textarea wire:model="problem_description" rows="4" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-400" placeholder="Contoh: Layar mati total, keyboard tidak berfungsi..."></textarea>
                        @error('problem_description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button wire:click="nextStep" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all">Lanjut &rarr;</button>
                    </div>
                </div>
            @elseif($step === 2)
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Langkah 2: Pilih Jadwal</h2>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tanggal & Waktu Kunjungan</label>
                        <input wire:model="appointment_date" type="datetime-local" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 font-mono">
                        @error('appointment_date') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-between">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800">Kembali</button>
                        <button wire:click="nextStep" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all">Lanjut &rarr;</button>
                    </div>
                </div>
            @elseif($step === 3)
                <div class="space-y-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">Langkah 3: Kontak Anda</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Lengkap</label>
                            <input wire:model="guest_name" type="text" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            @error('guest_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">No. WhatsApp</label>
                            <input wire:model="guest_phone" type="text" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500">
                            @error('guest_phone') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800">Kembali</button>
                        <button wire:click="nextStep" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all">Review &rarr;</button>
                    </div>
                </div>
            @elseif($step === 4)
                <div class="space-y-6 text-center">
                    <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Konfirmasi Booking</h2>
                    <p class="text-slate-500">Mohon periksa data Anda sebelum mengirim.</p>

                    <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-6 text-left space-y-3 text-sm border border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Perangkat</span>
                            <span class="font-bold uppercase text-slate-800 dark:text-white">{{ $device_type }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Jadwal</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ \Carbon\Carbon::parse($appointment_date)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-2">
                            <span class="text-slate-500">Kontak</span>
                            <span class="font-bold text-slate-800 dark:text-white">{{ $guest_name }} ({{ $guest_phone }})</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block mb-1">Masalah:</span>
                            <p class="text-slate-800 dark:text-white italic bg-white dark:bg-slate-800 p-2 rounded border border-slate-100 dark:border-slate-700">{{ $problem_description }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4">
                        <button wire:click="prevStep" class="text-slate-500 font-bold hover:text-slate-800">Ubah Data</button>
                        <button wire:click="submit" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-xl shadow-emerald-500/30 transition-all transform hover:-translate-y-1">
                            Kirim Booking
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
