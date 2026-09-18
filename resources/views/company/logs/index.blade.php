<x-layout title="Approve Intern Logs — Internship Management System">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Intern Weekly Log Sign-off</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Audit student timesheets and approve accreditation hours for university credit.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($logs->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Intern & Role</th>
                                <th class="py-3.5 px-4">Week & Dates</th>
                                <th class="py-3.5 px-4">Hours Logged</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-6">Work Summary</th>
                                <th class="py-3.5 px-6 text-right">Review Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ openReview: false }">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $log->placement->studentProfile->user->name }}</div>
                                        <div class="text-slate-600">{{ $log->placement->internshipPost->title }}</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <span class="font-bold text-slate-900">Week #{{ $log->week_number }}</span>
                                        <span class="block text-[11px] text-slate-600">{{ \Carbon\Carbon::parse($log->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($log->end_date)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="py-4 px-4 font-black text-emerald-600 text-sm">
                                        {{ $log->hours_completed }}h
                                    </td>
                                    <td class="py-4 px-4">
                                        <x-status-badge :status="$log->status" />
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 max-w-sm">
                                        <p class="line-clamp-2 text-xs">{{ $log->tasks_summary }}</p>
                                        @if ($log->company_feedback)
                                            <div class="mt-1 text-[11px] text-emerald-700 font-medium">Feedback: {{ $log->company_feedback }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button @click="openReview = !openReview" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 text-xs font-bold transition-all inline-flex items-center gap-1">
                                            <span>Sign Off</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                        </button>

                                        <!-- Sign off drawer -->
                                        <div x-show="openReview" x-transition class="mt-3 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3">
                                            <form action="{{ route('company.logs.update', $log) }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Approval Decision</label>
                                                    <select name="status" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                                        <option value="approved" {{ $log->status === 'approved' ? 'selected' : '' }}>✅ Approve Timesheet & Hours</option>
                                                        <option value="revision_requested" {{ $log->status === 'revision_requested' ? 'selected' : '' }}>⚠️ Request Revisions / More Detail</option>
                                                        <option value="rejected" {{ $log->status === 'rejected' ? 'selected' : '' }}>❌ Reject Log</option>
                                                    </select>
                                                </div>

                                                <div class="mt-2">
                                                    <label class="block text-[10px] uppercase font-bold text-slate-600 mb-1">Company Mentor Feedback</label>
                                                    <input type="text" name="company_feedback" value="{{ $log->company_feedback }}" placeholder="e.g. Good progress on tests; remember to follow commit guidelines" class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                                </div>

                                                <div class="mt-3 flex justify-end gap-2">
                                                    <button type="button" @click="openReview = false" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Cancel</button>
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs">Save Sign-off</button>
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
                    {{ $logs->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="check-circle" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No pending intern logs</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Weekly log submissions from placed interns will appear here for verification.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
