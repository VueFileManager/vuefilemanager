<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use Domain\Files\Resources\FileResource;

/**
 * Get shared file record
 */
class VisitorGetSingleFileInfoController
{
    public function __construct(
        public HelperService $helper,
    ) {
    }

    public function __invoke(
        Share $shared
    ): Response {
        // Check ability to access protected share files
        $this->helper->check_protected_share_record($shared);

        // Get file
        $file = File::whereUserId($shared->user_id)
            ->whereId($shared->item_id)
            ->firstOrFail();

        // Set access urls
        $file->setPublicUrl($shared->token);

        return response(new FileResource($file), 200);
    }
}
