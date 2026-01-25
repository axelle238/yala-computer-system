<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 relative overflow-hidden">
    <!-- Map Background (Abstract) -->
    <div class="absolute inset-0 opacity-10 pointer-events-none bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] bg-cover bg-center"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter mb-4">
                Get In <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">Touch</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
                Punya pertanyaan seputar rakit PC atau butuh penawaran untuk kantor? Tim kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- Contact Info -->
            <div class="space-y-8 animate-fade-in-up delay-100">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Markas Operasional</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-500">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat</p>
                                <p class="text-slate-800 dark:text-slate-200 font-medium leading-relaxed">
                                    {{ \App\Models\Setting::get('store_address', 'Jl. Teknologi No. 88, Jakarta Selatan, DKI Jakarta 12345') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-500">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Telepon / WhatsApp</p>
                                <p class="text-slate-800 dark:text-slate-200 font-mono font-medium">
                                    {{ \App\Models\Setting::get('store_phone', '+62 812-3456-7890') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-500">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Email</p>
                                <p class="text-slate-800 dark:text-slate-200 font-medium">
                                    hello@yalacomputer.id
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Accordion (Simple) -->
                <div class="space-y-3">
                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <span>Berapa lama garansi produk?</span>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" class="px-6 pb-4 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            Semua produk baru bergaransi resmi distributor (1-3 tahun tergantung brand). Untuk PC rakitan, kami memberikan garansi servis tambahan selama 1 tahun.
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <button @click="open = !open" class="w-full px-6 py-4 text-left flex justify-between items-center font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <span>Apakah bisa kirim ke luar kota?</span>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" class="px-6 pb-4 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                            Ya, kami melayani pengiriman ke seluruh Indonesia menggunakan ekspedisi terpercaya (JNE, J&T, SiCepat) dengan packing kayu wajib untuk PC rakitan.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl border border-slate-200 dark:border-slate-700 animate-fade-in-up delay-200">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Kirim Pesan</h3>
                
                <form wire:submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                        @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Alamat Email</label>
                        <input wire:model="email" type="email" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                        @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Subjek</label>
                        <select wire:model="subject" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                            <option value="">-- Pilih Topik --</option>
                            <option value="Penawaran Rakit PC">Penawaran Rakit PC</option>
                            <option value="Status Pesanan">Status Pesanan</option>
                            <option value="Klaim Garansi">Klaim Garansi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('subject') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Pesan</label>
                        <textarea wire:model="message" rows="5" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-rose-500 dark:text-white py-3 px-4 transition-all"></textarea>
                        @error('message') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-lg shadow-rose-600/30 transition-all transform hover:-translate-y-1">
                        Kirim Pesan Sekarang
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
