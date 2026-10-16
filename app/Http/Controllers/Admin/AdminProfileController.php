<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    /**
     * Display the Admin account profile & security settings.
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('Admin.profile', compact('user'));
    }

    /**
     * Update admin profile info & avatar picture.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Process avatar deletion if requested
        if ($request->input('delete_avatar') == '1' && $user->avatar_path) {
            if (Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $oldPublic = public_path('storage/' . $user->avatar_path);
            if (file_exists($oldPublic)) {
                @unlink($oldPublic);
            }
            $user->avatar_path = null;
        }

        // Process avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                if ($user->avatar_path) {
                    if (Storage::disk('public')->exists($user->avatar_path)) {
                        Storage::disk('public')->delete($user->avatar_path);
                    }
                    $oldPublic = public_path('storage/' . $user->avatar_path);
                    if (file_exists($oldPublic)) {
                        @unlink($oldPublic);
                    }
                }

                $avatarPath = $file->store('admin-avatars', 'public');
                $user->avatar_path = $avatarPath;

                // Sync directly to public_path for Windows symlink compatibility
                try {
                    $targetDir = public_path('storage/admin-avatars');
                    if (!file_exists($targetDir)) {
                        @mkdir($targetDir, 0755, true);
                    }
                    @copy(storage_path('app/public/' . $avatarPath), public_path('storage/' . $avatarPath));
                } catch (\Throwable $e) {
                    // Ignore fallback
                }
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Admin profile updated successfully.');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'The current password you entered is incorrect.',
            'password.different'                => 'The new password must be different from your current password.',
            'password.confirmed'                => 'The password confirmation does not match.',
            'password.min'                      => 'The new password must be at least 8 characters long.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Admin account password changed successfully.');
    }
}
