<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $ticket ? 'Edit Tiket Servis' : 'Input Tiket Baru' }}
            </h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Nomor Tiket: <span class="font-mono bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-bold">{{ $ticket_number }}</span></p>
        </div>
        <a href="{{ route('services.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">
            Kembali
        </a>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Customer Info -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Data Pelanggan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pelanggan</label>
                        <input wire:model="customer_name" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('customer_name') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No. WhatsApp</label>
                        <input wire:model="customer_phone" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('customer_phone') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Device Info -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Detail Perangkat</h3>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tipe/Model Perangkat</label>
                    <input wire:model="device_name" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Contoh: Asus ROG Strix G15 (Serial: XXXXX)">
                    @error('device_name') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Keluhan / Kerusakan</label>
                    <textarea wire:model="problem_description" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                    @error('problem_description') <span class="text-xs text-rose-500 font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Status & Cost -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2">Status Perbaikan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Saat Ini</label>
                        <select wire:model="status" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="pending">Menunggu Antrian</option>
                            <option value="diagnosing">Sedang Dicek (Diagnosa)</option>
                            <option value="waiting_part">Menunggu Sparepart</option>
                            <option value="repairing">Sedang Diperbaiki</option>
                            <option value="ready">Selesai (Siap Diambil)</option>
                            <option value="picked_up">Sudah Diambil</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Estimasi Biaya</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">Rp</span>
                            <input wire:model="estimated_cost" type="number" class="block w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Teknisi (Internal)</label>
                        <textarea wire:model="technician_notes" rows="2" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-600/30 font-bold transition-all transform active:scale-95">
                    Simpan Tiket
                </button>
            </div>
        </div>
    </form>
</div>
