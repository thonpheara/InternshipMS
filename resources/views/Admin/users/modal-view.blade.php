<!-- View User Modal Component -->
<div x-show="viewModalOpen"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', viewModalOpen)"
     @keydown.escape.window="viewModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-view-title">

    <!-- Modal Backdrop with Blur -->
    <div x-show="viewModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="viewModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Centered Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 lg:p-8">
        <div x-show="viewModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative w-full max-w-2xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden my-6">

            <!-- Modal Header with Profile Banner -->
            <div class="px-6 py-6 sm:px-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/70 via-slate-50/40 to-white flex items-start justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <!-- Squircle Avatar with Status Dot -->
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-lg font-black shadow-sm"
                             :class="viewUser.role === 'student' ? 'bg-[#059669]' : (viewUser.role === 'company' ? 'bg-teal-700' : 'bg-slate-700')"
                             x-text="viewUser.avatar_initials">
                        </div>
                        <span class="w-3.5 h-3.5 rounded-full border-2 border-white absolute -bottom-0.5 -right-0.5"
                              :class="viewUser.status === 'active' ? 'bg-emerald-500' : 'bg-rose-500'"
                              :title="viewUser.status === 'active' ? 'Active Account' : 'Inactive Account'"></span>
                    </div>

                    <!-- Name, Email, & Badges -->
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 id="modal-view-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight truncate" x-text="viewUser.name"></h3>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200" x-text="'#' + viewUser.id"></span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 truncate" x-text="viewUser.email"></p>
                        
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <!-- Role Badge -->
                            <template x-if="viewUser.role === 'student'">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0]">
                                    <i class="fa-solid fa-graduation-cap text-xs"></i>
                                    <span>Student Candidate</span>
                                </span>
                            </template>
                            <template x-if="viewUser.role === 'company'">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200">
                                    <i class="fa-solid fa-building text-xs"></i>
                                    <span>Host Company</span>
                                </span>
                            </template>
                            <template x-if="viewUser.role !== 'student' && viewUser.role !== 'company'">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200" x-text="viewUser.role"></span>
                            </template>

                            <!-- Status Badge -->
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                  :class="viewUser.status === 'active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
                                <span class="w-1.5 h-1.5 rounded-full" :class="viewUser.status === 'active' ? 'bg-emerald-600' : 'bg-rose-600'"></span>
                                <span x-text="viewUser.status === 'active' ? 'Active' : 'Inactive'"></span>
                            </span>

                            <!-- Extra Verification / Eligibility Badge -->
                            <template x-if="viewUser.role === 'student' && viewUser.eligibility_status">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200"
                                      x-text="'Eligibility: ' + viewUser.eligibility_status"></span>
                            </template>
                            <template x-if="viewUser.role === 'company' && viewUser.verification_status">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200"
                                      x-text="'Verified: ' + viewUser.verification_status"></span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" 
                            @click="switchViewToEdit()"
                            class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-[#059669] hover:bg-emerald-50 text-xs font-semibold shadow-2xs transition-colors flex items-center gap-1.5 cursor-pointer"
                            title="Edit this user">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span class="hidden sm:inline">Edit</span>
                    </button>
                    <button type="button" 
                            @click="viewModalOpen = false"
                            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                            title="Close (Esc)">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Scrollable Content -->
            <div class="max-h-[70vh] overflow-y-auto p-6 sm:p-8 space-y-6">

                <!-- 1. Quick Stats Metric Cards -->
                <template x-if="viewUser.role === 'student'">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-100/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Cumulative GPA</span>
                            <div class="mt-1 text-lg font-black text-emerald-700" x-text="viewUser.gpa || 'N/A'"></div>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Cohort Year</span>
                            <div class="mt-1 text-lg font-black text-slate-900" x-text="viewUser.cohort_year || 'N/A'"></div>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Applications</span>
                            <div class="mt-1 text-lg font-black text-slate-900" x-text="viewUser.applications_count || '0'"></div>
                        </div>
                    </div>
                </template>

                <template x-if="viewUser.role === 'company'">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3.5 rounded-2xl bg-teal-50/60 border border-teal-100/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-teal-800">Job Posts</span>
                            <div class="mt-1 text-lg font-black text-teal-700" x-text="viewUser.internship_posts_count || '0'"></div>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Candidates</span>
                            <div class="mt-1 text-lg font-black text-slate-900" x-text="viewUser.applications_count || '0'"></div>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Verification</span>
                            <div class="mt-1 text-sm font-black capitalize text-slate-900" x-text="viewUser.verification_status || 'Verified'"></div>
                        </div>
                    </div>
                </template>

                <!-- 2. Role-Specific Profile Details -->
                <!-- STUDENT PROFILE SECTION -->
                <template x-if="viewUser.role === 'student'">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                                Academic & Profile Attributes
                            </h4>
                        </div>

                        <div class="bg-slate-50/70 rounded-2xl border border-slate-200/80 p-4 sm:p-5 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Student ID Number</span>
                                    <span class="font-bold text-slate-900 text-sm" x-text="viewUser.student_id_number || 'Unassigned'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Major / Specialization</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.major || 'Not specified'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Department / Faculty</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.department || 'Not specified'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Phone Contact</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.phone || 'No phone provided'"></span>
                                </div>
                            </div>

                            <!-- Skills Tags -->
                            <div class="border-t border-slate-200/60 pt-3">
                                <span class="text-slate-500 block text-[11px] mb-1.5 font-medium">Competencies & Skills</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-if="viewUser.skills && viewUser.skills.length > 0">
                                        <template x-for="skill in viewUser.skills" :key="skill">
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200/80 text-[11px] font-semibold" x-text="skill"></span>
                                        </template>
                                    </template>
                                    <template x-if="!viewUser.skills || viewUser.skills.length === 0">
                                        <span class="text-slate-400 italic text-[11px]">No skills listed on profile</span>
                                    </template>
                                </div>
                            </div>

                            <!-- Student Bio -->
                            <template x-if="viewUser.bio">
                                <div class="border-t border-slate-200/60 pt-3">
                                    <span class="text-slate-500 block text-[11px] mb-1 font-medium">Student Summary / Bio</span>
                                    <p class="text-slate-700 leading-relaxed italic bg-white p-3 rounded-xl border border-slate-200/60 text-xs" x-text="viewUser.bio"></p>
                                </div>
                            </template>

                            <!-- Resume Document Item -->
                            <div class="border-t border-slate-200/60 pt-3">
                                <span class="text-slate-500 block text-[11px] mb-1.5 font-medium">Official Curriculum Vitae (Resume)</span>
                                <template x-if="viewUser.resume_path">
                                    <div class="p-3 bg-white rounded-xl border border-slate-200/80 flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-file-lines text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-bold text-slate-900 truncate block text-xs" x-text="viewUser.resume_filename" :title="viewUser.resume_filename"></span>
                                                <span class="text-[10px] text-emerald-600 font-medium block">Stored candidate document</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <template x-if="['pdf', 'png', 'jpg', 'jpeg'].includes(viewUser.resume_ext)">
                                                <a :href="viewUser.resume_preview_url" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors flex items-center gap-1">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                    <span>Preview</span>
                                                </a>
                                            </template>
                                            <a :href="viewUser.resume_download_url" :download="viewUser.resume_filename" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors flex items-center gap-1" :title="'Download ' + viewUser.resume_filename">
                                                <i class="fa-solid fa-download text-xs"></i>
                                                <span>Download</span>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!viewUser.resume_path">
                                    <p class="text-slate-400 italic text-xs">No resume document uploaded yet.</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- COMPANY PROFILE SECTION -->
                <template x-if="viewUser.role === 'company'">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                <i class="fa-solid fa-building text-teal-600"></i>
                                Organization & Partner Details
                            </h4>
                        </div>

                        <div class="bg-slate-50/70 rounded-2xl border border-slate-200/80 p-4 sm:p-5 space-y-3.5 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Corporate Name</span>
                                    <span class="font-bold text-slate-900 text-sm" x-text="viewUser.company_name || viewUser.name"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Industry Sector</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.industry || 'Not specified'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Official Website</span>
                                    <template x-if="viewUser.website">
                                        <a :href="viewUser.website" target="_blank" class="font-semibold text-[#059669] hover:underline flex items-center gap-1">
                                            <span class="truncate" x-text="viewUser.website"></span>
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                        </a>
                                    </template>
                                    <template x-if="!viewUser.website">
                                        <span class="text-slate-400 italic">No website provided</span>
                                    </template>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Operating Location</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.location || 'Location unassigned'"></span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-500 block text-[11px]">Physical Street Address</span>
                                    <span class="font-medium text-slate-800" x-text="viewUser.address || 'No street address on file'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Contact Person</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.contact_person || 'Unassigned'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[11px]">Contact Phone</span>
                                    <span class="font-semibold text-slate-900" x-text="viewUser.contact_phone || 'No phone provided'"></span>
                                </div>
                            </div>

                            <!-- Company Description -->
                            <template x-if="viewUser.description">
                                <div class="border-t border-slate-200/60 pt-3">
                                    <span class="text-slate-500 block text-[11px] mb-1 font-medium">Company Overview</span>
                                    <p class="text-slate-700 leading-relaxed bg-white p-3 rounded-xl border border-slate-200/60 text-xs" x-text="viewUser.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- 3. Account Activity & Timestamps -->
                <div class="bg-slate-50/50 rounded-2xl border border-slate-200/60 p-4 text-xs">
                    <div class="grid grid-cols-2 gap-3 text-slate-600">
                        <div>
                            <span class="text-[11px] text-slate-400 block">Registration Date</span>
                            <span class="font-semibold text-slate-800" x-text="viewUser.created_at_formatted || 'N/A'"></span>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block">Last Profile Update</span>
                            <span class="font-semibold text-slate-800" x-text="viewUser.updated_at_formatted || 'N/A'"></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 sm:px-8 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                <span class="text-[11px] text-slate-400">
                    User Account Record #<span x-text="viewUser.id"></span>
                </span>

                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="viewModalOpen = false"
                            class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-semibold transition-colors cursor-pointer">
                        Close
                    </button>
                    <button type="button" 
                            @click="switchViewToEdit()"
                            class="px-4 py-2 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                        <span>Edit User</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
