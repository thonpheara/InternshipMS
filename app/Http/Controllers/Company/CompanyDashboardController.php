<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use App\Models\Placement;
use App\Models\WeeklyLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CompanyDashboardController extends Controller
{
    /**
     * Display company portal dashboard with key applicant and intern metrics.
     */
    public function index(): View
    {
        $company = Auth::user()->companyProfile;

        // Post IDs belonging to this company
        $postIds = InternshipPost::where('company_profile_id', $company?->id)->pluck('id');

        // Placements belonging to this company
        $placementIds = Placement::where('company_profile_id', $company?->id)->pluck('id');

        // Stats
        $stats = [
            'active_posts' => InternshipPost::where('company_profile_id', $company?->id)->where('status', 'approved')->count(),
            'total_applicants' => Application::whereIn('internship_post_id', $postIds)->count(),
            'pending_review' => Application::whereIn('internship_post_id', $postIds)->where('status', 'pending')->count(),
            'active_interns' => Placement::where('company_profile_id', $company?->id)->where('status', 'active')->count(),
            'pending_logs' => WeeklyLog::whereIn('placement_id', $placementIds)->where('status', 'submitted')->count(),
        ];

        // Recent applications to review
        $recentApplicants = Application::with(['studentProfile.user', 'internshipPost'])
            ->whereIn('internship_post_id', $postIds)
            ->latest('applied_at')
            ->take(5)
            ->get();

        // Recent weekly logs needing approval
        $pendingLogs = WeeklyLog::with(['placement.studentProfile.user', 'placement.internshipPost'])
            ->whereIn('placement_id', $placementIds)
            ->where('status', 'submitted')
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentApplicants', 'pendingLogs'));
    }
}
