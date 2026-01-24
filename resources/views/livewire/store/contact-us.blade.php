<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Get In <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Touch</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg max-w-2xl mx-auto">
                Punya pertanyaan tentang produk atau layanan kami? Tim support kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            
            <!-- Contact Info -->
            <div class="space-y-8 animate-fade-in-up delay-100">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Lokasi Store
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
                        Ruko Mangga Dua Mall, Blok A No. 12<br>
                        Jl. Mangga Dua Raya, Jakarta Pusat<br>
                        DKI Jakarta, 10730
                    </p>
                    <div class="aspect-video rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-700 relative">
                        <!-- Map Placeholder -->
                        <div class="absolute inset-0 flex items-center justify-center text-slate-400">
                            <span class="text-xs font-bold uppercase tracking-widest">Google Maps Embed</span>
                        </div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.866763428354!2d106.82496431476876!3d-6.148648995548366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5f2b8c0c0c1%3A0x6c0c0c0c0c0c0c0c!2sMangga%20Dua%20Mall!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            Kontak
                        </h4>
                        <p class="text-sm text-slate-500 mb-1">021-6230-xxxx (Office)</p>
                        <p class="text-sm text-slate-500 mb-1">0812-3456-7890 (WA)</p>
                        <p class="text-sm text-slate-500">support@yalacomputer.com</p>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Jam Operasional
                        </h4>
                        <p class="text-sm text-slate-500 mb-1">Senin - Jumat: 10:00 - 18:00</p>
                        <p class="text-sm text-slate-500 mb-1">Sabtu: 10:00 - 15:00</p>
                        <p class="text-sm text-rose-500 font-bold">Minggu / Libur: Tutup</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-200">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Kirim Pesan</h3>
                <form wire:submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 focus:ring-blue-500">
                            @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Email</label>
                            <input wire:model="email" type="email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 focus:ring-blue-500">
                            @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Subjek</label>
                        <input wire:model="subject" type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 focus:ring-blue-500">
                        @error('subject') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Pesan</label>
                        <textarea wire:model="message" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 focus:ring-blue-500"></textarea>
                        @error('message') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Kirim Pesan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
