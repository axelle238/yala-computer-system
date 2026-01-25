<div>
    <h2 class="text-2xl font-bold text-white text-center mb-6">Daftar Akun Baru</h2>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Nama Lengkap</label>
            <input wire:model="name" type="text" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-slate-600" placeholder="John Doe">
            @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

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

        <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Konfirmasi Password</label>
            <input wire:model="password_confirmation" type="password" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-slate-600" placeholder="••••••••">
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all transform hover:-translate-y-0.5 mt-4">
            Daftar Sekarang
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun? <a href="{{ route('customer.login') }}" class="text-cyan-500 hover:text-cyan-400 font-bold">Login disini</a>
    </div>
</div>
