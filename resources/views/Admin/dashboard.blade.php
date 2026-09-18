<x-layout title="Admin / Coordinator Dashboard — Internship Management System">
    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-amber-600 via-slate-900 to-indigo-950 text-white p-6 sm:p-8 shadow-xl shadow-slate-950/10">
            <div class="absolute right-0 top-0 translate-x-12 -translate-y-8 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md border border-white/20 text-amber-200">
                            Academic Coordinator Portal
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500 text-white">System Active</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Institution KPI & Placement Command Center
                    </h2>
                    <p class="text-amber-100 text-sm mt-1 max-w-xl">
                        Monitor university student cohorts, moderate employer postings, and assign faculty supervisors.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.approvals.index') }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs shadow-md transition-all flex items-center gap-2">
                        <i data-lucide="check-square" class="w-4 h-4"></i>
                        Moderate Job Postings
                    </a>
                    <a href="{{ route('admin.placements.index', ['unassigned' => 1]) }}" class="px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold text-xs hover:bg-white/20 transition-all flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Assign Supervisors
                    </a>
                </div>
            </div>
        </div>

        <!-- Institutional Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Total Cohort Students</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="text-2xl font-black text-slate-900">{{ $stats['total_students'] }}</h3>
                    <span class="text-xs font-bold text-emerald-600">{{ $stats['eligible_students'] }} Eligible</span>
                </div>
                <p class="text-xs text-slate-600 mt-1">Enrolled university candidates</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Pending Job Approvals</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="text-2xl font-black {{ $stats['pending_posts'] > 0 ? 'text-amber-600' : 'text-slate-900' }}">
                        {{ $stats['pending_posts'] }}
                    </h3>
                    <span class="text-xs font-semibold text-slate-600">Company Postings</span>
                </div>
                <p class="text-xs text-slate-600 mt-1">Requires coordinator check</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Active Placements</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="text-2xl font-black text-indigo-600">{{ $stats['active_placements'] }}</h3>
                    <span class="text-xs font-bold {{ $stats['unassigned_supervisors'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        {{ $stats['unassigned_supervisors'] }} Unassigned
                    </span>
                </div>
                <p class="text-xs text-slate-600 mt-1">Ongoing student internships</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Total Hours Logged</span>
                <div class="flex items-baseline justify-between mt-2">
                    <h3 class="text-2xl font-black text-emerald-600">{{ number_format($stats['total_hours_logged'], 0) }}h</h3>
                    <span class="text-xs font-semibold text-slate-600">Cohort Total</span>
                </div>
                <p class="text-xs text-slate-600 mt-1">Verified timesheet hours</p>
            </div>
        </div>

        <!-- Moderation & Placement Queues -->
        <div class="grid lg:grid-cols-2 gap-6">

            <!-- Job Moderation Queue -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="check-square" class="w-4 h-4 text-amber-500"></i>
                        Postings Needing Moderation
                    </h4>
                    <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800">Review Queue</a>
                </div>

                @if ($pendingPosts->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($pendingPosts as $post)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $post->title }}</h5>
                                    <p class="text-xs text-slate-600">{{ $post->companyProfile->company_name }} • {{ ucfirst($post->type) }}</p>
                                    <span class="text-[11px] text-slate-600 mt-0.5 block">Deadline: {{ \Carbon\Carbon::parse($post->deadline)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <form action="{{ route('admin.approvals.update', $post) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.approvals.index') }}" class="p-1 text-slate-400 hover:text-slate-600">
                                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 text-xs">
                        <i data-lucide="check-check" class="w-8 h-8 mx-auto mb-2 text-emerald-400"></i>
                        No pending employer job postings awaiting moderation.
                    </div>
                @endif
            </div>

            <!-- Placements Needing Supervisor Assignment -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4 text-indigo-500"></i>
                        Unassigned Faculty Placements
                    </h4>
                    <a href="{{ route('admin.placements.index', ['unassigned' => 1]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
                </div>

                @if ($unassignedPlacements->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($unassignedPlacements as $pl)
                            <div class="py-3.5 flex items-start justify-between gap-4">
                                <div>
                                    <h5 class="text-xs font-bold text-slate-900">{{ $pl->studentProfile->user->name }}</h5>
                                    <p class="text-xs text-slate-600">{{ $pl->internshipPost->title }} at {{ $pl->companyProfile->company_name }}</p>
                                    <span class="text-[11px] text-rose-600 font-semibold mt-0.5 block">No faculty supervisor assigned</span>
                                </div>
                                <a href="{{ route('admin.placements.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 text-xs font-bold transition-all shrink-0">
                                    Assign
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 text-xs">
                        <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-2 text-indigo-400"></i>
                        All active student placements have assigned faculty supervisors.
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-layout>
