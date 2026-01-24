<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-6 uppercase tracking-tighter">
                Hubungi <span class="text-cyan-500">Kami</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400">
                Punya pertanyaan tentang produk, layanan, atau kemitraan? Tim kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            
            <!-- Contact Info -->
            <div class="space-y-8 animate-fade-in-up delay-100">
                <!-- Info Cards -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-cyan-500/50 transition-all">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                    <div class="relative z-10 flex items-start gap-6">
                        <div class="w-14 h-14 bg-cyan-100 dark:bg-cyan-900/30 rounded-2xl flex items-center justify-center text-cyan-600 dark:text-cyan-400 flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Lokasi Store</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                {{ \App\Models\Setting::get('address') }}
                            </p>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-cyan-600 mt-4 hover:text-cyan-500">
                                Lihat di Google Maps <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-emerald-500/50 transition-all">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="relative z-10 flex items-start gap-6">
                        <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Kontak Langsung</h3>
                            <p class="text-slate-600 dark:text-slate-400 mb-1">WhatsApp: <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ \App\Models\Setting::get('whatsapp_number') }}</span></p>
                            <p class="text-slate-600 dark:text-slate-400 mb-1">Email: <span class="font-mono font-bold text-slate-800 dark:text-slate-200">support@yalacomputer.com</span></p>
                            <p class="text-slate-600 dark:text-slate-400">Jam Operasional: 10:00 - 20:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-2xl animate-fade-in-up delay-200 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 opacity-50"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Kirim Pesan</h3>
                    
                    <form wire:submit.prevent="sendMessage" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Lengkap</label>
                                <input wire:model="name" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-400" placeholder="John Doe">
                                @error('name') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Email</label>
                                <input wire:model="email" type="email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-400" placeholder="john@example.com">
                                @error('email') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Subjek</label>
                            <input wire:model="subject" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-400" placeholder="Contoh: Pertanyaan Garansi">
                            @error('subject') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Pesan</label>
                            <textarea wire:model="message" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-cyan-500 transition-all placeholder-slate-400" placeholder="Tulis pesan Anda disini..."></textarea>
                            @error('message') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-bold rounded-xl shadow-lg hover:shadow-cyan-500/30 transition-all transform hover:-translate-y-1">
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>