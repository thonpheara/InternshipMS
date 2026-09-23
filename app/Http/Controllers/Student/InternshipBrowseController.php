<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\InternshipPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InternshipBrowseController extends Controller
{
    /**
     * Display a listing of available approved internship posts.
     */
    public function index(Request $request): View
    {
        $query = InternshipPost::with('companyProfile')
            ->approved()
            ->whereDate('deadline', '>=', now()->toDateString());

        // Search title/company/description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('companyProfile', function ($compQ) use ($search) {
                      $compQ->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $posts = $query->latest()->paginate(9)->withQueryString();

        return view('student.posts.index', compact('posts'));
    }

    /**
     * Show detailed internship post.
     */
    public function show(InternshipPost $post): View
    {
        $student = Auth::user()->studentProfile;

        // Check if student has already applied
        $existingApplication = Application::where('student_profile_id', $student?->id)
            ->where('internship_post_id', $post->id)
            ->first();

        return view('student.posts.show', compact('post', 'existingApplication', 'student'));
    }

    /**
     * Apply for an internship post.
     */
    public function apply(Request $request, InternshipPost $post): RedirectResponse
    {
        $student = Auth::user()->studentProfile;

        if (!$student) {
            return back()->with('error', 'Student profile not found.');
        }

        // Prevent duplicate application
        $exists = Application::where('student_profile_id', $student->id)
            ->where('internship_post_id', $post->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already applied for this position.');
        }

        $validated = $request->validate([
            'cover_letter' => ['required', 'string', 'min:30', 'max:3000'],
        ]);

        Application::create([
            'internship_post_id' => $post->id,
            'student_profile_id' => $student->id,
            'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('student.applications.index')
            ->with('success', "Application successfully submitted to {$post->companyProfile->company_name}!");
    }

    /**
     * List all submitted applications for this student.
     */
    public function applications(): View
    {
        $student = Auth::user()->studentProfile;

        $applications = Application::with(['internshipPost.companyProfile', 'placement'])
            ->where('student_profile_id', $student?->id)
            ->latest('applied_at')
            ->paginate(10);

        return view('student.applications.index', compact('applications'));
    }
}
