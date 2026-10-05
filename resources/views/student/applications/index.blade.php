<x-layout>
    <div class="flex flex-col h-[calc(100vh-6rem)] sm:h-[calc(100vh-7rem)] lg:h-[calc(100vh-8rem)]">

        <div class="flex items-center justify-between pb-4 shrink-0">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">My Internship Applications</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Real-time status updates from host company recruiters.</p>
            </div>
            <a href="{{ route('student.posts.index') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-colors flex items-center gap-1.5 shadow-xs shrink-0">
                <i class="fa-solid fa-plus w-4 h-4"></i>
                <span>Explore Open Roles</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between flex-1 min-h-0">
            @if ($applications->isNotEmpty())
                <div class="w-full overflow-auto flex-1 min-h-0">
                    <table class="w-full text-left border-collapse text-xs table-fixed">
                        <thead class="sticky top-0 z-10 bg-slate-50/95 backdrop-blur-xs">
                            <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3 px-6 w-[32%]">Role & Host Company</th>
                                <th class="py-3 px-4 w-[14%] whitespace-nowrap">Applied Date</th>
                                <th class="py-3 px-4 w-[12%] whitespace-nowrap">Status</th>
                                <th class="py-3 px-6 w-[28%]">Recruiter Feedback</th>
                                <th class="py-3 px-6 text-right w-[14%] whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3.5 px-6 min-w-0">
                                        <div class="font-extrabold text-slate-900 text-sm truncate" title="{{ $app->internshipPost->title }}">{{ $app->internshipPost->title }}</div>
                                        <div class="text-slate-600 font-semibold truncate">{{ $app->internshipPost->companyProfile->company_name }} • {{ $app->internshipPost->location }}</div>
                                        @php
                                             $appResumePath = $app->getEffectiveResumePath();
                                             $resumeExists = $appResumePath && (Storage::disk('local')->exists($appResumePath) || Storage::disk('public')->exists($appResumePath));
                                        @endphp
                                        @if($resumeExists)
                                            <div class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-medium mt-1 truncate">
                                                <i class="fa-solid fa-file-circle-check text-[10px]"></i>
                                                <span class="truncate">{{ basename($appResumePath) }} attached</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 whitespace-nowrap min-w-0">
                                        <span class="font-semibold text-slate-900 block">{{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}</span>
                                        <span class="text-[10px] text-slate-500 block">{{ \Carbon\Carbon::parse($app->applied_at)->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <x-status-badge :status="$app->status" />
                                    </td>
                                    <td class="py-3.5 px-6 text-slate-600 min-w-0">
                                        <p class="line-clamp-2 text-xs leading-relaxed" title="{{ $app->company_notes ?: 'Application under evaluation by recruitment team.' }}">
                                            {{ $app->company_notes ?: 'Application under evaluation by recruitment team.' }}
                                        </p>
                                    </td>
                                    <td class="py-3.5 px-6 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @if($resumeExists)
                                                <a href="{{ route('student.resume.preview', ['application_id' => $app->id]) }}" 
                                                   target="_blank" 
                                                   class="w-8 h-8 inline-flex items-center justify-center text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl border border-slate-200/70 transition-colors" 
                                                   title="View Submitted Resume">
                                                    <i class="fa-solid fa-file-lines text-xs text-emerald-600"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('student.messages.start', $app) }}" 
                                               class="w-8 h-8 inline-flex items-center justify-center text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl border border-slate-200/70 transition-colors" 
                                               title="Message Host Company">
                                                <i class="fa-solid fa-comment-dots text-xs"></i>
                                            </a>
                                            <a href="{{ route('student.posts.show', $app->internshipPost) }}" 
                                               class="w-8 h-8 inline-flex items-center justify-center text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-xl border border-slate-200/70 transition-colors" 
                                               title="View Job Details">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 bg-white shrink-0">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-16 text-center text-slate-600 space-y-3">
                    <i class="fa-solid fa-file-circle-xmark w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No applications on record</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Browse through our accredited internship vacancies and submit your first application.</p>
                    <a href="{{ route('student.posts.index') }}" class="inline-block px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors">
                        Browse Positions
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layout>
