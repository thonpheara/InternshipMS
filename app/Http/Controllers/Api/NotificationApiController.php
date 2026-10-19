<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationApiController extends BaseApiController
{
    /**
     * Get list of notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = min((int) ($request->input('per_page') ?: 20), 50);
        $notifications = $user->notifications()->paginate($perPage);

        return $this->sendResponse([
            'unread_count'  => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ], 'Notifications retrieved.');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        return $this->sendResponse([
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ], 'Unread count retrieved.');
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->sendError('Notification not found.', [], 404);
        }

        $notification->markAsRead();

        return $this->sendResponse([
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ], 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->sendResponse([
            'unread_count' => 0,
        ], 'All notifications marked as read.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->sendError('Notification not found.', [], 404);
        }

        $notification->delete();

        return $this->sendResponse([
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ], 'Notification deleted.');
    }

    /**
     * Clear all notifications for the user.
     */
    public function clearAll(Request $request): JsonResponse
    {
        $request->user()->notifications()->delete();

        return $this->sendResponse([
            'unread_count' => 0,
        ], 'All notifications cleared.');
    }
}
