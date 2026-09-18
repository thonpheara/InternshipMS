<x-layout title="Applicant Pipeline — Internship Management System">
    <div class="space-y-6">

        <!-- Page Header & Filter Form -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Applicant Review Board</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Review student resumes, shortlist profiles, and issue placement offers.</p>
            </div>

            <form action="{{ route('company.applicants.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                <select name="post_id" onchange="this.form.submit()" class="py-2 pl-3 pr-8 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium text-slate-700">
                    <option value="">All Job Listings</option>
                    @foreach ($companyPosts as $p)
                        <option value="{{ $p->id }}" {{ request('post_id') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="py-2 pl-3 pr-8 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium text-slate-700">
                    <option value="">All Candidacy Stages</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interviewed" {{ request('status') === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted / Placed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>

        <!-- Applicants Grid / Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($applications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Student Candidate</th>
                                <th class="py-3.5 px-4">Role Applied</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6">Statement / Cover Letter</th>
                                <th class="py-3.5 px-6 text-right">Review Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ openUpdate: false }">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $app->studentProfile->user->name }}</div>
                                        <div class="text-slate-600 font-semibold">{{ $app->studentProfile->major }} (GPA: {{ $app->studentProfile->gpa ?? 'N/A' }})</div>
                                        @if (is_array($app->studentProfile->skills))
                                            <div class="flex flex-wrap gap-1 mt-1.5">
                                                @foreach (array_slice($app->studentProfile->skills, 0, 3) as $skill)
                                                    <span class="px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <div class="font-bold text-slate-900">{{ $app->internshipPost->title }}</div>
                                        <span class="text-[11px] text-slate-600">Applied {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$app->status" />
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 max-w-sm">
                                        <p class="line-clamp-2 text-xs">{{ $app->cover_letter }}</p>
                                        @if ($app->company_notes)
                                            <div class="mt-1 text-[11px] text-emerald-700 font-medium">Note: {{ $app->company_notes }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @if($app->custom_resume_path || $app->studentProfile->resume_path)
                                                <a href="{{ route('company.applicants.resume', $app) }}" 
                                                   target="_blank"
                                                   class="px-2.5 py-1.5 text-slate-700 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 rounded-xl transition-all inline-flex items-center gap-1 text-xs font-bold border border-slate-200/60"
                                                   title="View candidate resume">
                                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-indigo-600"></i>
                                                    <span>Resume</span>
                                                </a>
                                            @endif
                                            <a href="{{ route('company.messages.start', $app) }}" 
                                               class="px-2.5 py-1.5 text-slate-700 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-all inline-flex items-center gap-1 text-xs font-bold border border-slate-200/60"
                                               title="Chat with candidate">
                                                <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                                <span>Message</span>
                                            </a>
                                            <button @click="openUpdate = !openUpdate" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 text-xs font-bold transition-all inline-flex items-center gap-1 border border-slate-200/60">
                                                <span>Update Status</span>
                                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </div>

                                        <!-- Expandable Status Update Drawer -->
                                        <div x-show="openUpdate" x-transition class="mt-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3">
                                            <form action="{{ route('company.applicants.update', $app) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Set Candidacy Stage</label>
                                                    <select name="status" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                                        <option value="pending" {{ $app->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                                        <option value="under_review" {{ $app->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                                        <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                        <option value="interviewed" {{ $app->status === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                                        <option value="accepted" {{ $app->status === 'accepted' ? 'selected' : '' }}>Offer Accepted (Creates Active Placement)</option>
                                                        <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </div>

                                                <div class="mt-2">
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Recruiter Feedback Note</label>
                                                    <input type="text" name="company_notes" value="{{ $app->company_notes }}" placeholder="e.g. Schedule technical interview for next Tuesday" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                                </div>

                                                <div class="mt-3 flex justify-end gap-2">
                                                    <button type="button" @click="openUpdate = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Cancel</button>
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs">Save Stage</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="users" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No applicants match this filter</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Select a different role or reset filters to review incoming candidate profiles.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
