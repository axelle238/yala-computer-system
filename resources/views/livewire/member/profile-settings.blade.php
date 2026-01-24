<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Pengaturan <span class="text-purple-600">Profil</span>
            </h1>
            <a href="{{ route('member.dashboard') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Profile Info -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Informasi Dasar</h3>
                
                <form wire:submit.prevent="updateProfile" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-purple-500">
                        @error('name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Email (Read Only)</label>
                        <input wire:model="email" type="email" readonly class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-800 text-slate-500 text-sm cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">No. Handphone</label>
                        <input wire:model="phone" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-purple-500">
                        @error('phone') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-purple-500/20 transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Password Security -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Keamanan & Password</h3>
                
                <form wire:submit.prevent="updatePassword" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Password Saat Ini</label>
                        <input wire:model="current_password" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-purple-500">
                        @error('current_password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Password Baru</label>
                        <input wire:model="password" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-purple-500">
                        @error('password') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Konfirmasi Password Baru</label>
                        <input wire:model="password_confirmation" type="password" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-purple-500">
                    </div>

                    <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold rounded-xl text-sm transition-all">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>