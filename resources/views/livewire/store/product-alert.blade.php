<div>
    <button wire:click="$set('showModal', true)" class="w-full py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
        <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        Ingatkan Saya
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-md w-full shadow-2xl relative">
                <button wire:click="$set('showModal', false)" class="absolute top-4 right-4 text-slate-400 hover:text-white"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Stok Habis? Jangan Khawatir!</h3>
                <p class="text-slate-500 mb-6">Masukkan email Anda, kami akan segera memberitahu saat produk ini tersedia kembali.</p>
                
                <form wire:submit.prevent="notifyMe" class="space-y-4">
                    <input wire:model="email" type="email" placeholder="Email Anda..." class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-cyan-500 focus:border-cyan-500 text-white">
                    @error('email') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
                    
                    <button type="submit" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-lg transition-all">
                        Beritahu Saya
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
