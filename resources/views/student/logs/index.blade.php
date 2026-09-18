<x-layout title="Weekly Logs & Hours — Internship Management System">
    <div class="space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Weekly Activity Logbook</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Submit weekly engineering reports and track your university accreditation hours.</p>
            </div>
            
            @if ($placement)
                <div class="text-right">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 block">Total Approved Hours</span>
                    <span class="text-xl font-black text-indigo-600">{{ number_format($placement->totalHoursLogged(), 1) }} / {{ $placement->total_hours_required }} hrs</span>
                </div>
            @endif
        </div>

        @if ($placement)
            <!-- Progress Bar Card -->
            <div class="p-6 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex justify-between text-xs font-bold text-slate-700">
                    <span>Internship: {{ $placement->internshipPost->title }} ({{ $placement->companyProfile->company_name }})</span>
                    <span class="text-indigo-600">{{ $placement->completionPercentage() }}% Completed</span>
                </div>
                <div class="w-full h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500 rounded-full transition-all duration-500" 
                         style="width: {{ $placement->completionPercentage() }}%"></div>
                </div>
            </div>

            <!-- Submit New Log Form Card -->
            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Submit Log for Week #{{ $nextWeekNumber }}</h3>
                        <p class="text-xs text-slate-600">Summarize the tasks you executed and key technical challenges encountered.</p>
                    </div>
                </div>

                <form action="{{ route('student.logs.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label for="week_number" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Week #</label>
                            <input type="number" 
                                   id="week_number" 
                                   name="week_number" 
                                   value="{{ old('week_number', $nextWeekNumber) }}" 
                                   required 
                                   min="1" 
                                   max="52"
                                   class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                        </div>

                        <div>
                            <label for="start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Start Date</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date', now()->subDays(6)->toDateString()) }}" 
                                   required
                                   class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                        </div>

                        <div>
                            <label for="end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">End Date</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date', now()->toDateString()) }}" 
                                   required
                                   class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                        </div>

                        <div>
                            <label for="hours_completed" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Hours Logged</label>
                            <input type="number" 
                                   step="0.5" 
                                   id="hours_completed" 
                                   name="hours_completed" 
                                   value="{{ old('hours_completed', 40.0) }}" 
                                   required 
                                   min="1" 
                                   max="80"
                                   class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">
                        </div>
                    </div>

                    <div>
                        <label for="tasks_summary" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Weekly Tasks Summary & Deliverables</label>
                        <textarea id="tasks_summary" 
                                  name="tasks_summary" 
                                  rows="3" 
                                  required 
                                  placeholder="Detail the modules, code commits, database migrations, or client deliverables completed this week..."
                                  class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">{{ old('tasks_summary') }}</textarea>
                    </div>

                    <div>
                        <label for="learnings_challenges" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Key Learnings & Obstacles Overcome (Optional)</label>
                        <textarea id="learnings_challenges" 
                                  name="learnings_challenges" 
                                  rows="2" 
                                  placeholder="Tools mastered, debugging hurdles, architectural insights gained..."
                                  class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-slate-900">{{ old('learnings_challenges') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition-all flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Submit Weekly Log</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Historical Logbook Entries Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Historical Weekly Logs</h3>
                    <span class="text-xs text-slate-600">{{ $logs->count() }} Entries Logged</span>
                </div>

                @if ($logs->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                    <th class="py-3.5 px-6">Week #</th>
                                    <th class="py-3.5 px-4">Date Range</th>
                                    <th class="py-3.5 px-4">Hours</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-6">Company Feedback</th>
                                    <th class="py-3.5 px-6">Faculty Feedback</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($logs as $log)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-4 px-6 font-extrabold text-slate-900">
                                            Week {{ $log->week_number }}
                                        </td>
                                        <td class="py-4 px-4 text-slate-600">
                                            {{ \Carbon\Carbon::parse($log->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($log->end_date)->format('M d, Y') }}
                                        </td>
                                        <td class="py-4 px-4 font-bold text-slate-900">
                                            {{ $log->hours_completed }}h
                                        </td>
                                        <td class="py-4 px-4">
                                            <x-status-badge :status="$log->status" />
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 max-w-xs">
                                            {{ $log->company_feedback ?: 'Awaiting mentor review' }}
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 max-w-xs">
                                            {{ $log->supervisor_feedback ?: 'Awaiting faculty review' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-12 text-center text-slate-600 text-xs">
                        No logs have been recorded for this placement yet.
                    </div>
                @endif
            </div>

        @else
            <!-- No active placement banner -->
            <div class="p-12 rounded-3xl bg-white border border-slate-200/80 text-center space-y-3">
                <i data-lucide="briefcase" class="w-12 h-12 mx-auto text-slate-300"></i>
                <h3 class="text-base font-bold text-slate-800">No Active Internship Placement</h3>
                <p class="text-xs text-slate-600 max-w-md mx-auto">
                    Weekly activity reporting becomes available once an internship application is officially accepted by a host company and recorded in the system.
                </p>
                <a href="{{ route('student.posts.index') }}" class="inline-block px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">
                    Find and Apply for Internships
                </a>
            </div>
        @endif

    </div>
</x-layout>
