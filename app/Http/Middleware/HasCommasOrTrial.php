<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
                'message' => 'Unauthorized. Please log in.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userCommas = $user->fassalat;
        $hasCommas = $userCommas && ($userCommas->remaining_commas > 0 || $userCommas->commas > 0);

        $createdAt = Carbon::parse($user->created_at);
        $isInTrialPeriod = $createdAt->diffInDays(Carbon::now()) < 15;

        if ($hasCommas || $isInTrialPeriod) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access denied. Your free trial has ended, or you have no remaining commas.',
            'requires_payment' => true
        ], Response::HTTP_PAYMENT_REQUIRED);

        return $next($request);
    }
}
