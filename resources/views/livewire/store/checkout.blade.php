<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a] py-12 font-sans">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Header Minimalis -->
        <div class="mb-12 animate-fade-in-up flex flex-col items-center">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                <div class="h-px w-16 bg-slate-200 dark:bg-slate-700"></div>
                <div class="w-8 h-8 bg-slate-200 dark:bg-slate-800 text-slate-500 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                <div class="h-px w-16 bg-slate-200 dark:bg-slate-700"></div>
                <div class="w-8 h-8 bg-slate-200 dark:bg-slate-800 text-slate-500 rounded-full flex items-center justify-center font-bold text-sm">3</div>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">
                Checkout Aman
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Lengkapi data pengiriman & pembayaran Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Kiri: Formulir (8 Kolom) -->
            <div class="lg:col-span-8 space-y-8 animate-fade-in-up delay-100">
                
                <!-- STEP 1: PENGIRIMAN -->
                <section class="bg-white dark:bg-[#161615] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="absolute top-0 left-0 w-1 h-full bg-black dark:bg-white"></div>
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Alamat Pengiriman
                        </h3>
                        @auth
                            @if(count($alamatTersimpan) > 0)
                                <button wire:click="bersihkanPilihanAlamat" class="text-xs font-bold text-slate-500 hover:text-black dark:hover:text-white underline decoration-slate-300 underline-offset-4">
                                    {{ $idAlamatTerpilih ? 'Gunakan Alamat Baru' : 'Reset Pilihan' }}
                                </button>
                            @endif
                        @endauth
                    </div>

                    <!-- Pilihan Buku Alamat -->
                    @auth
                        @if(count($alamatTersimpan) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                @foreach($alamatTersimpan as $addr)
                                    <button wire:click="pilihAlamat({{ $addr->id }})" 
                                        class="text-left border-2 rounded-2xl p-5 transition-all group/card relative overflow-hidden {{ $idAlamatTerpilih === $addr->id ? 'border-black dark:border-white bg-slate-50 dark:bg-slate-800' : 'border-slate-100 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                                        @if($idAlamatTerpilih === $addr->id)
                                            <div class="absolute top-3 right-3 w-2 h-2 bg-black dark:bg-white rounded-full"></div>
                                        @endif
                                        <span class="block font-bold text-sm text-slate-900 dark:text-white mb-1">{{ $addr->label }}</span>
                                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $addr->address_line }}, {{ $addr->city }}</p>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @endauth
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">Nama Penerima</label>
                            <input type="text" wire:model.blur="nama" class="w-full bg-transparent border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white px-0 py-3 text-slate-900 dark:text-white font-medium placeholder-slate-300 focus:ring-0 transition-colors" placeholder="Nama Lengkap">
                            @error('nama') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">No. WhatsApp</label>
                            <input type="text" wire:model.blur="telepon" class="w-full bg-transparent border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white px-0 py-3 text-slate-900 dark:text-white font-medium placeholder-slate-300 focus:ring-0 transition-colors" placeholder="08xxxxxxxxxx">
                            @error('telepon') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-8 space-y-2">
                        <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">Alamat Lengkap</label>
                        <textarea wire:model.blur="alamat" rows="2" class="w-full bg-slate-50 dark:bg-slate-900 border-0 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white transition-all placeholder-slate-400 text-sm leading-relaxed" placeholder="Jalan, Nomor Rumah, RT/RW, Kelurahan, Kecamatan"></textarea>
                        @error('alamat') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">Kota Tujuan</label>
                            <div class="relative">
                                <select wire:model.live="kota" class="w-full bg-slate-50 dark:bg-slate-900 border-0 rounded-xl px-4 py-3 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-black dark:focus:ring-white appearance-none cursor-pointer">
                                    <option value="">-- Pilih Kota --</option>
                                    @foreach($daftarKota as $namaKota => $ongkir)
                                        <option value="{{ $namaKota }}">{{ $namaKota }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('kota') <span class="text-rose-500 text-xs font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">Layanan Pengiriman</label>
                            <div class="relative">
                                <select wire:model.live="kurir" class="w-full bg-slate-50 dark:bg-slate-900 border-0 rounded-xl px-4 py-3 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-black dark:focus:ring-white appearance-none cursor-pointer">
                                    <option value="jne">JNE (Reguler)</option>
                                    <option value="jnt">J&T Express</option>
                                    <option value="sicepat">SiCepat Halu</option>
                                    <option value="gosend">GoSend (Instant)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- STEP 2: PEMBAYARAN -->
                <section class="bg-white dark:bg-[#161615] rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                    <div class="absolute top-0 left-0 w-1 h-full bg-black dark:bg-white"></div>
                    
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight flex items-center gap-3 mb-8">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        Metode Pembayaran
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <label class="cursor-pointer border-2 rounded-2xl p-5 flex items-center gap-4 transition-all {{ $metodePembayaran === 'midtrans' ? 'border-black dark:border-white bg-slate-50 dark:bg-slate-800 shadow-md' : 'border-slate-100 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-600' }}">
                            <input type="radio" wire:model.live="metodePembayaran" value="midtrans" class="sr-only">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center border border-slate-100 shadow-sm">
                                <svg class="w-6 h-6 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <div>
                                <span class="block font-black text-sm text-slate-900 dark:text-white">Otomatis (Midtrans)</span>
                                <span class="text-xs text-slate-500 font-medium">QRIS, VA, E-Wallet</span>
                            </div>
                            @if($metodePembayaran === 'midtrans')
                                <div class="ml-auto w-2 h-2 bg-black dark:bg-white rounded-full"></div>
                            @endif
                        </label>
                        
                        <div class="border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-5 flex items-center gap-4 opacity-50 cursor-not-allowed">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            </div>
                            <div>
                                <span class="block font-black text-sm text-slate-900 dark:text-white">Transfer Manual</span>
                                <span class="text-xs text-slate-500 font-medium">Segera Hadir</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase text-slate-400 tracking-wider">Catatan Tambahan (Opsional)</label>
                        <input type="text" wire:model="catatanPesanan" class="w-full bg-transparent border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white px-0 py-3 text-slate-900 dark:text-white text-sm placeholder-slate-300 focus:ring-0 transition-colors" placeholder="Pesan untuk penjual...">
                    </div>
                </section>

            </div>

            <!-- Kanan: Ringkasan Pesanan (4 Kolom) -->
            <div class="lg:col-span-4 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-[#161615] rounded-[2rem] p-6 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none sticky top-24">
                    <h3 class="font-black text-lg text-slate-900 dark:text-white mb-6 uppercase tracking-tight">Ringkasan</h3>
                    
                    <!-- Item List -->
                    <div class="space-y-5 mb-8 max-h-80 overflow-y-auto custom-scrollbar pr-2">
                        @foreach($itemKeranjang as $item)
                            <div class="flex gap-4 group">
                                <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 overflow-hidden border border-slate-100 dark:border-slate-800">
                                    @if($item['produk']->image_path)
                                        <img src="{{ asset('storage/' . $item['produk']->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-2 leading-relaxed">{{ $item['produk']->name }}</h4>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-[10px] text-slate-500 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded font-bold">x{{ $item['qty'] }}</span>
                                        <span class="text-xs font-mono font-medium text-slate-600 dark:text-slate-300">Rp {{ number_format($item['total_baris'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Voucher & Diskon -->
                    <div class="space-y-4 mb-6 border-t border-slate-100 dark:border-slate-800 pt-6">
                        @if($voucherTerpasang)
                            <div class="bg-black text-white rounded-xl p-3 flex justify-between items-center shadow-md">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 7.75a1 1 0 01.554 1.705l-2.428 2.365.573 3.343a1 1 0 01-1.451 1.054L11 14.5l-2.998 1.574a1 1 0 01-1.451-1.054l.573-3.343-2.428-2.365a1 1 0 01.554-1.705l3.354-.55L11.033 2.744A1 1 0 0112 2z" clip-rule="evenodd" /></svg>
                                    <div>
                                        <span class="block text-[10px] font-bold uppercase opacity-80">Voucher</span>
                                        <span class="font-bold text-sm">{{ $voucherTerpasang->code }}</span>
                                    </div>
                                </div>
                                <button wire:click="hapusVoucher" class="text-white/60 hover:text-white transition-colors">&times;</button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input type="text" wire:model="kodeVoucher" class="flex-1 bg-slate-50 dark:bg-slate-900 border-0 rounded-xl px-3 py-2 text-xs font-bold uppercase placeholder-slate-400 focus:ring-1 focus:ring-black dark:focus:ring-white transition-all" placeholder="KODE PROMO">
                                <button wire:click="pasangVoucher" class="px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-black rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">Pakai</button>
                            </div>
                            @error('kodeVoucher') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        @endif

                        <!-- Poin -->
                        @auth
                            <div class="flex items-center justify-between p-3 rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                                <div>
                                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Gunakan Poin</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">{{ number_format(auth()->user()->points) }} Poin</span>
                                </div>
                                <input type="number" wire:model.live.debounce.500ms="poinUntukDitukar" class="w-20 bg-transparent border-b border-slate-300 dark:border-slate-600 text-right font-mono text-sm focus:ring-0 focus:border-black dark:focus:border-white p-1" placeholder="0">
                            </div>
                        @endauth
                    </div>

                    <!-- Perhitungan -->
                    <div class="space-y-3 text-sm text-slate-600 dark:text-slate-400 mb-8 font-medium">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="text-slate-900 dark:text-white font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            @if($kota)
                                <span class="text-slate-900 dark:text-white font-bold">Rp {{ number_format($biayaPengiriman, 0, ',', '.') }}</span>
                            @else
                                <span class="text-xs italic opacity-50">--</span>
                            @endif
                        </div>
                        @if($diskonVoucher > 0)
                            <div class="flex justify-between text-black dark:text-white">
                                <span>Diskon Voucher</span>
                                <span class="font-bold">- Rp {{ number_format($diskonVoucher, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($jumlahDiskon > 0)
                            <div class="flex justify-between text-black dark:text-white">
                                <span>Diskon Poin</span>
                                <span class="font-bold">- Rp {{ number_format($jumlahDiskon, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-end pt-4 border-t-2 border-slate-900 dark:border-white text-slate-900 dark:text-white">
                            <span class="font-black uppercase tracking-wider text-xs">Total Akhir</span>
                            <span class="text-2xl font-black font-mono">Rp {{ number_format($totalAkhir, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- ACTION BUTTON - MODIFIED AS REQUESTED -->
                    <button wire:click="buatPesanan" 
                        class="w-full py-4 bg-white text-black border-2 border-black rounded-xl font-black uppercase tracking-widest hover:bg-black hover:text-white hover:shadow-xl transition-all duration-300 active:scale-95 flex items-center justify-center gap-3 group" 
                        wire:loading.attr="disabled">
                        <span wire:loading.remove class="flex items-center gap-2">
                            BAYAR SEKARANG
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            MEMPROSES...
                        </span>
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-400 mt-6 flex items-center justify-center gap-1.5 opacity-75">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Pembayaran Aman & Terenkripsi
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@php
    $snapUrl = config('services.midtrans.is_production') 
        ? 'https://app.midtrans.com/snap/snap.js' 
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
<script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('trigger-payment', (event) => {
            const data = Array.isArray(event) ? event[0] : event;
            snap.pay(data.token, {
                onSuccess: function(result){
                    window.location.href = "{{ route('toko.pesanan.berhasil', ':id') }}".replace(':id', data.orderId);
                },
                onPending: function(result){
                    window.location.href = "{{ route('toko.pesanan.berhasil', ':id') }}".replace(':id', data.orderId);
                },
                onError: function(result){
                    alert("Pembayaran gagal! Silakan coba lagi.");
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    window.location.href = "{{ route('anggota.pesanan') }}";
                }
            });
        });
    });
</script>
@endpush