<div>
    <h2 class="text-2xl font-bold text-white text-center mb-6">Login Pelanggan</h2>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Email Address</label>
            <input wire:model="email" type="email" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-slate-600" placeholder="name@example.com">
            @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Password</label>
            <input wire:model="password" type="password" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-slate-600" placeholder="••••••••">
            @error('password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-cyan-500/20">
                <span class="text-xs text-slate-400">Ingat Saya</span>
            </label>
            <a href="{{ route('pelanggan.lupa-sandi') }}" class="text-xs text-cyan-500 hover:text-cyan-400">Lupa Password?</a>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all transform hover:-translate-y-0.5">
            Masuk Sekarang
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Belum punya akun? <a href="{{ route('pelanggan.daftar') }}" class="text-cyan-500 hover:text-cyan-400 font-bold">Daftar disini</a>
    </div>
</div>
