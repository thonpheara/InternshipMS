<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $applications = $query->latest('applied_at')->paginate(5)->withQueryString();
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

        // Notify candidate of status update
        $statusLabel = ucfirst(str_replace('_', ' ', $validated['status']));
        $application->studentProfile?->user?->notify(new \App\Notifications\AppNotification(
            title: "Application Status: {$statusLabel}",
            message: "{$company->company_name} updated your application for '{$application->internshipPost->title}' to {$statusLabel}.",
            actionUrl: route('student.applications.index'),
            icon: match($validated['status']) {
                'accepted' => 'fa-solid fa-award',
                'shortlisted' => 'fa-solid fa-star',
                'rejected' => 'fa-solid fa-circle-xmark',
                default => 'fa-solid fa-file-circle-check',
            },
            color: match($validated['status']) {
                'accepted' => 'emerald',
                'shortlisted' => 'teal',
                'rejected' => 'rose',
                default => 'blue',
            }
        ));

        return back()->with('success', "Candidate status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])) . ".");
    }

    /**
     * Preview or download an applicant's resume.
     */
    public function resume(Request $request, Application $application)
    {
        $company = Auth::user()->companyProfile;
        abort_if(!$company || $application->internshipPost->company_profile_id !== $company->id, 403, 'Unauthorized.');

        $resumePath = $application->getEffectiveResumePath();
        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return back()->with('error', 'No resume document attached to this application.');
        }

        $fullPath = Storage::disk('public')->path($resumePath);
        if (!file_exists($fullPath)) {
            return back()->with('error', 'The candidate resume file is not available on the server disk.');
        }

        // Keep exact original file name - never rename
        $filename = basename($resumePath);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // For non-previewable files (docx, doc, etc.) or when download parameter is present, download with exact filename
        if ($request->has('download') || !in_array($extension, ['pdf', 'png', 'jpg', 'jpeg'])) {
            return response()->download($fullPath, $filename, [
                'Content-Disposition' => 'attachment; filename="' . addcslashes($filename, '"\\') . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
            ]);
        }

        return response()->file($fullPath, [
            'Content-Disposition' => 'inline; filename="' . addcslashes($filename, '"\\') . '"; filename*=UTF-8\'\'' . rawurlencode($filename),
        ]);
    }
}
