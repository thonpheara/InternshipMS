<x-layout title="Student Evaluations — Internship Management System">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900">Intern Performance Evaluations</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Submit formal midterm and final internship milestone assessments.</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            @if ($placements->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-600">
                                <th class="py-3.5 px-6">Intern Name</th>
                                <th class="py-3.5 px-4">Role Title</th>
                                <th class="py-3.5 px-4">Duration Range</th>
                                <th class="py-3.5 px-4">Submitted Evaluations</th>
                                <th class="py-3.5 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($placements as $placement)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-4 px-6 font-extrabold text-slate-900">
                                        <div class="text-sm">{{ $placement->studentProfile->user->name }}</div>
                                        <span class="text-slate-600 font-semibold">{{ $placement->studentProfile->student_id_number }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        <span class="font-bold text-slate-900">{{ $placement->internshipPost->title }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($placement->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($placement->end_date)->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-4">
                                        @if ($placement->evaluations->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($placement->evaluations as $ev)
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                                        {{ ucfirst($ev->type) }} ({{ $ev->performance_rating }}/5)
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-600 italic">None submitted yet</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('company.evaluations.create', $placement) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-xs transition-all">
                                            <i data-lucide="award" class="w-3.5 h-3.5"></i>
                                            <span>Submit Evaluation</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $placements->links() }}
                </div>
            @else
                <div class="py-16 text-center text-slate-600 space-y-3">
                    <i data-lucide="award" class="w-12 h-12 mx-auto text-slate-300"></i>
                    <h3 class="text-base font-bold text-slate-800">No active student placements</h3>
                    <p class="text-xs text-slate-600 max-w-sm mx-auto">Once applicants are accepted and placed, you will be able to submit midterm and final evaluations.</p>
                </div>
            @endif
        </div>

    </div>
</x-layout>
