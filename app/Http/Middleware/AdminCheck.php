<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

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
        if ( ! Gate::allows('admin-settings')) {
            abort(403, 'You don\'t have access for this operation!');
        }

        return $next($request);
    }
}
