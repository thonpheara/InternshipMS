<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Display student dashboard with stats.
     */
    public function index(): View
    {
        $user = Auth::user();
        $student = $user->studentProfile ?? $user->studentProfile()->create([
            'eligibility_status' => 'eligible',
        ]);

        // Recent applications (show 3)
        $recentApplications = Application::with(['internshipPost.companyProfile'])
            ->where('student_profile_id', $student?->id)
            ->latest()
            ->take(3)
            ->get();

        // Stats
        $stats = [
            'total_applications' => Application::where('student_profile_id', $student?->id)->count(),
            'shortlisted' => Application::where('student_profile_id', $student?->id)->where('status', 'shortlisted')->count(),
        ];

        return view('student.dashboard', compact('student', 'recentApplications', 'stats'));
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
        if (array_key_exists('skills_input', $validated)) {
            $skillsArray = !empty($validated['skills_input'])
                ? array_values(array_filter(array_map('trim', explode(',', $validated['skills_input']))))
                : null;
            $updateData['skills'] = $skillsArray;
        }

        // Process resume upload (isolated per student to avoid overwrites)
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            if ($file->isValid()) {
                if ($student->resume_path && Storage::disk('public')->exists($student->resume_path)) {
                    Storage::disk('public')->delete($student->resume_path);
                }

                $originalName = $file->getClientOriginalName();
                $storedPath = $file->storeAs('resumes/' . $student->id, $originalName, 'public');
                if ($storedPath) {
                    $updateData['resume_path'] = $storedPath;
                }
            }
        }

        $student->update($updateData);

        $activeTab = $request->input('active_tab');
        if ($request->hasFile('resume')) {
            $activeTab = 'resume';
        }

        return redirect()->route('student.profile', $activeTab ? ['tab' => $activeTab] : [])
            ->with('success', $request->hasFile('resume') ? 'Resume uploaded and profile updated successfully.' : 'Profile updated successfully.');
    }

    /**
     * Preview or download student's own resume.
     */
    public function previewResume(Request $request)
    {
        $student = Auth::user()->studentProfile;
        $resumePath = $student?->resume_path;

        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            return redirect()->route('student.profile', ['tab' => 'resume'])->with('error', 'Resume document not found.');
        }

        $fullPath = Storage::disk('public')->path($resumePath);
        if (!file_exists($fullPath)) {
            return redirect()->route('student.profile', ['tab' => 'resume'])->with('error', 'Resume file not found on disk. Please re-upload your resume.');
        }

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

        return redirect()->route('student.profile', ['tab' => 'resume'])->with('success', 'Resume document removed successfully.');
    }
}
