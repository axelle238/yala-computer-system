<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in-up">
    <div class="flex justify-between items-center mb-6">
        <div class="relative w-64">
            <input wire:model.live="cariProduk" type="text" class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800" placeholder="Cari Produk...">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <button wire:click="buat" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition">Buat Obral Kilat</button>
    </div>

    @if($tampilkanForm)
        <div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-fade-in-up">
                <h3 class="text-lg font-bold mb-4">Formulir Obral Kilat</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pilih Produk</label>
                        <input wire:model.live="cariProduk" type="text" class="w-full rounded-lg border-slate-300" placeholder="Ketik nama produk...">
                        @if(!empty($hasilPencarianProduk))
                            <div class="mt-1 bg-white border rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                @foreach($hasilPencarianProduk as $p)
                                    <div wire:click="pilihProduk({{ $p->id }}, '{{ $p->name }}')" class="p-2 hover:bg-slate-100 cursor-pointer text-sm">
                                        {{ $p->name }} - Rp {{ number_format($p->sell_price) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if($namaProdukTerpilih)
                            <div class="mt-2 text-sm font-bold text-emerald-600">Terpilih: {{ $namaProdukTerpilih }}</div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Harga Diskon</label>
                            <input wire:model="hargaDiskon" type="number" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Kuota</label>
                            <input wire:model="kuota" type="number" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Mulai</label>
                            <input wire:model="waktuMulai" type="datetime-local" class="w-full rounded-lg border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Selesai</label>
                            <input wire:model="waktuSelesai" type="datetime-local" class="w-full rounded-lg border-slate-300">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('tampilkanForm', false)" class="px-4 py-2 text-slate-500 hover:bg-slate-100 rounded-lg">Batal</button>
                    <button wire:click="simpan" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($obral as $item)
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition">
                    <button wire:click="hapus({{ $item->id }})" class="p-1 bg-white text-rose-500 rounded-lg shadow-sm hover:bg-rose-50"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                </div>
                
                <div class="flex gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center">
                        <span class="font-black text-indigo-500">{{ substr($item->product->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm line-clamp-1">{{ $item->product->name }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-400 line-through">Rp {{ number_format($item->product->sell_price/1000) }}k</span>
                            <span class="text-sm font-black text-rose-500">Rp {{ number_format($item->discount_price/1000) }}k</span>
                        </div>
                        <div class="text-[10px] text-slate-500 mt-2">
                            Berakhir: {{ $item->end_time->format('d M H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
