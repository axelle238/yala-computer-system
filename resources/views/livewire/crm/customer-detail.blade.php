<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('customers.index') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            &larr;
        </a>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-black text-indigo-600">
                {{ substr($customer->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h1>
                <div class="flex gap-3 text-sm text-gray-500">
                    <span>{{ $customer->email }}</span>
                    <span>•</span>
                    <span>{{ $customer->phone ?? '-' }}</span>
                    <span>•</span>
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold text-xs uppercase">{{ $customer->role }}</span>
                </div>
            </div>
        </div>
        <div class="ml-auto text-right">
            <p class="text-xs text-gray-500 uppercase font-bold">Loyalty Points</p>
            <p class="text-3xl font-black text-indigo-600">{{ number_format($customer->points) }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-bold uppercase">Total Spend</p>
            <p class="text-xl font-bold text-emerald-600">Rp {{ number_format($customer->orders->sum('total_amount'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-bold uppercase">Orders</p>
            <p class="text-xl font-bold text-gray-800">{{ $customer->orders_count }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-bold uppercase">Service Tickets</p>
            <p class="text-xl font-bold text-gray-800">{{ $customer->service_tickets_count }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-bold uppercase">Referrals</p>
            <p class="text-xl font-bold text-blue-600">{{ \App\Models\User::where('referred_by', $customer->id)->count() }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex gap-6">
            @foreach(['overview' => 'Overview', 'orders' => 'Order History', 'services' => 'Service Tickets', 'rma' => 'RMA / Warranty'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" 
                        class="pb-3 text-sm font-bold border-b-2 transition-colors {{ $activeTab === $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 min-h-[400px]">
        @if($activeTab === 'overview')
            <div class="p-6">
                <h3 class="font-bold text-gray-800 mb-4">Customer Insight</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 mb-2">Address Book</h4>
                        <ul class="space-y-2">
                            @foreach($customer->addresses as $addr)
                                <li class="p-3 bg-gray-50 rounded-lg text-sm">
                                    <span class="font-bold block">{{ $addr->label }}</span>
                                    <span class="text-gray-600">{{ $addr->address_line }}, {{ $addr->city }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 mb-2">Notes</h4>
                        <textarea class="w-full rounded-lg border-gray-300 text-sm" rows="4" placeholder="Internal notes..."></textarea>
                        <button class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold">Save Note</button>
                    </div>
                </div>
            </div>

        @elseif($activeTab === 'orders')
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Order #</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 font-mono">{{ $order->order_number }}</td>
                            <td class="px-6 py-4">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($order->total_amount) }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">{{ $order->status }}</span></td>
                            <td class="px-6 py-4"><a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 font-bold hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $orders->links() }}</div>

        @elseif($activeTab === 'services')
            <div class="p-6 space-y-4">
                @forelse($services as $ticket)
                    <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center">
                        <div>
                            <div class="font-bold text-gray-800">{{ $ticket->ticket_number }} - {{ $ticket->device_name }}</div>
                            <div class="text-sm text-gray-500">{{ $ticket->problem_description }}</div>
                        </div>
                        <div class="text-right">
                            <span class="{{ $ticket->status_color }} px-3 py-1 rounded-full text-xs font-bold">{{ $ticket->status_label }}</span>
                            <div class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 italic">No service history.</p>
                @endforelse
            </div>
        
        @elseif($activeTab === 'rma')
            <div class="p-6">
                @forelse($rmas as $rma)
                    <div class="border border-gray-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between">
                            <span class="font-bold text-indigo-600">{{ $rma->rma_number }}</span>
                            <span class="text-sm font-bold uppercase text-gray-500">{{ $rma->status }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Resolution: {{ $rma->resolution_type }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 italic">No RMA history.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
