<?php
namespace Domain\Files\Controllers;

use Gate;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FileResource;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Get shared file record
 */
class VisitorShowFileController
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(
        Share $shared
    ): JsonResponse {
        $file = File::whereUserId($shared->user_id)
            ->whereId($shared->item_id)
            ->firstOrFail();

        Gate::authorize('can-view', [$file, $shared]);

        // Set access urls
        $file->setSharedPublicUrl($shared->token);

        return response()->json(new FileResource($file));
    }
}
