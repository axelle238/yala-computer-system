<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <!-- Hero Header -->
    <div class="bg-indigo-700 py-16 mb-12 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl font-black font-tech text-white mb-4 uppercase tracking-tighter">Help <span class="text-cyan-400">Center</span></h1>
            <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto">Temukan jawaban atas pertanyaan Anda seputar produk, layanan, dan garansi.</p>
            
            <div class="max-w-xl mx-auto relative">
                <input wire:model.live.debounce.300ms="search" type="text" class="w-full py-4 pl-12 pr-4 rounded-full shadow-lg border-0 focus:ring-4 focus:ring-cyan-400/50 text-slate-800" placeholder="Cari topik bantuan (cth: garansi, pengiriman)...">
                <svg class="w-6 h-6 text-slate-400 absolute left-4 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        @if(strlen($search) > 0)
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-4">Hasil Pencarian</h3>
                <!-- Flat Search Results -->
                <div class="space-y-4">
                    @php $found = false; @endphp
                    @foreach($categories as $cat)
                        @foreach($cat->faqs as $faq)
                            @php $found = true; @endphp
                            <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                                <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $faq->question }}</span>
                                    <svg class="w-5 h-5 text-slate-400 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" class="p-5 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 prose prose-sm dark:prose-invert max-w-none">
                                    {!! nl2br(e($faq->answer)) !!}
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                    @if(!$found)
                        <div class="text-center py-12 text-slate-500">Tidak ditemukan hasil untuk "{{ $search }}".</div>
                    @endif
                </div>
            </div>
        @else
            <!-- Categorized View -->
            <div class="space-y-8">
                @foreach($categories as $cat)
                    @if($cat->faqs->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-200 dark:border-slate-700 pb-2">
                                {{ $cat->name }}
                            </h2>
                            <div class="space-y-3">
                                @foreach($cat->faqs as $faq)
                                    <div x-data="{ open: false }" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                                        <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                            <span class="font-medium text-slate-700 dark:text-slate-200">{{ $faq->question }}</span>
                                            <svg class="w-5 h-5 text-slate-400 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                        <div x-show="open" x-collapse class="p-5 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="mt-12 text-center">
            <p class="text-slate-500 mb-4">Masih butuh bantuan?</p>
            <a href="{{ route('store.contact') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>