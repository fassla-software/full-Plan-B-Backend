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
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $total_limit = UserSubscription::where('user_id', $user->id)
            ->where('payment_status', 'complete')
            ->whereDate('expire_date', '>', Carbon::now())
            ->sum('limit');

        $createdAt = Carbon::parse($user->created_at);
        $isInTrialPeriod = $createdAt->diffInDays(Carbon::now()) < 30;

        // 0 for intail version
        if ($total_limit > 0 || $isInTrialPeriod) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied. Your free trial has ended, or you have no remaining commas.',
            'requires_payment' => true
        ], Response::HTTP_PAYMENT_REQUIRED);

        return $next($request);
    }
}
