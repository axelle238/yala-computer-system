<div class="w-full">
    @if($isSubscribed)
        <button disabled class="w-full py-4 bg-emerald-500/10 text-emerald-500 font-bold rounded-xl border border-emerald-500/20 cursor-not-allowed flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Pengingat Stok Aktif
        </button>
    @else
        <div class="flex flex-col gap-3">
            @if(!auth()->check())
                <input wire:model="email" type="email" placeholder="Masukkan Email Anda" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 transition-all">
                @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
            @endif
            
            <button wire:click="subscribe" class="w-full py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-white/10 transition-all flex items-center justify-center gap-2 group">
                <svg class="w-5 h-5 text-slate-400 group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                Ingatkan Saat Ready
            </button>
        </div>
    @endif
</div>
