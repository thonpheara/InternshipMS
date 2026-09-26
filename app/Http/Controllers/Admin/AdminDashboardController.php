<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\InternshipPost;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display institutional overview metrics and pending moderation queues.
     */
    public function index(): View
    {
        $stats = [
            'total_accounts'    => User::whereIn('role', ['student', 'company'])->count(),
            'total_students'    => User::where('role', 'student')->count(),
            'total_companies'   => User::where('role', 'company')->count(),
            'pending_companies' => CompanyProfile::where('verification_status', 'pending')->count(),
            'active_accounts'   => User::whereIn('role', ['student', 'company'])->where('status', 'active')->count(),
            'pending_posts'     => InternshipPost::where('status', 'pending_approval')->count(),
            'total_placements'  => Application::where('status', 'accepted')->count(),
        ];

        // Posts needing admin review
        $pendingPosts = InternshipPost::with('companyProfile')
            ->where('status', 'pending_approval')
            ->latest()
            ->take(5)
            ->get();

        // Monthly applications and accepted placements for the chart
        $currentYear = (int) now()->format('Y');
        $lastYear = $currentYear - 1;

        $chartData = [
            'thisYear' => [
                'year' => $currentYear,
                'applied' => array_fill(0, 12, 0),
                'accepted' => array_fill(0, 12, 0),
            ],
            'lastYear' => [
                'year' => $lastYear,
                'applied' => array_fill(0, 12, 0),
                'accepted' => array_fill(0, 12, 0),
            ],
        ];

        $applications = Application::select(['id', 'status', 'created_at'])
            ->whereYear('created_at', '>=', $lastYear)
            ->get();

        foreach ($applications as $app) {
            if (!$app->created_at) {
                continue;
            }

            $appYear = (int) $app->created_at->format('Y');
            $monthIndex = ((int) $app->created_at->format('n')) - 1;

            if ($appYear === $currentYear && $monthIndex >= 0 && $monthIndex < 12) {
                $chartData['thisYear']['applied'][$monthIndex]++;
                if ($app->status === 'accepted') {
                    $chartData['thisYear']['accepted'][$monthIndex]++;
                }
            } elseif ($appYear === $lastYear && $monthIndex >= 0 && $monthIndex < 12) {
                $chartData['lastYear']['applied'][$monthIndex]++;
                if ($app->status === 'accepted') {
                    $chartData['lastYear']['accepted'][$monthIndex]++;
                }
            }
        }

        return view('admin.dashboard', compact('stats', 'pendingPosts', 'chartData'));
    }
}
