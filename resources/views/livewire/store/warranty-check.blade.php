<div class="relative min-h-screen py-20 px-4 sm:px-6 lg:px-8 overflow-hidden flex items-center justify-center">
    
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-cyan-500/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] animate-pulse delay-700"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl">
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-black font-tech text-slate-900 tracking-tight mb-6 drop-shadow-sm uppercase">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-700">Status</span> 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">Garansi</span>
            </h1>
            <p class="text-slate-600 text-lg max-w-2xl mx-auto font-medium">
                Masukkan Serial Number (SN) perangkat untuk verifikasi keaslian dan masa berlaku garansi resmi Yala Computer.
            </p>
        </div>

        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-900/10 border border-white/50 p-8 md:p-12 tech-border relative overflow-hidden">
            <!-- Decorative Grid -->
            <div class="absolute inset-0 grid-pattern opacity-10 pointer-events-none"></div>

            <form wire:submit="check" class="relative max-w-2xl mx-auto mb-10 z-10">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-cyan-400 to-blue-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative flex">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-slate-400 group-focus-within:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M12 12l2 2 4-4" /></svg>
                        </div>
                        <input wire:model="serial_number" type="text" class="block w-full pl-16 pr-36 py-5 border-none rounded-2xl bg-white shadow-inner focus:ring-0 text-xl font-bold font-mono uppercase placeholder-slate-300 text-slate-800 tracking-wider" placeholder="SERIAL NUMBER..." autofocus>
                        <button type="submit" class="absolute right-2 top-2 bottom-2 px-8 bg-slate-900 hover:bg-cyan-600 text-white rounded-xl font-bold font-tech tracking-wider transition-all shadow-lg hover:shadow-cyan-500/30 flex items-center gap-2">
                            <span wire:loading.remove>CEK</span>
                            <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
            
            @error('serial_number') 
                <div class="flex items-center justify-center gap-2 text-rose-500 bg-rose-50 p-3 rounded-xl max-w-md mx-auto mb-6 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    <span class="font-bold">{{ $message }}</span>
                </div>
            @enderror

            @if($result)
                <div class="mt-8 pt-8 border-t border-slate-100 animate-slide-up relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-6">
                            <div class="absolute inset-0 {{ $result['status'] == 'valid' ? 'bg-emerald-400' : 'bg-rose-400' }} blur-xl opacity-30 animate-pulse"></div>
                            <div class="relative w-24 h-24 rounded-2xl flex items-center justify-center shadow-xl {{ $result['status'] == 'valid' ? 'bg-gradient-to-br from-emerald-400 to-emerald-600 text-white' : 'bg-gradient-to-br from-rose-500 to-rose-700 text-white' }}">
                                @if($result['status'] == 'valid')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="text-3xl font-black font-tech text-slate-900 mb-2">{{ $result['product'] }}</h3>
                        <p class="text-slate-500 font-mono text-lg tracking-widest bg-slate-100 px-4 py-1 rounded-lg mb-8">SN: {{ strtoupper($serial_number) }}</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                            <div class="group bg-white p-6 rounded-2xl border border-slate-100 hover:border-cyan-200 hover:shadow-lg transition-all">
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">Tanggal Pembelian</p>
                                <p class="text-xl font-bold text-slate-800">{{ $result['purchase_date'] }}</p>
                            </div>
                            <div class="group bg-white p-6 rounded-2xl border border-slate-100 hover:border-cyan-200 hover:shadow-lg transition-all">
                                <p class="text-xs text-slate-400 uppercase font-bold tracking-wider mb-2">Berakhir Pada</p>
                                <p class="text-xl font-bold text-slate-800">{{ $result['expiry_date'] }}</p>
                            </div>
                            <div class="group p-6 rounded-2xl border transition-all {{ $result['status'] == 'valid' ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                                <p class="text-xs {{ $result['status'] == 'valid' ? 'text-emerald-600' : 'text-rose-600' }} uppercase font-bold tracking-wider mb-2">Status Garansi</p>
                                <p class="text-xl font-black font-tech {{ $result['status'] == 'valid' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $result['status'] == 'valid' ? "AKTIF ({$result['days_left']} HARI)" : 'EXPIRED' }}
                                </p>
                            </div>
                        </div>
                        
                        @if($result['status'] == 'valid')
                             <div class="mt-8 flex gap-2">
                                <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}?text=Halo%20Admin,%20saya%20ingin%20klaim%20garansi%20untuk%20SN:%20{{ $serial_number }}" target="_blank" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-emerald-600 transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    Klaim Garansi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
