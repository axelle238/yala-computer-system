<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 min-h-screen">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Cek Status Garansi</h1>
        <p class="text-slate-500 max-w-2xl mx-auto">Masukkan Serial Number (SN) perangkat Anda untuk mengetahui sisa masa garansi resmi toko.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        <form wire:submit="check" class="relative max-w-lg mx-auto mb-8">
            <input wire:model="serial_number" type="text" class="block w-full pl-6 pr-32 py-4 border-2 border-slate-200 rounded-full bg-slate-50 focus:bg-white focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-mono text-center text-lg uppercase placeholder-slate-400" placeholder="Contoh: SN12345678">
            <button type="submit" class="absolute right-2 top-2 bottom-2 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-bold transition-all">
                Cek
            </button>
        </form>
        @error('serial_number') <p class="text-center text-rose-500 font-bold mb-4">{{ $message }}</p> @enderror

        @if($result)
            <div class="mt-8 border-t border-slate-100 pt-8 animate-fade-in-up">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4 {{ $result['status'] == 'valid' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        @if($result['status'] == 'valid')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $result['product'] }}</h3>
                    <p class="text-slate-500 mb-6">SN: {{ $serial_number }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-2xl">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Tanggal Beli</p>
                            <p class="font-bold text-slate-800">{{ $result['purchase_date'] }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Berakhir Pada</p>
                            <p class="font-bold text-slate-800">{{ $result['expiry_date'] }}</p>
                        </div>
                        <div class="p-4 rounded-xl border {{ $result['status'] == 'valid' ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200' }}">
                            <p class="text-xs {{ $result['status'] == 'valid' ? 'text-emerald-600' : 'text-rose-600' }} uppercase font-bold tracking-wider mb-1">Status</p>
                            <p class="font-bold {{ $result['status'] == 'valid' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $result['status'] == 'valid' ? "Aktif ({$result['days_left']} hari)" : 'Habis (Expired)' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
