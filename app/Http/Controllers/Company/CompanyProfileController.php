<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    /**
     * Display the company profile edit form.
     */
    public function profile(): View
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        return view('company.profile', compact('user', 'company'));
    }

    /**
     * Update the company profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
        ]);

        $updateData = [
            'company_name' => $validated['company_name'],
            'industry' => $validated['industry'] ?? null,
            'location' => $validated['location'] ?? null,
            'contact_person' => $validated['contact_person'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        // Process logo upload
        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                if (Storage::disk('public')->exists($company->logo_path)) {
                    Storage::disk('public')->delete($company->logo_path);
                }
                $oldPublicFile = public_path('storage/' . $company->logo_path);
                if (file_exists($oldPublicFile)) {
                    @unlink($oldPublicFile);
                }
            }
            $updateData['logo_path'] = $request->file('logo')->store('company-logos', 'public');

            // Mirror to public/storage for instant static web server compatibility
            try {
                $targetDir = public_path('storage/company-logos');
                if (!file_exists($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                @copy(storage_path('app/public/' . $updateData['logo_path']), public_path('storage/' . $updateData['logo_path']));
            } catch (\Throwable $e) {
                // Ignore mirror failure; fallback route in web.php handles it
            }
        }

        $company->update($updateData);

        // Keep User account name in sync with company name
        $user->update([
            'name' => $validated['company_name'],
        ]);

        return back()->with('success', 'Company profile updated successfully.');
    }
}
