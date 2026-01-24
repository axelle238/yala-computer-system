<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 dark:border-slate-700 mb-8 flex flex-col md:flex-row items-center gap-6 animate-fade-in-up">
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-slate-500">{{ $user->email }}</p>
                <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase">Member</span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">Bergabung {{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
            <div class="flex gap-3">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-6 py-2 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold rounded-xl transition-colors text-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Services & Builds -->
            <div class="lg:col-span-2 space-y-8 animate-fade-in-up delay-100">
                
                <!-- Active Services -->
                @if($activeServices->isNotEmpty())
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Servis Aktif
                        </h2>
                        <div class="space-y-4">
                            @foreach($activeServices as $ticket)
                                <div class="bg-cyan-50 dark:bg-cyan-900/10 rounded-xl p-4 border border-cyan-100 dark:border-cyan-800 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $ticket->ticket_number }}</p>
                                        <p class="text-sm text-slate-500">{{ $ticket->device_name }} - {{ $ticket->problem_description }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2.5 py-1 bg-white dark:bg-slate-800 text-cyan-600 rounded-lg text-xs font-bold uppercase border border-cyan-200 dark:border-slate-600">
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Saved Builds -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                        Rakitan Tersimpan (Saved Builds)
                    </h2>
                    @if($savedBuilds->isEmpty())
                        <div class="text-center py-8 text-slate-400 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                            <p class="mb-2">Belum ada rakitan tersimpan.</p>
                            <a href="{{ route('pc-builder') }}" class="text-blue-600 font-bold hover:underline text-sm">Mulai Rakit PC Sekarang &rarr;</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($savedBuilds as $build)
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 group relative">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-slate-800 dark:text-white">{{ $build->name }}</h3>
                                        <span class="text-xs font-mono font-bold text-blue-600">Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-4">{{ count($build->components) }} Komponen • {{ $build->created_at->format('d M Y') }}</p>
                                    
                                    <div class="flex gap-2">
                                        {{-- Logic untuk Edit/Load build bisa ditambahkan nanti, sementara Delete --}}
                                        <button wire:click="deleteBuild({{ $build->id }})" wire:confirm="Hapus rakitan ini?" class="flex-1 py-2 bg-white dark:bg-slate-800 border border-rose-200 text-rose-500 rounded-lg text-xs font-bold hover:bg-rose-50 transition-colors">
                                            Hapus
                                        </button>
                                        {{-- <button class="flex-1 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors">Load</button> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Column: Order History -->
            <div class="lg:col-span-1 animate-fade-in-up delay-200">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24">
                    <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        Riwayat Pesanan
                    </h2>
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($recentOrders as $order)
                            <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-mono font-bold text-slate-500">{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                                {{-- Link to detail if needed --}}
                                {{-- <a href="#" class="block mt-2 text-center text-xs font-bold text-blue-600 hover:underline">Lihat Detail</a> --}}
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400">
                                <p>Belum ada riwayat pesanan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>