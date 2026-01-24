<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                Voucher <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-rose-500">Manager</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Buat kode promo dan diskon dinamis.</p>
        </div>
        
        <div class="flex gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm" placeholder="Cari kode...">
            <a href="{{ route('marketing.vouchers.create') }}" class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Voucher
            </a>
        </div>
    </div>

    <!-- Voucher Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vouchers as $voucher)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <!-- Status Stripe -->
                <div class="absolute top-0 left-0 w-1 h-full {{ $voucher->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-slate-100 dark:bg-slate-700 px-3 py-1 rounded-lg font-mono font-black text-slate-700 dark:text-slate-200 tracking-wider border border-dashed border-slate-300 dark:border-slate-600">
                            {{ $voucher->code }}
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="toggleStatus({{ $voucher->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors {{ $voucher->is_active ? 'text-emerald-500' : 'text-slate-400' }}">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                            <button wire:click="delete({{ $voucher->id }})" class="p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-900/20 text-slate-400 hover:text-rose-500 transition-colors" wire:confirm="Hapus voucher ini?">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>

                    <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-1">{{ $voucher->name }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $voucher->description }}</p>

                    <div class="flex items-center gap-4 mb-4 text-sm">
                        <div class="flex items-center gap-1.5 text-rose-600 font-bold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @if($voucher->type == 'fixed')
                                Rp {{ number_format($voucher->amount/1000) }}K OFF
                            @else
                                {{ $voucher->amount }}% OFF
                            @endif
                        </div>
                        <div class="text-slate-400 text-xs">
                            Min. {{ number_format($voucher->min_spend/1000) }}K
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center text-xs text-slate-500">
                        <span>Terpakai: <strong class="text-slate-700 dark:text-slate-300">{{ $voucher->usages_count }}</strong> / {{ $voucher->usage_limit ?? '∞' }}</span>
                        <span>Exp: {{ $voucher->end_date ? $voucher->end_date->format('d M Y') : 'Selamanya' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <p>Belum ada voucher aktif.</p>
            </div>
        @endforelse
    </div>
    
    {{ $vouchers->links() }}
</div>
