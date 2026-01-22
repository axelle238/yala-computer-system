<div 
    x-data="{ 
        open: @entangle('isOpen'), 
        focusIndex: -1
    }"
    x-on:open-spotlight.window="open = true; $nextTick(() => $refs.searchInput.focus())"
    x-on:keydown.window.ctrl.k.prevent="open = true; $nextTick(() => $refs.searchInput.focus())"
    x-on:keydown.escape.window="open = false; $wire.set('search', '')"
    x-on:keydown.arrow-down="focusIndex = (focusIndex + 1) % {{ count($results) }}"
    x-on:keydown.arrow-up="focusIndex = (focusIndex - 1 + {{ count($results) }}) % {{ count($results) }}"
    x-on:keydown.enter="if(focusIndex >= 0) { window.location.href = $refs['result' + focusIndex].href; }"
    class="relative z-[999]"
    style="display: none;"
    x-show="open"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" x-show="open" x-transition.opacity @click="open = false"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div class="mx-auto max-w-2xl transform divide-y divide-slate-700/50 overflow-hidden rounded-2xl bg-slate-900 shadow-2xl transition-all border border-slate-700 tech-border"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-cyan-500 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    x-ref="searchInput" 
                    wire:model.live.debounce.100ms="search"
                    type="text" 
                    class="h-14 w-full border-0 bg-transparent pl-11 pr-4 text-white placeholder:text-slate-500 focus:ring-0 sm:text-sm font-mono tracking-wide" 
                    placeholder="Search command..." 
                    role="combobox" 
                    aria-expanded="false" 
                    aria-controls="options"
                >
                <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                    <kbd class="inline-flex items-center rounded border border-slate-600 px-2 font-sans text-xs font-medium text-slate-400">ESC</kbd>
                </div>
            </div>

            <!-- Results -->
            @if(count($results) > 0)
                <ul class="max-h-96 scroll-py-2 overflow-y-auto py-2 text-sm text-slate-200 custom-scrollbar" id="options" role="listbox">
                    @foreach($results as $index => $result)
                        <li class="group cursor-pointer select-none px-4 py-2" id="option-{{ $index }}" role="option" tabindex="-1">
                            <a href="{{ $result['url'] }}" 
                               x-ref="result{{ $index }}"
                               :class="{ 'bg-cyan-900/30 border-l-2 border-cyan-500': focusIndex === {{ $index }} }"
                               class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 transition-all border-l-2 border-transparent hover:border-cyan-500"
                            >
                                <div class="flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-slate-800 text-cyan-400 group-hover:text-white group-hover:bg-cyan-600 transition-colors">
                                    @if($result['type'] === 'menu') 
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                    @elseif($result['type'] === 'product')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    @elseif($result['type'] === 'service')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    @endif
                                </div>
                                <div class="flex-auto">
                                    <p class="font-semibold text-slate-100 group-hover:text-cyan-400">{{ $result['title'] }}</p>
                                    <p class="text-xs text-slate-500 group-hover:text-slate-300">{{ $result['subtitle'] }}</p>
                                </div>
                                <svg class="h-5 w-5 flex-none text-slate-500 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @elseif(strlen($search) >= 2)
                <div class="py-14 px-6 text-center text-sm sm:px-14">
                    <svg class="mx-auto h-12 w-12 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 font-semibold text-white">Tidak ditemukan</p>
                    <p class="mt-2 text-slate-400">Kami tidak dapat menemukan apa pun dengan kata kunci tersebut.</p>
                </div>
            @else
                <div class="py-10 px-6 text-center text-sm sm:px-14">
                    <div class="flex justify-center gap-4 text-slate-500 mb-6">
                        <div class="flex flex-col items-center">
                            <span class="p-2 bg-slate-800 rounded-lg mb-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg></span>
                            <span class="text-xs">Dashboard</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="p-2 bg-slate-800 rounded-lg mb-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></span>
                            <span class="text-xs">Produk</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="p-2 bg-slate-800 rounded-lg mb-2"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg></span>
                            <span class="text-xs">Transaksi</span>
                        </div>
                    </div>
                    <p class="text-slate-500">Ketik untuk mencari di seluruh sistem.</p>
                </div>
            @endif
        </div>
    </div>
</div>