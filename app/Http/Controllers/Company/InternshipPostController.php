<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\InternshipPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InternshipPostController extends Controller
{
    /**
     * Display all posts created by this company.
     */
    public function index(): View
    {
        $company = Auth::user()->companyProfile;

        $posts = InternshipPost::where('company_profile_id', $company?->id)
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('company.posts.index', compact('posts'));
    }

    /**
     * Show create job post form.
     */
    public function create(): View
    {
        return view('company.posts.create');
    }

    /**
     * Store new job post.
     */
    public function store(Request $request): RedirectResponse
    {
        $company = Auth::user()->companyProfile;

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'max:100'],
            'description'     => ['required', 'string', 'min:30'],
            'responsibilities'=> ['nullable', 'string'],
            'requirements'    => ['nullable', 'string'],
            'location'        => ['required', 'string', 'max:255'],
            'type'            => ['required', 'in:remote,on_site,hybrid'],
            'duration_weeks'  => ['required', 'integer', 'min:4', 'max:52'],
            'stipend'         => ['nullable', 'numeric', 'min:0'],
            'slots'           => ['required', 'integer', 'min:1', 'max:50'],
            'deadline'        => ['required', 'date', 'after:today'],
        ]);

        $validated['company_profile_id'] = $company->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);
        $validated['status'] = 'pending_approval'; // Admin moderates new postings
        $validated['is_stipend_disclosed'] = !empty($validated['stipend']);

        InternshipPost::create($validated);

        return redirect()->route('company.posts.index')
            ->with('success', 'Internship post submitted for coordinator approval.');
    }

    /**
     * Show edit form.
     */
    public function edit(InternshipPost $post): View|RedirectResponse
    {
        $this->authorizeCompanyPost($post);

        return view('company.posts.edit', compact('post'));
    }

    /**
     * Update existing job post.
     */
    public function update(Request $request, InternshipPost $post): RedirectResponse
    {
        $this->authorizeCompanyPost($post);

        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'max:100'],
            'description'     => ['required', 'string', 'min:30'],
            'responsibilities'=> ['nullable', 'string'],
            'requirements'    => ['nullable', 'string'],
            'location'        => ['required', 'string', 'max:255'],
            'type'            => ['required', 'in:remote,on_site,hybrid'],
            'duration_weeks'  => ['required', 'integer', 'min:4', 'max:52'],
            'stipend'         => ['nullable', 'numeric', 'min:0'],
            'slots'           => ['required', 'integer', 'min:1', 'max:50'],
            'deadline'        => ['required', 'date'],
            'status'          => ['required', 'in:draft,pending_approval,approved,rejected,closed'],
        ]);

        $validated['is_stipend_disclosed'] = !empty($validated['stipend']);

        $post->update($validated);

        return redirect()->route('company.posts.index')
            ->with('success', 'Internship post updated successfully.');
    }

    /**
     * Delete/close post.
     */
    public function destroy(InternshipPost $post): RedirectResponse
    {
        $this->authorizeCompanyPost($post);

        $post->delete();

        return redirect()->route('company.posts.index')
            ->with('success', 'Internship post removed.');
    }

    protected function authorizeCompanyPost(InternshipPost $post): void
    {
        $company = Auth::user()->companyProfile;
        if ($post->company_profile_id !== $company?->id) {
            abort(403, 'Unauthorized action on this post.');
        }
    }
}
