<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompanyDashboardController extends Controller
{
    /**
     * Display company portal dashboard with key applicant metrics.
     */
    public function index(): View
    {
        $company = Auth::user()->companyProfile;

        // Post IDs belonging to this company
        $postIds = InternshipPost::where('company_profile_id', $company?->id)->pluck('id');

        // Stats
        $stats = [
            'active_posts' => InternshipPost::where('company_profile_id', $company?->id)->where('status', 'approved')->count(),
            'total_applicants' => Application::whereIn('internship_post_id', $postIds)->count(),
            'pending_review' => Application::whereIn('internship_post_id', $postIds)->where('status', 'pending')->count(),
        ];

        // Recent applications to review (show 4)
        $recentApplicants = Application::with(['studentProfile.user', 'internshipPost'])
            ->whereIn('internship_post_id', $postIds)
            ->latest('applied_at')
            ->take(4)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentApplicants'));
    }
}
