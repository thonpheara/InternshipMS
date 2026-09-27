<x-layout>
    <div class="space-y-6">

        <!-- Page Header & Filters -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Internship Vacancies</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Explore university-vetted opportunities from registered host companies.</p>
            </div>

            <!-- Search & Filters Form -->
            <form action="{{ route('student.posts.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <div class="relative min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass w-4 h-4"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search role, skills, company..."
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Custom Styled Category Filter Dropdown -->
                <div class="relative"
                     x-data="{
                         open: false,
                         catVal: '{{ request('category', '') }}',
                         options: [
                             { value: '', label: 'All Categories', icon: 'fa-solid fa-layer-group text-slate-400' },
                             { value: 'General IT', label: 'General IT', icon: 'fa-solid fa-laptop-code text-blue-500' },
                             { value: 'Software Engineering', label: 'Software Engineering', icon: 'fa-solid fa-code text-emerald-500' },
                             { value: 'Networking & Security', label: 'Networking & Security', icon: 'fa-solid fa-shield-halved text-rose-500' },
                             { value: 'Design & UI/UX', label: 'Design & UI/UX', icon: 'fa-solid fa-palette text-purple-500' },
                             { value: 'Business Administration', label: 'Business Administration', icon: 'fa-solid fa-briefcase text-amber-500' }
                         ],
                         get currentOption() {
                             return this.options.find(o => o.value === this.catVal) || this.options[0];
                         },
                         selectCategory(val) {
                             this.catVal = val;
                             this.open = false;
                             $nextTick(() => {
                                 $el.closest('form').submit();
                             });
                         }
                     }">
                    <input type="hidden" name="category" :value="catVal">
                    <button type="button" 
                            @click="open = !open" 
                            class="inline-flex items-center justify-between gap-2.5 py-2 pl-3.5 pr-3 text-xs rounded-xl bg-white hover:bg-slate-50 border border-slate-200 hover:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-medium text-slate-700 transition-all cursor-pointer shadow-2xs min-w-[160px]">
                        <span class="flex items-center gap-2 truncate">
                            <i :class="currentOption.icon" class="text-xs"></i>
                            <span class="font-semibold text-slate-800 truncate" x-text="currentOption.label"></span>
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200 shrink-0"
                           :class="open ? 'rotate-180 text-emerald-600' : ''"></i>
                    </button>

                    <div x-show="open" 
                         x-cloak
                         @click.outside="open = false"
                         @keydown.escape.window="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 sm:left-0 top-full z-40 mt-1.5 w-56 rounded-2xl bg-white border border-slate-200 shadow-xl p-1.5 space-y-0.5"
                         style="display: none;">
                        <template x-for="item in options" :key="item.value">
                            <button type="button" 
                                    @click="selectCategory(item.value)" 
                                    class="w-full flex items-center justify-between px-3 py-2 text-xs rounded-xl transition-all cursor-pointer text-left"
                                    :class="catVal === item.value ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'">
                                <div class="flex items-center gap-2.5 truncate">
                                    <i :class="item.icon" class="text-xs shrink-0"></i>
                                    <span class="truncate" x-text="item.label"></span>
                                </div>
                                <i x-show="catVal === item.value" class="fa-solid fa-check text-[10px] text-emerald-600 shrink-0"></i>
                            </button>
                        </template>
                    </div>
                </div>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Post Cards Grid -->
        @if ($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($posts as $post)
                    <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                @if ($post->category)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $post->category }}
                                    </span>
                                @else
                                    <div></div>
                                @endif
                                <span class="text-xs font-bold text-emerald-600">
                                    @if ($post->stipend)
                                        ${{ number_format($post->stipend, 0) }}/mo
                                    @else
                                        Standard Stipend
                                    @endif
                                </span>
                            </div>

                            <div>
                                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors line-clamp-1">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-xs font-semibold text-slate-600 mt-0.5">
                                    {{ $post->companyProfile->company_name }}
                                </p>
                            </div>

                            <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                {{ $post->description }}
                            </p>
                        </div>

                        <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="text-[11px] text-slate-600">
                                <span><i class="fa-solid fa-location-dot w-3.5 h-3.5 inline text-slate-400"></i> {{ $post->location }}</span>
                                <span class="block text-slate-600 font-medium mt-0.5">Deadline: {{ \Carbon\Carbon::parse($post->deadline)->format('M d') }}</span>
                            </div>

                            <a href="{{ route('student.posts.show', $post) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 group-hover:bg-emerald-600 group-hover:text-white text-slate-700 text-xs font-bold transition-all flex items-center gap-1">
                                <span>Details</span>
                                <i class="fa-solid fa-chevron-right w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <div class="p-12 rounded-3xl bg-white border border-slate-200/80 text-center space-y-3">
                <i class="fa-solid fa-magnifying-glass-minus w-10 h-10 mx-auto text-slate-300"></i>
                <h3 class="text-base font-bold text-slate-800">No internship listings match your filter</h3>
                <p class="text-xs text-slate-600 max-w-sm mx-auto">Try clearing your search query or selecting "All Categories" to view available positions.</p>
                <a href="{{ route('student.posts.index') }}" class="inline-block px-4 py-2 rounded-xl bg-slate-100 text-xs font-bold text-slate-700 hover:bg-slate-200">
                    Reset Filter
                </a>
            </div>
        @endif

    </div>
</x-layout>
