<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">PC Assembly Manager</h1>
            <p class="text-gray-500">Manage custom PC builds and assembly workflows.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="queued">Queued</option>
                <option value="picking">Picking Parts</option>
                <option value="building">Building</option>
                <option value="testing">Testing (QC)</option>
                <option value="completed">Completed</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search Order / Customer..." class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <!-- Kanban / List View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">Order Info</th>
                    <th class="px-6 py-4">Build Name</th>
                    <th class="px-6 py-4">Technician</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assemblies as $assembly)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $assembly->order->order_number }}</div>
                            <div class="text-xs">{{ $assembly->order->guest_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ $assembly->build_name ?? 'Custom Build' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($assembly->technician)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($assembly->technician->name, 0, 1) }}
                                    </div>
                                    {{ $assembly->technician->name }}
                                </div>
                            @else
                                <span class="text-gray-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'queued' => 'bg-gray-100 text-gray-600',
                                    'picking' => 'bg-yellow-100 text-yellow-700',
                                    'building' => 'bg-blue-100 text-blue-700',
                                    'testing' => 'bg-purple-100 text-purple-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase {{ $colors[$assembly->status] ?? 'bg-gray-100' }}">
                                {{ $assembly->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $assembly->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="openDetailPanel({{ $assembly->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                                Manage
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            No active assembly orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $assemblies->links() }}
        </div>
    </div>

    <!-- Detail Action Panel -->
    @if($activeAction === 'detail' && $selectedAssembly)
        <div class="mt-8 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $selectedAssembly->build_name }}</h2>
                    <p class="text-sm text-gray-500">Order #{{ $selectedAssembly->order->order_number }}</p>
                </div>
                <button wire:click="closePanel" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left: Status & Actions -->
                <div class="space-y-6">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Current Status</label>
                        <div class="grid grid-cols-1 gap-2">
                            <button wire:click="updateStatus('picking')" class="w-full text-left px-4 py-3 rounded-lg border transition-all {{ $selectedAssembly->status === 'picking' ? 'bg-yellow-50 border-yellow-500 text-yellow-700 ring-1 ring-yellow-500' : 'border-gray-200 hover:bg-white hover:border-gray-300' }}">
                                <span class="font-bold block">1. Picking Parts</span>
                                <span class="text-xs opacity-75">Kumpulkan komponen di gudang</span>
                            </button>
                            <button wire:click="updateStatus('building')" class="w-full text-left px-4 py-3 rounded-lg border transition-all {{ $selectedAssembly->status === 'building' ? 'bg-blue-50 border-blue-500 text-blue-700 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-white hover:border-gray-300' }}">
                                <span class="font-bold block">2. Assembly</span>
                                <span class="text-xs opacity-75">Perakitan & Cable Management</span>
                            </button>
                            <button wire:click="updateStatus('testing')" class="w-full text-left px-4 py-3 rounded-lg border transition-all {{ $selectedAssembly->status === 'testing' ? 'bg-purple-50 border-purple-500 text-purple-700 ring-1 ring-purple-500' : 'border-gray-200 hover:bg-white hover:border-gray-300' }}">
                                <span class="font-bold block">3. QC & Testing</span>
                                <span class="text-xs opacity-75">Install OS, Update BIOS, Stress Test</span>
                            </button>
                            <button wire:click="updateStatus('completed')" class="w-full text-left px-4 py-3 rounded-lg border transition-all {{ $selectedAssembly->status === 'completed' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 ring-1 ring-emerald-500' : 'border-gray-200 hover:bg-white hover:border-gray-300' }}">
                                <span class="font-bold block">4. Completed</span>
                                <span class="text-xs opacity-75">Siap kirim/ambil</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Benchmark Score (3DMark/Cinebench)</label>
                        <input type="text" wire:model="benchmarkScore" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. CB R23: 15000 pts">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Technician Notes</label>
                        <textarea wire:model="technicianNotes" rows="4" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan internal..."></textarea>
                    </div>
                    
                    <button wire:click="saveNotes" class="w-full py-2.5 bg-slate-800 text-white rounded-lg hover:bg-slate-900 font-bold text-sm shadow-lg transition-transform active:scale-95">Save Notes</button>
                </div>

                <!-- Right: Specs & Checklist -->
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-4">Build Specifications</h3>
                        <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($specs as $key => $item)
                                        @if($item)
                                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 font-medium text-gray-500 w-1/3 capitalize">
                                                    {{ str_replace('_', ' ', $key) }}
                                                </td>
                                                <td class="px-4 py-3 font-bold text-gray-900">
                                                    {{ is_array($item) ? ($item['name'] ?? 'Unknown') : $item }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 text-blue-800 rounded-xl text-sm border border-blue-100">
                        <h4 class="font-bold mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Standard Operating Procedure (SOP)
                        </h4>
                        <ul class="list-disc list-inside space-y-1 ml-1 opacity-80">
                            <li>Pastikan komponen sesuai dengan order.</li>
                            <li>Gunakan sarung tangan antistatis / gelang ESD.</li>
                            <li>Update BIOS ke versi stabil terbaru.</li>
                            <li>Enable XMP/EXPO profil RAM.</li>
                            <li>Cable management bagian belakang harus rapi (gunakan zip ties).</li>
                            <li>Pastikan airflow kipas benar (Front/Bottom: Intake, Top/Rear: Exhaust).</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
