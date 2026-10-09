<?php
namespace VueFileManager\Subscription\Support\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check if user have access to the administration
        if ($request->user()->role !== 'admin') {
            return response("You don't have access for this operation!", 403);
        }

        return $next($request);
    }
}
