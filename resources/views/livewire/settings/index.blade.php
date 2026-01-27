<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8 animate-fade-in-up">
    
    <!-- Header Utama -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-4xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tight">
                Pusat <span class="text-transparent bg-clip-text bg-gradient-to-r from-slate-600 to-slate-400">Pengaturan</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm border-l-2 border-indigo-500 pl-3 italic">Konfigurasi inti arsitektur sistem, integrasi pihak ketiga, dan manajemen profil otoritas toko.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button wire:click="resetKeSetelanAwal" wire:confirm="Anda akan mengembalikan formulir ke setelan pabrik. Lanjutkan?" class="px-6 py-3 border-2 border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all active:scale-95 shadow-sm">
                Setelan Pabrik
            </button>
            <button wire:click="simpanPerubahan" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-indigo-500/30 transition-all flex items-center gap-3 transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                SIMPAN KONFIGURASI
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- NAVIGASI KATEGORI (SIDEBAR) -->
        <div class="lg:col-span-1 space-y-2">
            @foreach([
                'umum' => ['label' => 'Identitas Toko', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                'keuangan' => ['label' => 'Finansial & Pajak', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                'sistem' => ['label' => 'Integrasi API', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                'seo' => ['label' => 'SEO & Sosmed', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                'notifikasi' => ['label' => 'Notifikasi WA', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                'operasional' => ['label' => 'Jadwal Toko', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                'pengiriman' => ['label' => 'Jasa Pengiriman', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
            ] as $idKategori => $info)
                <button wire:click="gantiKategori('{{ $idKategori }}')" 
                        class="w-full flex items-center justify-between px-5 py-4 rounded-[1.25rem] font-black uppercase tracking-widest text-[10px] transition-all {{ $tabAktif === $idKategori ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/30' : 'bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-white border border-slate-100 dark:border-slate-700' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}" /></svg>
                        {{ $info['label'] }}
                    </div>
                    @if($tabAktif === $idKategori)
                        <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- FORMULIR INPUT DINAMIS -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-700 p-10 min-h-[600px] relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-slate-50 dark:bg-slate-900 rounded-full pointer-events-none"></div>
                
                <!-- KATEGORI: UMUM -->
                <div x-show="$wire.tabAktif === 'umum'" class="space-y-10 animate-fade-in">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Identitas Korporasi</h3>
                        <p class="text-sm text-slate-400 font-medium">Data ini akan digunakan pada faktur, email, dan tampilan publik toko.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Nama Institusi Toko</label>
                            <input wire:model="formulir.store_name" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Hotline / WhatsApp</label>
                            <input wire:model="formulir.store_phone" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Domisili Fisik Lengkap</label>
                            <textarea wire:model="formulir.store_address" rows="3" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-medium py-4 px-5"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Alamat Surel Resmi</label>
                            <input wire:model="formulir.store_email" type="email" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100 dark:border-slate-700 space-y-6">
                        <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Branding & Aset Visual</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <label class="group relative flex items-center gap-5 p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/30 transition-all">
                                <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center text-slate-300 border border-slate-100 dark:border-slate-800 overflow-hidden shadow-inner">
                                    @if($logoBaru) <img src="{{ $logoBaru->temporaryUrl() }}" class="w-full h-full object-contain p-2">
                                    @elseif(\App\Models\Setting::get('store_logo')) <img src="{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}" class="w-full h-full object-contain p-2">
                                    @else <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> @endif
                                </div>
                                <div>
                                    <span class="block text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">Logo Utama</span>
                                    <span class="text-[10px] text-slate-400 font-bold">PNG Transparan Disarankan</span>
                                    <input type="file" wire:model="logoBaru" class="hidden">
                                </div>
                            </label>
                            
                            <label class="group relative flex items-center gap-5 p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/30 transition-all">
                                <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center text-slate-300 border border-slate-100 dark:border-slate-800 overflow-hidden shadow-inner">
                                    @if($faviconBaru) <img src="{{ $faviconBaru->temporaryUrl() }}" class="w-full h-full object-contain p-4">
                                    @elseif(\App\Models\Setting::get('store_favicon')) <img src="{{ asset('storage/' . \App\Models\Setting::get('store_favicon')) }}" class="w-full h-full object-contain p-4">
                                    @else <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @endif
                                </div>
                                <div>
                                    <span class="block text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-300">Favicon Browser</span>
                                    <span class="text-[10px] text-slate-400 font-bold">Icon Kecil (32x32 px)</span>
                                    <input type="file" wire:model="faviconBaru" class="hidden">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100 dark:border-slate-700">
                        <label class="flex items-center gap-4 cursor-pointer p-6 bg-slate-50 dark:bg-slate-900/50 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="formulir.store_announcement_active" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </div>
                            <div>
                                <span class="font-black text-xs uppercase tracking-widest text-slate-700 dark:text-slate-300">Bilah Pengumuman Storefront</span>
                                <span class="block text-[10px] text-slate-400 font-bold mt-0.5 uppercase tracking-tighter">Aktifkan untuk menampilkan pesan promo di bagian paling atas situs.</span>
                            </div>
                        </label>
                        <div class="mt-6 animate-fade-in" x-show="$wire.formulir.store_announcement_active">
                            <input wire:model="formulir.store_announcement_text" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-indigo-500 font-bold py-4 px-5 text-indigo-600 dark:text-indigo-400" placeholder="Tulis pesan pengumuman di sini...">
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: KEUANGAN -->
                <div x-show="$wire.tabAktif === 'keuangan'" class="space-y-10 animate-fade-in">
                    <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Arsitektur Finansial & Pajak</h3>
                         <p class="text-sm text-slate-400 font-medium">Pengaturan global untuk perhitungan pajak dan biaya layanan.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">Otoritas Pajak Nasional (PPN)</label>
                            <div class="relative max-w-xs">
                                <input wire:model="formulir.tax_rate" type="number" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-black py-4 px-5 pr-12 text-2xl">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-5 text-xl font-black text-slate-300">%</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest">Biaya Layanan Tetap (Service Charge)</label>
                            <div class="relative max-w-xs">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-5 text-sm font-black text-slate-400">Rp</span>
                                <input wire:model="formulir.service_charge" type="number" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-black py-4 pl-12 pr-5 text-2xl">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: SISTEM (INTEGRASI API) -->
                <div x-show="$wire.tabAktif === 'sistem'" class="space-y-10 animate-fade-in">
                    <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Integrasi Gateway Pembayaran (Midtrans)</h3>
                         <p class="text-sm text-slate-400 font-medium">Koneksikan sistem dengan Midtrans untuk pembayaran otomatis.</p>
                    </div>
                    
                    <div class="space-y-4 bg-slate-50 dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-700 dark:text-slate-300">Mode Produksi</span>
                            <div class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="formulir.midtrans_is_production" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400">Matikan untuk menggunakan mode Sandbox (Uji Coba).</p>
                    </div>

                    <div class="space-y-6">
                         <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Merchant ID</label>
                            <input wire:model="formulir.midtrans_merchant_id" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                         <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Client Key</label>
                            <input wire:model="formulir.midtrans_client_key" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                         <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Server Key</label>
                            <input wire:model="formulir.midtrans_server_key" type="password" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-700">

                    <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Konfigurasi Email (SMTP)</h3>
                         <p class="text-sm text-slate-400 font-medium">Pengaturan server untuk pengiriman notifikasi email sistem.</p>
                    </div>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">SMTP Host</label>
                            <input wire:model="formulir.smtp_host" type="text" placeholder="smtp.gmail.com" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">SMTP Port</label>
                            <input wire:model="formulir.smtp_port" type="text" placeholder="587" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Username</label>
                            <input wire:model="formulir.smtp_username" type="text" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Password</label>
                            <input wire:model="formulir.smtp_password" type="password" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: SEO & SOSMED -->
                <div x-show="$wire.tabAktif === 'seo'" class="space-y-10 animate-fade-in">
                    <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Optimasi Mesin Pencari (SEO)</h3>
                         <p class="text-sm text-slate-400 font-medium">Tingkatkan visibilitas toko di Google Search.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Deskripsi Meta (Meta Description)</label>
                            <textarea wire:model="formulir.seo_meta_description" rows="3" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-medium py-4 px-5"></textarea>
                            <p class="text-xs text-slate-400 text-right">Maksimal 160 karakter disarankan.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Kata Kunci (Keywords)</label>
                            <input wire:model="formulir.seo_meta_keywords" type="text" placeholder="komputer, laptop, gaming..." class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-700">

                    <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Jejaring Sosial</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <input wire:model="formulir.social_facebook" type="text" placeholder="URL Facebook Page" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center text-white shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </div>
                            <input wire:model="formulir.social_instagram" type="text" placeholder="URL Instagram" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                            </div>
                            <input wire:model="formulir.social_tiktok" type="text" placeholder="URL TikTok" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                        </div>
                    </div>
                </div>

                <!-- KATEGORI: NOTIFIKASI WA -->
                <div x-show="$wire.tabAktif === 'notifikasi'" class="space-y-10 animate-fade-in">
                     <div>
                         <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Templat Pesan Otomatis (WhatsApp)</h3>
                         <p class="text-sm text-slate-400 font-medium">Sesuaikan pesan yang dikirimkan oleh bot ke pelanggan.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Pesanan Berhasil Dibuat</label>
                            <textarea wire:model="formulir.wa_template_order_success" rows="4" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-medium py-4 px-5"></textarea>
                            <p class="text-xs text-indigo-500 font-bold">Variabel: @{{nama}}, @{{order_id}}, @{{total}}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Pengingat Pembayaran</label>
                            <textarea wire:model="formulir.wa_template_payment_reminder" rows="4" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-medium py-4 px-5"></textarea>
                        </div>
                    </div>
                </div>

                <!-- KATEGORI OPERASIONAL -->
                <div x-show="$wire.tabAktif === 'operasional'" class="space-y-10 animate-fade-in">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Manajemen Waktu Operasional</h3>
                        <p class="text-sm text-slate-400 font-medium">Jam operasional ini akan ditampilkan di halaman kontak.</p>
                    </div>
                    
                    <div class="overflow-hidden border border-slate-100 dark:border-slate-700 rounded-[2rem] shadow-inner bg-slate-50 dark:bg-slate-900/30">
                        <table class="w-full text-left">
                            <thead class="bg-slate-100 dark:bg-slate-900 text-slate-500 font-black uppercase text-[10px] tracking-widest border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-8 py-5">Hari Kerja</th>
                                    <th class="px-8 py-5 text-center">Waktu Buka</th>
                                    <th class="px-8 py-5 text-center">Waktu Tutup</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @php
                                    $petaHari = [
                                        'mon' => 'Senin', 'tue' => 'Selasa', 'wed' => 'Rabu', 
                                        'thu' => 'Kamis', 'fri' => 'Jumat', 'sat' => 'Sabtu', 'sun' => 'Minggu'
                                    ];
                                @endphp
                                @foreach($petaHari as $idHari => $labelHari)
                                    <tr class="hover:bg-white dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-8 py-4 font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter text-sm">{{ $labelHari }}</td>
                                        <td class="px-8 py-4 text-center">
                                            <input wire:model="formulir.store_open_{{ $idHari }}" type="text" class="w-32 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-2.5 text-center font-mono font-bold text-indigo-600 focus:ring-indigo-500 shadow-sm">
                                        </td>
                                        <td class="px-8 py-4 text-center">
                                            <input wire:model="formulir.store_close_{{ $idHari }}" type="text" class="w-32 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 py-2.5 text-center font-mono font-bold text-indigo-600 focus:ring-indigo-500 shadow-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KATEGORI: PENGIRIMAN (BARU) -->
                <div x-show="$wire.tabAktif === 'pengiriman'" class="space-y-10 animate-fade-in">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-2">Jasa Pengiriman Barang</h3>
                        <p class="text-sm text-slate-400 font-medium">Konfigurasi kurir, integrasi RajaOngkir, dan biaya tambahan.</p>
                    </div>

                    <div class="space-y-6">
                        <!-- API Key -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">API Key RajaOngkir</label>
                            <input wire:model="formulir.rajaongkir_api_key" type="password" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-mono font-bold py-4 px-5">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tipe Akun -->
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Tipe Akun RajaOngkir</label>
                                <select wire:model="formulir.rajaongkir_type" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                                    <option value="starter">Starter (Gratis)</option>
                                    <option value="pro">Pro (Berbayar)</option>
                                </select>
                            </div>
                            
                            <!-- Kota Asal -->
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">ID Kota Asal (Origin)</label>
                                <input wire:model="formulir.shipping_origin_city_id" type="number" placeholder="Contoh: 152 (Jakarta Pusat)" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5">
                            </div>
                        </div>

                        <hr class="border-slate-100 dark:border-slate-700">

                        <!-- Kurir Aktif -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em] mb-4">Kurir Aktif</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach(['jne' => 'JNE', 'pos' => 'POS Indonesia', 'tiki' => 'TIKI', 'sicepat' => 'SiCepat'] as $code => $name)
                                    <label class="flex items-center gap-3 p-4 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                        <input type="checkbox" wire:model="formulir.shipping_couriers_{{ $code }}" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="font-bold text-sm">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <hr class="border-slate-100 dark:border-slate-700">

                        <!-- Biaya Tambahan -->
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">Markup Biaya Packing (Rp)</label>
                            <input wire:model="formulir.shipping_markup_fee" type="number" class="w-full rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 focus:ring-indigo-500 font-bold py-4 px-5" placeholder="0">
                            <p class="text-xs text-slate-400">Biaya tambahan yang ditambahkan ke ongkir untuk packing/handling.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>