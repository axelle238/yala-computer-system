<div 
    x-data="{ 
        open: false, 
        query: '', 
        results: [],
        init() {
            this.$watch('query', value => {
                if (value.length < 2) { this.results = []; return; }
                // Simulate search (Replace with actual backend call via Livewire if needed)
                this.results = [
                    { title: 'Cari Produk: ' + value, type: 'action', url: '{{ route('products.index') }}?search=' + value },
                    { title: 'Transaksi Baru', type: 'link', url: '{{ route('transactions.create') }}' },
                    { title: 'Laporan Keuangan', type: 'link', url: '{{ route('finance.profit-loss') }}' }
                ];
            });
        }
    }"
    x-on:open-spotlight.window="open = true; $nextTick(() => $refs.searchInput.focus())"
    x-on:keydown.window.ctrl.k.prevent="open = true; $nextTick(() => $refs.searchInput.focus())"
    x-on:keydown.escape.window="open = false"
    class="relative z-[999]"
    style="display: none;"
    x-show="open"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" x-show="open" x-transition.opacity @click="open = false"></div>

    <!-- Modal -->
    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div class="mx-auto max-w-2xl transform divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl transition-all border border-slate-200 dark:border-slate-700"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                </svg>
                <input x-ref="searchInput" type="text" x-model="query" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-slate-900 dark:text-white placeholder:text-slate-400 focus:ring-0 sm:text-sm" placeholder="Cari menu, produk, atau perintah... (Ctrl + K)" role="combobox" aria-expanded="false" aria-controls="options">
            </div>

            <!-- Results -->
            <ul x-show="query.length > 0 || results.length > 0" class="max-h-80 scroll-py-2 overflow-y-auto py-2 text-sm text-slate-800 dark:text-slate-200" id="options" role="listbox">
                <template x-for="(result, index) in results" :key="index">
                    <li class="cursor-default select-none px-4 py-2 hover:bg-cyan-50 dark:hover:bg-slate-800" id="option-1" role="option" tabindex="-1">
                        <a :href="result.url" class="flex items-center gap-3">
                            <div class="flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <span class="flex-auto truncate" x-text="result.title"></span>
                            <span class="text-xs text-slate-400">Jump to</span>
                        </a>
                    </li>
                </template>
                
                <li x-show="results.length === 0 && query.length > 0" class="p-4 text-center text-sm text-slate-500">
                    Tidak ada hasil ditemukan.
                </li>
            </ul>

            <!-- Empty State / Quick Links -->
            <div x-show="query.length === 0" class="py-14 px-6 text-center text-sm sm:px-14">
                <svg class="mx-auto h-6 w-6 text-slate-400 opacity-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59" />
                </svg>
                <p class="mt-4 font-semibold text-slate-900 dark:text-white">Pencarian Cepat</p>
                <p class="mt-2 text-slate-500">Ketik untuk mencari produk, transaksi, atau menu navigasi.</p>
                
                <div class="mt-6 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('products.index') }}" class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-cyan-100 hover:text-cyan-700 transition-colors">Stok Barang</a>
                    <a href="{{ route('transactions.index') }}" class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-cyan-100 hover:text-cyan-700 transition-colors">Transaksi</a>
                    <a href="{{ route('customers.index') }}" class="rounded-full bg-slate-100 dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-cyan-100 hover:text-cyan-700 transition-colors">Pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</div>
