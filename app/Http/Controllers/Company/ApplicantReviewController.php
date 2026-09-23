<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\Placement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicantReviewController extends Controller
{
    /**
     * List all applications received for this company's posts.
     */
    public function index(Request $request): View
    {
        $company = Auth::user()->companyProfile;
        $postIds = InternshipPost::where('company_profile_id', $company?->id)->pluck('id');

        $query = Application::with(['studentProfile.user', 'internshipPost'])
            ->whereIn('internship_post_id', $postIds);

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by post
        if ($postId = $request->input('post_id')) {
            $query->where('internship_post_id', $postId);
        }

        $applications = $query->latest('applied_at')->paginate(6)->withQueryString();
        $companyPosts = InternshipPost::where('company_profile_id', $company?->id)->get();

        return view('company.applicants.index', compact('applications', 'companyPosts'));
    }

    /**
     * Update applicant status and optional company notes.
     */
    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $company = Auth::user()->companyProfile;

        // Ensure post belongs to this company
        if ($application->internshipPost->company_profile_id !== $company?->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,under_review,shortlisted,interviewed,accepted,rejected'],
            'company_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $application->update([
            'status' => $validated['status'],
            'company_notes' => $validated['company_notes'] ?? $application->company_notes,
            'reviewed_at' => now(),
        ]);

        return back()->with('success', "Candidate status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])) . ".");
    }

    /**
     * Preview or download an applicant's resume.
     */
    public function resume(Application $application)
    {
        $company = Auth::user()->companyProfile;
        abort_if(!$company || $application->internshipPost->company_profile_id !== $company->id, 403, 'Unauthorized.');

        $resumePath = $application->custom_resume_path ?? $application->studentProfile->resume_path;
        abort_if(!$resumePath, 404, 'Resume not found.');

        $fullPath = storage_path('app/public/' . $resumePath);
        abort_if(!file_exists($fullPath), 404, 'Resume file not found on disk.');

        return response()->file($fullPath);
    }
}
