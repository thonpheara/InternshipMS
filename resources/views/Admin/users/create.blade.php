<x-layout>
    <div class="max-w-4xl mx-auto space-y-6" x-data="{ role: '{{ old('role', $selectedRole ?? 'student') }}' }">

        <!-- Top Breadcrumb & Title -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-gray-500 hover:text-[#111827] hover:bg-gray-50 transition-colors shadow-xs">
                    <i class="fa-solid fa-arrow-left w-4 h-4"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-[#111827]">Create User Account</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Add a new student candidate or host company partner to the system.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Card 1: Role Selection & Core Credentials -->
            <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#059669]"></span>
                        Account Role &amp; Access
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Select the account type to configure role-specific permissions and profile fields.</p>
                </div>

                <!-- Segmented Role Selector -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Account Type *
                    </label>
                    <input type="hidden" name="role" :value="role">
                    <div class="bg-[#F3F4F6] p-1.5 rounded-2xl grid grid-cols-2 gap-2 select-none">
                        <button type="button" 
                                @click="role = 'student'" 
                                :class="role === 'student' ? 'bg-white text-[#059669] font-bold shadow-xs border border-emerald-200' : 'text-gray-600 hover:text-[#111827] font-medium'"
                                class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-graduation-cap w-4 h-4"></i>
                            <span>Student Candidate</span>
                        </button>

                        <button type="button" 
                                @click="role = 'company'" 
                                :class="role === 'company' ? 'bg-white text-[#059669] font-bold shadow-xs border border-emerald-200' : 'text-gray-600 hover:text-[#111827] font-medium'"
                                class="flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                            <i class="fa-solid fa-building w-4 h-4"></i>
                            <span>Host Company</span>
                        </button>
                    </div>
                </div>

                <!-- Core Credentials Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Full Name / Primary Contact *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g. Sokha Chan or Sarah Connor" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('name')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Login Email Address *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="user@example.com" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Initial Password (min 8 chars) *
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Account Status *
                        </label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Can sign in)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 2A: Student Academic Profile Fields -->
            <div x-show="role === 'student'" x-transition class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-5">
                <div>
                    <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                        Student Academic &amp; Personal Profile
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Enter curriculum and academic details for university internship monitoring.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Student ID Number -->
                    <div>
                        <label for="student_id_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Student ID Number
                        </label>
                        <input type="text" 
                               id="student_id_number" 
                               name="student_id_number" 
                               value="{{ old('student_id_number') }}" 
                               placeholder="e.g. STU-2026-089" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Faculty / Department
                        </label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department', 'Faculty of Computer Science') }}" 
                               placeholder="e.g. Faculty of Computer Science" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Major -->
                    <div>
                        <label for="major" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Degree Major
                        </label>
                        <input type="text" 
                               id="major" 
                               name="major" 
                               value="{{ old('major', 'Software Engineering') }}" 
                               placeholder="e.g. Software Engineering" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Cohort Year -->
                    <div>
                        <label for="cohort_year" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Cohort Year
                        </label>
                        <input type="number" 
                               id="cohort_year" 
                               name="cohort_year" 
                               value="{{ old('cohort_year', now()->year) }}" 
                               placeholder="2026" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Cumulative GPA -->
                    <div>
                        <label for="gpa" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Cumulative GPA (0.00 - 4.00)
                        </label>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               max="4" 
                               id="gpa" 
                               name="gpa" 
                               value="{{ old('gpa', '3.50') }}" 
                               placeholder="3.50" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Contact Phone
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="+855 12 345 678" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <!-- Skills -->
                <div>
                    <label for="skills" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Technical Skills (comma-separated)
                    </label>
                    <input type="text" 
                           id="skills" 
                           name="skills" 
                           value="{{ old('skills', 'PHP, Laravel, Tailwind CSS, JavaScript') }}" 
                           placeholder="e.g. PHP, Laravel, Tailwind CSS, MySQL" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Student Bio / Background
                    </label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="3" 
                              placeholder="Brief description about the student's career interests..." 
                              class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('bio') }}</textarea>
                </div>
            </div>

            <!-- Card 2B: Company Partner Profile Fields -->
            <div x-show="role === 'company'" x-transition class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-5">
                <div>
                    <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                        <i class="fa-solid fa-building text-[#059669]"></i>
                        Host Organization Profile
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Configure host company details for internship vacancy postings.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Company Legal Name *
                        </label>
                        <input type="text" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name') }}" 
                               placeholder="e.g. TechCorp Solutions Ltd" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('company_name')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Industry -->
                    <div>
                        <label for="industry" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Industry Sector
                        </label>
                        <input type="text" 
                               id="industry" 
                               name="industry" 
                               value="{{ old('industry', 'Information Technology') }}" 
                               placeholder="e.g. Financial Technology, Healthcare" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Website URL -->
                    <div>
                        <label for="website" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Website URL
                        </label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               value="{{ old('website') }}" 
                               placeholder="https://company.com" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Location / City -->
                    <div>
                        <label for="location" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            City / Province
                        </label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location', 'Phnom Penh, Cambodia') }}" 
                               placeholder="e.g. Phnom Penh, Cambodia" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            HR / Contact Person Name
                        </label>
                        <input type="text" 
                               id="contact_person" 
                               name="contact_person" 
                               value="{{ old('contact_person') }}" 
                               placeholder="e.g. Jonathan Smith" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Contact Phone -->
                    <div>
                        <label for="contact_phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Company Contact Phone
                        </label>
                        <input type="text" 
                               id="contact_phone" 
                               name="contact_phone" 
                               value="{{ old('contact_phone') }}" 
                               placeholder="+855 23 999 888" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                        Company Overview
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Brief company mission and internship environment description..." 
                              class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-5 py-2.5 rounded-xl bg-white border border-[#E5E7EB] text-gray-700 hover:bg-gray-50 text-xs sm:text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-check w-4 h-4"></i>
                    <span>Create User Account</span>
                </button>
            </div>

        </form>

    </div>
</x-layout>
