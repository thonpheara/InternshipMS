<x-layout title="Company Dashboard — Internship Management System">
    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-800 via-slate-900 to-teal-950 text-white p-6 sm:p-8 shadow-xl shadow-emerald-950/10">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-8 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md border border-white/20 text-emerald-200">
                            {{ $company->company_name }}
                        </span>
                        <x-status-badge :status="$company->verification_status ?? 'verified'" />
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Recruiter Dashboard
                    </h2>
                    <p class="text-emerald-200 text-sm mt-1 max-w-xl">
                        Industry: {{ $company->industry }} • Location: {{ $company->location }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('company.posts.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition-all flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Post New Internship
                    </a>
                    <a href="{{ route('company.applicants.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-xs hover:bg-white/20 transition-all flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Review Applicants
                    </a>
                </div>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Active Listings</span>
                <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $stats['active_posts'] }}</h3>
                <p class="text-xs text-slate-600 mt-1">Vetted by university</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Total Applicants</span>
                <h3 class="text-2xl font-black text-slate-900 mt-2">{{ $stats['total_applicants'] }}</h3>
                <p class="text-xs text-slate-600 mt-1">Received candidacies</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Pending Reviews</span>
                <h3 class="text-2xl font-black text-amber-600 mt-2">{{ $stats['pending_review'] }}</h3>
                <p class="text-xs text-amber-600 font-semibold mt-1">Action required</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Active Interns</span>
                <h3 class="text-2xl font-black text-emerald-600 mt-2">{{ $stats['active_interns'] }}</h3>
                <p class="text-xs text-slate-600 mt-1">Currently placed</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Weekly Logs to Review</span>
                <h3 class="text-2xl font-black text-purple-600 mt-2">{{ $stats['pending_logs'] }}</h3>
                <p class="text-xs text-slate-600 mt-1">Pending approval</p>
            </div>
        </div>

        <!-- Two Column Workspace: Applicants & Pending Logs -->
        <div class="grid lg:grid-cols-2 gap-6">

            <!-- Recent Applicants -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
                        Recent Candidate Submissions
                    </h4>
                    <a href="{{ route('company.applicants.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Pipeline Board</a>
                </div>

                @if ($recentApplicants->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($recentApplicants as $app)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $app->studentProfile->user->name }}</h5>
                                    <p class="text-xs text-slate-600">{{ $app->internshipPost->title }}</p>
                                    <span class="text-[11px] text-slate-600 mt-0.5 block">GPA: {{ $app->studentProfile->gpa ?? 'N/A' }} • Applied {{ \Carbon\Carbon::parse($app->applied_at)->diffForHumans() }}</span>
                                </div>
                                <x-status-badge :status="$app->status" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 text-xs">
                        No recent applications received yet.
                    </div>
                @endif
            </div>

            <!-- Pending Intern Logs -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-emerald-600"></i>
                        Weekly Logs Awaiting Sign-off
                    </h4>
                    <a href="{{ route('company.logs.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Review All</a>
                </div>

                @if ($pendingLogs->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($pendingLogs as $log)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $log->placement->studentProfile->user->name }} (Week #{{ $log->week_number }})</h5>
                                    <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">{{ $log->tasks_summary }}</p>
                                    <span class="text-[11px] text-emerald-600 font-bold mt-1 block">{{ $log->hours_completed }} Hours Reported</span>
                                </div>
                                <a href="{{ route('company.logs.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 text-xs font-bold transition-all shrink-0">
                                    Sign Off
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 text-xs">
                        <i data-lucide="check-circle-2" class="w-8 h-8 mx-auto mb-2 text-emerald-400"></i>
                        All intern weekly logs have been reviewed!
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout>
