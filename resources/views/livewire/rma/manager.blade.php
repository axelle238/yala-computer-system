<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">RMA Manager</h1>
            <p class="text-gray-500">Kelola klaim garansi dan retur barang.</p>
        </div>
        <div class="flex gap-2">
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="received_goods">Received Goods</option>
                <option value="completed">Completed</option>
                <option value="rejected">Rejected</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search RMA / Customer..." class="rounded-lg border-gray-300">
        </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">RMA Number</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Request Type</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rmas as $rma)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $rma->rma_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold">{{ $rma->guest_name ?? $rma->user->name }}</div>
                            <div class="text-xs text-gray-400">Ord: {{ $rma->order->order_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 capitalize">{{ $rma->resolution_type }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold uppercase {{ $colors[$rma->status] ?? 'bg-gray-100' }}">
                                {{ str_replace('_', ' ', $rma->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $rma->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="manage({{ $rma->id }})" class="text-blue-600 font-bold hover:underline">Manage</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No RMA requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">{{ $rmas->links() }}</div>
    </div>

    <!-- Modal -->
    @if($showModal && $selectedRma)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h2 class="text-xl font-bold">Manage {{ $selectedRma->rma_number }}</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Details -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-xl">
                            <h3 class="font-bold text-gray-800 mb-2">Customer & Order</h3>
                            <p class="text-sm"><span class="text-gray-500">Name:</span> {{ $selectedRma->guest_name }}</p>
                            <p class="text-sm"><span class="text-gray-500">Phone:</span> {{ $selectedRma->guest_phone }}</p>
                            <p class="text-sm"><span class="text-gray-500">Order:</span> #{{ $selectedRma->order->order_number }}</p>
                            <p class="text-sm mt-2"><span class="text-gray-500">Reason (General):</span> {{ $selectedRma->reason }}</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Items Claimed</h3>
                            <div class="space-y-3">
                                @foreach($selectedRma->items as $item)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="font-bold text-sm">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500 flex justify-between mt-1">
                                            <span>Qty: {{ $item->quantity }}</span>
                                            <span class="capitalize">Cond: {{ str_replace('_', ' ', $item->condition) }}</span>
                                        </div>
                                        <div class="mt-2 text-xs bg-red-50 text-red-700 p-2 rounded">
                                            Problem: {{ $item->problem_description }}
                                        </div>
                                        @if($item->evidence_files)
                                            <div class="mt-2 flex gap-2 overflow-x-auto">
                                                @foreach($item->evidence_files as $file)
                                                    <a href="{{ asset('storage/'.$file) }}" target="_blank" class="block w-16 h-16 rounded overflow-hidden border border-gray-300">
                                                        <img src="{{ asset('storage/'.$file) }}" class="w-full h-full object-cover">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <h3 class="font-bold text-blue-800 mb-4">Admin Actions</h3>
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Update Status</label>
                                <select wire:model="updateStatusTo" class="w-full rounded-lg border-gray-300">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved (Send Instructions)</option>
                                    <option value="received_goods">Received Goods (Under Inspection)</option>
                                    <option value="checking">Checking / Repairing</option>
                                    <option value="completed">Completed (Resolved)</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Admin Notes / Instruction</label>
                                <textarea wire:model="adminNotes" rows="5" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Write internal notes or instructions for customer..."></textarea>
                            </div>

                            <button wire:click="saveManagement" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                                Save Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>