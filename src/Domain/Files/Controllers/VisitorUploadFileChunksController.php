<?php
namespace Domain\Files\Controllers;

use Str;
use Storage;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\Files\Requests\UploadChunkRequest;
use Support\Demo\Actions\FakeUploadFileAction;
use Domain\Files\Actions\StoreFileChunksAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * guest user upload file into shared folder
 */
class VisitorUploadFileChunksController extends Controller
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
        UploadChunkRequest $request,
        Share $shared,
    ) {
        if (isDemoAccount()) {
            return ($this->fakeUploadFile)($request);
        }

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            return response()->json(accessDeniedError(), 403);
        }

        // Add default parent id if missing
        if ($request->missing('parent_id')) {
            $request->merge(['parent_id' => $shared->item_id]);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        // Store file chunks
        $chunkPath = ($this->storeFileChunks)($request);

        // Proceed after last chunk
        if ($request->boolean('is_last_chunk')) {
            // Get file name
            $name = Str::uuid() . '.' . $request->input('extension');

            // Move file to user directory
            Storage::disk('local')->move($chunkPath, "files/{$shared->user->id}/$name");

            // Process file
            $file = ($this->processFie)($request, $shared->user, $name);

            // Set public access url
            $file->setSharedPublicUrl($shared->token);

            return response()->json(new FileResource($file), 201);
        }
    }
}
