<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlacementSupervisorController extends Controller
{
    /**
     * List all placements with filter for unassigned supervisor.
     */
    public function index(Request $request): View
    {
        $query = Placement::with(['studentProfile.user', 'companyProfile', 'internshipPost', 'supervisor']);

        if ($request->boolean('unassigned')) {
            $query->whereNull('supervisor_id');
        }

        $placements = $query->latest()->paginate(12)->withQueryString();

        // Get all faculty / coordinators / admins who can act as supervisors
        $supervisors = User::whereIn('role', ['admin', 'coordinator'])->get();

        return view('admin.placements.index', compact('placements', 'supervisors'));
    }

    /**
     * Assign supervisor or update placement status.
     */
    public function update(Request $request, Placement $placement): RedirectResponse
    {
        $validated = $request->validate([
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'in:active,completed,terminated'],
            'completion_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $placement->update($validated);

        return back()->with('success', "Placement for {$placement->studentProfile->user->name} updated successfully.");
    }
}
