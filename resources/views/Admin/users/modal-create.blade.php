<!-- Add New User Modal Component -->
<div x-show="createModalOpen"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', createModalOpen)"
     @keydown.escape.window="createModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-create-title">

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
             class="relative w-full max-w-2xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden my-6">

            <!-- Modal Header -->
            <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/70 via-slate-50/40 to-white flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-[#059669] text-white flex items-center justify-center shadow-md shadow-emerald-600/20 shrink-0">
                        <i class="fa-solid fa-user-plus text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="modal-create-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Add New User</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full capitalize"
                                  :class="createRole === 'student' ? 'bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]' : 'bg-teal-50 text-teal-800 border border-teal-200'"
                                  x-text="createRole"></span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">
                            Register a new candidate or host company partner into the system.
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

            <!-- Form starts here -->
            <form action="{{ route('admin.users.store') }}" method="POST" id="create-user-modal-form">
                @csrf

                <!-- Scrollable Form Body -->
                <div class="max-h-[72vh] overflow-y-auto px-6 py-6 sm:px-8 space-y-6">

                    <!-- Section 1: Role Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Account Type *
                        </label>
                        <input type="hidden" name="role" :value="createRole">
                        <div class="bg-[#F3F4F6] p-1.5 rounded-2xl grid grid-cols-2 gap-2 select-none">
                            <button type="button" 
                                    @click="createRole = 'student'" 
                                    :class="createRole === 'student' ? 'bg-white text-[#059669] font-bold shadow-xs border border-emerald-200' : 'text-gray-600 hover:text-[#111827] font-medium'"
                                    class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                                <i class="fa-solid fa-graduation-cap w-4 h-4"></i>
                                <span>Student Candidate</span>
                            </button>

                            <button type="button" 
                                    @click="createRole = 'company'" 
                                    :class="createRole === 'company' ? 'bg-white text-[#059669] font-bold shadow-xs border border-emerald-200' : 'text-gray-600 hover:text-[#111827] font-medium'"
                                    class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                                <i class="fa-solid fa-building w-4 h-4"></i>
                                <span>Host Company</span>
                            </button>
                        </div>
                    </div>

                    <!-- Section 2: Core Account Credentials -->
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#059669]"></span>
                            Account Credentials &amp; Access
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="create_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Full Name / Contact Name *
                                </label>
                                <input type="text" 
                                       id="create_name" 
                                       name="name" 
                                       value="{{ old('_user_id') ? '' : old('name') }}" 
                                       required 
                                       placeholder="e.g. Pheara Thon or Den Dol" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                @if(!$errors->has('_user_id'))
                                    @error('name')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="create_email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Login Email Address *
                                </label>
                                <input type="email" 
                                       id="create_email" 
                                       name="email" 
                                       value="{{ old('_user_id') ? '' : old('email') }}" 
                                       required 
                                       placeholder="user@example.com" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                @if(!$errors->has('_user_id'))
                                    @error('email')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="create_password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Initial Password (min 8 chars) *
                                </label>
                                <input type="password" 
                                       id="create_password" 
                                       name="password" 
                                       required 
                                       placeholder="••••••••" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                @if(!$errors->has('_user_id'))
                                    @error('password')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Account Status Custom Dropdown -->
                            <div class="relative" 
                                 x-data="{ 
                                     open: false, 
                                     statusVal: '{{ old('status', 'active') }}',
                                     options: [
                                         { 
                                             value: 'active', 
                                             label: 'Active (Can sign in)', 
                                             desc: 'Full access to log in and use features', 
                                             dot: 'bg-emerald-500 ring-2 ring-emerald-200', 
                                             activeBg: 'bg-emerald-50 text-emerald-900 border-emerald-200' 
                                         },
                                         { 
                                             value: 'inactive', 
                                             label: 'Inactive / Suspended', 
                                             desc: 'Login disabled, account locked', 
                                             dot: 'bg-rose-500 ring-2 ring-rose-200', 
                                             activeBg: 'bg-rose-50 text-rose-900 border-rose-200' 
                                         }
                                     ],
                                     get currentOption() {
                                         return this.options.find(o => o.value === this.statusVal) || this.options[0];
                                     },
                                     selectStatus(val) {
                                         this.statusVal = val;
                                         this.open = false;
                                     }
                                 }">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Account Status *
                                </label>

                                <input type="hidden" name="status" :value="statusVal">

                                <!-- Dropdown Trigger Button -->
                                <button type="button" 
                                        @click="open = !open" 
                                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] hover:border-[#059669] bg-[#F9FAFB] hover:bg-white text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium cursor-pointer shadow-2xs">
                                    <span class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" :class="currentOption.dot"></span>
                                        <span class="font-semibold text-slate-800" x-text="currentOption.label"></span>
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                                       :class="open ? 'rotate-180 text-[#059669]' : ''"></i>
                                </button>

                                <!-- Custom Dropdown Menu Floating Panel -->
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
                                     class="absolute left-0 right-0 z-50 mt-1.5 rounded-2xl bg-white border border-slate-200 shadow-2xl p-1.5 space-y-1"
                                     style="display: none;">
                                    <template x-for="item in options" :key="item.value">
                                        <button type="button" 
                                                @click="selectStatus(item.value)" 
                                                class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition-all cursor-pointer border"
                                                :class="statusVal === item.value ? item.activeBg + ' font-bold' : 'border-transparent hover:bg-slate-50 text-slate-700'">
                                            <div class="flex items-center gap-3">
                                                <span class="w-2.5 h-2.5 rounded-full shrink-0" :class="item.dot"></span>
                                                <div>
                                                    <div class="text-xs sm:text-sm font-bold" x-text="item.label"></div>
                                                    <div class="text-[11px] text-slate-500 font-normal" x-text="item.desc"></div>
                                                </div>
                                            </div>
                                            <div x-show="statusVal === item.value" class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                            </div>
                                        </button>
                                    </template>
                                </div>

                                @if(!$errors->has('_user_id'))
                                    @error('status')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section 3A: Student Academic Profile Fields -->
                    <div x-show="createRole === 'student'" x-transition class="space-y-4 pt-2 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                            Student Academic &amp; Personal Profile
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Student ID Number -->
                            <div>
                                <label for="create_student_id_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Student ID Number
                                </label>
                                <input type="text" 
                                       id="create_student_id_number" 
                                       name="student_id_number" 
                                       value="{{ old('student_id_number') }}" 
                                       placeholder="e.g. STU-2026-089" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="create_department" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Faculty / Department
                                </label>
                                <input type="text" 
                                       id="create_department" 
                                       name="department" 
                                       value="{{ old('department', 'Faculty of Computer Science') }}" 
                                       placeholder="e.g. Faculty of Computer Science" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Major -->
                            <div>
                                <label for="create_major" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Degree Major
                                </label>
                                <input type="text" 
                                       id="create_major" 
                                       name="major" 
                                       value="{{ old('major', 'Software Engineering') }}" 
                                       placeholder="e.g. Software Engineering" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Cohort Year -->
                            <div>
                                <label for="create_cohort_year" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Cohort Year
                                </label>
                                <input type="number" 
                                       id="create_cohort_year" 
                                       name="cohort_year" 
                                       value="{{ old('cohort_year', now()->year) }}" 
                                       placeholder="2026" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Cumulative GPA -->
                            <div>
                                <label for="create_gpa" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Cumulative GPA (0.00 - 4.00)
                                </label>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="4" 
                                       id="create_gpa" 
                                       name="gpa" 
                                       value="{{ old('gpa', '3.50') }}" 
                                       placeholder="3.50" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="create_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Contact Phone
                                </label>
                                <input type="text" 
                                       id="create_phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="+855 12 345 678" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>
                        </div>

                        <!-- Skills -->
                        <div>
                            <label for="create_skills" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Technical Skills (comma-separated)
                            </label>
                            <input type="text" 
                                   id="create_skills" 
                                   name="skills" 
                                   value="{{ old('skills', 'PHP, Laravel, Tailwind CSS, JavaScript') }}" 
                                   placeholder="e.g. PHP, Laravel, Tailwind CSS, MySQL" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="create_bio" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Student Bio / Background
                            </label>
                            <textarea id="create_bio" 
                                      name="bio" 
                                      rows="3" 
                                      placeholder="Brief description about the student's career interests..." 
                                      class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('bio') }}</textarea>
                        </div>
                    </div>

                    <!-- Section 3B: Company Partner Profile Fields -->
                    <div x-show="createRole === 'company'" x-transition class="space-y-4 pt-2 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                            <i class="fa-solid fa-building text-[#059669]"></i>
                            Host Organization Profile
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Company Name -->
                            <div>
                                <label for="create_company_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Company Legal Name *
                                </label>
                                <input type="text" 
                                       id="create_company_name" 
                                       name="company_name" 
                                       value="{{ old('company_name') }}" 
                                       placeholder="e.g. Internet Technology" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                @if(!$errors->has('_user_id'))
                                    @error('company_name')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                @endif
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="create_industry" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Industry Sector
                                </label>
                                <input type="text" 
                                       id="create_industry" 
                                       name="industry" 
                                       value="{{ old('industry', 'Information Technology') }}" 
                                       placeholder="e.g. Financial Technology, Healthcare" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Website URL -->
                            <div>
                                <label for="create_website" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Website URL
                                </label>
                                <input type="url" 
                                       id="create_website" 
                                       name="website" 
                                       value="{{ old('website') }}" 
                                       placeholder="https://company.com" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Location / City -->
                            <div>
                                <label for="create_location" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    City / Province
                                </label>
                                <input type="text" 
                                       id="create_location" 
                                       name="location" 
                                       value="{{ old('location', 'Phnom Penh, Cambodia') }}" 
                                       placeholder="e.g. Phnom Penh, Cambodia" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Contact Person -->
                            <div>
                                <label for="create_contact_person" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    HR / Contact Person Name
                                </label>
                                <input type="text" 
                                       id="create_contact_person" 
                                       name="contact_person" 
                                       value="{{ old('contact_person') }}" 
                                       placeholder="e.g. Pheara Thon" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="create_contact_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Company Contact Phone
                                </label>
                                <input type="text" 
                                       id="create_contact_phone" 
                                       name="contact_phone" 
                                       value="{{ old('contact_phone') }}" 
                                       placeholder="+855 23 999 888" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Verification Status -->
                            <div class="sm:col-span-2">
                                <label for="create_verification_status" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                    Verification Badge Status
                                </label>
                                <div class="relative">
                                    <select id="create_verification_status" 
                                            name="verification_status" 
                                            class="w-full appearance-none pl-3.5 pr-10 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium cursor-pointer">
                                        <option value="verified" {{ old('verification_status', 'verified') === 'verified' ? 'selected' : '' }}>Verified Partner (Recommended)</option>
                                        <option value="pending" {{ old('verification_status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="rejected" {{ old('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected / Flagged</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="create_description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Company Overview
                            </label>
                            <textarea id="create_description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Brief company mission and internship environment description..." 
                                      class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('description') }}</textarea>
                        </div>
                    </div>

                </div>

                <!-- Modal Sticky Footer Actions -->
                <div class="px-6 py-4 sm:px-8 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="createModalOpen = false"
                            class="px-5 py-2.5 rounded-xl bg-white border border-[#E5E7EB] text-gray-700 hover:bg-gray-100 text-xs sm:text-sm font-semibold transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all cursor-pointer">
                        <i class="fa-solid fa-check w-4 h-4"></i>
                        <span>Create User Account</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
