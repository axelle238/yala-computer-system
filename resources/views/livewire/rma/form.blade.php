<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-black text-slate-800 dark:text-white font-tech uppercase tracking-tight">
            {{ $rma ? 'Edit RMA' : 'Buat RMA Baru' }}
        </h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 space-y-6">
        <!-- Order Lookup -->
        <div class="flex gap-4">
            <input wire:model="searchOrder" type="text" placeholder="Cari No Order (TRX-...) untuk Auto-Fill" class="flex-1 bg-slate-50 border-none rounded-xl px-4 py-2">
            <button wire:click="loadOrder" class="bg-slate-900 text-white px-4 py-2 rounded-xl font-bold">Cari</button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">No RMA</label>
                <input wire:model="rma_number" type="text" class="w-full bg-slate-100 border-none rounded-lg px-4 py-2 font-mono font-bold" readonly>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Pelanggan</label>
                <input wire:model="guest_name" type="text" class="w-full bg-slate-50 border-none rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Status</label>
                <select wire:model="status" class="w-full bg-slate-50 border-none rounded-lg px-4 py-2">
                    <option value="pending">Menunggu Konfirmasi</option>
                    <option value="approved">Disetujui</option>
                    <option value="received_goods">Barang Diterima</option>
                    <option value="checking">Pengecekan</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>
        </div>

        <!-- Items -->
        <div>
            <h3 class="font-bold text-lg mb-2">Barang Retur</h3>
            <div class="space-y-3">
                @foreach($items as $index => $item)
                <div class="flex gap-2 items-start">
                    <select wire:model="items.{{ $index }}.product_id" class="flex-1 bg-slate-50 border-none rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih Produk...</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <input wire:model="items.{{ $index }}.serial" type="text" placeholder="Serial Number" class="w-1/4 bg-slate-50 border-none rounded-lg px-3 py-2 text-sm">
                    <input wire:model="items.{{ $index }}.problem" type="text" placeholder="Keluhan" class="w-1/3 bg-slate-50 border-none rounded-lg px-3 py-2 text-sm">
                    <button wire:click="removeItem({{ $index }})" class="text-rose-500 font-bold px-2">X</button>
                </div>
                @endforeach
                <button wire:click="addItem" class="text-sm text-cyan-600 font-bold mt-2">+ Tambah Barang</button>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Admin</label>
            <textarea wire:model="admin_notes" class="w-full bg-slate-50 border-none rounded-lg px-4 py-2"></textarea>
        </div>

        <div class="flex justify-end">
            <button wire:click="save" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-emerald-500/30">Simpan RMA</button>
        </div>
    </div>
</div>
