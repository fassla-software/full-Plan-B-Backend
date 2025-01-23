<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventProjectUrlAccess
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
        if (get_static_option('project_enable_disable') != 'disable') {
            return $next($request);
        }
        return abort(404);
    }
}
