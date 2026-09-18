<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\Placement;
use App\Models\StudentProfile;
use App\Models\User;
use App\Models\WeeklyLog;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display institutional overview metrics and pending moderation queues.
     */
    public function index(): View
    {
        $stats = [
            'total_students' => StudentProfile::count(),
            'eligible_students' => StudentProfile::where('eligibility_status', 'eligible')->count(),
            'total_companies' => CompanyProfile::count(),
            'pending_posts' => InternshipPost::where('status', 'pending_approval')->count(),
            'active_placements' => Placement::where('status', 'active')->count(),
            'unassigned_supervisors' => Placement::whereNull('supervisor_id')->where('status', 'active')->count(),
            'total_hours_logged' => WeeklyLog::where('status', 'approved')->sum('hours_completed'),
        ];

        // Posts needing coordinator review
        $pendingPosts = InternshipPost::with('companyProfile')
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        // Placements requiring supervisor assignment
        $unassignedPlacements = Placement::with(['studentProfile.user', 'companyProfile', 'internshipPost'])
            ->whereNull('supervisor_id')
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingPosts', 'unassignedPlacements'));
    }
}
