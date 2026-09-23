<x-layout>
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Top Breadcrumb & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white border border-[#E5E7EB] text-gray-500 hover:text-[#111827] hover:bg-gray-50 transition-colors shadow-xs">
                    <i class="fa-solid fa-arrow-left w-4 h-4"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-[#111827]">User Details</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Full profile, account settings, and participation history.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs sm:text-sm font-semibold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-pen-to-square w-4 h-4"></i>
                    <span>Edit User</span>
                </a>

                @if ($user->id !== Auth::id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to permanently delete the {{ $user->role }} account \'{{ $user->name }}\'?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs sm:text-sm font-semibold transition-colors cursor-pointer">
                            <i class="fa-solid fa-trash-can w-4 h-4"></i>
                            <span>Delete</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Main Content 2-Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Identity Card -->
            <div class="lg:col-span-4 space-y-5">
                <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 flex flex-col items-center text-center space-y-4">
                    <!-- Squircle Avatar -->
                    <div class="relative">
                        <div class="w-20 h-20 rounded-2xl bg-[#059669] text-white text-2xl font-black flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        @if ($user->isActive())
                            <span class="w-4 h-4 rounded-full bg-emerald-500 border-2 border-white absolute -bottom-1 -right-1" title="Active Account"></span>
                        @else
                            <span class="w-4 h-4 rounded-full bg-rose-500 border-2 border-white absolute -bottom-1 -right-1" title="Suspended Account"></span>
                        @endif
                    </div>

                    <!-- User Name & Email -->
                    <div>
                        <h2 class="text-lg font-bold text-[#111827] leading-tight">
                            {{ $user->name }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $user->email }}
                        </p>
                    </div>

                    <!-- Badges -->
                    <div class="flex items-center justify-center gap-2 pt-1">
                        @if ($user->isStudent())
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46] border border-[#A7F3D0] flex items-center gap-1.5">
                                <i class="fa-solid fa-graduation-cap w-3 h-3"></i>
                                <span>Student</span>
                            </span>
                        @elseif ($user->isCompany())
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                                <i class="fa-solid fa-building w-3 h-3"></i>
                                <span>Host Company</span>
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                {{ ucfirst($user->role) }}
                            </span>
                        @endif

                        @if ($user->isActive())
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Active
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <!-- Metadata List -->
                    <div class="w-full border-t border-gray-100 pt-4 space-y-3 text-xs text-left">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Account ID</span>
                            <span class="font-semibold text-[#111827]">#{{ $user->id }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Joined Date</span>
                            <span class="font-semibold text-[#111827]">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Last Modified</span>
                            <span class="font-semibold text-[#111827]">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</span>
                        </div>
                        @if ($user->isStudent() && isset($activity['applications_count']))
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Applications</span>
                                <span class="font-bold text-[#059669]">{{ $activity['applications_count'] }} Submitted</span>
                            </div>
                        @elseif ($user->isCompany() && isset($activity['posts_count']))
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Job Postings</span>
                                <span class="font-bold text-[#059669]">{{ $activity['posts_count'] }} Listed</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Profile Specifics Card -->
            <div class="lg:col-span-8 space-y-5">
                @if ($user->isStudent() && $user->studentProfile)
                    @php $sp = $user->studentProfile; @endphp
                    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-6">
                        <div>
                            <h3 class="text-lg font-extrabold text-[#111827] flex items-center gap-2">
                                <i class="fa-solid fa-graduation-cap text-[#059669]"></i>
                                Academic Profile Information
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Enrolled student program, progress, and skills.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Student ID Number</span>
                                <span class="font-bold text-[#111827] text-sm mt-0.5 block">{{ $sp->student_id_number ?: 'Not specified' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Cumulative GPA</span>
                                <span class="font-bold text-[#059669] text-sm mt-0.5 block">{{ $sp->gpa !== null ? number_format($sp->gpa, 2) . ' / 4.00' : 'N/A' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Faculty / Department</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $sp->department ?: 'N/A' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Degree Major</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $sp->major ?: 'N/A' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Cohort Year</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $sp->cohort_year ?: 'N/A' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Contact Phone</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $sp->phone ?: 'Not provided' }}</span>
                            </div>
                        </div>

                        <!-- Technical Skills -->
                        <div>
                            <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Technical Skills</span>
                            @if (!empty($sp->skills) && is_array($sp->skills))
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($sp->skills as $skill)
                                        <span class="px-2.5 py-1 rounded-lg bg-[#D1FAE5] text-[#065F46] font-semibold text-xs border border-[#A7F3D0]">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic">No skills listed yet.</p>
                            @endif
                        </div>

                        <!-- Bio -->
                        @if ($sp->bio)
                            <div>
                                <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Student Bio</span>
                                <p class="text-xs text-gray-600 leading-relaxed bg-[#F9FAFB] p-3.5 rounded-2xl border border-[#E5E7EB]">
                                    {{ $sp->bio }}
                                </p>
                            </div>
                        @endif

                        <!-- Resume Link -->
                        @if ($sp->resume_path)
                            <div class="pt-2">
                                <a href="{{ route('student.resume.preview') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-[#111827] text-xs font-semibold transition-colors">
                                    <i class="fa-solid fa-file-pdf text-rose-500 w-4 h-4"></i>
                                    <span>Preview Uploaded Resume</span>
                                </a>
                            </div>
                        @endif
                    </div>

                @elseif ($user->isCompany() && $user->companyProfile)
                    @php $cp = $user->companyProfile; @endphp
                    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-6 sm:p-8 space-y-6">
                        <div>
                            <h3 class="text-lg font-extrabold text-[#111827] flex items-center gap-2">
                                <i class="fa-solid fa-building text-[#059669]"></i>
                                Host Organization Details
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">Corporate identity, location, and recruiter contact info.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Company Name</span>
                                <span class="font-bold text-[#111827] text-sm mt-0.5 block">{{ $cp->company_name }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Industry Sector</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $cp->industry ?: 'General' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Primary Location</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $cp->location ?: 'Not specified' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Contact Person</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $cp->contact_person ?: $user->name }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Contact Phone</span>
                                <span class="font-semibold text-[#111827] mt-0.5 block">{{ $cp->contact_phone ?: 'Not specified' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9FAFB] border border-[#E5E7EB]">
                                <span class="block text-gray-400 text-[10px] uppercase font-bold">Website</span>
                                @if ($cp->website)
                                    <a href="{{ $cp->website }}" target="_blank" class="font-semibold text-[#059669] hover:underline mt-0.5 block truncate">
                                        {{ $cp->website }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic mt-0.5 block">Not specified</span>
                                @endif
                            </div>
                        </div>

                        <!-- Street Address -->
                        @if ($cp->address)
                            <div>
                                <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Office Address</span>
                                <p class="text-xs text-gray-600 bg-[#F9FAFB] p-3.5 rounded-2xl border border-[#E5E7EB]">
                                    {{ $cp->address }}
                                </p>
                            </div>
                        @endif

                        <!-- Description -->
                        @if ($cp->description)
                            <div>
                                <span class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Organization Overview</span>
                                <p class="text-xs text-gray-600 leading-relaxed bg-[#F9FAFB] p-3.5 rounded-2xl border border-[#E5E7EB]">
                                    {{ $cp->description }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs p-8 text-center text-gray-400 text-xs">
                        <i class="fa-solid fa-circle-info w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                        No specific student or company profile record linked with this account.
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout>
