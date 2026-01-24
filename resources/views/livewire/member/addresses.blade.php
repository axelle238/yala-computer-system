<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        <div class="flex items-center justify-between mb-8 animate-fade-in-up">
            <div>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white mb-2 uppercase">Buku Alamat</h1>
                <p class="text-slate-500">Kelola alamat pengiriman Anda untuk checkout lebih cepat.</p>
            </div>
            <button wire:click="create" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Alamat
            </button>
        </div>

        @if($showForm)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 mb-8 border border-slate-200 dark:border-slate-700 shadow-lg animate-fade-in">
                <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-4">{{ $editId ? 'Edit Alamat' : 'Tambah Alamat Baru' }}</h3>
                <form wire:submit.prevent="save" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Label Alamat</label>
                            <input type="text" wire:model="label" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm" placeholder="Contoh: Rumah, Kantor">
                            @error('label') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Penerima</label>
                            <input type="text" wire:model="recipient_name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                            @error('recipient_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">No. Telepon</label>
                            <input type="text" wire:model="phone_number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                            @error('phone_number') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kota</label>
                            <select wire:model="city" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                            @error('city') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Alamat Lengkap</label>
                        <textarea wire:model="address_line" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm"></textarea>
                        @error('address_line') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_primary" id="is_primary" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        <label for="is_primary" class="text-sm text-slate-600 dark:text-slate-300">Jadikan Alamat Utama</label>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-slate-500 hover:text-slate-700 font-bold text-sm">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-lg shadow transition-all text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fade-in-up delay-100">
            @forelse($addresses as $addr)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border {{ $addr->is_primary ? 'border-cyan-500 ring-1 ring-cyan-500' : 'border-slate-200 dark:border-slate-700' }} shadow-sm hover:shadow-md transition-all relative group">
                    @if($addr->is_primary)
                        <span class="absolute top-4 right-4 px-2 py-1 bg-cyan-100 text-cyan-700 text-[10px] font-bold uppercase rounded">Utama</span>
                    @endif
                    
                    <div class="mb-4">
                        <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            {{ $addr->label }}
                        </h4>
                        <p class="text-sm text-slate-500 font-bold">{{ $addr->recipient_name }} | {{ $addr->phone_number }}</p>
                    </div>
                    
                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-6 leading-relaxed">
                        {{ $addr->address_line }}<br>
                        {{ $addr->city }}{{ $addr->postal_code ? ', ' . $addr->postal_code : '' }}
                    </p>

                    <div class="flex gap-2 border-t border-slate-100 dark:border-slate-700 pt-4">
                        <button wire:click="edit({{ $addr->id }})" class="text-xs font-bold text-blue-600 hover:underline">Edit</button>
                        <span class="text-slate-300">|</span>
                        <button wire:click="delete({{ $addr->id }})" wire:confirm="Hapus alamat ini?" class="text-xs font-bold text-rose-600 hover:underline">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-400 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                    <p>Belum ada alamat tersimpan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
