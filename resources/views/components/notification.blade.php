<div 
    x-data="{ 
        notifications: [],
        add(e) {
            this.notifications.push({
                id: Date.now(),
                message: e.detail.message,
                type: e.detail.type || 'success',
                show: true
            });
        },
        remove(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 400); // Wait for transition out
            }
        }
    }"
    x-on:notify.window="add($event)"
    class="fixed top-24 right-4 z-[100] flex flex-col gap-3 pointer-events-none w-full max-w-sm"
>
    <template x-for="notif in notifications" :key="notif.id">
        <div 
            x-show="notif.show"
            x-init="setTimeout(() => remove(notif.id), 4000)"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-x-8 scale-90"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-8 scale-90"
            class="pointer-events-auto relative group overflow-hidden rounded-xl p-4 backdrop-blur-xl border shadow-2xl transition-all"
            :class="{
                'bg-emerald-50/90 dark:bg-emerald-900/80 border-emerald-200 dark:border-emerald-700': notif.type === 'success',
                'bg-rose-50/90 dark:bg-rose-900/80 border-rose-200 dark:border-rose-700': notif.type === 'error',
                'bg-blue-50/90 dark:bg-blue-900/80 border-blue-200 dark:border-blue-700': notif.type === 'info',
                'bg-amber-50/90 dark:bg-amber-900/80 border-amber-200 dark:border-amber-700': notif.type === 'warning'
            }"
        >
            <!-- Glow Effect -->
            <div class="absolute inset-0 opacity-20 group-hover:opacity-40 transition-opacity bg-gradient-to-r"
                 :class="{
                    'from-emerald-400 to-cyan-400': notif.type === 'success',
                    'from-rose-400 to-red-500': notif.type === 'error',
                    'from-blue-400 to-indigo-500': notif.type === 'info',
                    'from-amber-400 to-orange-500': notif.type === 'warning'
                 }"
            ></div>

            <div class="relative flex items-start gap-3">
                <!-- Icon -->
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="notif.type === 'success'">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-800 flex items-center justify-center text-emerald-600 dark:text-emerald-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                    </template>
                    <template x-if="notif.type === 'error'">
                        <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-800 flex items-center justify-center text-rose-600 dark:text-rose-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                    </template>
                    <template x-if="notif.type === 'info'">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center text-blue-600 dark:text-blue-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </template>
                    <template x-if="notif.type === 'warning'">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-800 flex items-center justify-center text-amber-600 dark:text-amber-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                    </template>
                </div>

                <!-- Text -->
                <div class="flex-1">
                    <h4 class="text-sm font-bold uppercase tracking-wider mb-0.5"
                        :class="{
                            'text-emerald-800 dark:text-emerald-200': notif.type === 'success',
                            'text-rose-800 dark:text-rose-200': notif.type === 'error',
                            'text-blue-800 dark:text-blue-200': notif.type === 'info',
                            'text-amber-800 dark:text-amber-200': notif.type === 'warning'
                        }"
                        x-text="notif.type === 'success' ? 'Berhasil' : (notif.type === 'error' ? 'Kesalahan' : (notif.type === 'info' ? 'Informasi' : 'Peringatan'))"
                    ></h4>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-300 leading-snug" x-text="notif.message"></p>
                </div>

                <!-- Close Button -->
                <button @click="remove(notif.id)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-gradient-to-r w-full origin-left animate-shrink"
                 :class="{
                    'from-emerald-400 to-cyan-400': notif.type === 'success',
                    'from-rose-400 to-red-500': notif.type === 'error',
                    'from-blue-400 to-indigo-500': notif.type === 'info',
                    'from-amber-400 to-orange-500': notif.type === 'warning'
                 }"
                 style="animation-duration: 4000ms; animation-timing-function: linear;"
            ></div>
        </div>
    </template>

    <style>
        @keyframes shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
        .animate-shrink {
            animation-name: shrink;
        }
    </style>
</div>
