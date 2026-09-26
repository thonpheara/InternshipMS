<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyVerificationController extends Controller
{
    /**
     * Display the company verification queue.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $search = trim($request->input('search', ''));

        $counts = [
            'pending'  => CompanyProfile::where('verification_status', 'pending')->count(),
            'verified' => CompanyProfile::where('verification_status', 'verified')->count(),
            'rejected' => CompanyProfile::where('verification_status', 'rejected')->count(),
            'all'      => CompanyProfile::count(),
        ];

        $query = CompanyProfile::with(['user'])->withCount(['internshipPosts', 'applications']);

        if ($status !== 'all') {
            $query->where('verification_status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        $companies = $query->latest()->get();

        return view('Admin.companies.index', compact('companies', 'counts', 'status', 'search'));
    }

    /**
     * Update the verification status of a company profile.
     */
    public function update(Request $request, CompanyProfile $company): RedirectResponse
    {
        $validated = $request->validate([
            'verification_status' => ['required', 'in:verified,rejected,pending'],
            'rejection_reason'    => ['nullable', 'string', 'max:1000'],
        ]);

        $company->update([
            'verification_status' => $validated['verification_status'],
            'rejection_reason'    => $validated['verification_status'] === 'rejected' ? ($validated['rejection_reason'] ?: 'Does not meet institutional verification criteria.') : null,
        ]);

        $statusText = match ($validated['verification_status']) {
            'verified' => 'successfully verified as an institutional partner',
            'rejected' => 'marked as rejected',
            default    => 'reset to pending review',
        };

        // Notify company user
        $isVerified = $validated['verification_status'] === 'verified';
        $company->user?->notify(new \App\Notifications\AppNotification(
            title: $isVerified ? 'Company Profile Verified' : 'Verification Rejected',
            message: $isVerified 
                ? 'Your company profile has been verified! You can now publish internship vacancies.'
                : 'Your verification request was rejected. Reason: ' . ($company->rejection_reason ?? 'Please contact administration.'),
            actionUrl: route('company.profile'),
            icon: $isVerified ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark',
            color: $isVerified ? 'emerald' : 'rose'
        ));

        return back()->with('success', "Company '{$company->company_name}' has been {$statusText}.");
    }
}
