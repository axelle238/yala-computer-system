<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Review Manager</h1>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 uppercase text-xs font-semibold text-gray-700">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4">Comment</th>
                    <th class="px-6 py-4 text-center">Approved</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($reviews as $review)
                    <tr>
                        <td class="px-6 py-4 font-bold">{{ $review->user->name }}</td>
                        <td class="px-6 py-4">{{ $review->product->name }}</td>
                        <td class="px-6 py-4 text-yellow-500 font-bold">{{ $review->rating }} ★</td>
                        <td class="px-6 py-4 truncate max-w-xs">{{ $review->comment }}</td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleApproval({{ $review->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $review->is_approved ? 'bg-green-500' : 'bg-gray-200' }}">
                                <span class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $review->is_approved ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $reviews->links() }}</div>
    </div>
</div>
