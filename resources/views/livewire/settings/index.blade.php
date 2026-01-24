<div class="max-w-7xl mx-auto space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
            System <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Settings</span>
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Konfigurasi global aplikasi Yala Computer.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Navigation (Optional in future, now just content) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Branding Section (New) -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    Branding & Identitas Toko
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Logo Toko (Header)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-900 rounded-lg border border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden relative group">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="object-contain w-full h-full">
                                @elseif($current_logo)
                                    <img src="{{ asset('storage/' . $current_logo) }}" class="object-contain w-full h-full">
                                @else
                                    <span class="text-xs text-slate-400">No Logo</span>
                                @endif
                                
                                <label class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    <input type="file" wire:model="logo" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 mb-2">Upload logo transparan (PNG). Rekomendasi ukuran: 200x50px.</p>
                                <div wire:loading wire:target="logo" class="text-xs text-indigo-500 font-bold">Uploading...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Favicon (Browser Tab)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 rounded-lg border border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden relative group">
                                @if($favicon)
                                    <img src="{{ $favicon->temporaryUrl() }}" class="object-contain w-8 h-8">
                                @elseif($current_favicon)
                                    <img src="{{ asset('storage/' . $current_favicon) }}" class="object-contain w-8 h-8">
                                @else
                                    <span class="text-[10px] text-slate-400">Default</span>
                                @endif
                                
                                <label class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    <input type="file" wire:model="favicon" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 mb-2">Ikon tab browser (ICO/PNG). Ukuran: 32x32px.</p>
                                <div wire:loading wire:target="favicon" class="text-xs text-indigo-500 font-bold">Uploading...</div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Toko</label>
                        <input type="text" wire:model="store_name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Informasi Umum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nomor WhatsApp</label>
                        <input type="text" wire:model="whatsapp_number" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Rekening Bank</label>
                        <input type="text" wire:model="bank_account" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Alamat Toko</label>
                        <textarea wire:model="address" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500"></textarea>
                    </div>
                </div>
            </div>

            <!-- Storefront Config -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Storefront Homepage</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Hero Title</label>
                            <input type="text" wire:model="hero_title" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Hero Subtitle</label>
                            <input type="text" wire:model="hero_subtitle" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pengumuman Toko (Running Text)</label>
                        <div class="flex gap-4">
                            <input type="text" wire:model="store_announcement_text" class="flex-1 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500" placeholder="Contoh: Diskon 50% Hari Ini!">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" wire:model="store_announcement_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: System & Save -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Save Button (Sticky) -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg sticky top-6">
                <button wire:click="save" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    Simpan Perubahan
                </button>
            </div>

            <!-- Features Toggle -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Fitur Modul</h3>
                <div class="space-y-3">
                    <label class="flex items-center justify-between cursor-pointer p-3 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 transition">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Flash Sale</span>
                        <input type="checkbox" wire:model="feature_flash_sale" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>
                    
                    <label class="flex items-center justify-between cursor-pointer p-3 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 transition">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Service Tracking</span>
                        <input type="checkbox" wire:model="feature_service_tracking" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>

                    <label class="flex items-center justify-between cursor-pointer p-3 bg-rose-50 dark:bg-rose-900/20 rounded-xl hover:bg-rose-100 transition border border-rose-100 dark:border-rose-800">
                        <span class="text-sm font-bold text-rose-700 dark:text-rose-400">Maintenance Mode</span>
                        <input type="checkbox" wire:model="maintenance_mode" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                    </label>
                </div>
            </div>

            <!-- Payment Gateway Settings -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    Payment Gateway (Midtrans)
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Merchant ID</label>
                        <input type="text" wire:model="midtrans_merchant_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-xs font-mono focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Client Key</label>
                        <input type="text" wire:model="midtrans_client_key" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-xs font-mono focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Server Key</label>
                        <input type="password" wire:model="midtrans_server_key" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-xs font-mono focus:ring-indigo-500">
                    </div>
                    <label class="flex items-center justify-between cursor-pointer p-3 bg-slate-50 dark:bg-slate-900 rounded-xl hover:bg-slate-100 transition">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Mode Produksi</span>
                        <input type="checkbox" wire:model="midtrans_is_production" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>
