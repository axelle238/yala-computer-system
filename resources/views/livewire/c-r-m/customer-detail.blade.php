<div class="space-y-8 animate-fade-in-up">
    <!-- Header Profile -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        
        <div class="flex flex-col md:flex-row gap-8 relative z-10">
            <div class="flex-shrink-0 text-center">
                <div class="w-32 h-32 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto flex items-center justify-center text-4xl font-bold text-slate-500 mb-4 border-4 border-white dark:border-slate-600 shadow-md">
                    {{ substr($this->customer->name, 0, 2) }}
                </div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $this->customer->customerGroup ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $this->customer->customerGroup->name ?? 'Regular Member' }}
                </div>
            </div>
            
            <div class="flex-1 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ $this->customer->name }}</h1>
                        <p class="text-slate-500 flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $this->customer->email }}
                        </p>
                        <p class="text-slate-500 flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $this->customer->phone ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <button wire:click="recalculateTier" class="text-xs font-bold text-indigo-600 hover:underline">Recalculate Tier</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 uppercase font-bold">Lifetime Value</p>
                        <p class="text-lg font-mono font-black text-slate-800 dark:text-white">Rp {{ number_format($this->customer->lifetime_value / 1000000, 1) }}M</p>
                    </div>
                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-800">
                        <p class="text-xs text-amber-600 uppercase font-bold">Poin Reward</p>
                        <p class="text-lg font-mono font-black text-amber-700 dark:text-amber-400">{{ number_format($this->customer->points) }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 uppercase font-bold">Total Order</p>
                        <p class="text-lg font-mono font-black text-slate-800 dark:text-white">{{ $this->customer->orders->count() }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-500 uppercase font-bold">Bergabung</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white mt-1">{{ $this->customer->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Actions & Log -->
        <div class="space-y-8">
            <!-- Add Interaction -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Catat Aktivitas</h3>
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <select wire:model="interactionType" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm w-1/3">
                            <option value="note">Catatan</option>
                            <option value="call">Telepon</option>
                            <option value="meeting">Meeting</option>
                            <option value="complaint">Komplain</option>
                        </select>
                        <input type="date" wire:model="interactionDate" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm w-2/3">
                    </div>
                    <textarea wire:model="interactionContent" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500" placeholder="Detail interaksi..."></textarea>
                    <button wire:click="addInteraction" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm text-sm">Simpan Catatan</button>
                </div>
            </div>

            <!-- Adjust Points -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Penyesuaian Poin</h3>
                <div class="space-y-3">
                    <div class="flex gap-2">
                        <select wire:model="pointAction" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm w-1/3">
                            <option value="add">Tambah (+)</option>
                            <option value="sub">Kurangi (-)</option>
                        </select>
                        <input type="number" wire:model="pointAmount" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm w-2/3 font-mono" placeholder="Jumlah">
                    </div>
                    <input type="text" wire:model="pointReason" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm" placeholder="Alasan penyesuaian...">
                    <button wire:click="adjustPoints" class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-sm text-sm">Update Poin</button>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700">
                @foreach(['overview' => 'Timeline', 'orders' => 'Pesanan', 'loyalty' => 'Mutasi Poin'] as $key => $label)
                    <button wire:click="$set('activeTab', '{{ $key }}')" 
                        class="px-4 py-2 text-sm font-bold border-b-2 transition-all {{ $activeTab === $key ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Tab Content -->
            @if($activeTab === 'overview')
                <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-700 space-y-8">
                    @forelse($interactions as $log)
                        <div class="relative">
                            <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-white dark:bg-slate-800 border-2 {{ $log->type === 'complaint' ? 'border-rose-500' : 'border-indigo-500' }}"></div>
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider {{ $log->type === 'complaint' ? 'text-rose-600' : 'text-indigo-600' }}">{{ $log->type }}</span>
                                    <span class="text-xs text-slate-400">{{ $log->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <p class="text-sm text-slate-700 dark:text-slate-300">{{ $log->content }}</p>
                                <p class="text-xs text-slate-400 mt-2">Oleh: {{ $log->staff->name }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm">Belum ada riwayat interaksi.</p>
                    @endforelse
                    {{ $interactions->links() }}
                </div>
            @elseif($activeTab === 'orders')
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex justify-between items-center">
                            <div>
                                <p class="font-mono font-bold text-slate-700 dark:text-slate-300">{{ $order->order_number }}</p>
                                <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($order->total_amount) }}</p>
                                <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 uppercase">{{ $order->status }}</span>
                            </div>
                        </div>
                    @endforeach
                    {{ $orders->links() }}
                </div>
            @elseif($activeTab === 'loyalty')
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Tipe</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Ket</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($logs as $log)
                            <tr>
                                <td class="px-4 py-3">{{ $log->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 uppercase text-xs">{{ $log->type }}</td>
                                <td class="px-4 py-3 font-bold {{ $log->points > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500">{{ $log->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $logs->links() }}
            @endif
        </div>
    </div>
</div>
