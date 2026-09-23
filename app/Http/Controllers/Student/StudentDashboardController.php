<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Placement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Display student dashboard with active placement progress and stats.
     */
    public function index(): View
    {
        $user = Auth::user();
        $student = $user->studentProfile ?? $user->studentProfile()->create([
            'eligibility_status' => 'eligible',
        ]);

        // Active placement
        $activePlacement = Placement::with(['companyProfile', 'internshipPost', 'supervisor', 'weeklyLogs'])
            ->where('student_profile_id', $student?->id)
            ->where('status', 'active')
            ->first();

        // Recent applications (show 3)
        $recentApplications = Application::with(['internshipPost.companyProfile'])
            ->where('student_profile_id', $student?->id)
            ->latest()
            ->take(3)
            ->get();

        // Recent weekly logs
        $recentLogs = $activePlacement ? $activePlacement->weeklyLogs()->latest('week_number')->take(4)->get() : collect();

        // Stats
        $stats = [
            'total_applications' => Application::where('student_profile_id', $student?->id)->count(),
            'shortlisted' => Application::where('student_profile_id', $student?->id)->where('status', 'shortlisted')->count(),
            'hours_logged' => $activePlacement ? $activePlacement->totalHoursLogged() : 0,
            'hours_required' => $activePlacement ? $activePlacement->total_hours_required : 480,
            'progress_percent' => $activePlacement ? $activePlacement->completionPercentage() : 0,
        ];

        return view('student.dashboard', compact('student', 'activePlacement', 'recentApplications', 'recentLogs', 'stats'));
    }

    /**
     * Show profile edit form.
     */
    public function profile(): View
    {
        $user = Auth::user();
        $student = $user->studentProfile ?? $user->studentProfile()->create([
            'eligibility_status' => 'eligible',
        ]);

        return view('student.profile', compact('user', 'student'));
    }

    /**
     * Update student profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $student = $user->studentProfile ?? $user->studentProfile()->create([
            'eligibility_status' => 'eligible',
        ]);

        $validated = $request->validate([
            'student_id_number' => ['nullable', 'string', 'max:50', Rule::unique('student_profiles', 'student_id_number')->ignore($student->id)],
            'department' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'cohort_year' => ['nullable', 'integer', 'min:2020', 'max:2035'],
            'gpa' => ['nullable', 'numeric', 'min:0.00', 'max:4.00'],
            'phone' => ['nullable', 'string', 'max:25'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'skills_input' => ['nullable', 'string', 'max:500'], // comma-separated
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // max 5MB
        ]);

        $updateData = [
            'student_id_number' => $validated['student_id_number'] ?? null,
            'department' => $validated['department'] ?? null,
            'major' => $validated['major'] ?? null,
            'cohort_year' => $validated['cohort_year'] ?? null,
            'gpa' => $validated['gpa'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];

        // Process skills as array
        if (!empty($validated['skills_input'])) {
            $skillsArray = array_values(array_filter(array_map('trim', explode(',', $validated['skills_input']))));
            $updateData['skills'] = $skillsArray;
        }

        // Process resume upload
        if ($request->hasFile('resume')) {
            if ($student->resume_path && Storage::disk('public')->exists($student->resume_path)) {
                Storage::disk('public')->delete($student->resume_path);
            }
            $updateData['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $student->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Preview or download student's own resume.
     */
    public function previewResume()
    {
        $student = Auth::user()->studentProfile;
        abort_if(!$student || !$student->resume_path, 404, 'Resume not found.');

        $fullPath = storage_path('app/public/' . $student->resume_path);
        abort_if(!file_exists($fullPath), 404, 'Resume file not found on disk.');

        return response()->file($fullPath);
    }

    /**
     * Delete the student's uploaded resume file.
     */
    public function deleteResume(): RedirectResponse
    {
        $student = Auth::user()->studentProfile;
        abort_if(!$student, 403);

        if ($student->resume_path) {
            $fullPath = storage_path('app/public/' . $student->resume_path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
            if (Storage::disk('public')->exists($student->resume_path)) {
                Storage::disk('public')->delete($student->resume_path);
            }
            $student->update(['resume_path' => null]);
        }

        return back()->with('success', 'Resume document removed successfully.');
    }
}
