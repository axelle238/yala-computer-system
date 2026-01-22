<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Control Center</h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">Konfigurasi global toko dan gateway komunikasi.</p>
        </div>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 space-y-8">
            
            <!-- Notification Success -->
            @if (session()->has('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6">
                @foreach($settings as $setting)
                    <div wire:key="{{ $setting->id }}">
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ $setting->label }}</label>
                        
                        @if($setting->type === 'textarea')
                            <textarea wire:model="form.{{ $setting->key }}" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800"></textarea>
                        @elseif($setting->type === 'number')
                            <input wire:model="form.{{ $setting->key }}" type="number" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800 font-mono">
                            @if($setting->key === 'whatsapp_number')
                                <p class="mt-1 text-xs text-slate-400">Gunakan kode negara tanpa tanda tambah (+). Contoh: 628123456789</p>
                            @endif
                        @else
                            <input wire:model="form.{{ $setting->key }}" type="text" class="block w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-slate-800 font-bold">
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-slate-900 hover:bg-blue-600 text-white rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 font-bold transition-all transform active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Simpan Konfigurasi
                </button>
            </div>
        </div>
    </form>
</div>
