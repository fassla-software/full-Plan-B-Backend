<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Beste\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationManagementController extends Controller
{
    public function unread_notification_count(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $unreadCount = $user->unreadNotifications->count();

        return response()->json(
            [
                'message' => 'success',
                'unread_notification_count' => $unreadCount,
            ]
        );
    }

    public function unread_notification(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $unreadNotifications = $user->unreadNotifications;

        return response()->json([
            'message' => 'success',
            'unread_notifications' => $unreadNotifications,
        ]);
    }

    public function read_notification(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $readNotifications = $user->readNotifications;

        return response()->json([
            'message' => 'success',
            'read_notifications' => $readNotifications,
        ]);
    }

    public function make_read_notification($id): JsonResponse
    {
        $user = auth('sanctum')->user();

        $notification = $user->notifications()->find($id);

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'message' => 'success',
            'data' => 'notification read successfully',
        ]);
    }

    public function make_unread_notification($id): JsonResponse
    {
        $user = auth('sanctum')->user();

        $notification = $user->notifications()->find($id);

        if ($notification && !is_null($notification->read_at)) {
            $notification->markAsUnread();
        }

        return response()->json([
            'message' => 'success',
            'data' => 'notification unread successfully',
        ]);
    }

    public function read_all_notification(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'success',
            'data' => 'all notifications read successfully',
        ]);
    }

    public function unread_all_notification(): JsonResponse
    {
        $user = auth('sanctum')->user();

        $user->readNotifications->markAsUnread();

        return response()->json([
            'message' => 'success',
            'data' => 'all notifications unread successfully',
        ]);
    }
}
