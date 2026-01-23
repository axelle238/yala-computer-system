<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar Menu -->
        <div class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-slate-500">Member</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('member.dashboard') }}" class="block px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white font-bold">Dashboard</a>
                    <a href="{{ route('member.orders') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-600 dark:text-slate-300 transition-colors">Riwayat Pesanan</a>
                    <a href="{{ route('member.services') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-600 dark:text-slate-300 transition-colors">Status Servis</a>
                    <a href="{{ route('member.rma.request') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-600 dark:text-slate-300 transition-colors">Ajukan Klaim Garansi</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-rose-500 font-bold hover:bg-rose-50">Logout</button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 space-y-6">
            <!-- Points Card -->
            <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-indigo-100 text-sm font-bold uppercase tracking-wider mb-1">Yala Points</p>
                    <h2 class="text-4xl font-black font-tech">{{ number_format($user->points ?? 0) }}</h2>
                    <p class="text-xs mt-2 opacity-80">Gunakan poin untuk potongan harga belanja berikutnya.</p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Pesanan Terakhir</h3>
                    <div class="space-y-4">
                        @forelse($recentOrders as $order)
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700 last:border-0 last:pb-0">
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $order->order_number }}</div>
                                    <div class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono font-bold text-slate-700 dark:text-slate-200">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold uppercase">{{ $order->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm italic">Belum ada pesanan.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Active Services -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <h3 class="font-bold text-lg mb-4 text-slate-800 dark:text-white">Servis Aktif</h3>
                    <div class="space-y-4">
                        @forelse($activeServices as $service)
                            <div class="flex justify-between items-center pb-4 border-b border-slate-100 dark:border-slate-700 last:border-0 last:pb-0">
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $service->device_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $service->ticket_number }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-bold uppercase">{{ $service->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-sm italic">Tidak ada servis aktif.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
