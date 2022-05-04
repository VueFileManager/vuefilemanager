<?php
namespace Domain\Files\Controllers;

use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Support\Demo\Actions\FakeUploadFileAction;
use Domain\Files\Actions\StoreFileChunksAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * guest user upload file into shared folder
 */
class VisitorUploadFileController extends Controller
{
    public function __construct(
        public ProcessFileAction $processFie,
        public StoreFileChunksAction $storeFileChunks,
        private FakeUploadFileAction $fakeUploadFile,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(
        UploadRequest $request,
        Share $shared,
    ) {
        if (isDemoAccount()) {
            return ($this->fakeUploadFile)($request);
        }

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        // Store file chunks
        $chunkPath = ($this->storeFileChunks)($request);

        // Proceed after last chunk
        if ($request->boolean('is_last')) {
            // Process file
            $file = ($this->processFie)($request, $shared->user, $chunkPath);

            // Set public access url
            $file->setSharedPublicUrl($shared->token);

            return response(new FileResource($file), 201);
        }
    }
}
