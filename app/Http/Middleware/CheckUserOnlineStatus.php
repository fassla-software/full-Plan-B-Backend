<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Auth;
use Cache;
use Carbon\Carbon;

class CheckUserOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $expiresAt = Carbon::now()->addMinutes(3); // keep online for 3 min
            Cache::put('user_is_online_' . Auth::guard('web')->user()->id, true, $expiresAt);
            // last seen
            User::where('id', Auth::guard('web')->user()->id)->update(['check_online_status' => (new \DateTime())->format("Y-m-d H:i:s")]);
        }else if (auth('sanctum')->check()) {
            $expiresAt = Carbon::now()->addMinutes(3); // keep online for 3 min
            Cache::put('user_is_online_' . auth('sanctum')->user()->id, true, $expiresAt);
            // last seen
            User::where('id', auth('sanctum')->user()->id)->update(['check_online_status' => (new \DateTime())->format("Y-m-d H:i:s")]);
    }
        return $next($request);
    }
}
