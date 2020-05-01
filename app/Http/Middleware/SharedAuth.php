<?php

namespace App\Http\Middleware;

use Closure;

class SharedAuth
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
        if (!$request->bearerToken()) {
            if ($request->hasCookie('shared_access_token')) {

                $shared_access_token = $request->cookie('shared_access_token');

                $request->headers->add(['Authorization' => 'Bearer ' . $shared_access_token]);

            }
        }
        return $next($request);
    }
}
