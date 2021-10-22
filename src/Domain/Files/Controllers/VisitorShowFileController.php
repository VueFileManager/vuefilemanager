<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use Gate;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Domain\Files\Resources\FileResource;
use Domain\Sharing\Actions\ProtectShareRecordAction;

/**
 * Get shared file record
 */
class VisitorShowFileController
{
    public function __invoke(
        Share $shared
    ): Response {

        $file = File::whereUserId($shared->user_id)
            ->whereId($shared->item_id)
            ->firstOrFail();

        Gate::authorize('can-view', [$file, $shared]);

        // Set access urls
        $file->setPublicUrl($shared->token);

        return response(new FileResource($file), 200);
    }
}
