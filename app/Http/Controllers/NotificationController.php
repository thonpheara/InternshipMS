<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and optionally redirect to its action URL.
     */
    public function read(Request $request, string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $actionUrl = $notification->data['action_url'] ?? null;

            if ($actionUrl) {
                return redirect($actionUrl);
            }
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications of the authenticated user as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        Auth::user()->notifications()->where('id', $id)->delete();

        return back()->with('success', 'Notification removed.');
    }

    /**
     * Clear all notifications of the authenticated user.
     */
    public function clearAll(Request $request): RedirectResponse
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'All notifications cleared.');
    }
}
