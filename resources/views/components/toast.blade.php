<!-- Floating Toast Notification System (CodingLab / Modern Pill Toast Style) -->
<div x-data="{
        clientToasts: [],
        add(detail) {
            const id = Date.now() + Math.random();
            const toast = {
                id,
                type: detail.type || 'success',
                title: detail.title || (detail.type === 'error' ? 'Error' : (detail.type === 'warning' ? 'Warning' : (detail.type === 'info' ? 'Info' : 'Success'))),
                message: detail.message || '',
                timeout: null
            };
            this.clientToasts.push(toast);
            this.startTimer(toast);
        },
        remove(id) {
            this.clientToasts = this.clientToasts.filter(t => t.id !== id);
        },
        startTimer(toast) {
            toast.timeout = setTimeout(() => {
                this.remove(toast.id);
            }, 4500);
        },
        pauseTimer(toast) {
            if (toast.timeout) clearTimeout(toast.timeout);
        },
        resumeTimer(toast) {
            toast.timeout = setTimeout(() => {
                this.remove(toast.id);
            }, 2000);
        }
    }"
    @toast.window="add($event.detail)"
    class="fixed top-5 right-5 sm:top-6 sm:right-6 z-[9999] flex flex-col gap-3 pointer-events-none max-w-sm w-[calc(100%-2.5rem)]">

    {{-- Server-Side Session Success Toast --}}
    @if (session('success'))
        <div x-data="{ 
                show: true,
                timeout: null,
                init() {
                    this.timeout = setTimeout(() => { this.show = false }, 4500);
                }
             }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 sm:translate-x-8"
             @mouseenter="clearTimeout(timeout)"
             @mouseleave="timeout = setTimeout(() => { show = false }, 2000)"
             class="toast-card relative flex items-center justify-between gap-3.5 bg-white rounded-xl sm:rounded-2xl border border-slate-100 border-l-[6px] border-l-[#059669] p-4 sm:p-4.5 shadow-[0_10px_30px_rgba(0,0,0,0.08)] overflow-hidden pointer-events-auto">
            
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Emerald Circle Icon -->
                <div class="w-9 h-9 rounded-full bg-[#059669] text-white flex items-center justify-center shrink-0 shadow-sm shadow-[#059669]/25">
                    <i class="fa-solid fa-check text-sm font-bold"></i>
                </div>

                <!-- Text Block -->
                <div class="flex flex-col min-w-0 pr-2">
                    <span class="text-sm font-bold text-slate-800 leading-tight">Success</span>
                    <span class="text-xs text-slate-500 font-normal leading-normal mt-0.5 break-words">{{ session('success') }}</span>
                </div>
            </div>

            <!-- Close Button -->
            <button @click="show = false" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer shrink-0 self-start -mt-0.5 -mr-1" aria-label="Close">
                <i class="fa-solid fa-xmark text-xs sm:text-sm"></i>
            </button>

            <!-- Bottom Animated Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-slate-100">
                <div class="h-full bg-[#059669] animate-toast-progress"></div>
            </div>
        </div>
    @endif

    {{-- Server-Side Session Error Toast --}}
    @if (session('error'))
        <div x-data="{ 
                show: true,
                timeout: null,
                init() {
                    this.timeout = setTimeout(() => { this.show = false }, 5000);
                }
             }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 sm:translate-x-8"
             @mouseenter="clearTimeout(timeout)"
             @mouseleave="timeout = setTimeout(() => { show = false }, 2000)"
             class="toast-card relative flex items-center justify-between gap-3.5 bg-white rounded-xl sm:rounded-2xl border border-slate-100 border-l-[6px] border-l-rose-500 p-4 sm:p-4.5 shadow-[0_10px_30px_rgba(0,0,0,0.08)] overflow-hidden pointer-events-auto">
            
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Rose Circle Icon -->
                <div class="w-9 h-9 rounded-full bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-rose-500/25">
                    <i class="fa-solid fa-xmark text-sm font-bold"></i>
                </div>

                <!-- Text Block -->
                <div class="flex flex-col min-w-0 pr-2">
                    <span class="text-sm font-bold text-slate-800 leading-tight">Error</span>
                    <span class="text-xs text-slate-500 font-normal leading-normal mt-0.5 break-words">{{ session('error') }}</span>
                </div>
            </div>

            <!-- Close Button -->
            <button @click="show = false" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer shrink-0 self-start -mt-0.5 -mr-1" aria-label="Close">
                <i class="fa-solid fa-xmark text-xs sm:text-sm"></i>
            </button>

            <!-- Bottom Animated Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-slate-100">
                <div class="h-full bg-rose-500 animate-toast-progress"></div>
            </div>
        </div>
    @endif

    {{-- Server-Side Session Warning Toast --}}
    @if (session('warning'))
        <div x-data="{ 
                show: true,
                timeout: null,
                init() {
                    this.timeout = setTimeout(() => { this.show = false }, 4500);
                }
             }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 sm:translate-x-8"
             @mouseenter="clearTimeout(timeout)"
             @mouseleave="timeout = setTimeout(() => { show = false }, 2000)"
             class="toast-card relative flex items-center justify-between gap-3.5 bg-white rounded-xl sm:rounded-2xl border border-slate-100 border-l-[6px] border-l-amber-500 p-4 sm:p-4.5 shadow-[0_10px_30px_rgba(0,0,0,0.08)] overflow-hidden pointer-events-auto">
            
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Amber Circle Icon -->
                <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-500/25">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                </div>

                <!-- Text Block -->
                <div class="flex flex-col min-w-0 pr-2">
                    <span class="text-sm font-bold text-slate-800 leading-tight">Warning</span>
                    <span class="text-xs text-slate-500 font-normal leading-normal mt-0.5 break-words">{{ session('warning') }}</span>
                </div>
            </div>

            <!-- Close Button -->
            <button @click="show = false" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer shrink-0 self-start -mt-0.5 -mr-1" aria-label="Close">
                <i class="fa-solid fa-xmark text-xs sm:text-sm"></i>
            </button>

            <!-- Bottom Animated Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-slate-100">
                <div class="h-full bg-amber-500 animate-toast-progress"></div>
            </div>
        </div>
    @endif

    {{-- Server-Side Session Status/Info Toast --}}
    @if (session('status') || session('info'))
        <div x-data="{ 
                show: true,
                timeout: null,
                init() {
                    this.timeout = setTimeout(() => { this.show = false }, 4500);
                }
             }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 sm:translate-x-8"
             @mouseenter="clearTimeout(timeout)"
             @mouseleave="timeout = setTimeout(() => { show = false }, 2000)"
             class="toast-card relative flex items-center justify-between gap-3.5 bg-white rounded-xl sm:rounded-2xl border border-slate-100 border-l-[6px] border-l-sky-500 p-4 sm:p-4.5 shadow-[0_10px_30px_rgba(0,0,0,0.08)] overflow-hidden pointer-events-auto">
            
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Sky Circle Icon -->
                <div class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-sky-500/25">
                    <i class="fa-solid fa-circle-info text-sm font-bold"></i>
                </div>

                <!-- Text Block -->
                <div class="flex flex-col min-w-0 pr-2">
                    <span class="text-sm font-bold text-slate-800 leading-tight">Notice</span>
                    <span class="text-xs text-slate-500 font-normal leading-normal mt-0.5 break-words">{{ session('status') ?? session('info') }}</span>
                </div>
            </div>

            <!-- Close Button -->
            <button @click="show = false" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer shrink-0 self-start -mt-0.5 -mr-1" aria-label="Close">
                <i class="fa-solid fa-xmark text-xs sm:text-sm"></i>
            </button>

            <!-- Bottom Animated Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-slate-100">
                <div class="h-full bg-sky-500 animate-toast-progress"></div>
            </div>
        </div>
    @endif

    {{-- Client-Side Dispatched Toasts (via window.dispatchEvent(new CustomEvent('toast', ...))) --}}
    <template x-for="t in clientToasts" :key="t.id">
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 scale-100 sm:translate-x-0"
             x-transition:leave-end="opacity-0 scale-95 sm:translate-x-8"
             @mouseenter="pauseTimer(t)"
             @mouseleave="resumeTimer(t)"
             class="toast-card relative flex items-center justify-between gap-3.5 bg-white rounded-xl sm:rounded-2xl border border-slate-100 p-4 sm:p-4.5 shadow-[0_10px_30px_rgba(0,0,0,0.08)] overflow-hidden pointer-events-auto"
             :class="{
                 'border-l-[6px] border-l-[#059669]': t.type === 'success',
                 'border-l-[6px] border-l-rose-500': t.type === 'error',
                 'border-l-[6px] border-l-amber-500': t.type === 'warning',
                 'border-l-[6px] border-l-sky-500': t.type === 'info'
             }">

            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Icon Circle -->
                <div class="w-9 h-9 rounded-full text-white flex items-center justify-center shrink-0 shadow-sm"
                     :class="{
                         'bg-[#059669] shadow-[#059669]/25': t.type === 'success',
                         'bg-rose-500 shadow-rose-500/25': t.type === 'error',
                         'bg-amber-500 shadow-amber-500/25': t.type === 'warning',
                         'bg-sky-500 shadow-sky-500/25': t.type === 'info'
                     }">
                    <template x-if="t.type === 'success'">
                        <i class="fa-solid fa-check text-sm font-bold"></i>
                    </template>
                    <template x-if="t.type === 'error'">
                        <i class="fa-solid fa-xmark text-sm font-bold"></i>
                    </template>
                    <template x-if="t.type === 'warning'">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    </template>
                    <template x-if="t.type === 'info'">
                        <i class="fa-solid fa-circle-info text-sm font-bold"></i>
                    </template>
                </div>

                <!-- Text Block -->
                <div class="flex flex-col min-w-0 pr-2">
                    <span class="text-sm font-bold text-slate-800 leading-tight" x-text="t.title"></span>
                    <span class="text-xs text-slate-500 font-normal leading-normal mt-0.5 break-words" x-text="t.message"></span>
                </div>
            </div>

            <!-- Close Button -->
            <button @click="remove(t.id)" type="button" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer shrink-0 self-start -mt-0.5 -mr-1" aria-label="Close">
                <i class="fa-solid fa-xmark text-xs sm:text-sm"></i>
            </button>

            <!-- Bottom Animated Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-slate-100">
                <div class="h-full animate-toast-progress"
                     :class="{
                         'bg-[#059669]': t.type === 'success',
                         'bg-rose-500': t.type === 'error',
                         'bg-amber-500': t.type === 'warning',
                         'bg-sky-500': t.type === 'info'
                     }"></div>
            </div>
        </div>
    </template>
</div>
