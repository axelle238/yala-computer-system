<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fade-in-up">
            <div>
                <a href="{{ route('member.dashboard') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 mb-2 inline-block">&larr; Kembali ke Dashboard</a>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Buku <span class="text-blue-600">Alamat</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400">Kelola alamat pengiriman Anda untuk checkout lebih cepat.</p>
            </div>
            <button wire:click="create" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Alamat
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-100">
            @foreach($addresses as $address)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border {{ $address->is_primary ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/10' : 'border-slate-200 dark:border-slate-700' }} shadow-sm relative group hover:shadow-md transition-all">
                    
                    @if($address->is_primary)
                        <span class="absolute top-4 right-4 px-2 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider rounded-md">Utama</span>
                    @endif

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white">{{ $address->label }}</h3>
                            <p class="text-xs text-slate-500">{{ $address->recipient_name }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-1 leading-relaxed">{{ $address->address_line }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">{{ $address->city }}, {{ $address->postal_code }}</p>
                    <p class="text-xs font-mono text-slate-400 mb-6 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        {{ $address->phone_number }}
                    </p>

                    <div class="flex gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button wire:click="edit({{ $address->id }})" class="flex-1 py-2 bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">Edit</button>
                        <button wire:click="delete({{ $address->id }})" class="flex-1 py-2 bg-rose-50 dark:bg-rose-900/20 text-rose-600 text-xs font-bold rounded-lg hover:bg-rose-100 transition-colors" onclick="confirm('Hapus alamat ini?') || event.stopImmediatePropagation()">Hapus</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Form -->
        @if($showForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 flex justify-between items-center">
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg">{{ $editId ? 'Edit Alamat' : 'Tambah Alamat Baru' }}</h3>
                        <button wire:click="$set('showForm', false)" class="text-slate-400 hover:text-rose-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Label Alamat (Contoh: Rumah, Kantor)</label>
                            <input type="text" wire:model="label" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                            @error('label') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Penerima</label>
                                <input type="text" wire:model="recipient_name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                                @error('recipient_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nomor HP</label>
                                <input type="text" wire:model="phone_number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                                @error('phone_number') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Alamat Lengkap</label>
                            <textarea wire:model="address_line" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500"></textarea>
                            @error('address_line') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kota</label>
                                <select wire:model="city" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                                    <option value="">Pilih Kota</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c }}">{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('city') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Pos</label>
                                <input type="text" wire:model="postal_code" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" wire:model="is_primary" id="primary" class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <label for="primary" class="text-sm font-bold text-slate-700 dark:text-slate-300 select-none">Jadikan Alamat Utama</label>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                        <button wire:click="$set('showForm', false)" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-bold text-sm">Batal</button>
                        <button wire:click="save" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all">Simpan Alamat</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>