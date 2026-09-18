<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\WeeklyLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WeeklyLogController extends Controller
{
    /**
     * Display student's weekly logs.
     */
    public function index(): View
    {
        $student = Auth::user()->studentProfile;

        $placement = Placement::with(['companyProfile', 'internshipPost', 'weeklyLogs'])
            ->where('student_profile_id', $student?->id)
            ->where('status', 'active')
            ->first();

        $logs = $placement ? $placement->weeklyLogs()->orderBy('week_number', 'desc')->get() : collect();

        $nextWeekNumber = $logs->isNotEmpty() ? $logs->max('week_number') + 1 : 1;

        return view('student.logs.index', compact('placement', 'logs', 'nextWeekNumber'));
    }

    /**
     * Submit a new weekly log entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->studentProfile;

        $placement = Placement::where('student_profile_id', $student?->id)
            ->where('status', 'active')
            ->first();

        if (!$placement) {
            return back()->with('error', 'No active placement found to submit logs for.');
        }

        $validated = $request->validate([
            'week_number' => [
                'required',
                'integer',
                'min:1',
                'max:52',
                Rule::unique('weekly_logs')->where(fn ($query) => $query->where('placement_id', $placement->id)),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'hours_completed' => ['required', 'numeric', 'min:1', 'max:80'],
            'tasks_summary' => ['required', 'string', 'min:20', 'max:3000'],
            'learnings_challenges' => ['nullable', 'string', 'max:3000'],
        ]);

        WeeklyLog::create([
            'placement_id' => $placement->id,
            'week_number' => $validated['week_number'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'hours_completed' => $validated['hours_completed'],
            'tasks_summary' => $validated['tasks_summary'],
            'learnings_challenges' => $validated['learnings_challenges'] ?? null,
            'status' => 'submitted',
        ]);

        return back()->with('success', "Weekly Log for Week #{$validated['week_number']} submitted successfully.");
    }
}
