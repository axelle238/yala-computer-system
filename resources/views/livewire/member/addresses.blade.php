<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10 animate-fade-in-up">
            <div class="flex items-center gap-4">
                <a href="{{ route('member.dashboard') }}" class="p-2 rounded-full bg-white dark:bg-slate-800 text-slate-500 hover:text-indigo-500 shadow-sm transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">Buku Alamat</h1>
                    <p class="text-slate-500 text-sm">Kelola alamat pengiriman Anda agar checkout lebih cepat.</p>
                </div>
            </div>
            @if(!$tampilkanForm)
                <button wire:click="buat" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Alamat Baru
                </button>
            @endif
        </div>

        <!-- Form Inline (Pengganti Modal) -->
        @if($tampilkanForm)
            <div class="mb-10 animate-fade-in-up">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $idAlamat ? 'Ubah Alamat' : 'Tambah Alamat Baru' }}</h3>
                        <button wire:click="$set('tampilkanForm', false)" class="text-slate-400 hover:text-rose-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Label Alamat</label>
                                <input wire:model="label" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500" placeholder="Contoh: Rumah, Kantor">
                                @error('label') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Penerima</label>
                                    <input wire:model="namaPenerima" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                                    @error('namaPenerima') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">No. HP / WA</label>
                                    <input wire:model="nomorTelepon" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                                    @error('nomorTelepon') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kota</label>
                                <select wire:model="kota" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($daftarKota as $k)
                                        <option value="{{ $k }}">{{ $k }}</option>
                                    @endforeach
                                </select>
                                @error('kota') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat Lengkap</label>
                                <textarea wire:model="barisAlamat" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:ring-indigo-500" placeholder="Jalan, No. Rumah, RT/RW..."></textarea>
                                @error('barisAlamat') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-2 pt-2">
                                <input wire:model="isUtama" type="checkbox" id="isUtama" class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-slate-300">
                                <label for="isUtama" class="text-sm font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Jadikan Alamat Utama</label>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                        <button wire:click="$set('tampilkanForm', false)" class="px-4 py-2 text-slate-500 font-bold hover:bg-slate-200 rounded-lg transition">Batal</button>
                        <button wire:click="simpan" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition">Simpan Alamat</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            @forelse($daftarAlamat as $alamat)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border {{ $alamat->is_primary ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-slate-200 dark:border-slate-700' }} shadow-sm hover:shadow-md transition-all group relative">
                    
                    @if($alamat->is_primary)
                        <span class="absolute top-4 right-4 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Utama</span>
                    @endif

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500">
                            @if(stripos($alamat->label, 'kantor') !== false)
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">{{ $alamat->label }}</h3>
                    </div>

                    <div class="space-y-2 mb-6">
                        <p class="font-bold text-slate-700 dark:text-slate-300">{{ $alamat->recipient_name }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $alamat->phone_number }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $alamat->address_line }}, {{ $alamat->city }}</p>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button wire:click="ubah({{ $alamat->id }})" class="flex-1 py-2 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-lg transition-colors">Ubah</button>
                        <button wire:click="hapus({{ $alamat->id }})" wire:confirm="Hapus alamat ini?" class="flex-1 py-2 text-sm font-bold text-rose-500 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-lg transition-colors">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-slate-400 border border-dashed border-slate-200 dark:border-slate-700 rounded-3xl">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p>Belum ada alamat tersimpan.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
