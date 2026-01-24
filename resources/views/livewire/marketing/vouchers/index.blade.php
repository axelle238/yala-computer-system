<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Voucher & Promo</h1>
            <p class="text-gray-500">Buat kode diskon untuk meningkatkan penjualan.</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 shadow-lg">
            + Buat Voucher Baru
        </button>
    </div>

    @if($showForm)
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-lg mb-6 max-w-4xl">
            <h3 class="font-bold text-lg mb-4">{{ $voucherId ? 'Edit Voucher' : 'Voucher Baru' }}</h3>
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kode Voucher</label>
                    <div class="flex gap-2">
                        <input wire:model="code" type="text" class="w-full rounded-lg border-gray-300 font-mono font-bold text-lg uppercase tracking-wider" placeholder="CONTOH: RAMADHAN2026">
                        <button type="button" wire:click="generateCode" class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200">Generate</button>
                    </div>
                    @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipe Diskon</label>
                    <select wire:model.live="type" class="w-full rounded-lg border-gray-300">
                        <option value="fixed">Nominal Tetap (Rp)</option>
                        <option value="percent">Persentase (%)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nilai Diskon</label>
                    <input wire:model="discount_value" type="number" class="w-full rounded-lg border-gray-300">
                </div>

                @if($type === 'percent')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Maksimal Diskon (Rp)</label>
                        <input wire:model="max_discount_amount" type="number" class="w-full rounded-lg border-gray-300" placeholder="Kosongkan jika unlimited">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Minimal Belanja</label>
                    <input wire:model="min_spend" type="number" class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Batas Penggunaan (Kuota)</label>
                    <input wire:model="usage_limit" type="number" class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mulai Berlaku</label>
                    <input wire:model="start_date" type="datetime-local" class="w-full rounded-lg border-gray-300">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Berakhir Pada</label>
                    <input wire:model="end_date" type="datetime-local" class="w-full rounded-lg border-gray-300">
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    <span class="text-sm font-bold text-gray-700">Aktifkan Voucher Ini</span>
                </div>

                <div class="md:col-span-2 flex justify-end gap-2 border-t pt-4">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 text-gray-600 font-bold hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700">Simpan Voucher</button>
                </div>
            </form>
        </div>
    @endif

    <!-- List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Kode Voucher</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4">Ketentuan</th>
                    <th class="px-6 py-4">Periode</th>
                    <th class="px-6 py-4 text-center">Terpakai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vouchers as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono font-bold text-lg text-gray-800">{{ $v->code }}</td>
                        <td class="px-6 py-4 font-bold text-pink-600">
                            {{ $v->type === 'fixed' ? 'Rp '.number_format($v->discount_value) : $v->discount_value.'%' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div>Min. Belanja: Rp {{ number_format($v->min_spend) }}</div>
                            @if($v->max_discount_amount)
                                <div>Max Diskon: Rp {{ number_format($v->max_discount_amount) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div>{{ $v->start_date->format('d/m/y') }}</div>
                            <div class="text-gray-400">s/d</div>
                            <div>{{ $v->end_date->format('d/m/y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $v->used_count }} / {{ $v->usage_limit }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleStatus({{ $v->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $v->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                <span class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $v->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="edit({{ $v->id }})" class="text-blue-600 hover:underline">Edit</button>
                            <span class="text-gray-300 mx-1">|</span>
                            <button wire:click="delete({{ $v->id }})" class="text-red-500 hover:underline">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada voucher.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $vouchers->links() }}</div>
    </div>
</div>