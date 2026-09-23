<x-layout>
    <div class="space-y-6"
         x-data="{
             statusModalOpen: false,
             statusApp: {
                 id: '',
                 candidateName: '',
                 roleTitle: '',
                 status: 'pending',
                 companyNotes: '',
                 updateUrl: ''
             },
             openStatusModal(data) {
                 this.statusApp = { ...data };
                 this.statusModalOpen = true;
             }
         }">

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
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interviewed" {{ request('status') === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>

        <!-- Applicants Grid / Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($applications->isNotEmpty())
                <div class="w-full overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs table-fixed">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6 w-[24%]">Student Candidate</th>
                                <th class="py-3.5 px-4 w-[22%]">Role Applied</th>
                                <th class="py-3.5 px-4 w-[14%] whitespace-nowrap">Status</th>
                                <th class="py-3.5 px-4 w-[26%]">Statement / Cover Letter</th>
                                <th class="py-3.5 px-6 text-right w-[14%] whitespace-nowrap">Review Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 px-6 min-w-0">
                                        <div class="font-extrabold text-slate-900 text-sm truncate">{{ $app->studentProfile->user->name }}</div>
                                        <div class="text-slate-600 font-semibold truncate">{{ $app->studentProfile->major }} (GPA: {{ $app->studentProfile->gpa ?? 'N/A' }})</div>
                                        @if (is_array($app->studentProfile->skills))
                                            <div class="flex flex-wrap gap-1 mt-1.5">
                                                @foreach (array_slice($app->studentProfile->skills, 0, 3) as $skill)
                                                    <span class="px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold truncate max-w-[100px]">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 min-w-0">
                                        <div class="font-bold text-slate-900 truncate" title="{{ $app->internshipPost->title }}">{{ $app->internshipPost->title }}</div>
                                        <span class="text-[11px] text-slate-600 block truncate">Applied {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-4 px-4 whitespace-nowrap">
                                        <x-status-badge :status="$app->status" />
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 min-w-0">
                                        <p class="line-clamp-2 text-xs break-all break-words leading-relaxed">{{ $app->cover_letter }}</p>
                                        @if ($app->company_notes)
                                            <div class="mt-1 text-[11px] text-emerald-700 font-medium truncate break-all break-words">Note: {{ $app->company_notes }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @if($app->custom_resume_path || $app->studentProfile->resume_path)
                                                <a href="{{ route('company.applicants.resume', $app) }}" 
                                                   target="_blank"
                                                   class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                   title="View Resume">
                                                    <i class="fa-solid fa-file-lines text-xs text-emerald-600"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('company.messages.start', $app) }}" 
                                               class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                               title="Chat with Candidate">
                                                <i class="fa-solid fa-comment-dots text-xs"></i>
                                            </a>
                                            <button type="button"
                                                    @click="openStatusModal({
                                                        id: {{ $app->id }},
                                                        candidateName: @js($app->studentProfile->user->name),
                                                        roleTitle: @js($app->internshipPost->title),
                                                        status: @js($app->status),
                                                        companyNotes: @js($app->company_notes ?? ''),
                                                        updateUrl: @js(route('company.applicants.update', $app))
                                                    })"
                                                    class="w-8 h-8 flex items-center justify-center text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors border border-slate-200/60 cursor-pointer"
                                                    title="Update Candidacy Status">
                                                <i class="fa-solid fa-sliders text-xs"></i>
                                            </button>
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
                    <i class="fa-solid fa-users w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No applicants match this filter</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Select a different role or reset filters to review incoming candidate profiles.</p>
                </div>
            @endif
        </div>

        {{-- Update Candidacy Status Modal --}}
        @include('company.applicants.modal-status')

    </div>
</x-layout>
