<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl">
        
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Pengaturan <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">Profil</span>
                </h1>
                <p class="text-slate-500 text-sm mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
            <a href="{{ route('anggota.beranda') }}" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Foto Profil & Info Ringkas -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm text-center">
                    <div class="relative inline-block mb-6 group">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white dark:border-slate-700 shadow-xl bg-slate-100 dark:bg-slate-900">
                            @if($fotoProfil)
                                <img src="{{ $fotoProfil->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif($pathFotoSaatIni)
                                <img src="{{ asset('storage/' . $pathFotoSaatIni) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-black text-slate-300">
                                    {{ substr($nama, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <label class="absolute bottom-0 right-0 p-2 bg-indigo-600 text-white rounded-full cursor-pointer shadow-lg hover:bg-indigo-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="file" wire:model="fotoProfil" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <h3 class="font-bold text-xl text-slate-900 dark:text-white">{{ $nama }}</h3>
                    <p class="text-sm text-slate-500 mb-6">{{ $surel }}</p>
                    
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-400">Poin Loyalitas</span>
                            <span class="font-bold text-amber-500">{{ number_format(auth()->user()->points) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Level Member</span>
                            <span class="font-bold text-indigo-500 uppercase">{{ auth()->user()->loyalty_tier ?? 'Bronze' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulir Detail Profil -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Data Pribadi -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6 flex items-center gap-2 uppercase tracking-wider text-sm">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Data Pribadi
                    </h3>
                    
                    <form wire:submit.prevent="perbaruiProfil" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nama Lengkap</label>
                                <input wire:model="nama" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-indigo-500 dark:text-white py-3 px-4 transition-all">
                                @error('nama') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nomor Telepon / WA</label>
                                <input wire:model="telepon" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-indigo-500 dark:text-white py-3 px-4 transition-all">
                                @error('telepon') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nomor KTP (16 Digit)</label>
                                <input wire:model="nomorKtp" type="text" maxlength="16" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-indigo-500 dark:text-white py-3 px-4 transition-all" placeholder="317xxxxxxxxxxxxx">
                                @error('nomorKtp') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Alamat Email (Tetap)</label>
                                <input wire:model="surel" type="email" readonly class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-slate-400 text-sm cursor-not-allowed py-3 px-4 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Alamat Lengkap</label>
                            <textarea wire:model="alamatLengkap" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-indigo-500 dark:text-white py-3 px-4 transition-all" placeholder="Nama jalan, gedung, blok, dll..."></textarea>
                            @error('alamatLengkap') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-indigo-600/20 transition-all transform active:scale-95" wire:loading.attr="disabled">
                                <span wire:loading.remove>Simpan Perubahan</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Keamanan -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6 flex items-center gap-2 uppercase tracking-wider text-sm">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Keamanan Akun
                    </h3>
                    
                    <form wire:submit.prevent="perbaruiKataSandi" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Kata Sandi Saat Ini</label>
                            <input wire:model="kataSandiSaatIni" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                            @error('kataSandiSaatIni') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Kata Sandi Baru</label>
                                <input wire:model="kataSandiBaru" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                                @error('kataSandiBaru') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Konfirmasi Kata Sandi Baru</label>
                                <input wire:model="konfirmasiKataSandi" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-rose-500 dark:text-white py-3 px-4 transition-all">
                                @error('konfirmasiKataSandi') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold rounded-xl text-sm transition-all transform active:scale-95">
                                Ganti Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
