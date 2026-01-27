<div class="space-y-8 animate-fade-in-up">
    
    <!-- Welcome Section -->
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">
            Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">{{ explode(' ', $user->name)[0] }}</span>! 👋
        </h1>
        <p class="text-slate-500 dark:text-slate-400">Selamat datang kembali di pusat kendali akun Anda.</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    Pesanan Terbaru
                </h3>
                <a href="{{ route('anggota.pesanan') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-4 flex-1">
                @forelse($recentOrders as $order)
                    <div class="flex flex-col sm:flex-row gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                        <!-- Thumbnails -->
                        <div class="flex items-center gap-2 overflow-hidden">
                            @foreach($order->items->take(3) as $item)
                                <div class="w-12 h-12 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($item->product && $item->product->images && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/'.$item->product->images[0]) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <div class="font-bold text-slate-800 dark:text-white text-xs">#{{ $order->order_number }}</div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $order->status_label ?? $order->status }}
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-500 mb-2">{{ $order->created_at->format('d M Y') }}</p>
                            <div class="flex justify-between items-center">
                                <div class="text-xs font-black text-slate-900 dark:text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                <a href="{{ route('anggota.pesanan.detail', $order->id) }}" class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-50 transition-all">Detail</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        <p class="text-sm">Belum ada pesanan.</p>
                        <a href="{{ route('toko.katalog') }}" class="text-blue-600 font-bold text-xs hover:underline mt-2 inline-block">Mulai Belanja</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Active Services Status -->
        <div class="space-y-6">
            @if($activeServices->isNotEmpty())
                <div class="bg-gradient-to-br from-cyan-600 to-blue-600 rounded-3xl p-6 shadow-lg text-white">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Servis Berjalan
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
            @else
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white mb-1">Tidak Ada Servis Aktif</h3>
                    <p class="text-xs text-slate-500 mb-4">Perangkat Anda dalam kondisi prima!</p>
                    <a href="{{ route('anggota.servis.pesan') }}" class="inline-block px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl text-xs font-bold transition-all hover:scale-105">Booking Servis</a>
                </div>
            @endif
        </div>
    </div>
</div>
