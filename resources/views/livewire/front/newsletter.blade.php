<div class="bg-gradient-to-r from-blue-900 to-cyan-900 rounded-3xl p-8 md:p-12 relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl -ml-16 -mb-16"></div>

    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="text-center md:text-left">
            <h3 class="text-2xl md:text-3xl font-black font-tech text-white uppercase tracking-tight mb-2">
                Join Our <span class="text-cyan-400">Newsletter</span>
            </h3>
            <p class="text-cyan-100 max-w-md">Dapatkan info promo eksklusif, rilis produk terbaru, dan tips rakit PC langsung di inbox Anda.</p>
        </div>

        <form wire:submit.prevent="subscribe" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <input wire:model="email" type="email" placeholder="Masukkan email Anda..." class="w-full sm:w-80 px-6 py-4 rounded-xl bg-white/10 border border-white/20 text-white placeholder-cyan-200/50 focus:ring-2 focus:ring-cyan-400 focus:border-transparent outline-none transition-all">
            <button type="submit" class="px-8 py-4 bg-white text-blue-900 font-bold rounded-xl hover:bg-cyan-50 transition-colors shadow-lg flex items-center justify-center gap-2 group">
                <span wire:loading.remove>Subscribe</span>
                <span wire:loading>...</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>
    </div>
</div>
