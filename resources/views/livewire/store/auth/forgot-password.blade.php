<div>
    <h2 class="text-2xl font-bold text-white text-center mb-6">Reset Kata Sandi</h2>
    
    @if ($status)
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg dark:text-green-400 dark:bg-green-900/30">
            {{ $status }}
        </div>
    @endif

    <p class="text-slate-400 text-sm mb-6 text-center">Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi.</p>

    <form wire:submit="sendResetLink" class="space-y-4">
        <div>
            <label class="block text-sm font-bold text-slate-400 mb-1">Alamat Email</label>
            <input wire:model="email" type="email" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-slate-600" placeholder="name@example.com">
            @error('email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-cyan-500/20 transition-all transform hover:-translate-y-0.5">
            Kirim Link Reset
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        <a href="{{ route('customer.login') }}" class="text-cyan-500 hover:text-cyan-400 font-bold">Kembali ke Login</a>
    </div>
</div>
