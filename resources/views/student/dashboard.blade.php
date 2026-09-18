<x-layout title="Student Dashboard — Internship Management System">
    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 text-white p-6 sm:p-8 shadow-xl shadow-indigo-950/10">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-8 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md border border-white/20 text-indigo-200">
                            {{ $student->student_id_number ?? 'ID: Pending' }}
                        </span>
                        <x-status-badge :status="$student->eligibility_status ?? 'pending'" />
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Hello, {{ Auth::user()->name }}! 👋
                    </h2>
                    <p class="text-indigo-200 text-sm mt-1 max-w-xl">
                        {{ $student->major ?? 'Software Engineering' }} • {{ $student->department ?? 'Faculty of Computing' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('student.posts.index') }}" class="px-4 py-2.5 rounded-xl bg-white text-indigo-700 font-bold text-xs hover:bg-indigo-50 shadow-md transition-all flex items-center gap-2">
                        <i data-lucide="briefcase" class="w-4 h-4"></i>
                        Explore Internships
                    </a>
                    <a href="{{ route('student.logs.index') }}" class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-xs hover:bg-white/20 transition-all flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        Submit Weekly Log
                    </a>
                </div>
            </div>
        </div>

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Total Applications</span>
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i data-lucide="send" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900">{{ $stats['total_applications'] }}</h3>
                    <p class="text-xs text-slate-600 mt-1">Submitted positions</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Shortlisted</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="sparkles" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900">{{ $stats['shortlisted'] }}</h3>
                    <p class="text-xs text-slate-600 mt-1">Interview invitations</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Hours Approved</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900">{{ number_format($stats['hours_logged'], 1) }}h</h3>
                    <p class="text-xs text-emerald-600 font-semibold mt-1">Of {{ $stats['hours_required'] }}h target</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600">Completion</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i data-lucide="percent" class="w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-slate-900">{{ $stats['progress_percent'] }}%</h3>
                    <p class="text-xs text-slate-600 mt-1">Accreditation progress</p>
                </div>
            </div>
        </div>

        <!-- Active Placement Status Card -->
        @if ($activePlacement)
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Current Active Internship</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900">
                            {{ $activePlacement->internshipPost->title }}
                        </h3>
                        <p class="text-xs text-slate-600">
                            Host Organization: <strong class="text-slate-800">{{ $activePlacement->companyProfile->company_name }}</strong> • Supervisor: <strong class="text-slate-800">{{ $activePlacement->supervisor->name ?? 'Dr. Eleanor Vance' }}</strong>
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-indigo-600">{{ $activePlacement->completionPercentage() }}%</span>
                        <span class="block text-[11px] text-slate-600">Target: {{ $activePlacement->total_hours_required }} Hours</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="flex justify-between text-xs font-semibold text-slate-600 mb-2">
                        <span>{{ number_format($activePlacement->totalHoursLogged(), 1) }} Hours Approved</span>
                        <span>{{ max(0, $activePlacement->total_hours_required - $activePlacement->totalHoursLogged()) }} Hours Remaining</span>
                    </div>
                    <div class="w-full h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-full transition-all duration-500" 
                             style="width: {{ $activePlacement->completionPercentage() }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2 text-xs text-slate-600">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="block text-slate-600 text-[10px] uppercase font-bold">Start Date</span>
                        <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($activePlacement->start_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="block text-slate-600 text-[10px] uppercase font-bold">End Date</span>
                        <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($activePlacement->end_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="block text-slate-600 text-[10px] uppercase font-bold">Status</span>
                        <span class="font-semibold text-emerald-600 uppercase">Active Agreement</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Two Column Content: Recent Logs & Applications -->
        <div class="grid lg:grid-cols-2 gap-6">
            
            <!-- Recent Weekly Logs -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-indigo-500"></i>
                        Recent Weekly Log Submissions
                    </h4>
                    <a href="{{ route('student.logs.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                </div>

                @if ($recentLogs->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($recentLogs as $log)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900">Week #{{ $log->week_number }}</span>
                                        <x-status-badge :status="$log->status" />
                                    </div>
                                    <p class="text-xs text-slate-600 line-clamp-2 mt-1">{{ $log->tasks_summary }}</p>
                                    <span class="text-[11px] text-slate-600 mt-1 block">Hours: {{ $log->hours_completed }}h</span>
                                </div>
                                <span class="text-[11px] text-slate-600 shrink-0">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-slate-600 text-xs">
                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        No weekly logs submitted yet.
                    </div>
                @endif
            </div>

            <!-- Recent Applications -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4 text-indigo-500"></i>
                        Application Status Tracker
                    </h4>
                    <a href="{{ route('student.applications.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                </div>

                @if ($recentApplications->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($recentApplications as $app)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $app->internshipPost->title }}</h5>
                                    <p class="text-xs text-slate-600">{{ $app->internshipPost->companyProfile->company_name }}</p>
                                    <span class="text-[11px] text-slate-600 mt-1 block">Applied: {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                </div>
                                <x-status-badge :status="$app->status" />
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-slate-600 text-xs">
                        <i data-lucide="file-question" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        You haven't applied for any positions yet.
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout>
