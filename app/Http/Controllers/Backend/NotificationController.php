<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $notificationId)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->where('id', $notificationId)->first();

            if ($notification) {
                $notification->markAsRead();

                // Hitung ulang notifikasi yang belum dibaca setelah menandai
                $unreadNotificationsCount = $user->unreadNotifications()->count();

                return response()->json([
                    'success' => true,
                    'unread_count' => $unreadNotificationsCount,
                    'message' => 'Notification marked as read successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking notification as read.'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = Auth::user();
            $user->unreadNotifications->markAsRead(); // Menandai semua notifikasi yang belum dibaca

            return response()->json([
                'success' => true,
                'unread_count' => 0,
                'message' => 'All notifications marked as read successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while marking all notifications as read.'
            ], 500);
        }
    }

    /**
     * Get unread notifications for AJAX
     */
    public function getUnreadNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications;
            $unreadNotificationsCount = $unreadNotifications->count();

            // Format notifikasi agar mudah dirender di frontend
            $formattedNotifications = $unreadNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $notification->data['message'] ?? 'No message available',
                    'created_at' => $notification->created_at,
                    'created_at_human' => $notification->created_at->diffForHumans(),
                    'data' => $notification->data
                ];
            });

            return response()->json([
                'success' => true,
                'count' => $unreadNotificationsCount,
                'notifications' => $formattedNotifications,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting unread notifications: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching notifications.',
                'count' => 0,
                'notifications' => []
            ], 500);
        }
    }

    /**
     * Get all notifications (read and unread) with pagination
     */
    public function getAllNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->get('per_page', 15);

            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $formattedNotifications = $notifications->getCollection()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $notification->data['message'] ?? 'No message available',
                    'created_at' => $notification->created_at,
                    'created_at_human' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at,
                    'is_read' => !is_null($notification->read_at),
                    'data' => $notification->data
                ];
            });

            return response()->json([
                'success' => true,
                'notifications' => $formattedNotifications,
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'from' => $notifications->firstItem(),
                    'to' => $notifications->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all notifications: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching notifications.',
                'notifications' => [],
                'pagination' => null
            ], 500);
        }
    }

    /**
     * Delete a specific notification
     */
    public function deleteNotification(Request $request, $notificationId)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->where('id', $notificationId)->first();

            if ($notification) {
                $notification->delete();

                // Hitung ulang notifikasi yang belum dibaca setelah menghapus
                $unreadNotificationsCount = $user->unreadNotifications()->count();

                return response()->json([
                    'success' => true,
                    'unread_count' => $unreadNotificationsCount,
                    'message' => 'Notification deleted successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting notification.'
            ], 500);
        }
    }

    /**
     * Clear all notifications (delete all)
     */
    public function clearAllNotifications(Request $request)
    {
        try {
            $user = Auth::user();
            $user->notifications()->delete();

            return response()->json([
                'success' => true,
                'unread_count' => 0,
                'message' => 'All notifications cleared successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing all notifications: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while clearing notifications.'
            ], 500);
        }
    }
}
