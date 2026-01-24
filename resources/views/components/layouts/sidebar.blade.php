<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col h-full shadow-2xl md:shadow-none flex-shrink-0">
    <!-- Brand -->
    <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-100 dark:border-slate-800 flex-shrink-0">
        @if(\App\Models\Setting::get('store_logo'))
            <img src="{{ asset('storage/' . \App\Models\Setting::get('store_logo')) }}" class="w-10 h-10 object-contain">
        @else
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-black text-xl font-tech shadow-lg shadow-indigo-500/30">Y</div>
        @endif
        <div>
            <h1 class="font-bold text-lg text-slate-900 dark:text-white leading-none tracking-tight">{{ \App\Models\Setting::get('store_name', 'YALA SYSTEM') }}</h1>
            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1">Admin Panel</p>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden ml-auto text-slate-500 hover:text-slate-700">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        @foreach(config('nav.menu') as $group)
            {{-- Role & Permission Check for Group --}}
            @php
                $hasGroupAccess = false;
                foreach($group['items'] as $item) {
                    // Check Role Legacy OR Permission DB
                    if(in_array(auth()->user()->role, $item['roles']) || (isset($item['permission']) && auth()->user()->hasPermissionTo($item['permission']))) {
                        $hasGroupAccess = true;
                        break;
                    }
                }
            @endphp

            @if($hasGroupAccess)
                <div>
                    <h3 class="px-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">
                        {{ $group['title'] }}
                    </h3>
                    <div class="space-y-1">
                        @foreach($group['items'] as $item)
                            @if(in_array(auth()->user()->role, $item['roles']) || (isset($item['permission']) && auth()->user()->hasPermissionTo($item['permission'])))
                                <a href="{{ route($item['route']) }}" 
                                   @if(isset($item['target'])) target="{{ $item['target'] }}" @endif
                                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all group
                                   {{ request()->routeIs($item['route'].'*') 
                                        ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' 
                                        : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' 
                                   }}">
                                    
                                    <span class="w-5 h-5 flex-shrink-0 {{ request()->routeIs($item['route'].'*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 group-hover:text-slate-600' }}">
                                        @include('components.icons.' . $item['icon'])
                                    </span>
                                    
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center font-bold text-indigo-700 dark:text-indigo-300">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 truncate capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
