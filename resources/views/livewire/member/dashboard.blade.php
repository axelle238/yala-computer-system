<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">
                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">{{ explode(' ', $user->name)[0] }}</span>! 👋
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Selamat datang kembali di pusat kendali akun Anda.</p>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <!-- Total Orders -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Total Pesanan</span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white">
                        {{ $user->orders()->count() }}
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-sm font-bold text-slate-500 dark:text-slate-400">Total Belanja</span>
                    </div>
                    <div class="text-2xl font-black text-slate-900 dark:text-white truncate">
                        Rp {{ number_format($user->total_spent ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Loyalty Points -->
            <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-3xl p-6 shadow-lg shadow-indigo-500/30 text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="text-indigo-200 text-xs font-bold uppercase tracking-wider mb-1">Loyalty Points</div>
                            <div class="text-4xl font-black">{{ number_format($user->loyalty_points ?? 0) }}</div>
                        </div>
                        <div class="px-3 py-1 bg-white/20 rounded-lg text-xs font-bold backdrop-blur-sm border border-white/10">
                            {{ $user->level['name'] }}
                        </div>
                    </div>
                    <div class="w-full bg-black/20 rounded-full h-1.5 mb-2 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: {{ $user->next_level_progress['percent'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-indigo-200">
                        {{ number_format($user->next_level_progress['remaining']) }} poin lagi ke level berikutnya.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Menu Cepat</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('anggota.pesanan') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-slate-100 dark:border-slate-700 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Pesanan</span>
                        </a>
                        <a href="{{ route('anggota.servis.pesan') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:bg-cyan-50 dark:hover:bg-cyan-900/20 border border-slate-100 dark:border-slate-700 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Booking Servis</span>
                        </a>
                        <a href="{{ route('anggota.penawaran') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:bg-purple-50 dark:hover:bg-purple-900/20 border border-slate-100 dark:border-slate-700 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Penawaran</span>
                        </a>
                        <a href="{{ route('anggota.garansi.ajukan') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 hover:bg-amber-50 dark:hover:bg-amber-900/20 border border-slate-100 dark:border-slate-700 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Klaim Garansi</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Orders with Thumbnails -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Pesanan Terbaru
                        </h3>
                        <a href="{{ route('anggota.pesanan') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentOrders as $order)
                            <div class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                                <!-- Thumbnails -->
                                <div class="flex items-center gap-2 overflow-hidden">
                                    @foreach($order->items->take(3) as $item)
                                        <div class="w-14 h-14 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($item->product && $item->product->images && count($item->product->images) > 0)
                                                <img src="{{ asset('storage/'.$item->product->images[0]) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <div class="w-14 h-14 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-500">
                                            +{{ $order->items->count() - 3 }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="font-bold text-slate-800 dark:text-white text-sm">Order #{{ $order->order_number }}</div>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $order->status_label ?? $order->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-2">{{ $order->created_at->format('d F Y, H:i') }}</p>
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm font-black text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                        <a href="{{ route('anggota.pesanan.detail', $order->id) }}" class="text-xs font-bold text-blue-600 hover:underline">Detail</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400">
                                <p>Belum ada pesanan.</p>
                                <a href="{{ route('toko.katalog') }}" class="text-blue-600 font-bold hover:underline mt-2 inline-block">Mulai Belanja</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Active Services Status -->
                @if($activeServices->isNotEmpty())
                    <div class="bg-gradient-to-br from-cyan-600 to-blue-600 rounded-3xl p-6 shadow-lg text-white">
                        <h3 class="font-bold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Servis Sedang Berjalan
                        </h3>
                        <div class="space-y-3">
                            @foreach($activeServices as $ticket)
                                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                                    <div class="flex justify-between text-xs mb-1 opacity-80">
                                        <span>{{ $ticket->ticket_number }}</span>
                                        <span class="font-bold uppercase">{{ str_replace('_', ' ', $ticket->status) }}</span>
                                    </div>
                                    <div class="font-bold text-sm">{{ $ticket->device_name }}</div>
                                    <div class="text-xs opacity-75 truncate">{{ $ticket->problem_description }}</div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('toko.lacak-servis') }}" class="block text-center mt-4 text-xs font-bold bg-white text-blue-600 py-2 rounded-lg hover:bg-blue-50 transition">Lacak Detail</a>
                    </div>
                @endif

                <!-- Account Links -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Pengaturan Akun</h3>
                    <ul class="space-y-2 text-sm font-medium text-slate-600 dark:text-slate-400">
                        <li>
                            <a href="{{ route('anggota.profil') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Profil Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('anggota.alamat') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Buku Alamat
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('keluar') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/20 text-rose-600 transition-colors font-bold text-left">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Keluar Akun
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>