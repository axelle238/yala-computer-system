<div wire:poll.10s class="flex items-center gap-2 px-3 py-2">
    <div class="relative flex h-3 w-3">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $status == 'safe' ? 'bg-emerald-400' : ($status == 'warning' ? 'bg-amber-400' : 'bg-red-400') }}"></span>
      <span class="relative inline-flex rounded-full h-3 w-3 {{ $status == 'safe' ? 'bg-emerald-500' : ($status == 'warning' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
    </div>
    <span class="text-[10px] font-black uppercase tracking-widest {{ $status == 'safe' ? 'text-emerald-500' : ($status == 'warning' ? 'text-amber-500' : 'text-red-500') }}">
        {{ $status == 'safe' ? 'SECURE' : ($status == 'warning' ? 'ALERT' : 'LOCKDOWN') }}
    </span>
</div>
