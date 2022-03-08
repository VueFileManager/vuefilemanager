<?php

namespace Support\Middleware;

use Closure;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if user have access to administration settings
        if ( $request->user()->role !== 'admin') {
            abort(403, 'You don\'t have access for this operation!');
        }

        return $next($request);
    }
}
