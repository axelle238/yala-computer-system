<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-600 to-slate-400">Pengaturan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Konfigurasi global sistem, integrasi, dan profil toko.</p>
        </div>
        <button wire:click="simpan" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
            Simpan Perubahan
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Tab -->
        <div class="lg:col-span-1 space-y-2">
            @foreach([
                'umum' => ['label' => 'Identitas Toko', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                'keuangan' => ['label' => 'Pembayaran & Pajak', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                'sistem' => ['label' => 'Integrasi Sistem', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ] as $key => $meta)
                <button wire:click="gantiTab('{{ $key }}')" 
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition-all {{ $tabAktif === $key ? 'bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 shadow-sm ring-1 ring-slate-200 dark:ring-slate-700' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}" /></svg>
                    {{ $meta['label'] }}
                </button>
            @endforeach
        </div>

        <!-- Konten Form -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 min-h-[500px]">
                
                <!-- TAB UMUM -->
                <div x-show="$wire.tabAktif === 'umum'" class="space-y-8 animate-fade-in">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">Identitas & Profil Toko</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Toko</label>
                            <input wire:model="formulir.store_name" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon / WA</label>
                            <input wire:model="formulir.store_phone" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea wire:model="formulir.store_address" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Toko</label>
                            <input wire:model="formulir.store_email" type="email" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Branding (Logo & Ikon)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center gap-4 p-4 border border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                        @if($logoBaru) <img src="{{ $logoBaru->temporaryUrl() }}" class="w-full h-full object-contain">
                                        @elseif(\App\Models\Setting::get('store_logo')) <img src="{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}" class="w-full h-full object-contain">
                                        @else <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> @endif
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-bold text-slate-700">Unggah Logo Utama</span>
                                        <span class="text-xs text-slate-400">PNG/JPG Max 2MB</span>
                                        <input type="file" wire:model="logoBaru" class="hidden">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center gap-4 p-4 border border-dashed border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                        @if($faviconBaru) <img src="{{ $faviconBaru->temporaryUrl() }}" class="w-full h-full object-contain">
                                        @elseif(\App\Models\Setting::get('store_favicon')) <img src="{{ asset('storage/' . \App\Models\Setting::get('store_favicon')) }}" class="w-full h-full object-contain">
                                        @else <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @endif
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-bold text-slate-700">Unggah Favicon</span>
                                        <span class="text-xs text-slate-400">Icon Browser (32x32)</span>
                                        <input type="file" wire:model="faviconBaru" class="hidden">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="formulir.store_announcement_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Aktifkan Pengumuman Bar Atas (Storefront)</span>
                        </label>
                        <div class="mt-3" x-show="$wire.formulir.store_announcement_active">
                            <input wire:model="formulir.store_announcement_text" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500" placeholder="Teks pengumuman...">
                        </div>
                    </div>
                </div>

                <!-- TAB KEUANGAN -->
                <div x-show="$wire.tabAktif === 'keuangan'" class="space-y-8 animate-fade-in" style="display: none;">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">Integrasi Pembayaran (Midtrans)</h3>
                    
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800 mb-6 flex items-start gap-3">
                        <svg class="w-6 h-6 text-indigo-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm text-indigo-800 dark:text-indigo-300">
                            Konfigurasi ini menghubungkan sistem dengan Midtrans Payment Gateway. Pastikan Server Key dan Client Key sesuai dengan akun Midtrans Anda.
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-bold text-slate-500">Mode Lingkungan:</span>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-100 dark:bg-slate-900 p-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                <span class="px-3 py-1 rounded-md text-xs font-bold transition-all {{ !$formulir['midtrans_is_production'] ? 'bg-white shadow text-slate-800' : 'text-slate-400' }}">Sandbox</span>
                                <input type="checkbox" wire:model.live="formulir.midtrans_is_production" class="hidden">
                                <span class="px-3 py-1 rounded-md text-xs font-bold transition-all {{ $formulir['midtrans_is_production'] ? 'bg-indigo-600 text-white shadow' : 'text-slate-400' }}">Production</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Merchant ID</label>
                            <input wire:model="formulir.midtrans_merchant_id" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Server Key</label>
                            <input wire:model="formulir.midtrans_server_key" type="password" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Client Key</label>
                            <input wire:model="formulir.midtrans_client_key" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono">
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <h4 class="font-bold text-slate-800 dark:text-white mb-4">Pengaturan Pajak & Biaya</h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pajak PPN (%)</label>
                                <input wire:model="formulir.tax_rate" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Biaya Layanan (Flat)</label>
                                <input wire:model="formulir.service_charge" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB SISTEM -->
                <div x-show="$wire.tabAktif === 'sistem'" class="space-y-8 animate-fade-in" style="display: none;">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4 mb-6">Konfigurasi Teknis</h3>
                    
                    <div class="space-y-6">
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 text-sm uppercase">Pengaturan Email (SMTP)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">SMTP Host</label>
                                <input wire:model="formulir.smtp_host" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">SMTP Port</label>
                                <input wire:model="formulir.smtp_port" type="number" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                                <input wire:model="formulir.smtp_username" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                                <input wire:model="formulir.smtp_password" type="password" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6 space-y-6">
                        <h4 class="font-bold text-indigo-600 dark:text-indigo-400 text-sm uppercase">Perangkat Keras & Integrasi</h4>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">IP Address Printer Termal</label>
                            <input wire:model="formulir.printer_ip_address" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500" placeholder="192.168.1.200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">URL Gateway WhatsApp</label>
                            <input wire:model="formulir.whatsapp_gateway_url" type="url" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500" placeholder="https://api.whatsapp.com/send">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>