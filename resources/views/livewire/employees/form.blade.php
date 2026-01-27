<div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                {{ $karyawan ? 'Edit' : 'Tambah' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">Karyawan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium">Manajemen data pegawai dan hak akses sistem.</p>
        </div>
        <a href="{{ route('admin.karyawan.indeks') }}" class="group flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-600 dark:text-slate-300 font-bold hover:border-orange-500 hover:text-orange-500 transition-all shadow-sm">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <form wire:submit="simpan" class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl shadow-orange-900/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-orange-500/10 to-amber-500/10 rounded-bl-full pointer-events-none"></div>
        
        <div class="relative z-10 space-y-8">
            <!-- Profil Dasar -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input wire:model="nama" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all font-bold text-slate-800 dark:text-white placeholder-slate-400" placeholder="Nama Pegawai">
                        @error('nama') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Surel (Email Login)</label>
                        <input wire:model="surel" type="email" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="email@yalacomputer.com">
                        @error('surel') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                        <input wire:model="kataSandi" type="password" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="{{ $karyawan ? 'Isi jika ingin mengubah' : 'Password Login' }}">
                        @error('kataSandi') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Data Personal -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <span class="w-8 h-8 rounded-lg bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-pink-600 dark:text-pink-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884.39 1.676 1 2.222V11a3 3 0 003 3a3 3 0 003-3V7.222c.61-.546 1-1.338 1-2.222" /></svg>
                    </span>
                    Data Personal & Kontak
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK (KTP)</label>
                        <input wire:model="nik" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="16 Digit NIK">
                        @error('nik') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NPWP</label>
                        <input wire:model="npwp" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="Nomor NPWP">
                        @error('npwp') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
                        <input wire:model="tempatLahir" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="Kota Kelahiran">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                        <input wire:model="tanggalLahir" type="date" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon / WA</label>
                        <input wire:model="nomorTelepon" type="text" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="08xxxxxxxxxx">
                        @error('nomorTelepon') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                         <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Domisili</label>
                         <textarea wire:model="alamatLengkap" rows="2" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all text-slate-700 dark:text-slate-300" placeholder="Alamat lengkap sesuai KTP/Domisili"></textarea>
                    </div>
                </div>
            </div>

            <!-- Peran & Hak Akses -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 19l-1 1-1 1-2-2m-4.743 4.743a6 6 0 01-7.743-5.743m7.743 4.743L7 7m0 0a2 2 0 012-2" /></svg>
                    </span>
                    Jabatan & Akses
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipe Akun</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('tipePeran', 'khusus')" class="px-4 py-3 rounded-xl border font-bold text-sm transition-all {{ $tipePeran == 'khusus' ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400' }}">
                                Pegawai / Khusus
                            </button>
                            <button type="button" wire:click="$set('tipePeran', 'admin')" class="px-4 py-3 rounded-xl border font-bold text-sm transition-all {{ $tipePeran == 'admin' ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400' }}">
                                Super Admin
                            </button>
                        </div>
                    </div>

                    @if($tipePeran == 'khusus')
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Peran / Jabatan</label>
                            <div class="relative">
                                <select wire:model="idPeranTerpilih" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none cursor-pointer font-medium text-slate-700 dark:text-slate-300">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($daftarPeran as $peran)
                                        <option value="{{ $peran->id }}">{{ $peran->nama }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            @error('idPeranTerpilih') <span class="text-xs text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            <div class="mt-2 text-right">
                                <a href="{{ route('admin.karyawan.peran.buat') }}" class="text-xs font-bold text-indigo-500 hover:underline">+ Buat Jabatan Baru</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Kepegawaian -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-slate-800 dark:text-white flex items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Data Kepegawaian & Gaji
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Bergabung</label>
                        <input wire:model="tanggalBergabung" type="date" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium text-slate-700 dark:text-slate-300">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gaji Pokok (Bulanan)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">Rp</span>
                            <input wire:model="gajiPokok" type="number" class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono font-bold text-slate-800 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Uang Makan (Harian)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold">Rp</span>
                            <input wire:model="uangHarian" type="number" class="block w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono font-bold text-slate-800 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Komisi (%)</label>
                        <div class="relative">
                            <input wire:model="persentaseKomisi" type="number" step="0.1" class="block w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-mono font-bold text-slate-800 dark:text-white">
                            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 font-bold">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-orange-600 to-amber-500 hover:from-orange-700 hover:to-amber-600 text-white rounded-2xl font-black text-lg shadow-xl shadow-orange-500/30 transition-all hover:-translate-y-1 hover:shadow-orange-500/50 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ $karyawan ? 'SIMPAN PERUBAHAN' : 'TAMBAH PEGAWAI' }}
                </button>
            </div>
        </div>
    </form>
</div>