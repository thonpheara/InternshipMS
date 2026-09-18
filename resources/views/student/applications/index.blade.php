<x-layout title="My Applications — Internship Management System">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">My Internship Applications</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Real-time status updates from host company recruiters.</p>
            </div>
            <a href="{{ route('student.posts.index') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition-colors flex items-center gap-1.5 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Explore Open Roles</span>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($applications->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Role & Host Company</th>
                                <th class="py-3.5 px-4">Applied Date</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6">Recruiter Feedback</th>
                                <th class="py-3.5 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach ($applications as $app)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $app->internshipPost->title }}</div>
                                        <div class="text-slate-600 font-semibold">{{ $app->internshipPost->companyProfile->company_name }} • {{ ucfirst($app->internshipPost->type) }}</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($app->applied_at)->format('M d, Y') }}
                                        <span class="block text-[10px] text-slate-600">{{ \Carbon\Carbon::parse($app->applied_at)->diffForHumans() }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$app->status" />
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 max-w-xs">
                                        {{ $app->company_notes ?: 'Application under evaluation by recruitment team.' }}
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <a href="{{ route('student.messages.start', $app) }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 hover:text-indigo-600 bg-slate-100 hover:bg-indigo-50 px-2.5 py-1.5 rounded-xl border border-slate-200/60 transition-all" title="Message Host Company">
                                                <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                                <span>Message</span>
                                            </a>
                                            <a href="{{ route('student.posts.show', $app->internshipPost) }}" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 px-2 py-1">
                                                <span>View Job</span>
                                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                            </a>
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
                    <i data-lucide="file-x" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No applications on record</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Browse through our accredited internship vacancies and submit your first application.</p>
                    <a href="{{ route('student.posts.index') }}" class="inline-block px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">
                        Browse Positions
                    </a>
                </div>
            @endif
        </div>

    </div>
</x-layout>
