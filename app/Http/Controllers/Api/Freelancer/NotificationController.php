<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unread_notification()
    {
        $notifications = FreelancerNotification::where('freelancer_id',auth('sanctum')->user()->id)
            ->where('is_read','unread')
            ->paginate(10)
            ->withQueryString();
        if($notifications->count() >= 1){
            return response()->json([
                'notifications' => $notifications,
            ]);
        }
        return response()->json(['msg' => __('No notifications found.')]);
    }

    public function unread_notification_count()
    {
        $notifications = FreelancerNotification::where('freelancer_id',auth('sanctum')->user()->id)
            ->where('is_read','unread')
            ->count();

        return response()->json([
            'unread_notifications' => $notifications,
        ]);
    }

    public function read_notification()
    {
        FreelancerNotification::where('freelancer_id',auth('sanctum')->user()->id)
            ->where('is_read','unread')
            ->update(['is_read' => 'read']);
        return response()->json(['msg' => __('Read all notifications.')]);
    }
}