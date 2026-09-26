<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostApprovalController extends Controller
{
    /**
     * List all posts with moderation filters.
     */
    public function index(Request $request): View
    {
        $query = InternshipPost::with('companyProfile');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            // Default to pending_approval if not specified
            $query->where('status', 'pending_approval');
        }

        $posts = $query->latest()->get();

        return view('admin.approvals.index', compact('posts'));
    }

    /**
     * Approve or reject a job posting.
     */
    public function update(Request $request, InternshipPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $post->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['status'] === 'rejected' ? $validated['rejection_reason'] : null,
        ]);

        // Notify employer of moderation decision
        $isApproved = $validated['status'] === 'approved';
        $post->companyProfile?->user?->notify(new \App\Notifications\AppNotification(
            title: $isApproved ? 'Job Post Approved' : 'Job Post Rejected',
            message: $isApproved
                ? "Your listing '{$post->title}' has been approved and is now visible to students."
                : "Your listing '{$post->title}' was rejected. Reason: " . ($post->rejection_reason ?? 'Please review your listing.'),
            actionUrl: route('company.posts.index'),
            icon: $isApproved ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark',
            color: $isApproved ? 'emerald' : 'rose'
        ));

        return back()->with('success', "Internship post '{$post->title}' marked as " . ucfirst($validated['status']) . ".");
    }
}
