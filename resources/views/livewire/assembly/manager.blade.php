<div class="space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-tech text-slate-900 dark:text-white tracking-tight uppercase">
                PC <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-500">Assembly</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 font-medium text-sm">Pipeline produksi rakitan komputer.</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Job Baru
        </button>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-slate-200 dark:border-slate-700 pb-1 overflow-x-auto">
        @foreach(['queued' => 'Menunggu (Antrian)', 'building' => 'Sedang Dirakit', 'testing' => 'Testing & QC', 'completed' => 'Selesai'] as $key => $label)
            <button wire:click="$set('activeTab', '{{ $key }}')" 
                class="px-4 py-2 text-sm font-bold whitespace-nowrap transition-all {{ $activeTab === $key ? 'text-violet-600 border-b-2 border-violet-500' : 'text-slate-500 hover:text-slate-800' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Kanban Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($assemblies as $job)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all p-6 flex flex-col h-full relative overflow-hidden group">
                <!-- Status Stripe -->
                <div class="absolute top-0 left-0 w-1 h-full 
                    {{ $job->status === 'queued' ? 'bg-slate-400' : '' }}
                    {{ $job->status === 'building' ? 'bg-blue-500' : '' }}
                    {{ $job->status === 'testing' ? 'bg-amber-500' : '' }}
                    {{ $job->status === 'completed' ? 'bg-emerald-500' : '' }}
                "></div>

                <div class="flex justify-between items-start mb-4 pl-3">
                    <div>
                        <span class="text-xs font-mono text-slate-400">Order #{{ $job->order->order_number }}</span>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white leading-tight">{{ $job->build_name }}</h3>
                    </div>
                    @if($job->status === 'queued')
                        <button wire:click="takeJob({{ $job->id }})" class="px-3 py-1 bg-violet-100 text-violet-700 rounded-lg text-xs font-bold hover:bg-violet-200 transition">
                            Ambil Job
                        </button>
                    @endif
                </div>

                <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-3 mb-4 border border-slate-100 dark:border-slate-700 flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Spesifikasi Singkat:</p>
                    <p class="text-xs text-slate-700 dark:text-slate-300 line-clamp-4">{{ $job->specs_snapshot }}</p>
                </div>

                <div class="pl-3 space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                            {{ substr($job->technician->name ?? '?', 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">
                            {{ $job->technician->name ?? 'Belum ada teknisi' }}
                        </span>
                    </div>

                    <!-- Action Controls -->
                    @if($job->technician_id == auth()->id() || auth()->user()->isAdmin())
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                            @if($job->status === 'building')
                                <button wire:click="updateStatus({{ $job->id }}, 'testing')" class="col-span-2 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition">
                                    Mulai Testing (QC)
                                </button>
                            @elseif($job->status === 'testing')
                                <button wire:click="updateStatus({{ $job->id }}, 'completed')" class="col-span-2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                    Selesai Rakit
                                </button>
                            @endif
                        </div>
                        
                        <!-- Benchmark Input (Expandable) -->
                        <div x-data="{ open: false }">
                            <button @click="open = !open" wire:click="loadAssemblyData({{ $job->id }})" class="text-[10px] text-slate-400 hover:text-violet-500 w-full text-center mt-2">
                                + Input Catatan & Benchmark
                            </button>
                            <div x-show="open" class="mt-2 space-y-2">
                                <input type="text" wire:model="benchmarkScore" class="w-full text-xs px-2 py-1 border rounded" placeholder="Score (e.g. Time Spy 15000)">
                                <textarea wire:model="technicianNotes" class="w-full text-xs px-2 py-1 border rounded" placeholder="Catatan..."></textarea>
                                <button wire:click="saveNotes({{ $job->id }})" class="w-full py-1 bg-slate-200 text-slate-600 text-[10px] font-bold rounded hover:bg-slate-300">Simpan</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <p>Tidak ada pekerjaan rakitan di tahap ini.</p>
            </div>
        @endforelse
    </div>

    {{ $assemblies->links() }}

    <!-- Create Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Buat Job Rakitan Baru</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Pilih Order (Lunas)</label>
                        <select wire:model="selectedOrderId" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl px-3 py-2 text-sm">
                            <option value="">-- Pilih Order --</option>
                            @foreach($eligibleOrders as $ord)
                                <option value="{{ $ord->id }}">{{ $ord->order_number }} - {{ $ord->guest_name }} (Rp {{ number_format($ord->total_amount) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-xs text-slate-400">Hanya pesanan dengan status pembayaran 'PAID' dan belum masuk antrian rakitan yang muncul di sini.</p>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-slate-500 font-bold text-sm">Batal</button>
                    <button wire:click="createFromOrder" class="px-6 py-2 bg-violet-600 text-white font-bold rounded-xl shadow-lg hover:bg-violet-700 transition">Buat Job</button>
                </div>
            </div>
        </div>
    @endif
</div>
