<!-- Post Internship Modal Component -->
<div x-show="createModalOpen"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', createModalOpen)"
     @keydown.escape.window="createModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-title">

    <!-- Modal Backdrop with Blur -->
    <div x-show="createModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="createModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Centered Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 lg:p-8">
        <div x-show="createModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative w-full max-w-3xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden my-6">

            <!-- Modal Header -->
            <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 via-slate-50/40 to-white flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/20 shrink-0">
                        <i class="fa-solid fa-briefcase text-lg"></i>
                    </div>
                    <div>
                        <h3 id="modal-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Post New Internship Vacancy
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            New listings will be reviewed by university faculty coordinators before going live.
                        </p>
                    </div>
                </div>
                <button type="button" 
                        @click="createModalOpen = false"
                        class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close (Esc)">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form action="{{ route('company.posts.store') }}" method="POST" id="create-post-modal-form" class="max-h-[75vh] overflow-y-auto px-6 py-6 sm:px-8 space-y-7">
                @csrf

                @if ($errors->any())
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                            <span>Please correct the errors below before submitting:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 pl-5 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ══════════ Section 1: Position Details ══════════ --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                            Position Details
                        </h4>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="modal-title-input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Position Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="modal-title-input" name="title"
                               value="{{ old('title') }}" required
                               placeholder="e.g. Web Developer Intern, IT Support Technician"
                               class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400 @error('title') border-rose-300 ring-1 ring-rose-300 @enderror">
                        @error('title')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category Custom Dropdown --}}
                    <div x-data="categoryDropdown('{{ old('category') }}')" @click.outside="open = false" class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Position Category <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="category" :value="selected">

                        {{-- Trigger Button --}}
                        <button type="button" @click="open = !open"
                                :class="open ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-white'"
                                class="w-full flex items-center justify-between px-4 py-3 border rounded-xl text-sm transition-all cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <span x-text="selectedIcon || '🔖'" class="text-base leading-none"></span>
                                <span x-text="selected || 'Select a position category...'"
                                      :class="selected ? 'text-slate-900 font-semibold' : 'text-slate-400'"></span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 :class="open ? 'rotate-180' : ''"
                                 class="text-slate-400 transition-transform duration-200">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute z-50 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/80 overflow-hidden"
                             style="display:none;">

                            {{-- Search Input --}}
                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                    </svg>
                                    <input type="text" x-model="search" @click.stop
                                           placeholder="Search categories..."
                                           class="w-full pl-8 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-700">
                                </div>
                            </div>

                            {{-- Options List --}}
                            <ul class="max-h-52 overflow-y-auto py-1">
                                <template x-for="cat in filtered" :key="cat.label">
                                    <li>
                                        <button type="button" @click="select(cat)"
                                                :class="selected === cat.label ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-xs sm:text-sm transition-colors text-left cursor-pointer">
                                            <span x-text="cat.icon" class="text-base w-5 text-center leading-none"></span>
                                            <span x-text="cat.label" class="flex-1"></span>
                                            <template x-if="selected === cat.label">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                     class="text-emerald-600 shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                            </template>
                                        </button>
                                    </li>
                                </template>
                                <li x-show="filtered.length === 0" class="px-4 py-3 text-xs text-slate-400 text-center">
                                    No categories match your search.
                                </li>
                            </ul>
                        </div>
                        @error('category')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Work Location --}}
                    <div>
                        <label for="modal-location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Work Location <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                            <input type="text" id="modal-location" name="location"
                                   value="{{ old('location') }}" required
                                   placeholder="e.g. Phnom Penh, Siem Reap"
                                   class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400 @error('location') border-rose-300 ring-1 ring-rose-300 @enderror">
                        </div>
                        @error('location')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ══════════ Section 2: Terms & Capacity ══════════ --}}
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                            Terms & Capacity
                        </h4>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label for="modal-duration" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Duration (wks) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="modal-duration" name="duration_weeks"
                                       value="{{ old('duration_weeks', 12) }}" required min="4" max="52"
                                       class="w-full pl-8 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                            @error('duration_weeks')
                                <p class="text-[10px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modal-slots" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Open Slots <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-users absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="modal-slots" name="slots"
                                       value="{{ old('slots', 1) }}" required min="1" max="50"
                                       class="w-full pl-8 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                            @error('slots')
                                <p class="text-[10px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="modal-stipend" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Stipend / Mo
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">$</span>
                                <input type="number" step="50" id="modal-stipend" name="stipend"
                                       value="{{ old('stipend') }}" placeholder="0"
                                       class="w-full pl-7 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400">
                            </div>
                        </div>

                        <div>
                            <label for="modal-deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Deadline <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="date" id="modal-deadline" name="deadline"
                                       value="{{ old('deadline', now()->addDays(30)->toDateString()) }}" required
                                       class="w-full pl-8 pr-2 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                            @error('deadline')
                                <p class="text-[10px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ══════════ Section 3: Description & Requirements ══════════ --}}
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                            Description & Requirements
                        </h4>
                    </div>

                    <div>
                        <label for="modal-description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Job Description <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="modal-description" name="description" rows="4" required
                                  placeholder="Describe the internship scope, team structure, tools, and project objectives (min 30 chars)..."
                                  class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none @error('description') border-rose-300 ring-1 ring-rose-300 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal-responsibilities" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Responsibilities <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="modal-responsibilities" name="responsibilities" rows="3"
                                      placeholder="- Write and test code&#10;- Attend daily standups&#10;- Collaborate with mentors"
                                      class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none">{{ old('responsibilities') }}</textarea>
                        </div>

                        <div>
                            <label for="modal-requirements" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Qualifications <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="modal-requirements" name="requirements" rows="3"
                                      placeholder="- Basic web fundamentals&#10;- Familiar with Git & GitHub&#10;- Good problem solving skills"
                                      class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none">{{ old('requirements') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer Actions (Inside Form) --}}
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-[11px] text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>Faculty coordinator review required before listing is made public.</span>
                    </p>
                    <div class="flex items-center justify-end gap-3 shrink-0">
                        <button type="button" 
                                @click="createModalOpen = false"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Submit for Approval</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const categories = [
            { icon: '🖥️', label: 'IT Support & Helpdesk' },
            { icon: '🌐', label: 'Web Development' },
            { icon: '📱', label: 'Mobile Development' },
            { icon: '☕', label: 'Java / Spring Boot' },
            { icon: '⚙️', label: 'Backend Engineering' },
            { icon: '🎨', label: 'Frontend Engineering' },
            { icon: '🔗', label: 'Full-Stack Development' },
            { icon: '📊', label: 'Data Science & Analytics' },
            { icon: '🔒', label: 'Cybersecurity' },
            { icon: '☁️', label: 'DevOps & Cloud' },
            { icon: '✏️', label: 'UI/UX Design' },
            { icon: '🔌', label: 'Network Engineering' },
            { icon: '🗄️', label: 'Database Administration' },
            { icon: '🧪', label: 'Quality Assurance' },
            { icon: '📋', label: 'Business Analysis' },
            { icon: '💡', label: 'General IT' },
        ];

        function initCategoryDropdown() {
            if (window.Alpine && !Alpine.data('categoryDropdown')) {
                Alpine.data('categoryDropdown', (initial = '') => ({
                    open: false,
                    search: '',
                    selected: initial || '',
                    selectedIcon: '',
                    categories: categories,
                    init() {
                        if (this.selected) {
                            const found = this.categories.find(c => c.label === this.selected);
                            if (found) this.selectedIcon = found.icon;
                        }
                    },
                    get filtered() {
                        if (!this.search) return this.categories;
                        return this.categories.filter(c => c.label.toLowerCase().includes(this.search.toLowerCase()));
                    },
                    select(cat) {
                        this.selected = cat.label;
                        this.selectedIcon = cat.icon;
                        this.open = false;
                        this.search = '';
                    }
                }));
            }
        }

        if (window.Alpine) {
            initCategoryDropdown();
        } else {
            document.addEventListener('alpine:init', initCategoryDropdown);
        }
    })();
</script>
