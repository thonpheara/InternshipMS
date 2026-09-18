<x-layout title="Student Profile — Internship Management System">
    <div class="space-y-4">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <div>
                <h2 class="text-xl sm:text-2xl font-black tracking-tight text-slate-900">Student Profile & Academic Credentials</h2>
                <p class="text-xs text-slate-600">Complete your academic credentials, contact information, and resume for host company review.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                    <span>Please correct the errors below</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Left Column: Academic Record & Resume (col-span-4) -->
                <div class="lg:col-span-4 space-y-4">
                    <!-- Academic Summary Card -->
                    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600"></i>
                                Academic Status
                            </h3>
                            <x-status-badge :status="$student->eligibility_status ?? 'pending'" />
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="block text-[10px] uppercase font-bold text-slate-500">Student ID</span>
                                <span class="font-extrabold text-slate-900 truncate block">{{ $student->student_id_number ?: 'Not specified' }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="block text-[10px] uppercase font-bold text-slate-500">Cohort Year</span>
                                <span class="font-extrabold text-slate-900 block">{{ $student->cohort_year ?: 'N/A' }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="block text-[10px] uppercase font-bold text-slate-500">Cumulative GPA</span>
                                <span class="font-extrabold text-slate-900 block">{{ $student->gpa !== null ? number_format($student->gpa, 2) : 'N/A' }} / 4.00</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="block text-[10px] uppercase font-bold text-slate-500">Faculty</span>
                                <span class="font-extrabold text-slate-900 truncate block" title="{{ $student->department }}">{{ $student->department ?: 'Not set' }}</span>
                            </div>
                            <div class="col-span-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="block text-[10px] uppercase font-bold text-slate-500">Degree Major</span>
                                <span class="font-extrabold text-slate-900 truncate block" title="{{ $student->major }}">{{ $student->major ?: 'Not set' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resume Upload Section -->
                    <div class="p-4 sm:p-5 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="file-text" class="w-4 h-4 text-indigo-600"></i>
                                Official Resume
                            </h3>
                            @if ($student->resume_path)
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-100 flex items-center gap-1">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    <span>Uploaded</span>
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-100 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                    <span>Missing</span>
                                </span>
                            @endif
                        </div>

                        @if ($student->resume_path)
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 truncate">
                                        <i data-lucide="file-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                                        <span class="font-bold text-slate-800 text-[11px] truncate">Active Resume on File</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <a href="{{ route('student.resume.preview') }}" target="_blank" class="px-2 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-[10px] font-bold transition-colors inline-flex items-center gap-1">
                                            <span>Preview</span>
                                            <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="if(confirm('Are you sure you want to delete your resume?')) document.getElementById('delete-resume-form').submit();" 
                                                class="px-2 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 text-[10px] font-bold transition-colors inline-flex items-center gap-1 cursor-pointer">
                                            <i data-lucide="trash-2" class="w-2.5 h-2.5"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">
                                {{ $student->resume_path ? 'Replace Current Resume (PDF/DOCX, max 5MB)' : 'Choose Resume to Upload (PDF/DOCX, max 5MB)' }}
                            </label>
                            <input type="file" 
                                   name="resume" 
                                   accept=".pdf,.doc,.docx"
                                   class="block w-full text-xs text-slate-600 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[11px] file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                            <p class="text-[10px] text-slate-600 mt-1">Select file and click "Save Profile Changes" below to upload.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Academic & Personal Profile Form (col-span-8) -->
                <div class="lg:col-span-8 p-4 sm:p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-6 flex flex-col justify-between">
                    <div class="space-y-6">
                        
                        <!-- Section 1: Academic Profile Information -->
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold text-indigo-600 uppercase tracking-wider pb-2.5 border-b border-slate-100 mb-3.5">
                                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                <span>Academic Profile Information</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label for="student_id_number" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Student ID Number</label>
                                    <input type="text" 
                                           id="student_id_number" 
                                           name="student_id_number" 
                                           value="{{ old('student_id_number', $student->student_id_number) }}" 
                                           placeholder="e.g. STU-2024-088"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="cohort_year" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Cohort Graduation Year</label>
                                    <input type="number" 
                                           id="cohort_year" 
                                           name="cohort_year" 
                                           value="{{ old('cohort_year', $student->cohort_year ?? 2024) }}" 
                                           min="2020" 
                                           max="2035"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="department" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Faculty / Department</label>
                                    <input type="text" 
                                           id="department" 
                                           name="department" 
                                           value="{{ old('department', $student->department ?? 'Computer Science & IT') }}" 
                                           placeholder="e.g. Computer Science & IT"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="major" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Degree Major</label>
                                    <input type="text" 
                                           id="major" 
                                           name="major" 
                                           value="{{ old('major', $student->major ?? 'Software Engineering') }}" 
                                           placeholder="e.g. Software Engineering"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="gpa" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Cumulative GPA (Scale: 0.00 - 4.00)</label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0.00" 
                                           max="4.00" 
                                           id="gpa" 
                                           name="gpa" 
                                           value="{{ old('gpa', $student->gpa) }}" 
                                           placeholder="e.g. 3.50"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                    <p class="text-[10px] text-slate-500 mt-1">Enter your current cumulative GPA as shown on your academic transcript.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Personal Information & Background -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5 pb-2.5 border-b border-slate-100 mb-3.5">
                                <i data-lucide="user-pen" class="w-4 h-4 text-indigo-600"></i>
                                Personal Information & Skills
                            </h3>

                            <div class="space-y-3.5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                    <div>
                                        <label for="name" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Full Name</label>
                                        <input type="text" id="name" value="{{ $user->name }}" disabled class="w-full p-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                                    </div>

                                    <div>
                                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address</label>
                                        <input type="email" id="email" value="{{ $user->email }}" disabled class="w-full p-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl text-slate-500 cursor-not-allowed">
                                    </div>
                                </div>

                                <div>
                                    <label for="phone" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Contact Phone Number</label>
                                    <input type="text" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $student->phone) }}" 
                                           placeholder="+1 (555) 000-0000"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                </div>

                                <div>
                                    <label for="skills_input" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Technical Skills & Competencies (Comma-separated)</label>
                                    <input type="text" 
                                           id="skills_input" 
                                           name="skills_input" 
                                           value="{{ old('skills_input', is_array($student->skills) ? implode(', ', $student->skills) : '') }}" 
                                           placeholder="PHP, Laravel, Vue.js, MySQL, Docker, Tailwind CSS"
                                           class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @if (is_array($student->skills))
                                            @foreach ($student->skills as $skill)
                                                <span class="px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-semibold border border-indigo-100">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <label for="bio" class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1">Professional Summary / Bio</label>
                                    <textarea id="bio" 
                                              name="bio" 
                                              rows="3" 
                                              placeholder="Brief summary of your academic projects, career interests, and passion..."
                                              class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">{{ old('bio', $student->bio) }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100 mt-4">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2 cursor-pointer hover:translate-y-[-1px]">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>

        <!-- Hidden Delete Resume Form -->
        <form id="delete-resume-form" action="{{ route('student.resume.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>
</x-layout>
