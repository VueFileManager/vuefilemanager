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
        // Get upload request
        $uploadRequest = $request->route()->parameter('uploadRequest');

        // Check if upload request is active
        if (! in_array($uploadRequest->status, ['active', 'filling'])) {
            return response()->json([
                'type'    => 'gone',
                'message' => 'The file request is not active anymore',
            ], 410);
        }

        return $next($request);
    }
}
