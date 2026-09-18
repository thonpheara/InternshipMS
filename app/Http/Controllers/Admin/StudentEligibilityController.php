<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentEligibilityController extends Controller
{
    /**
     * List students with eligibility status.
     */
    public function index(Request $request): View
    {
        $query = StudentProfile::with('user');

        if ($status = $request->input('status')) {
            $query->where('eligibility_status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('student_id_number', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('major', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($userQ) => $userQ->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Update student eligibility status.
     */
    public function update(Request $request, StudentProfile $student): RedirectResponse
    {
        $validated = $request->validate([
            'eligibility_status' => ['required', 'in:eligible,ineligible,pending'],
            'eligibility_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $student->update($validated);

        return back()->with('success', "Eligibility for student {$student->user->name} updated to " . ucfirst($validated['eligibility_status']) . ".");
    }
}
