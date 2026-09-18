<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Placement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    /**
     * Display placements and their evaluations.
     */
    public function index(): View
    {
        $company = Auth::user()->companyProfile;

        $placements = Placement::with(['studentProfile.user', 'internshipPost', 'evaluations'])
            ->where('company_profile_id', $company?->id)
            ->latest()
            ->paginate(10);

        return view('company.evaluations.index', compact('placements'));
    }

    /**
     * Show form to submit an evaluation for a placement.
     */
    public function create(Placement $placement): View
    {
        $company = Auth::user()->companyProfile;

        if ($placement->company_profile_id !== $company?->id) {
            abort(403, 'Unauthorized.');
        }

        return view('company.evaluations.create', compact('placement'));
    }

    /**
     * Store new evaluation.
     */
    public function store(Request $request, Placement $placement): RedirectResponse
    {
        $company = Auth::user()->companyProfile;

        if ($placement->company_profile_id !== $company?->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:midterm,final'],
            'performance_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'technical_skills_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'soft_skills_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'attendance_punctuality_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comments' => ['required', 'string', 'min:20', 'max:3000'],
            'recommendation' => ['required', 'in:outstanding,satisfactory,needs_improvement,unsatisfactory'],
        ]);

        $validated['placement_id'] = $placement->id;
        $validated['evaluator_id'] = Auth::id();
        $validated['submitted_at'] = now();

        Evaluation::create($validated);

        return redirect()->route('company.evaluations.index')
            ->with('success', ucfirst($validated['type']) . ' evaluation submitted successfully.');
    }
}
