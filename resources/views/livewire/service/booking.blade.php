<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-2xl">
        
        <div class="text-center mb-10 animate-fade-in-up">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">
                Booking <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Service</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Jadwalkan kedatangan Anda untuk konsultasi atau perbaikan perangkat tanpa antre.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-100">
            <form wire:submit.prevent="book" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tanggal Kedatangan</label>
                        <input type="date" wire:model="date" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 px-4 py-3">
                        @error('date') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Jam</label>
                        <select wire:model="time" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 px-4 py-3">
                            <option value="">-- Pilih Jam --</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                        </select>
                        @error('time') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Jenis Perangkat</label>
                    <select wire:model="device_type" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 px-4 py-3">
                        <option value="Laptop">Laptop / Notebook</option>
                        <option value="PC">PC Desktop / Rakitan</option>
                        <option value="Printer">Printer</option>
                        <option value="Other">Lainnya (Aksesoris/Monitor)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Keluhan / Masalah</label>
                    <textarea wire:model="problem_description" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-blue-500 px-4 py-3" placeholder="Contoh: Laptop mati total, layar bergaris, ingin upgrade RAM..."></textarea>
                    @error('problem_description') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
