<div class="hidden md:flex flex-col w-72 bg-white dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 h-screen fixed left-0 top-0 z-40 transition-all duration-300 font-sans shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    
    <!-- 1. Header & Logo -->
    <div class="h-20 flex items-center px-6 border-b border-slate-100 dark:border-slate-800/50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl sticky top-0 z-20">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            <div class="flex flex-col">
                <h1 class="text-xl font-black font-tech tracking-tight text-slate-800 dark:text-white uppercase leading-none group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                    Yala
                </h1>
                <span class="text-[10px] font-bold tracking-[0.2em] text-slate-400 dark:text-slate-500 uppercase">System v2.5</span>
            </div>
        </div>
    </div>

    <!-- 2. Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar scroll-smooth">
        @php
            // Palet warna untuk setiap bagian menu agar colorful dan mudah dibedakan
            $sectionColors = [
                'indigo',   // Utama
                'blue',     // Operasional
                'amber',    // Inventaris
                'emerald',  // B2B
                'rose',     // SDM
                'fuchsia',  // Marketing
                'cyan',     // Sistem
            ];
        @endphp

        @foreach(config('nav.menu') as $index => $section)
            @php
                $color = $sectionColors[$index] ?? 'slate';
                $colorClasses = match($color) {
                    'indigo' => 'text-indigo-600 bg-indigo-50 dark:bg-indigo-500/10 dark:text-indigo-400',
                    'blue' => 'text-blue-600 bg-blue-50 dark:bg-blue-500/10 dark:text-blue-400',
                    'amber' => 'text-amber-600 bg-amber-50 dark:bg-amber-500/10 dark:text-amber-400',
                    'emerald' => 'text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400',
                    'rose' => 'text-rose-600 bg-rose-50 dark:bg-rose-500/10 dark:text-rose-400',
                    'fuchsia' => 'text-fuchsia-600 bg-fuchsia-50 dark:bg-fuchsia-500/10 dark:text-fuchsia-400',
                    'cyan' => 'text-cyan-600 bg-cyan-50 dark:bg-cyan-500/10 dark:text-cyan-400',
                    default => 'text-slate-600 bg-slate-50 dark:bg-slate-500/10 dark:text-slate-400',
                };
            @endphp

            <div class="space-y-2">
                <!-- Section Title -->
                <div class="px-3 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500"></span>
                    <h3 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest font-tech">
                        {{ $section['title'] }}
                    </h3>
                </div>
                
                <ul class="space-y-1.5">
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
                                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group relative overflow-hidden
                                            {{ (collect($item['sub_menu'])->contains(fn($sub) => request()->routeIs($sub['route']))) 
                                                ? 'bg-slate-100 dark:bg-white/5 text-slate-900 dark:text-white' 
                                                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' 
                                            }}">
                                        
                                        <div class="flex items-center gap-3 z-10">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-300 {{ $colorClasses }}">
                                                @include('components.icons.' . ($item['icon'] ?? 'circle'), ['class' => 'w-4 h-4'])
                                            </div>
                                            <span>{{ $item['label'] }}</span>
                                        </div>

                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 z-10" :class="expanded ? 'rotate-90 text-{{ $color }}-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>

                                    <!-- Submenu Items -->
                                    <div x-show="expanded" x-collapse 
                                         class="mt-1 ml-7 pl-4 border-l-2 border-slate-100 dark:border-slate-800 space-y-1">
                                        @foreach($item['sub_menu'] as $subItem)
                                            <a href="{{ route($subItem['route']) }}" 
                                               class="block px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 relative
                                               {{ request()->routeIs($subItem['route']) 
                                                    ? 'text-' . $color . '-600 dark:text-' . $color . '-400 bg-' . $color . '-50 dark:bg-' . $color . '-500/10 translate-x-1' 
                                                    : 'text-slate-500 dark:text-slate-500 hover:text-slate-900 dark:hover:text-slate-300 hover:translate-x-1' 
                                               }}">
                                                {{ $subItem['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Single Menu Item -->
                                    <a href="{{ route($item['route']) }}" 
                                       target="{{ $item['target'] ?? '_self' }}"
                                       class="relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group overflow-hidden
                                       {{ request()->routeIs($item['route']) 
                                            ? 'bg-gradient-to-r from-' . $color . '-600 to-' . $color . '-500 text-white shadow-lg shadow-' . $color . '-500/25' 
                                            : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' 
                                       }}">
                                        
                                        <!-- Icon Box -->
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-300 
                                            {{ request()->routeIs($item['route']) 
                                                ? 'bg-white/20 text-white' 
                                                : $colorClasses 
                                            }}">
                                            @include('components.icons.' . ($item['icon'] ?? 'circle'), ['class' => 'w-4 h-4'])
                                        </div>

                                        <span class="z-10">{{ $item['label'] }}</span>

                                        @if(request()->routeIs($item['route']))
                                            <div class="absolute right-3 w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>
                                        @endif
                                    </a>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endforeach
        
        <!-- Bottom Spacer -->
        <div class="h-20"></div>
    </nav>

    <!-- 3. User Profile Footer -->
    <div class="absolute bottom-0 left-0 w-full p-4 bg-white/90 dark:bg-slate-950/90 backdrop-blur-md border-t border-slate-100 dark:border-slate-800">
        <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded-xl border border-slate-100 dark:border-slate-800 flex items-center gap-3 shadow-sm group hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-colors">
            <div class="relative">
                <div class="w-10 h-10 rounded-lg bg-slate-200 dark:bg-slate-800 overflow-hidden ring-2 ring-white dark:ring-slate-700 group-hover:ring-indigo-500 transition-all">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-900 text-white font-bold text-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full"></div>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate group-hover:text-indigo-600 transition-colors">
                    {{ auth()->user()->name }}
                </p>
                <div class="flex items-center gap-1.5">
                    <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                        {{ auth()->user()->role ?? 'Staff' }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('keluar') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-all" title="Keluar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>