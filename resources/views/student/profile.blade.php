<x-layout>
    @php
        $nameParts = array_filter(explode(' ', trim($user->name ?? 'Student')));
        $initials = count($nameParts) >= 2 
            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
            : strtoupper(substr($user->name ?? 'ST', 0, 2));
        $registeredMonth = $user->created_at ? $user->created_at->format('M Y') : 'Aug 2026';
    @endphp

    <div class="space-y-6 max-w-7xl mx-auto" x-data="{ tab: 'all' }">

        <!-- Top Header -->
        <div class="pb-2">
            <div class="flex items-center gap-2 sm:hidden mb-2">
                <button @click="sidebarOpen = true" class="p-1.5 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <i class="fa-solid fa-bars w-5 h-5"></i>
                </button>
                <span class="text-xs font-semibold text-slate-500">Internship Management System</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                Student Profile
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Manage your academic credentials, contact information, and resume for host company review.
            </p>
        </div>

        <!-- Sub-navigation Tabs Bar (Matching Photo) -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200/70 overflow-x-auto text-xs">
            <div class="flex items-center gap-2 shrink-0">
                <!-- All Settings Tab -->
                <button @click="tab = 'all'" 
                        :class="tab === 'all' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-grip w-3.5 h-3.5"></i>
                    <span>All Details</span>
                </button>

                <!-- Academic Tab -->
                <button @click="tab = 'academic'" 
                        :class="tab === 'academic' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-graduation-cap w-3.5 h-3.5"></i>
                    <span>Academic Credentials</span>
                </button>

                <!-- Personal Info Tab -->
                <button @click="tab = 'personal'" 
                        :class="tab === 'personal' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-user w-3.5 h-3.5"></i>
                    <span>Personal &amp; Skills</span>
                </button>

                <!-- Resume Tab -->
                <button @click="tab = 'resume'" 
                        :class="tab === 'resume' ? 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 font-medium border-transparent'"
                        class="px-3.5 py-1.5 rounded-lg border flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-file-lines w-3.5 h-3.5"></i>
                    <span>Official Resume</span>
                </button>
            </div>

            <!-- Auto-saved session indicator -->
            <div class="hidden sm:flex items-center gap-1.5 text-slate-400 text-xs font-normal shrink-0">
                <i class="fa-solid fa-circle-info w-3.5 h-3.5"></i>
                <span>Auto-saved session</span>
            </div>
        </div>

        <!-- Error Alerts -->
        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Please correct the errors below</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Column: Profile Summary & Security Tip (4 Cols - Sticky on scroll) -->
            <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-6 lg:self-start">
                
                <!-- Profile Summary Card (Matching Photo) -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col items-center text-center space-y-4">
                    <!-- Squircle Avatar with Green Online Dot -->
                    <div class="relative group">
                        <div class="w-20 h-20 rounded-2xl bg-[#059669] text-white text-2xl font-black flex items-center justify-center shadow-md shadow-[#059669]/20">
                            {{ $initials }}
                        </div>
                        <span class="w-4 h-4 rounded-full bg-emerald-500 border-2 border-white absolute -bottom-1 -right-1"></span>
                    </div>

                    <!-- User Name & Email -->
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 leading-tight">
                            {{ $user->name }}
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $user->email }}
                        </p>
                    </div>

                    <!-- Role & Status Badges -->
                    <div class="flex items-center justify-center gap-2 pt-1">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0] flex items-center gap-1">
                            <i class="fa-solid fa-graduation-cap w-3 h-3"></i>
                            <span>Student Portal</span>
                        </span>
                    </div>

                    <!-- Account Overview Section -->
                    <div class="w-full border-t border-slate-100 pt-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 text-left w-full block mb-3">
                            Academic Overview
                        </span>

                        <div class="space-y-3 text-xs w-full">
                            <!-- Student ID -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-circle-check w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Student ID</span>
                                </div>
                                <span class="font-medium text-slate-700 truncate max-w-[140px]">{{ $student->student_id_number ?: 'Not specified' }}</span>
                            </div>

                            <!-- GPA -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-award w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Cumulative GPA</span>
                                </div>
                                <span class="font-semibold text-[#059669]">{{ $student->gpa !== null ? number_format($student->gpa, 2) : 'N/A' }} / 4.00</span>
                            </div>

                            <!-- Cohort Year -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-calendar-days w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Cohort Year</span>
                                </div>
                                <span class="font-medium text-slate-700">{{ $student->cohort_year ?: 'N/A' }}</span>
                            </div>

                            <!-- Degree Major -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-book-open w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Degree Major</span>
                                </div>
                                <span class="font-medium text-slate-700 truncate max-w-[130px]" title="{{ $student->major }}">{{ $student->major ?: 'Not set' }}</span>
                            </div>

                            <!-- Resume Status -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="fa-solid fa-file-lines w-3.5 h-3.5 text-slate-400"></i>
                                    <span>Resume File</span>
                                </div>
                                @if ($student->resume_path)
                                    <span class="font-semibold text-emerald-600 flex items-center gap-1">
                                        <i class="fa-solid fa-check w-3 h-3"></i> Uploaded
                                    </span>
                                @else
                                    <span class="font-semibold text-amber-600">Missing</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Tip Box (Matching Photo) -->
                <div class="bg-[#D1FAE5]/60 border border-[#A7F3D0] rounded-2xl p-4.5 flex items-start gap-3">
                    <div class="w-6 h-6 rounded-lg bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-lightbulb w-3.5 h-3.5"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-emerald-900">Academic Tip</h4>
                        <p class="text-xs text-emerald-800/80 leading-relaxed mt-1">
                            Keep your cumulative GPA and technical skills updated so host companies can quickly verify your eligibility for internship placements.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Right Column: Forms Stack (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Card 1: Academic Credentials -->
                    <div x-show="tab === 'all' || tab === 'academic'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-graduation-cap w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Academic Credentials</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Update your institutional ID, graduation cohort year, and cumulative GPA details.
                                </p>
                            </div>
                        </div>

                        <!-- Form Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Student ID Number -->
                            <div>
                                <label for="student_id_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Student ID Number
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-circle-check w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="student_id_number" 
                                           name="student_id_number" 
                                           value="{{ old('student_id_number', $student->student_id_number) }}" 
                                           placeholder="e.g. STU-2024-088"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Cohort Year -->
                            <div>
                                <label for="cohort_year" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Cohort Graduation Year
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-calendar-days w-4 h-4"></i>
                                    </div>
                                    <input type="number" 
                                           id="cohort_year" 
                                           name="cohort_year" 
                                           value="{{ old('cohort_year', $student->cohort_year ?? 2024) }}" 
                                           min="2020" 
                                           max="2035"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Faculty / Department -->
                            <div>
                                <label for="department" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Faculty / Department
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-building w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="department" 
                                           name="department" 
                                           value="{{ old('department', $student->department ?? 'Computer Science & IT') }}" 
                                           placeholder="e.g. Computer Science & IT"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Degree Major -->
                            <div>
                                <label for="major" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Degree Major
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-book-open w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="major" 
                                           name="major" 
                                           value="{{ old('major', $student->major ?? 'Software Engineering') }}" 
                                           placeholder="e.g. Software Engineering"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Cumulative GPA -->
                            <div class="sm:col-span-2">
                                <label for="gpa" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Cumulative GPA (Scale: 0.00 - 4.00)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-award w-4 h-4"></i>
                                    </div>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0.00" 
                                           max="4.00" 
                                           id="gpa" 
                                           name="gpa" 
                                           value="{{ old('gpa', $student->gpa) }}" 
                                           placeholder="e.g. 3.50"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                                <span class="text-[11px] text-slate-400 mt-1 block">Enter your current cumulative GPA as shown on your university transcript.</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-bookmark w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 2: Personal Information & Technical Skills -->
                    <div x-show="tab === 'all' || tab === 'personal'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-user w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Personal Information &amp; Skills</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Update your contact phone, technical competencies, and professional bio.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Full Name (Read-only) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-user w-4 h-4"></i>
                                    </div>
                                    <input type="text" value="{{ $user->name }}" disabled class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50 text-xs text-slate-500 font-medium cursor-not-allowed">
                                </div>
                            </div>

                            <!-- Email Address (Read-only) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-envelope w-4 h-4"></i>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" disabled class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200/80 bg-slate-50 text-xs text-slate-500 font-medium cursor-not-allowed">
                                </div>
                            </div>

                            <!-- Contact Phone Number -->
                            <div class="sm:col-span-2">
                                <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Contact Phone Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-phone w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $student->phone) }}" 
                                           placeholder="+1 (555) 000-0000"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                            </div>

                            <!-- Skills Input -->
                            <div class="sm:col-span-2">
                                <label for="skills_input" class="block text-xs font-semibold text-slate-700 mb-1.5">Technical Skills &amp; Competencies (Comma-separated)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-code w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           id="skills_input" 
                                           name="skills_input" 
                                           value="{{ old('skills_input', is_array($student->skills) ? implode(', ', $student->skills) : '') }}" 
                                           placeholder="PHP, Laravel, Vue.js, MySQL, Docker, Tailwind CSS"
                                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">
                                </div>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @if (is_array($student->skills))
                                        @foreach ($student->skills as $skill)
                                            <span class="px-2.5 py-0.5 rounded-md bg-[#D1FAE5] text-[#065F46] text-[11px] font-semibold border border-[#A7F3D0]">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Professional Bio -->
                            <div class="sm:col-span-2">
                                <label for="bio" class="block text-xs font-semibold text-slate-700 mb-1.5">Professional Summary / Bio</label>
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="3" 
                                          placeholder="Brief summary of your academic projects, career interests, and passion..."
                                          class="w-full p-3 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#059669]/20 focus:border-[#059669] transition-all font-medium">{{ old('bio', $student->bio) }}</textarea>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-bookmark w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3: Official Resume -->
                    <div x-show="tab === 'all' || tab === 'resume'" x-transition class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
                        <!-- Card Header -->
                        <div class="flex items-start gap-3.5 pb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-file-lines w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Official Resume</h3>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Upload and maintain your latest curriculum vitae in PDF or Word document format (max 5MB).
                                </p>
                            </div>
                        </div>

                        @if ($student->resume_path)
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-circle-check w-5 h-5"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-xs font-bold text-slate-900 truncate">Active Resume on File</span>
                                        <span class="block text-[11px] text-emerald-600 font-medium">Ready for employer review</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('student.resume.preview') }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-[#D1FAE5] text-[#059669] hover:bg-emerald-100 text-xs font-semibold transition-colors inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-arrow-up-right-from-square w-3 h-3"></i>
                                        <span>Preview PDF</span>
                                    </a>
                                    <button type="button" 
                                            onclick="if(confirm('Are you sure you want to delete your resume?')) document.getElementById('delete-resume-form').submit();" 
                                            class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-semibold transition-colors inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-trash-can w-3.5 h-3.5"></i>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-2">
                            <label class="block text-xs font-bold text-slate-800">
                                {{ $student->resume_path ? 'Replace Current Resume (PDF, DOCX, max 5MB)' : 'Upload New Resume (PDF, DOCX, max 5MB)' }}
                            </label>
                            <input type="file" 
                                   name="resume" 
                                   accept=".pdf,.doc,.docx"
                                   class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#059669] file:text-white hover:file:bg-[#047857] cursor-pointer">
                            <span class="block text-[11px] text-slate-400">Select file and click "Save Changes" below to upload.</span>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white font-semibold text-xs shadow-xs transition-all hover:translate-y-[-1px]">
                                <i class="fa-solid fa-arrow-up-from-bracket w-4 h-4 stroke-[2.2]"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

        <!-- Hidden Delete Resume Form -->
        <form id="delete-resume-form" action="{{ route('student.resume.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</x-layout>
