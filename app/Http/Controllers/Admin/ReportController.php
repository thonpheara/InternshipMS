<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CompanyProfile;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Placement and Application Reports overview.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status', 'all');
        $department = $request->input('department', 'all');
        $year = $request->input('year', 'all');
        $search = trim($request->input('search', ''));

        $query = $this->buildReportQuery($status, $department, $year, $search);

        // Overall stats (unfiltered baseline for KPI cards)
        $totalApplications = Application::count();
        $totalAccepted = Application::where('status', 'accepted')->count();
        $totalShortlisted = Application::where('status', 'shortlisted')->count();
        $totalCompaniesHiring = CompanyProfile::whereHas('internshipPosts.applications', function ($q) {
            $q->where('status', 'accepted');
        })->count();

        $placementRate = $totalApplications > 0 ? round(($totalAccepted / $totalApplications) * 100, 1) : 0;

        $stats = [
            'total_applications'     => $totalApplications,
            'total_accepted'         => $totalAccepted,
            'total_shortlisted'      => $totalShortlisted,
            'total_companies_hiring' => $totalCompaniesHiring,
            'placement_rate'         => $placementRate,
        ];

        // Filter dropdown options
        $departments = StudentProfile::whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        $years = Application::whereNotNull('created_at')
            ->pluck('created_at')
            ->map(fn ($date) => (string) $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        $applications = $query->latest('applied_at')->get();

        return view('Admin.reports.index', compact(
            'applications',
            'stats',
            'departments',
            'years',
            'status',
            'department',
            'year',
            'search'
        ));
    }

    /**
     * Export Placement Report as CSV (compatible with Excel & supports Khmer UTF-8 BOM).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $status = $request->input('status', 'all');
        $department = $request->input('department', 'all');
        $year = $request->input('year', 'all');
        $search = trim($request->input('search', ''));

        $query = $this->buildReportQuery($status, $department, $year, $search);
        $applications = $query->latest('applied_at')->get();

        $filename = 'internship_placement_report_' . now()->format('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () use ($applications) {
            $handle = fopen('php://output', 'w');

            // Insert UTF-8 BOM so Microsoft Excel correctly displays UTF-8 and Khmer scripts
            fputs($handle, "\xEF\xBB\xBF");

            // CSV Header Row
            fputcsv($handle, [
                'No.',
                'Student Name',
                'Student ID',
                'Department',
                'Major',
                'GPA',
                'Phone',
                'Email',
                'Company Name',
                'Industry',
                'Internship Title',
                'Category',
                'Workplace Type',
                'Stipend ($)',
                'Application Status',
                'Applied Date',
                'Reviewed Date',
            ]);

            $index = 1;
            foreach ($applications as $app) {
                $student = $app->studentProfile;
                $user = $student?->user;
                $post = $app->internshipPost;
                $company = $post?->companyProfile;

                fputcsv($handle, [
                    $index++,
                    $user?->name ?? 'N/A',
                    $student?->student_id_number ?? 'N/A',
                    $student?->department ?? 'N/A',
                    $student?->major ?? 'N/A',
                    $student?->gpa !== null ? number_format($student->gpa, 2) : 'N/A',
                    $student?->phone ?? 'N/A',
                    $user?->email ?? 'N/A',
                    $company?->company_name ?? 'N/A',
                    $company?->industry ?? 'N/A',
                    $post?->title ?? 'N/A',
                    $post?->category ?? 'General',
                    ucfirst($post?->type ?? 'N/A'),
                    $post?->stipend ? number_format($post->stipend, 2) : '0.00',
                    ucfirst(str_replace('_', ' ', $app->status)),
                    $app->applied_at ? $app->applied_at->format('Y-m-d H:i') : ($app->created_at ? $app->created_at->format('Y-m-d H:i') : 'N/A'),
                    $app->reviewed_at ? $app->reviewed_at->format('Y-m-d H:i') : 'Pending',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Formal printable PDF report view.
     */
    public function print(Request $request): View
    {
        $status = $request->input('status', 'all');
        $department = $request->input('department', 'all');
        $year = $request->input('year', 'all');
        $search = trim($request->input('search', ''));

        $query = $this->buildReportQuery($status, $department, $year, $search);
        $applications = $query->latest('applied_at')->get();

        $totalApplications = $applications->count();
        $totalAccepted = $applications->where('status', 'accepted')->count();
        $placementRate = $totalApplications > 0 ? round(($totalAccepted / $totalApplications) * 100, 1) : 0;

        return view('Admin.reports.print', compact(
            'applications',
            'totalApplications',
            'totalAccepted',
            'placementRate',
            'status',
            'department',
            'year'
        ));
    }

    /**
     * Helper to build filtered query.
     */
    protected function buildReportQuery(string $status, string $department, string $year, string $search)
    {
        $query = Application::with([
            'studentProfile.user',
            'internshipPost.companyProfile.user'
        ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($department !== 'all') {
            $query->whereHas('studentProfile', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        if ($year !== 'all' && is_numeric($year)) {
            $query->whereBetween('created_at', [
                "{$year}-01-01 00:00:00",
                "{$year}-12-31 23:59:59",
            ]);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('studentProfile.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('studentProfile', function ($sq) use ($search) {
                    $sq->where('student_id_number', 'like', "%{$search}%");
                })->orWhereHas('internshipPost', function ($pq) use ($search) {
                    $pq->where('title', 'like', "%{$search}%")
                      ->orWhereHas('companyProfile', function ($cq) use ($search) {
                          $cq->where('company_name', 'like', "%{$search}%");
                      });
                });
            });
        }

        return $query;
    }
}
