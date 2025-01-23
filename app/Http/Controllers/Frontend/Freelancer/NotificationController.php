<?php

namespace App\Http\Controllers\Frontend\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\FreelancerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read_notification()
    {
        FreelancerNotification::where('freelancer_id',Auth::guard('web')->user()->id)
            ->where('is_read','unread')
            ->update(['is_read' => 'read']);
        return response()->json(['status' => 'success']);
    }
}
