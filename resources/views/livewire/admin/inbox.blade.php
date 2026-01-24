<div class="h-[calc(100vh-8rem)] flex overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">
    <!-- Sidebar List -->
    <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
        <div class="p-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-800">Pesan Masuk</h2>
            <input type="text" placeholder="Cari pelanggan..." class="mt-2 w-full text-sm rounded-lg border-gray-300">
        </div>
        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $convo)
                <button wire:click="selectConversation({{ $convo->id }})" class="w-full text-left p-4 hover:bg-white border-b border-gray-100 transition-colors {{ $activeConversation == $convo->id ? 'bg-white border-l-4 border-l-indigo-500' : '' }}">
                    <div class="flex justify-between mb-1">
                        <span class="font-bold text-sm text-gray-900">{{ $convo->name }}</span>
                        <span class="text-xs text-gray-400">12:30</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">Klik untuk melihat percakapan...</p>
                </button>
            @empty
                <div class="p-8 text-center text-gray-400 text-sm">Belum ada pesan.</div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col bg-white">
        @if($activeConversation)
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-800">Nama Pelanggan</h3>
                    <p class="text-xs text-green-500 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Online</p>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                <!-- Messages Placeholder -->
                <div class="flex justify-start">
                    <div class="bg-white p-3 rounded-lg rounded-tl-none shadow-sm max-w-md text-sm text-gray-700">
                        Halo admin, apakah stok laptop ASUS TUF masih ada?
                        <span class="text-[10px] text-gray-400 block mt-1">10:00 AM</span>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="bg-indigo-600 p-3 rounded-lg rounded-tr-none shadow-sm max-w-md text-sm text-white">
                        Halo kak, stok masih tersedia banyak. Silakan diorder!
                        <span class="text-[10px] text-indigo-200 block mt-1">10:05 AM</span>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 bg-white">
                <form wire:submit.prevent="sendReply" class="flex gap-2">
                    <input wire:model="replyMessage" type="text" class="flex-1 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ketik balasan...">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    </button>
                </form>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400">
                <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                <p>Pilih percakapan untuk memulai chat.</p>
            </div>
        @endif
    </div>
</div>