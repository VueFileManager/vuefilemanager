<?php
namespace Domain\UploadRequest\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectUploadRequestRoutes
{
    /**
     * Prevent access for setup wizard controllers after initial app installation.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Check if upload request is active
        if ($request->route()->parameter('uploadRequest')->status !== 'active') {
            return response('Gone', 410);
        }

        return $next($request);
    }
}
