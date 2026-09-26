<x-layout>
    <div class="space-y-6">

        <!-- Page Header & Export Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#111827]">Reports</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Comprehensive placement analytics, employer hiring pipelines, and student outcomes.</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2">
                <!-- Export to CSV / Excel -->
                <a href="{{ route('admin.reports.exportCsv', request()->query()) }}" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-file-excel w-4 h-4"></i>
                    <span>Export CSV (Excel)</span>
                </a>

                <!-- Print / PDF View -->
                <a href="{{ route('admin.reports.print', request()->query()) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-xs transition-all cursor-pointer">
                    <i class="fa-solid fa-print w-4 h-4"></i>
                    <span>Print / PDF</span>
                </a>
            </div>
        </div>

        <!-- KPI Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Applications -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-file-lines w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Total Applications</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ number_format($stats['total_applications']) }}</div>
                </div>
            </div>

            <!-- Accepted Placements -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#059669] flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-check w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Accepted Placements</span>
                    <div class="text-xl font-black text-[#059669] mt-0.5">{{ number_format($stats['total_accepted']) }}</div>
                </div>
            </div>

            <!-- Placement Rate -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-chart-pie w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Placement Success Rate</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ $stats['placement_rate'] }}%</div>
                </div>
            </div>

            <!-- Hiring Partner Companies -->
            <div class="p-4 bg-white rounded-2xl border border-[#E5E7EB] shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-building-user w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs font-semibold text-gray-500">Active Hiring Partners</span>
                    <div class="text-xl font-black text-[#111827] mt-0.5">{{ number_format($stats['total_companies_hiring']) }}</div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-4 shadow-xs">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <!-- Status Filter -->
                <div class="flex items-center gap-1.5">
                    <label class="text-[11px] font-bold text-gray-500 uppercase">Status:</label>
                    <select name="status" class="py-1.5 px-3 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Accepted (Placed)</option>
                        <option value="shortlisted" {{ $status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="interviewed" {{ $status === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                        <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Department Filter -->
                @if($departments->isNotEmpty())
                    <div class="flex items-center gap-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase">Department:</label>
                        <select name="department" class="py-1.5 px-3 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                            <option value="all" {{ $department === 'all' ? 'selected' : '' }}>All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Year Filter -->
                @if($years->isNotEmpty())
                    <div class="flex items-center gap-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase">Year:</label>
                        <select name="year" class="py-1.5 px-3 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                            <option value="all" {{ $year === 'all' ? 'selected' : '' }}>All Years</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ (string)$year === (string)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass w-3.5 h-3.5"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search candidate, ID, company..." 
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-white border border-[#E5E7EB] rounded-xl focus:ring-2 focus:ring-[#059669] focus:outline-hidden">
                </div>

                <!-- Submit & Reset -->
                <button type="submit" class="px-4 py-1.5 rounded-xl bg-[#059669] hover:bg-[#047857] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
                    Apply Filter
                </button>
                @if($status !== 'all' || $department !== 'all' || $year !== 'all' || !empty($search))
                    <a href="{{ route('admin.reports.index') }}" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Reports Data Table -->
        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden">
            @if ($applications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 bg-[#F9FAFB] text-[11px] font-bold uppercase tracking-wider text-gray-500">
                                <th class="py-3.5 px-6">Candidate / Student</th>
                                <th class="py-3.5 px-4">Academic Program</th>
                                <th class="py-3.5 px-4">Host Company</th>
                                <th class="py-3.5 px-4">Internship Role & Stipend</th>
                                <th class="py-3.5 px-4">Placement Status</th>
                                <th class="py-3.5 px-6 text-right">Applied Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($applications as $app)
                                @php
                                    $student = $app->studentProfile;
                                    $user = $student?->user;
                                    $post = $app->internshipPost;
                                    $company = $post?->companyProfile;
                                    $initials = strtoupper(substr($user?->name ?: 'ST', 0, 2));
                                @endphp
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <!-- Candidate -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-[#059669] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                                {{ $initials }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-extrabold text-[#111827] text-sm">{{ $user?->name ?? 'N/A' }}</div>
                                                <span class="text-gray-500 text-xs block truncate">{{ $user?->email ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Academic Program -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-[#111827]">{{ $student?->student_id_number ?: 'ID Unassigned' }}</div>
                                        <span class="text-xs text-gray-600 block">{{ $student?->major ?: 'General' }}</span>
                                        <span class="text-[11px] text-gray-400 block">{{ $student?->department ?: 'N/A' }} • GPA: {{ $student?->gpa !== null ? number_format($student->gpa, 2) : 'N/A' }}</span>
                                    </td>

                                    <!-- Company -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-[#111827]">{{ $company?->company_name ?: 'Unnamed Company' }}</div>
                                        <span class="text-gray-500 text-[11px] block">{{ $company?->industry ?: 'N/A' }}</span>
                                        <span class="text-gray-400 text-[10px] block">{{ $company?->location }}</span>
                                    </td>

                                    <!-- Role & Stipend -->
                                    <td class="py-4 px-4 text-gray-600">
                                        <div class="font-bold text-gray-900 text-xs line-clamp-1">{{ $post?->title }}</div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            @if($post?->category)
                                                <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                                                    {{ $post->category }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] font-bold text-[#059669]">
                                                {{ $post?->stipend ? '$' . number_format($post->stipend, 0) . '/mo' : 'Standard' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Placement Status -->
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$app->status" />
                                    </td>

                                    <!-- Applied Date -->
                                    <td class="py-4 px-6 text-right text-gray-500 text-[11px]">
                                        <div>{{ $app->applied_at ? $app->applied_at->format('M d, Y') : ($app->created_at ? $app->created_at->format('M d, Y') : 'N/A') }}</div>
                                        @if($app->reviewed_at)
                                            <span class="text-[10px] text-gray-400 block">Reviewed: {{ $app->reviewed_at->format('M d') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-16 text-center text-gray-400 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center mx-auto mb-2">
                        <i class="fa-solid fa-file-invoice w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#111827]">No Placement Records Found</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">No candidate placement records match the current filter selection.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
