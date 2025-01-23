<?php

namespace Modules\Chat\Http\Middleware;
use Auth;
use Cache;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Modules\Vendor\Entities\Vendor;

class CheckVendorActiveMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard("vendor")->check()) {
            $expiresAt = Carbon::now()->addMinutes(1); // keep online for 1 min
            Cache::put('vendor_is_online_' . Auth::guard("vendor")->id(), true, $expiresAt);
            Vendor::where('id', Auth::guard("vendor")->id())->update(['check_online_status' => (new \DateTime())->format("Y-m-d H:i:s")]);
        }

        return $next($request);
    }
}
