<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Profile Header with Gamification -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 dark:border-slate-700 mb-8 flex flex-col md:flex-row items-center gap-6 animate-fade-in-up relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl -mr-16 -mt-16"></div>

            <div class="relative w-24 h-24 flex-shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <!-- Level Badge -->
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-slate-900 rounded-full border-4 border-slate-800 flex items-center justify-center {{ $user->level['color'] }} shadow-lg" title="{{ $user->level['name'] }}">
                    @if($user->level['icon'] == 'crown') <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2l2.5 5 4.5.5-3.5 3 1 4.5L10 12.5 5.5 15l1-4.5-3.5-3 4.5-.5L10 2z"/></svg> 
                    @elseif($user->level['icon'] == 'lightning-bolt') <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                    @elseif($user->level['icon'] == 'star') <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @else <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                    @endif
                </div>
            </div>

            <div class="flex-1 w-full text-center md:text-left">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-3 mb-1">
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                        {{ $user->level['name'] }} Member
                    </span>
                </div>
                <p class="text-slate-500 mb-4">{{ $user->email }}</p>
                
                <!-- Level Progress -->
                @php $progress = $user->next_level_progress; @endphp
                @if($progress['target'] > 0)
                    <div class="w-full max-w-md mx-auto md:mx-0">
                        <div class="flex justify-between text-xs font-bold text-slate-400 mb-1">
                            <span>XP Progress</span>
                            <span>{{ number_format($progress['current']) }} / {{ number_format($progress['target']) }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full transition-all duration-1000" style="width: {{ $progress['percent'] }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Belanja Rp {{ number_format($progress['remaining'], 0, ',', '.') }} lagi untuk naik level!</p>
                    </div>
                @else
                    <div class="text-sm font-bold text-amber-400 flex items-center gap-1 justify-center md:justify-start">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        Legendary Status Reached!
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-3 w-full md:w-auto">
                <a href="{{ route('anggota.alamat') }}" class="w-full px-6 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-white font-bold rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors text-sm text-center">
                    Kelola Alamat
                </a>
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 border border-slate-200 dark:border-slate-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 text-slate-600 dark:text-slate-300 hover:text-rose-600 dark:hover:text-rose-400 font-bold rounded-xl transition-colors text-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Services & Builds -->
            <div class="lg:col-span-2 space-y-8 animate-fade-in-up delay-100">
                
                <!-- Referral Program Link -->
                <a href="{{ route('anggota.referal') }}" class="block bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group hover:scale-[1.01] transition-transform">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-white/20 transition-all"></div>
                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <h2 class="font-bold text-lg mb-1 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                Referral & Cuan
                            </h2>
                            <p class="text-indigo-100 text-sm">Ajak teman, dapatkan poin. Klik untuk detail.</p>
                        </div>
                        <svg class="w-6 h-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </a>

                <!-- Active Services -->
                @if($activeServices->isNotEmpty())
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Status Servis
                        </h2>
                        <div class="space-y-4">
                            @foreach($activeServices as $ticket)
                                <a href="{{ route('toko.lacak-servis', ['ticket' => $ticket->ticket_number]) }}" class="block bg-cyan-50 dark:bg-cyan-900/10 rounded-xl p-4 border border-cyan-100 dark:border-cyan-800 hover:border-cyan-300 transition-colors group">
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="font-bold text-slate-800 dark:text-white group-hover:text-cyan-600 transition-colors">{{ $ticket->ticket_number }}</p>
                                        <span class="px-2.5 py-1 bg-white dark:bg-slate-800 text-cyan-600 rounded-lg text-[10px] font-bold uppercase border border-cyan-200 dark:border-slate-600">
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $ticket->device_name }}</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $ticket->problem_description }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Saved Builds -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                            Rakitan Tersimpan
                        </h2>
                        <a href="{{ route('toko.rakit-pc') }}" class="text-xs font-bold text-purple-600 hover:underline">+ Rakit Baru</a>
                    </div>

                    @if($savedBuilds->isEmpty())
                        <div class="text-center py-8 text-slate-400 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                            <p class="mb-2 text-sm">Belum ada rakitan tersimpan.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($savedBuilds as $build)
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 group relative">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-slate-800 dark:text-white truncate pr-4" title="{{ $build->name }}">{{ $build->name }}</h3>
                                    </div>
                                    <div class="flex justify-between items-end mb-4">
                                        <p class="text-xs text-slate-500">{{ count($build->components) }} Parts</p>
                                        <span class="text-sm font-mono font-bold text-purple-600">Rp {{ number_format($build->total_price_estimated, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button wire:click="deleteBuild({{ $build->id }})" wire:confirm="Hapus rakitan ini?" class="flex-1 py-2 bg-white dark:bg-slate-800 border border-rose-200 text-rose-500 rounded-lg text-xs font-bold hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                            Hapus
                                        </button>
                                        <button class="flex-1 py-2 bg-purple-600 text-white rounded-lg text-xs font-bold hover:bg-purple-700 transition-colors shadow-sm">
                                            Load
                                        </button>
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
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Pesanan Terakhir
                        </h2>
                        <a href="{{ route('anggota.pesanan') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($recentOrders as $order)
                            <a href="{{ route('anggota.pesanan.detail', $order->id) }}" class="block p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-mono font-bold text-slate-500 group-hover:text-emerald-600 transition-colors">{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status == 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 text-slate-400">
                                <p class="text-sm">Belum ada riwayat pesanan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
