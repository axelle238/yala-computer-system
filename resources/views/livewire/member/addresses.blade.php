<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                    Buku <span class="text-blue-600">Alamat</span>
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola alamat pengiriman untuk checkout lebih cepat.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('member.dashboard') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-300 transition-colors">
                    &larr; Dashboard
                </a>
                <button wire:click="create" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all">
                    + Tambah Alamat
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Address List -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($addresses as $address)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm relative group overflow-hidden {{ $address->is_primary ? 'ring-2 ring-blue-500' : '' }}">
                        @if($address->is_primary)
                            <div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">Utama</div>
                        @endif
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                    {{ $address->label }}
                                </h3>
                                <p class="text-slate-500 text-sm">{{ $address->recipient_name }} | {{ $address->phone_number }}</p>
                            </div>
                        </div>
                        
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed mb-4">
                            {{ $address->address_line }}<br>
                            {{ $address->city }} {{ $address->postal_code }}
                        </p>

                        <div class="flex gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button wire:click="edit({{ $address->id }})" class="text-xs font-bold text-blue-600 hover:underline">Edit</button>
                            <button wire:click="delete({{ $address->id }})" wire:confirm="Hapus alamat ini?" class="text-xs font-bold text-rose-500 hover:underline">Hapus</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <p class="text-slate-500">Belum ada alamat tersimpan.</p>
                    </div>
                @endforelse
            </div>

            <!-- Form -->
            @if($showForm)
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-xl border border-slate-200 dark:border-slate-700 sticky top-24 animate-fade-in-up">
                        <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-6">
                            {{ $editId ? 'Edit Alamat' : 'Tambah Alamat Baru' }}
                        </h3>
                        
                        <form wire:submit.prevent="save" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Label Alamat</label>
                                <input wire:model="label" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500" placeholder="Rumah, Kantor, dll">
                                @error('label') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Penerima</label>
                                    <input wire:model="recipient_name" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500">
                                    @error('recipient_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">No. HP</label>
                                    <input wire:model="phone_number" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500">
                                    @error('phone_number') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Alamat Lengkap</label>
                                <textarea wire:model="address_line" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500"></textarea>
                                @error('address_line') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kota</label>
                                    <select wire:model="city" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500">
                                        <option value="">Pilih Kota</option>
                                        @foreach($cities as $c)
                                            <option value="{{ $c }}">{{ $c }}</option>
                                        @endforeach
                                    </select>
                                    @error('city') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kode Pos</label>
                                    <input wire:model="postal_code" type="text" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-sm focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="flex items-center gap-2 py-2">
                                <input wire:model="is_primary" type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-slate-600 dark:text-slate-300">Jadikan Alamat Utama</span>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" wire:click="$set('showForm', false)" class="flex-1 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-sm hover:bg-slate-200 transition-colors">Batal</button>
                                <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
