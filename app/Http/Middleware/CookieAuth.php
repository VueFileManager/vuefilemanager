<?php

namespace App\Http\Middleware;

use Closure;

class CookieAuth
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
            if ($request->hasCookie('token')) {

                $token = $request->cookie('token');

                $request->headers->add(['Authorization' => 'Bearer ' . $token]);

            } else {
                abort(401);
            }
        }
        return $next($request);
    }
}
