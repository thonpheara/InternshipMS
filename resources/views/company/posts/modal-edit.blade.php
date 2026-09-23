<!-- Edit Post Internship Modal Component -->
<div x-show="editModalOpen"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', editModalOpen)"
     @keydown.escape.window="editModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-edit-title">

    <!-- Modal Backdrop with Blur -->
    <div x-show="editModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="editModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Centered Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 lg:p-8">
        <div x-show="editModalOpen"
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
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                    </div>
                    <div>
                        <h3 id="modal-edit-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Edit Internship Listing
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Update listing details, capacity, or lifecycle status.
                        </p>
                    </div>
                </div>
                <button type="button" 
                        @click="editModalOpen = false"
                        class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close (Esc)">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form :action="editPost.updateUrl" method="POST" id="edit-post-modal-form" class="max-h-[75vh] overflow-y-auto px-6 py-6 sm:px-8 space-y-7">
                @csrf
                @method('PUT')
                <input type="hidden" name="editing_post_id" :value="editPost.id">

                @if ($errors->any() && old('editing_post_id'))
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                            <span>Please correct the errors below before saving:</span>
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
                        <label for="edit-modal-title-input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Position Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="edit-modal-title-input" name="title"
                               x-model="editPost.title" required
                               placeholder="e.g. Web Developer Intern, IT Support Technician"
                               class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400">
                    </div>

                    {{-- Category Custom Dropdown --}}
                    <div @click.outside="categoryDropdownOpen = false" class="relative">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Position Category <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="category" :value="editCategory">

                        {{-- Trigger Button --}}
                        <button type="button" @click="categoryDropdownOpen = !categoryDropdownOpen"
                                :class="categoryDropdownOpen ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-white'"
                                class="w-full flex items-center justify-between px-4 py-3 border rounded-xl text-sm transition-all cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <span x-text="editCategoryIcon || '🔖'" class="text-base leading-none"></span>
                                <span x-text="editCategory || 'Select a position category...'"
                                      :class="editCategory ? 'text-slate-900 font-semibold' : 'text-slate-400'"></span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 :class="categoryDropdownOpen ? 'rotate-180' : ''"
                                 class="text-slate-400 transition-transform duration-200">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div x-show="categoryDropdownOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute z-50 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/80 overflow-hidden"
                             style="display:none;">

                            <div class="p-3 border-b border-slate-100 bg-slate-50/50">
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                                    </svg>
                                    <input type="text" x-model="categorySearch" @click.stop
                                           placeholder="Search categories..."
                                           class="w-full pl-8 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-700">
                                </div>
                            </div>

                            <ul class="max-h-52 overflow-y-auto py-1">
                                <template x-for="cat in filteredEditCategories" :key="cat.label">
                                    <li>
                                        <button type="button" @click="selectEditCategory(cat)"
                                                :class="editCategory === cat.label ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-xs sm:text-sm transition-colors text-left cursor-pointer">
                                            <span x-text="cat.icon" class="text-base w-5 text-center leading-none"></span>
                                            <span x-text="cat.label" class="flex-1"></span>
                                            <template x-if="editCategory === cat.label">
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

                    {{-- Work Location --}}
                    <div>
                        <label for="edit-modal-location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Work Location <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                            <input type="text" id="edit-modal-location" name="location"
                                   x-model="editPost.location" required
                                   placeholder="e.g. Phnom Penh, Siem Reap, or Remote"
                                   class="w-full pl-10 pr-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400">
                        </div>
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
                            <label for="edit-modal-duration" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Duration (wks) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="edit-modal-duration" name="duration_weeks"
                                       x-model="editPost.duration_weeks" required min="4" max="52"
                                       class="w-full pl-8 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="edit-modal-slots" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Open Slots <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-users absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="number" id="edit-modal-slots" name="slots"
                                       x-model="editPost.slots" required min="1" max="50"
                                       class="w-full pl-8 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="edit-modal-stipend" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Stipend / Mo
                            </label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">$</span>
                                <input type="number" step="50" id="edit-modal-stipend" name="stipend"
                                       x-model="editPost.stipend" placeholder="0"
                                       class="w-full pl-7 pr-2 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400">
                            </div>
                        </div>

                        <div>
                            <label for="edit-modal-deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Deadline <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"></i>
                                <input type="date" id="edit-modal-deadline" name="deadline"
                                       x-model="editPost.deadline" required
                                       class="w-full pl-8 pr-2 py-2.5 text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ══════════ Section 3: Status Lifecycle Dropdown ══════════ --}}
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                            Listing Lifecycle & Status
                        </h4>
                    </div>

                    <div @click.outside="statusDropdownOpen = false" class="relative max-w-sm">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Publication Status <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="status" :value="editStatus">

                        <button type="button" @click="statusDropdownOpen = !statusDropdownOpen"
                                :class="statusDropdownOpen ? 'ring-2 ring-emerald-500 border-emerald-500 bg-white' : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:bg-white'"
                                class="w-full flex items-center justify-between px-4 py-3 border rounded-xl text-sm transition-all cursor-pointer">
                            <span class="flex items-center gap-2.5">
                                <span x-text="editStatusIcon || '⏳'" class="text-base leading-none"></span>
                                <span x-text="editStatusLabel || 'Select status...'" class="text-slate-900 font-semibold"></span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 :class="statusDropdownOpen ? 'rotate-180' : ''"
                                 class="text-slate-400 transition-transform duration-200">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="statusDropdownOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute z-50 mt-2 w-full bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/80 overflow-hidden"
                             style="display:none;">
                            <ul class="py-1.5">
                                <template x-for="s in statuses" :key="s.value">
                                    <li>
                                        <button type="button" @click="selectEditStatus(s)"
                                                :class="editStatus === s.value ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-slate-700 hover:bg-slate-50 font-medium'"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-xs sm:text-sm transition-colors text-left cursor-pointer">
                                            <span x-text="s.icon" class="text-base w-5 text-center leading-none"></span>
                                            <span x-text="s.label" class="flex-1"></span>
                                            <template x-if="editStatus === s.value">
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

                {{-- ══════════ Section 4: Description & Requirements ══════════ --}}
                <div class="space-y-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 pb-1.5 border-b border-slate-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-700">
                            Description & Requirements
                        </h4>
                    </div>

                    <div>
                        <label for="edit-modal-description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Job Description <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="edit-modal-description" name="description" rows="4" required
                                  x-model="editPost.description"
                                  placeholder="Describe the internship scope, team structure, tools, and project objectives (min 30 chars)..."
                                  class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-modal-responsibilities" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Responsibilities <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="edit-modal-responsibilities" name="responsibilities" rows="3"
                                      x-model="editPost.responsibilities"
                                      placeholder="- Write and test code&#10;- Attend daily standups&#10;- Collaborate with mentors"
                                      class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none"></textarea>
                        </div>

                        <div>
                            <label for="edit-modal-requirements" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                Qualifications <span class="text-slate-400 font-normal normal-case">(optional)</span>
                            </label>
                            <textarea id="edit-modal-requirements" name="requirements" rows="3"
                                      x-model="editPost.requirements"
                                      placeholder="- Basic web fundamentals&#10;- Familiar with Git & GitHub&#10;- Good problem solving skills"
                                      class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer Actions (Inside Form) --}}
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-[11px] text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-amber-500"></i>
                        <span>Changes to an approved post may require university coordinator re-moderation.</span>
                    </p>
                    <div class="flex items-center justify-end gap-3 shrink-0">
                        <button type="button" 
                                @click="editModalOpen = false"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
