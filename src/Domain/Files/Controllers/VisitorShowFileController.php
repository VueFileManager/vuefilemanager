<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Domain\Files\Resources\FileResource;
use Domain\Sharing\Actions\ProtectShareRecordAction;

/**
 * Get shared file record
 */
class VisitorShowFileController
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
    ) {
    }

    public function __invoke(
        Share $shared
    ): Response {
        // Check ability to access protected share files
        ($this->protectShareRecord)($shared);

        // Get file
        $file = File::whereUserId($shared->user_id)
            ->whereId($shared->item_id)
            ->firstOrFail();

        // Set access urls
        $file->setPublicUrl($shared->token);

        return response(new FileResource($file), 200);
    }
}
