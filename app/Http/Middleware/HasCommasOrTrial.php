<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\Subscription\Entities\UserSubscription;

class HasCommasOrTrial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $param): Response
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $total_limit = getCurrentUserSubsicription($user)->limit;

        $createdAt = Carbon::parse($user->created_at);
        $isInTrialPeriod = $createdAt->diffInDays(Carbon::now()) < 30;

        // 0 for intail version
        if ($total_limit >= $param || $isInTrialPeriod) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied. Your free trial has ended, or you have no remaining commas to do this operation',
            'requires_payment' => true
        ], Response::HTTP_PAYMENT_REQUIRED);

        return $next($request);
    }
}
