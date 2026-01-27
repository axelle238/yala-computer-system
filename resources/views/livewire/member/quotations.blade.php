<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-black font-tech text-slate-900 dark:text-white uppercase tracking-tighter">
                Pusat <span class="text-blue-600">Penawaran</span>
            </h1>
            <a href="{{ route('anggota.beranda') }}" class="text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold text-sm">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-1 rounded-xl bg-slate-200/50 dark:bg-slate-800/50 p-1 mb-6 w-fit mx-auto md:mx-0">
            <button wire:click="$set('activeTab', 'received')" class="w-full rounded-lg py-2.5 px-6 text-sm font-bold leading-5 ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 {{ $activeTab === 'received' ? 'bg-white dark:bg-slate-700 text-blue-700 dark:text-white shadow' : 'text-slate-600 dark:text-slate-400 hover:bg-white/[0.12] hover:text-white' }}">
                Dari Toko (Beli)
            </button>
            <button wire:click="$set('activeTab', 'sent')" class="w-full rounded-lg py-2.5 px-6 text-sm font-bold leading-5 ring-white ring-opacity-60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2 {{ $activeTab === 'sent' ? 'bg-white dark:bg-slate-700 text-blue-700 dark:text-white shadow' : 'text-slate-600 dark:text-slate-400 hover:bg-white/[0.12] hover:text-white' }}">
                Saya Tawarkan (Jual)
            </button>
        </div>

        @if($activeTab === 'received')
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <tr>
                                <th class="p-6">Nomor Penawaran</th>
                                <th class="p-6">Tanggal</th>
                                <th class="p-6">Status</th>
                                <th class="p-6 text-right">Total Nilai</th>
                                <th class="p-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                            @forelse($quotations as $quote)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="p-6 font-mono font-bold text-slate-800 dark:text-white">
                                        {{ $quote->quotation_number }}
                                    </td>
                                    <td class="p-6 text-slate-500">
                                        {{ $quote->created_at->format('d M Y') }}
                                    </td>
                                    <td class="p-6">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-700',
                                                'approved' => 'bg-blue-100 text-blue-700',
                                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                                'rejected' => 'bg-red-100 text-red-700',
                                            ];
                                            $status = $quote->approval_status === 'pending' ? 'pending' : $quote->approval_status;
                                            if ($quote->converted_order_id) $status = 'accepted';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $colors[$status] ?? 'bg-slate-100' }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="p-6 text-right font-bold text-slate-800 dark:text-white">
                                        Rp {{ number_format($quote->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="p-6 text-center">
                                        <button class="text-blue-600 hover:text-blue-800 font-bold hover:underline opacity-50 cursor-not-allowed" title="Coming Soon">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400">
                                        Belum ada permintaan penawaran dari toko.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($quotations->hasPages())
                    <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                        {{ $quotations->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Sent Offers (Jual) -->
            <div class="space-y-6 animate-fade-in">
                <div class="flex justify-end">
                    <button wire:click="openCreateOfferModal" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Ajukan Penawaran Baru
                    </button>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                <tr>
                                    <th class="p-6">Produk</th>
                                    <th class="p-6">Kondisi</th>
                                    <th class="p-6">Ekspektasi Harga</th>
                                    <th class="p-6">Status</th>
                                    <th class="p-6">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-sm">
                                @forelse($offers as $offer)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                        <td class="p-6 font-bold text-slate-800 dark:text-white">
                                            <div class="flex items-center gap-3">
                                                @if($offer->image_path)
                                                    <img src="{{ asset('storage/'.$offer->image_path) }}" class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold">{{ $offer->product_name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $offer->brand }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-6">
                                            <span class="text-xs font-bold px-2 py-1 bg-slate-100 rounded text-slate-600">{{ $offer->condition_label }}</span>
                                        </td>
                                        <td class="p-6 font-mono font-bold text-slate-700 dark:text-slate-300">
                                            Rp {{ number_format($offer->expected_price, 0, ',', '.') }}
                                        </td>
                                        <td class="p-6">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $offer->status_color }}">
                                                {{ $offer->status_label }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-slate-500">
                                            {{ $offer->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center text-slate-400">
                                            Anda belum mengajukan penawaran produk apapun.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($offers->hasPages())
                        <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                            {{ $offers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Create Offer Modal -->
    <div x-data="{ show: @entangle('showCreateOfferModal') }" 
         x-show="show" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" 
                 aria-hidden="true"
                 @click="show = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white mb-4" id="modal-title">
                                Ajukan Penawaran Produk
                            </h3>
                            
                            <form wire:submit.prevent="submitOffer" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Produk</label>
                                    <input wire:model="offer_product_name" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-blue-500">
                                    @error('offer_product_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Merek (Opsional)</label>
                                        <input wire:model="offer_brand" type="text" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Kondisi</label>
                                        <select wire:model="offer_condition" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-blue-500">
                                            <option value="new">Baru (Segel)</option>
                                            <option value="used_like_new">Bekas (Seperti Baru)</option>
                                            <option value="used_good">Bekas (Layak Pakai)</option>
                                            <option value="used_fair">Bekas (Perlu Perbaikan)</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Ekspektasi Harga</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 text-sm">Rp</span>
                                        <input wire:model="offer_price" type="number" class="w-full pl-10 rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-blue-500">
                                    </div>
                                    @error('offer_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi / Kelengkapan</label>
                                    <textarea wire:model="offer_description" rows="3" class="w-full rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-sm focus:ring-blue-500" placeholder="Contoh: Lengkap dengan dus, garansi habis..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Foto Produk</label>
                                    <input wire:model="offer_image" type="file" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @if ($offer_image)
                                        <img src="{{ $offer_image->temporaryUrl() }}" class="mt-2 h-20 rounded-lg object-cover">
                                    @endif
                                    @error('offer_image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="submitOffer" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Kirim Penawaran
                    </button>
                    <button wire:click="$set('showCreateOfferModal', false)" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>