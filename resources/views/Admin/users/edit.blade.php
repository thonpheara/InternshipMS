<x-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Top Breadcrumb & Title -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-gray-500 hover:text-[#111827] hover:bg-gray-50 transition-colors shadow-xs">
                    <i class="fa-solid fa-arrow-left w-4 h-4"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-black tracking-tight text-[#111827]">Edit User: {{ $user->name }}</h1>
                        @if ($user->isStudent())
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                                Student
                            </span>
                        @elseif ($user->isCompany())
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Host Company
                            </span>
                        @endif
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Update credentials, profile attributes, and system authorization status.</p>
                </div>
            </div>

            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-[#E5E7EB] text-gray-700 hover:bg-gray-50 text-xs font-semibold shadow-xs transition-colors">
                <i class="fa-solid fa-eye w-3.5 h-3.5 text-[#059669]"></i>
                <span>View Profile</span>
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Card 1: Core Account Credentials -->
            <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#059669]"></span>
                        Account Credentials &amp; Status
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Primary login and authentication details.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Full Name / Account Name *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
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
                               value="{{ old('email', $user->email) }}" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('email')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Optional reset) -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Reset Password (leave blank to keep current)
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="New password (min 8 chars)" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Account Authorization Status *
                        </label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active (Full system access)</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 2: Student Specific Profile -->
            @if ($user->isStudent())
                @php $sp = $user->studentProfile; @endphp
                <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-5">
                    <div>
                        <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                            Student Academic Profile
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Edit academic enrollment records and internship eligibility.</p>
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
                                   value="{{ old('student_id_number', $sp?->student_id_number) }}" 
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
                                   value="{{ old('department', $sp?->department) }}" 
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
                                   value="{{ old('major', $sp?->major) }}" 
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
                                   value="{{ old('cohort_year', $sp?->cohort_year) }}" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        </div>

                        <!-- Cumulative GPA -->
                        <div>
                            <label for="gpa" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Cumulative GPA
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   max="4" 
                                   id="gpa" 
                                   name="gpa" 
                                   value="{{ old('gpa', $sp?->gpa) }}" 
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
                                   value="{{ old('phone', $sp?->phone) }}" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        </div>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label for="skills" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Technical Skills (comma-separated)
                        </label>
                        @php
                            $skillsStr = is_array($sp?->skills) ? implode(', ', $sp->skills) : ($sp?->skills ?? '');
                        @endphp
                        <input type="text" 
                               id="skills" 
                               name="skills" 
                               value="{{ old('skills', $skillsStr) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                            Student Bio / Background Summary
                        </label>
                        <textarea id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('bio', $sp?->bio) }}</textarea>
                    </div>
                </div>

            @elseif ($user->isCompany())
                @php $cp = $user->companyProfile; @endphp
                <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-5">
                    <div>
                        <h2 class="text-base font-extrabold text-[#111827] flex items-center gap-2">
                            <i class="fa-solid fa-building text-[#059669]"></i>
                            Host Organization Details
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">Manage organization profile and contact information.</p>
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
                                   value="{{ old('company_name', $cp?->company_name) }}" 
                                   required 
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
                                   value="{{ old('industry', $cp?->industry) }}" 
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
                                   value="{{ old('website', $cp?->website) }}" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                City / Province
                            </label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', $cp?->location) }}" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <label for="contact_person" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
                                Contact Person Name
                            </label>
                            <input type="text" 
                                   id="contact_person" 
                                   name="contact_person" 
                                   value="{{ old('contact_person', $cp?->contact_person) }}" 
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
                                   value="{{ old('contact_phone', $cp?->contact_phone) }}" 
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
                                  class="w-full p-3 rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] text-xs sm:text-sm text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">{{ old('description', $cp?->description) }}</textarea>
                    </div>
                </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-5 py-2.5 rounded-xl bg-white border border-[#E5E7EB] text-gray-700 hover:bg-gray-50 text-xs sm:text-sm font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-check w-4 h-4"></i>
                    <span>Save Changes</span>
                </button>
            </div>

        </form>

    </div>
</x-layout>
