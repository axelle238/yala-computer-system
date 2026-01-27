<div class="hidden md:flex flex-col w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 h-screen fixed left-0 top-0 z-40 transition-all duration-300">
    <!-- Brand Logo -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-slate-800/50 bg-white dark:bg-slate-900 sticky top-0 z-20">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/20">
                Y
            </div>
            <h1 class="text-xl font-black font-tech tracking-tight text-slate-800 dark:text-white uppercase">
                Yala <span class="text-indigo-600 dark:text-indigo-400">Comp</span>
            </h1>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        @foreach(config('nav.menu') as $section)
            <div class="space-y-2">
                <h3 class="px-3 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider font-tech">
                    {{ $section['title'] }}
                </h3>
                
                <ul class="space-y-1">
                    @foreach($section['items'] as $item)
                        <!-- Permission & Role Check -->
                        @if(
                            (empty($item['permission']) || auth()->user()->can($item['permission'])) &&
                            (empty($item['roles']) || auth()->user()->hasAnyRole($item['roles']))
                        )
                            <li x-data="{ expanded: {{ (isset($item['sub_menu']) && collect($item['sub_menu'])->contains(fn($sub) => request()->routeIs($sub['route']))) ? 'true' : 'false' }} }">
                                @if(isset($item['sub_menu']))
                                    <!-- Parent Menu with Dropdown -->
                                    <button @click="expanded = !expanded" 
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 group
                                            {{ (collect($item['sub_menu'])->contains(fn($sub) => request()->routeIs($sub['route']))) 
                                                ? 'bg-indigo-50 dark:bg-slate-800 text-indigo-700 dark:text-indigo-400' 
                                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' 
                                            }}">
                                        <div class="flex items-center gap-3">
                                            <!-- Icon Handling -->
                                            @include('components.icons.' . ($item['icon'] ?? 'circle'), ['class' => 'w-5 h-5 opacity-75'])
                                            <span>{{ $item['label'] }}</span>
                                        </div>
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    <!-- Submenu Items -->
                                    <div x-show="expanded" x-collapse class="mt-1 pl-10 space-y-1">
                                        @foreach($item['sub_menu'] as $subItem)
                                            <a href="{{ route($subItem['route']) }}" 
                                               class="block px-3 py-2 rounded-md text-xs font-medium transition-colors
                                               {{ request()->routeIs($subItem['route']) 
                                                    ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/10 border-l-2 border-indigo-600 dark:border-indigo-400' 
                                                    : 'text-slate-500 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-300' 
                                               }}">
                                                {{ $subItem['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Single Menu Item -->
                                    <a href="{{ route($item['route']) }}" 
                                       target="{{ $item['target'] ?? '_self' }}"
                                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 group
                                       {{ request()->routeIs($item['route']) 
                                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' 
                                            : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' 
                                       }}">
                                        @include('components.icons.' . ($item['icon'] ?? 'circle'), ['class' => request()->routeIs($item['route']) ? 'w-5 h-5 text-white' : 'w-5 h-5 opacity-75 group-hover:text-indigo-600 dark:group-hover:text-indigo-400'])
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-500 font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('keluar') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors" title="Keluar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>
