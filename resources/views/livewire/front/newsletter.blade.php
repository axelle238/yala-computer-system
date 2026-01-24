<div class="bg-indigo-900 rounded-3xl p-8 md:p-12 text-center relative overflow-hidden border border-white/10 shadow-2xl">
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/20 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-pink-500/20 rounded-full blur-[80px] pointer-events-none"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto">
        <h2 class="text-3xl font-black font-tech text-white uppercase tracking-tight mb-4">Jangan Ketinggalan Info!</h2>
        <p class="text-indigo-200 mb-8">Dapatkan update produk terbaru, promo flash sale, dan artikel teknologi langsung di email Anda.</p>
        
        <form wire:submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3">
            <input wire:model="email" type="email" placeholder="Masukkan alamat email Anda" class="flex-1 px-6 py-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-indigo-300 focus:ring-2 focus:ring-cyan-400 focus:bg-white/20 transition-all font-medium backdrop-blur-sm">
            <button type="submit" class="px-8 py-4 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-black uppercase tracking-widest rounded-xl transition-all shadow-lg hover:shadow-cyan-500/50 hover:-translate-y-1">
                Langganan
            </button>
        </form>
        @error('email') <p class="text-rose-400 text-sm mt-2 font-bold">{{ $message }}</p> @enderror
        
        <p class="text-xs text-indigo-400 mt-6">Kami menghargai privasi Anda. Unsubscribe kapan saja.</p>
    </div>
</div>