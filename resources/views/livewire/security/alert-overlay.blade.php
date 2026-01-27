<div wire:poll.10s>
    @if($lockdownMode)
        <div class="fixed top-0 left-0 right-0 z-[100] bg-red-600 text-white px-4 py-2 shadow-xl flex items-center justify-between animate-pulse">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span class="font-black uppercase tracking-widest text-xs md:text-sm">SYSTEM LOCKDOWN ACTIVE - ACCESS RESTRICTED</span>
            </div>
            <a href="{{ route('admin.keamanan.dashboard') }}" class="px-3 py-1 bg-white text-red-600 rounded text-xs font-bold hover:bg-red-50">MANAGE</a>
        </div>
    @endif
</div>
