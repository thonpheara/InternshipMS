<x-layout title="Edit Listing — Internship Management System">
    <div class="space-y-6 max-w-4xl mx-auto">

        <div>
            <a href="{{ route('company.posts.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-600 transition-colors mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to listings</span>
            </a>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">Edit Internship Listing</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Update listing details, slots, or post lifecycle status.</p>
        </div>

        <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
            <form action="{{ route('company.posts.update', $post) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- ══════════ Section 1: Position Details ══════════ --}}
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-2 mb-5">
                        <i data-lucide="briefcase" class="w-4 h-4"></i> Position Details
                    </h3>
                    <div class="space-y-5">

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Position Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                   value="{{ old('title', $post->title) }}" required
                                   class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all">
                        </div>

                        {{-- ── Category Custom Dropdown ── --}}
                        <div x-data="categoryDropdown()" @click.outside="open = false" class="relative">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Position Category <span class="text-rose-500">*</span>
                            </label>
                            <input type="hidden" name="category" :value="selected">

                            <button type="button" @click="open = !open"
                                    :class="open ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-white'"
                                    class="w-full flex items-center justify-between px-4 py-3 border rounded-xl text-sm transition-all">
                                <span class="flex items-center gap-2.5">
                                    <span x-text="selectedIcon || '🔖'" class="text-base leading-none"></span>
                                    <span x-text="selected || 'Select a category...'"
                                          :class="selected ? 'text-slate-900 font-semibold' : 'text-slate-400'"></span>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     :class="open ? 'rotate-180' : ''" class="text-slate-400 transition-transform duration-200">
                                    <path d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="absolute z-50 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 overflow-hidden"
                                 style="display:none;">
                                <div class="p-3 border-b border-slate-100">
                                    <div class="relative">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                        </svg>
                                        <input type="text" x-model="search" @click.stop placeholder="Search categories..."
                                               class="w-full pl-8 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-700">
                                    </div>
                                </div>
                                <ul class="max-h-56 overflow-y-auto py-1.5">
                                    <template x-for="cat in filtered" :key="cat.label">
                                        <li>
                                            <button type="button" @click="select(cat)"
                                                    :class="selected === cat.label ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50'"
                                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors">
                                                <span x-text="cat.icon" class="text-base w-5 text-center leading-none"></span>
                                                <span x-text="cat.label" class="font-medium flex-1 text-left"></span>
                                                <template x-if="selected === cat.label">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                         class="text-emerald-600 shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                                </template>
                                            </button>
                                        </li>
                                    </template>
                                    <li x-show="filtered.length === 0" class="px-4 py-3 text-xs text-slate-400 text-center">No categories match.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Location + Work Mode --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Work Location <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i data-lucide="map-pin" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                    <input type="text" id="location" name="location"
                                           value="{{ old('location', $post->location) }}" required
                                           class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all">
                                </div>
                            </div>

                            <div x-data="{ mode: '{{ old('type', $post->type) }}' }">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                    Work Mode <span class="text-rose-500">*</span>
                                </label>
                                <input type="hidden" name="type" :value="mode">
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" @click="mode='on_site'"
                                            :class="mode==='on_site' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 ring-2 ring-emerald-500/20' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                            class="flex flex-col items-center gap-1 p-2.5 rounded-xl border-2 text-center transition-all cursor-pointer">
                                        <span class="text-lg">🏢</span><span class="text-[10px] font-bold">On-Site</span>
                                    </button>
                                    <button type="button" @click="mode='hybrid'"
                                            :class="mode==='hybrid' ? 'border-violet-500 bg-violet-50 text-violet-700 ring-2 ring-violet-500/20' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                            class="flex flex-col items-center gap-1 p-2.5 rounded-xl border-2 text-center transition-all cursor-pointer">
                                        <span class="text-lg">🔀</span><span class="text-[10px] font-bold">Hybrid</span>
                                    </button>
                                    <button type="button" @click="mode='remote'"
                                            :class="mode==='remote' ? 'border-sky-500 bg-sky-50 text-sky-700 ring-2 ring-sky-500/20' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                            class="flex flex-col items-center gap-1 p-2.5 rounded-xl border-2 text-center transition-all cursor-pointer">
                                        <span class="text-lg">🌐</span><span class="text-[10px] font-bold">Remote</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════ Section 2: Terms & Capacity ══════════ --}}
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-2 mb-5">
                        <i data-lucide="calendar" class="w-4 h-4"></i> Terms & Capacity
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label for="duration_weeks" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Duration (wks) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="clock" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="duration_weeks" name="duration_weeks"
                                       value="{{ old('duration_weeks', $post->duration_weeks) }}" required min="4" max="52"
                                       class="w-full pl-8 pr-3 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="slots" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Slots <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="users" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="slots" name="slots"
                                       value="{{ old('slots', $post->slots) }}" required min="1" max="50"
                                       class="w-full pl-8 pr-3 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="stipend" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Stipend / Month</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">$</span>
                                <input type="number" step="50" id="stipend" name="stipend"
                                       value="{{ old('stipend', $post->stipend) }}"
                                       class="w-full pl-7 pr-3 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Deadline <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="date" id="deadline" name="deadline"
                                       value="{{ old('deadline', \Carbon\Carbon::parse($post->deadline)->toDateString()) }}" required
                                       class="w-full pl-8 pr-3 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════ Section 3: Status — Custom Dropdown ══════════ --}}
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-2 mb-5">
                        <i data-lucide="toggle-left" class="w-4 h-4"></i> Post Status
                    </h3>
                    <div class="max-w-sm relative" x-data="statusDropdown()" @click.outside="open = false">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Status Lifecycle <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="status" :value="selected">

                        <button type="button" @click="open = !open"
                                :class="open ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-white'"
                                class="w-full flex items-center justify-between px-4 py-3 border rounded-xl text-sm transition-all">
                            <span class="flex items-center gap-2.5">
                                <span x-text="selectedIcon" class="text-base leading-none"></span>
                                <span x-text="selectedLabel" class="text-slate-900 font-semibold"></span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 :class="open ? 'rotate-180' : ''" class="text-slate-400 transition-transform duration-200">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute z-50 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 overflow-hidden"
                             style="display:none;">
                            <ul class="py-1.5">
                                <template x-for="s in statuses" :key="s.value">
                                    <li>
                                        <button type="button" @click="select(s)"
                                                :class="selected === s.value ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-50'"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors">
                                            <span x-text="s.icon" class="text-base w-5 text-center leading-none"></span>
                                            <span x-text="s.label" class="font-medium flex-1 text-left"></span>
                                            <template x-if="selected === s.value">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                                     class="text-emerald-600 shrink-0"><path d="M20 6 9 17l-5-5"/></svg>
                                            </template>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ══════════ Section 4: Description ══════════ --}}
                <div class="pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-2 mb-5">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Description & Requirements
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Job Description <span class="text-rose-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" required
                                      class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all resize-none">{{ old('description', $post->description) }}</textarea>
                        </div>
                        <div>
                            <label for="responsibilities" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Responsibilities <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="responsibilities" name="responsibilities" rows="3"
                                      class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all resize-none">{{ old('responsibilities', $post->responsibilities) }}</textarea>
                        </div>
                        <div>
                            <label for="requirements" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Requirements <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="requirements" name="requirements" rows="3"
                                      class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all resize-none">{{ old('requirements', $post->requirements) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ══════════ Actions ══════════ --}}
                <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                    <p class="text-[11px] text-slate-400">
                        <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                        Changes to an approved post may require re-moderation.
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('company.posts.index') }}"
                           class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 active:scale-95 shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Alpine data in script to avoid Blade HTML-entity escaping issues with & characters --}}
    <script>
        document.addEventListener('alpine:init', () => {
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

            const statuses = [
                { value: 'approved',         icon: '✅', label: 'Approved & Published' },
                { value: 'pending_approval', icon: '⏳', label: 'Pending Approval' },
                { value: 'closed',           icon: '🔒', label: 'Closed' },
                { value: 'draft',            icon: '📝', label: 'Draft' },
            ];

            // Pre-fill from server
            const currentCategory = @json(old('category', $post->category ?? 'General IT'));
            const currentStatus   = @json(old('status', $post->status ?? 'pending_approval'));

            const matchedCat    = categories.find(c => c.label === currentCategory);
            const matchedStatus = statuses.find(s => s.value === currentStatus);

            Alpine.data('categoryDropdown', () => ({
                open: false,
                search: '',
                selected: currentCategory,
                selectedIcon: matchedCat ? matchedCat.icon : '💡',
                categories: categories,
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

            Alpine.data('statusDropdown', () => ({
                open: false,
                selected: currentStatus,
                selectedIcon: matchedStatus ? matchedStatus.icon : '⏳',
                selectedLabel: matchedStatus ? matchedStatus.label : 'Pending Approval',
                statuses: statuses,
                select(s) {
                    this.selected = s.value;
                    this.selectedIcon = s.icon;
                    this.selectedLabel = s.label;
                    this.open = false;
                }
            }));
        });
    </script>
</x-layout>
