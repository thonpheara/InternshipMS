<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\WeeklyLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InternLogReviewController extends Controller
{
    /**
     * Display intern logs submitted to this company.
     */
    public function index(Request $request): View
    {
        $company = Auth::user()->companyProfile;
        $placementIds = Placement::where('company_profile_id', $company?->id)->pluck('id');

        $query = WeeklyLog::with(['placement.studentProfile.user', 'placement.internshipPost'])
            ->whereIn('placement_id', $placementIds);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('company.logs.index', compact('logs'));
    }

    /**
     * Approve or reject a weekly log.
     */
    public function update(Request $request, WeeklyLog $log): RedirectResponse
    {
        $company = Auth::user()->companyProfile;

        // Verify log belongs to company's placement
        if ($log->placement->company_profile_id !== $company?->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:approved,revision_requested,rejected'],
            'company_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $log->update([
            'status' => $validated['status'],
            'company_feedback' => $validated['company_feedback'],
            'approved_at' => $validated['status'] === 'approved' ? now() : null,
        ]);

        return back()->with('success', "Weekly Log #{$log->week_number} status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])) . ".");
    }
}
