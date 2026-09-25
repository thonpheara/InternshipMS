<!-- Edit User Modal Component -->
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
             class="relative w-full max-w-2xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden my-6">

            <!-- Modal Header -->
            <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/70 via-slate-50/40 to-white flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-11 h-11 rounded-2xl bg-[#059669] text-white flex items-center justify-center shadow-md shadow-emerald-600/20 shrink-0">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 id="modal-edit-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            <span>Edit User Account</span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full capitalize"
                                  :class="editUser.role === 'student' ? 'bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]' : 'bg-teal-50 text-teal-800 border border-teal-200'"
                                  x-text="editUser.role"></span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">
                            Modifying <span class="font-bold text-slate-800" x-text="editUser.name"></span> (<span x-text="editUser.email"></span>)
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

            <!-- Form starts here -->
            <form :action="editUser.updateUrl" method="POST" id="edit-user-modal-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="_user_id" :value="editUser.id">

                <!-- Scrollable Form Body -->
                <div class="max-h-[72vh] overflow-y-auto px-6 py-6 sm:px-8 space-y-6">

                    <!-- Section 1: Core Account Credentials & Status -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#059669]"></span>
                            Account Credentials &amp; Authorization
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Full Name / Account Name *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       x-model="editUser.name" 
                                       required 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Login Email -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Login Email Address *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       x-model="editUser.email" 
                                       required 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                            </div>

                            <!-- Reset Password -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Reset Password (Optional)
                                </label>
                                <input type="password" 
                                       name="password" 
                                       placeholder="Leave blank to keep current password" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium placeholder-slate-400">
                            </div>

                            <!-- Account Status -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Account Status *
                                </label>
                                <select name="status" 
                                        x-model="editUser.status" 
                                        required 
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                    <option value="active">Active (Access Allowed)</option>
                                    <option value="inactive">Inactive (Suspended)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Student Specific Profile Fields -->
                    <template x-if="editUser.role === 'student'">
                        <div class="space-y-4 border-t border-slate-100 pt-5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                                Student Academic Profile
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Student ID Number -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Student ID Number
                                    </label>
                                    <input type="text" 
                                           name="student_id_number" 
                                           x-model="editUser.student_id_number" 
                                           placeholder="e.g. ST-2024-001" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Cohort Year -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Cohort / Graduation Year
                                    </label>
                                    <input type="number" 
                                           name="cohort_year" 
                                           min="2000" 
                                           max="2100" 
                                           x-model="editUser.cohort_year" 
                                           placeholder="e.g. 2024" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Major -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Major / Specialization
                                    </label>
                                    <input type="text" 
                                           name="major" 
                                           x-model="editUser.major" 
                                           placeholder="e.g. Computer Science" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Department -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Department / Faculty
                                    </label>
                                    <input type="text" 
                                           name="department" 
                                           x-model="editUser.department" 
                                           placeholder="e.g. Science & Technology" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Cumulative GPA -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Cumulative GPA (0.00 - 4.00)
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="4" 
                                           name="gpa" 
                                           x-model="editUser.gpa" 
                                           placeholder="3.85" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Phone Contact
                                    </label>
                                    <input type="text" 
                                           name="phone" 
                                           x-model="editUser.phone" 
                                           placeholder="e.g. +855 12 345 678" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Eligibility Status -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Internship Eligibility Status
                                    </label>
                                    <select name="eligibility_status" 
                                            x-model="editUser.eligibility_status" 
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                        <option value="eligible">Eligible (Cleared for internships)</option>
                                        <option value="pending">Pending Review</option>
                                        <option value="ineligible">Ineligible (Not cleared)</option>
                                    </select>
                                </div>

                                <!-- Skills Input -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Competencies &amp; Skills (Comma Separated)
                                    </label>
                                    <input type="text" 
                                           name="skills" 
                                           x-model="editUser.skills_string" 
                                           placeholder="e.g. PHP, Laravel, TailwindCSS, MySQL, Android" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                    <p class="text-[11px] text-slate-400 mt-1">Separate individual competencies with commas.</p>
                                </div>

                                <!-- Bio -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Student Bio / Introduction
                                    </label>
                                    <textarea name="bio" 
                                              rows="3" 
                                              x-model="editUser.bio" 
                                              placeholder="Summary of student academic background and career goals..." 
                                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Section 3: Company Specific Profile Fields -->
                    <template x-if="editUser.role === 'company'">
                        <div class="space-y-4 border-t border-slate-100 pt-5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-building text-teal-600"></i>
                                Company Organization Profile
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Company Name -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Company / Corporate Name *
                                    </label>
                                    <input type="text" 
                                           name="company_name" 
                                           x-model="editUser.company_name" 
                                           :required="editUser.role === 'company'" 
                                           placeholder="e.g. Canadia Bank PLC" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Industry -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Industry Sector
                                    </label>
                                    <input type="text" 
                                           name="industry" 
                                           x-model="editUser.industry" 
                                           placeholder="e.g. Financial Services" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Website URL -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Official Website URL
                                    </label>
                                    <input type="url" 
                                           name="website" 
                                           x-model="editUser.website" 
                                           placeholder="https://example.com" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Location -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Location / City
                                    </label>
                                    <input type="text" 
                                           name="location" 
                                           x-model="editUser.location" 
                                           placeholder="e.g. Phnom Penh" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Address -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Physical Street Address
                                    </label>
                                    <input type="text" 
                                           name="address" 
                                           x-model="editUser.address" 
                                           placeholder="Building, Street, District..." 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Contact Person -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Contact Person / Representative
                                    </label>
                                    <input type="text" 
                                           name="contact_person" 
                                           x-model="editUser.contact_person" 
                                           placeholder="e.g. HR Manager Name" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Contact Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Contact Phone
                                    </label>
                                    <input type="text" 
                                           name="contact_phone" 
                                           x-model="editUser.contact_phone" 
                                           placeholder="e.g. +855 23 888 999" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                </div>

                                <!-- Verification Status -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Partner Verification Status
                                    </label>
                                    <select name="verification_status" 
                                            x-model="editUser.verification_status" 
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium">
                                        <option value="verified">Verified Partner</option>
                                        <option value="pending">Pending Verification</option>
                                        <option value="rejected">Rejected / Unverified</option>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Company Description / Overview
                                    </label>
                                    <textarea name="description" 
                                              rows="3" 
                                              x-model="editUser.description" 
                                              placeholder="Summary of company business and recruitment activities..." 
                                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] focus:bg-white transition-all font-medium"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 sm:px-8 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                    <button type="button" 
                            @click="editModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-semibold transition-colors cursor-pointer">
                        Cancel
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                class="px-5 py-2 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
