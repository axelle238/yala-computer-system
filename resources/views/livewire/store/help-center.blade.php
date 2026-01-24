<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl md:text-5xl font-black font-tech text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">
                Help <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Center</span>
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg mb-8">Temukan jawaban cepat atau hubungi tim support kami.</p>
            
            <div class="relative max-w-xl mx-auto">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full pl-12 pr-4 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-lg focus:ring-cyan-500 focus:border-cyan-500 transition-all text-lg" placeholder="Cari solusi masalah Anda...">
                <svg class="w-6 h-6 text-slate-400 absolute left-4 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Categories -->
            <div class="lg:col-span-1 space-y-6 animate-fade-in-up delay-100">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 sticky top-24">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4 uppercase text-sm tracking-wider">Kategori Bantuan</h3>
                    <div class="space-y-2">
                        @foreach($categories as $cat)
                            <button wire:click="setTab('{{ $cat->slug }}')" class="w-full text-left px-4 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-between {{ $activeTab === $cat->slug ? 'bg-cyan-50 dark:bg-cyan-900/20 text-cyan-600 dark:text-cyan-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                                {{ $cat->name }}
                                @if($activeTab === $cat->slug)
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Contact Support Widget -->
                <div class="bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl p-6 text-white shadow-lg sticky top-[400px]">
                    <h3 class="font-bold text-lg mb-2">Masih butuh bantuan?</h3>
                    <p class="text-blue-100 text-sm mb-4">Tim support kami siap membantu Anda menyelesaikan masalah teknis.</p>
                    <a href="#contact-form" class="block w-full py-3 bg-white text-blue-600 font-bold rounded-xl text-center hover:bg-blue-50 transition-colors shadow">Hubungi Kami</a>
                </div>
            </div>

            <!-- Content FAQ -->
            <div class="lg:col-span-2 space-y-8 animate-fade-in-up delay-200">
                @if(count($faqs) > 0)
                    <div class="space-y-4">
                        @foreach($faqs as $faq)
                            <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden transition-all hover:shadow-md">
                                <button @click="open = !open" class="w-full text-left px-6 py-4 flex justify-between items-center font-bold text-slate-800 dark:text-white">
                                    <span>{{ $faq->question }}</span>
                                    <svg class="w-5 h-5 transition-transform duration-300" :class="open ? 'rotate-180 text-cyan-500' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="px-6 pb-6 text-slate-600 dark:text-slate-300 text-sm leading-relaxed border-t border-slate-100 dark:border-slate-700 pt-4">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 text-slate-400">
                        <p>Tidak ada artikel ditemukan.</p>
                    </div>
                @endif

                <!-- Contact Form Section -->
                <div id="contact-form" class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-200 dark:border-slate-700 mt-12">
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white mb-6">Kirim Pesan ke Support</h3>
                    <form wire:submit.prevent="sendMessage" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                                @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Email</label>
                                <input type="email" wire:model="email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                                @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Subjek</label>
                            <input type="text" wire:model="subject" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                            @error('subject') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pesan</label>
                            <textarea wire:model="message" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm"></textarea>
                            @error('message') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-8 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition-all">Kirim Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
