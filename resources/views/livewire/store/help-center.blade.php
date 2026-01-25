<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <!-- Hero Search -->
    <div class="bg-indigo-600 py-16 -mt-12 mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mb-10 -mr-10"></div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-3xl md:text-4xl font-black font-tech text-white mb-6 tracking-tight">Bagaimana kami bisa membantu?</h1>
            <div class="max-w-2xl mx-auto relative">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full py-4 pl-12 pr-6 rounded-2xl shadow-xl border-none focus:ring-4 focus:ring-indigo-400/50 text-slate-800 placeholder-slate-400" placeholder="Cari pertanyaan (contoh: garansi, pengiriman)...">
                <svg class="w-6 h-6 text-slate-400 absolute left-4 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Categories -->
            <div class="lg:col-span-1 space-y-4">
                <h3 class="font-bold text-slate-400 text-xs uppercase tracking-wider mb-2">Kategori Bantuan</h3>
                <button wire:click="selectCategory(null)" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all flex items-center gap-3 {{ is_null($activeCategory) ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    <span class="w-8 h-8 rounded-lg bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></span>
                    Semua Topik
                </button>
                @foreach($categories as $cat)
                    <button wire:click="selectCategory({{ $cat['id'] }})" class="w-full text-left px-4 py-3 rounded-xl font-bold transition-all flex items-center gap-3 {{ $activeCategory == $cat['id'] ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                            <!-- Dynamic Icon Mock -->
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        {{ $cat['name'] }}
                    </button>
                @endforeach
            </div>

            <!-- FAQ List -->
            <div class="lg:col-span-3">
                <div class="space-y-4">
                    @forelse($faqs as $faq)
                        <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-all">
                            <button @click="open = !open" class="w-full px-6 py-5 text-left flex justify-between items-center font-bold text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <span>{{ $faq['question'] }}</span>
                                <span class="ml-4 transform transition-transform duration-200" :class="open ? 'rotate-180 text-indigo-500' : 'text-slate-400'">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>
                            <div x-show="open" x-collapse class="px-6 pb-6 text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700 pt-4">
                                {!! nl2br(e($faq['answer'])) !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 text-slate-400 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="font-bold">Pertanyaan tidak ditemukan.</p>
                            <p class="text-sm">Coba kata kunci lain atau hubungi support kami.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Contact CTA -->
                <div class="mt-12 bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 text-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold text-white mb-2">Masih butuh bantuan?</h3>
                        <p class="text-slate-400 mb-6">Tim support kami siap membantu Anda menyelesaikan masalah.</p>
                        <a href="{{ route('store.contact') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-slate-900 font-bold rounded-xl hover:bg-indigo-50 transition-colors shadow-lg">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
